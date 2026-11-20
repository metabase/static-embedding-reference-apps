const assert = require("node:assert/strict");
const { after, before, describe, test } = require("node:test");
const jwt = require("jsonwebtoken");

const {
  app,
  METABASE_SITE_URL,
  METABASE_SECRET_KEY,
  METABASE_DASHBOARD_ID,
} = require("../index");

let server;
let baseUrl;

before(async () => {
  server = app.listen(0);
  await new Promise((resolve) => server.once("listening", resolve));
  baseUrl = `http://localhost:${server.address().port}`;
});

after(() => server.close());

// Signs in and returns the session cookie.
async function signIn(username, password = "password") {
  const response = await fetch(`${baseUrl}/login`, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({ username, password }),
    redirect: "manual",
  });
  return {
    status: response.status,
    cookie: (response.headers.getSetCookie()[0] || "").split(";")[0],
  };
}

async function dashboardPage(username, path = "/analytics") {
  const { cookie } = await signIn(username);
  const response = await fetch(baseUrl + path, { headers: { Cookie: cookie } });
  return { status: response.status, html: await response.text() };
}

// Loads the dashboard page as `username` and decodes the token it renders.
// Verifying checks the signature and the expiration.
async function embedPayload(username, path) {
  const { status, html } = await dashboardPage(username, path);
  assert.equal(status, 200);
  const match = html.match(/<metabase-dashboard token="([^"]+)"/);
  assert.ok(match, "the page should render a <metabase-dashboard>");
  return jwt.verify(match[1], METABASE_SECRET_KEY, { algorithms: ["HS256"] });
}

describe("the embedded dashboard", () => {
  test("home page shows a public embed", async () => {
    const response = await fetch(baseUrl);
    assert.equal(response.status, 200);
    const html = await response.text();
    assert.ok(
      html.includes(`<iframe src="${METABASE_SITE_URL}/public/dashboard/`),
    );
  });

  test("anonymous visitor is sent to login", async () => {
    const response = await fetch(`${baseUrl}/analytics`, {
      redirect: "manual",
    });
    assert.equal(response.status, 302);
    assert.equal(response.headers.get("location"), "/login");
  });

  test("wrong password doesn't sign anyone in", async () => {
    const { status, cookie } = await signIn("alice", "wrong");
    assert.equal(status, 401);
    const response = await fetch(`${baseUrl}/analytics`, {
      headers: { Cookie: cookie },
      redirect: "manual",
    });
    assert.equal(response.status, 302);
  });

  test("page loads embed.js as a guest embed", async () => {
    const { html } = await dashboardPage("alice");
    assert.ok(html.includes(`${METABASE_SITE_URL}/app/embed.js`));
    assert.ok(html.includes("isGuest: true"));
    assert.ok(!html.includes("<iframe"));
  });

  test("token locks the customer's ID", async () => {
    const payload = await embedPayload("alice");
    assert.deepEqual(payload.resource, { dashboard: METABASE_DASHBOARD_ID });
    assert.deepEqual(payload.params, { customer_id: [5] });
  });

  test("token expires", async () => {
    const payload = await embedPayload("alice");
    const now = Date.now() / 1000;
    assert.ok(payload.exp > now);
    assert.ok(payload.exp <= now + 10 * 60);
  });

  test("users of one customer get the same locked value", async () => {
    const alice = await embedPayload("alice");
    const bob = await embedPayload("bob");
    assert.deepEqual(alice.params, bob.params);
  });

  test("users of different customers get different locked values", async () => {
    const alice = await embedPayload("alice");
    const carol = await embedPayload("carol");
    assert.deepEqual(carol.params, { customer_id: [12] });
    assert.notDeepEqual(alice.params, carol.params);
  });

  test("request cannot choose the customer", async () => {
    const payload = await embedPayload("alice", "/analytics?customer_id=12");
    assert.deepEqual(payload.params, { customer_id: [5] });
  });
});
