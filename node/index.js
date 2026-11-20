const express = require("express");
const session = require("express-session");
const jwt = require("jsonwebtoken");

const PORT = parseInt(process.env.PORT || "3001");

// The URL people's browsers use to reach your Metabase.
const METABASE_SITE_URL =
  process.env.METABASE_SITE_URL || "http://localhost:3000";

// The embedding secret key, from Embedding hub > Security in your Metabase.
// The default is the key of the demo Metabase in this repository. Keep your
// own key on the server and out of version control.
const METABASE_SECRET_KEY =
  process.env.METABASE_SECRET_KEY ||
  "a1c0952f3ff361f1e7dd8433a0a50689a004317a198ecb0a67ba90c73c27a958";

// The dashboard to embed. In the demo Metabase, dashboard 3 is "Customer
// overview", published as a guest embed with its Customer ID filter locked.
const METABASE_DASHBOARD_ID = parseInt(
  process.env.METABASE_DASHBOARD_ID || "3",
);

// Demo customers and users. A real app would keep these in its database.
// Each customer's ID matches an Account ID in Metabase's Sample Database.
const CUSTOMERS = [
  { id: 5, name: "Acme Corp" },
  { id: 12, name: "Bluebird Labs" },
];

const USERS = [
  { username: "alice", password: "password", customerId: 5 },
  { username: "bob", password: "password", customerId: 5 },
  { username: "carol", password: "password", customerId: 12 },
];

function findUser(username) {
  return USERS.find((user) => user.username === username);
}

// Signs a guest embed token that locks the dashboard to one customer.
//
// The dashboard's Customer ID filter is set to "Locked" in Metabase, so
// Metabase only accepts a value for it from a token signed with the embedding
// secret key. Nothing in the browser can change or remove the value.
function signDashboardToken(customer) {
  const payload = {
    resource: { dashboard: METABASE_DASHBOARD_ID },
    params: {
      // Keyed by the filter's slug. The value comes from the signed-in user's
      // customer, never from the request. Always pass an ID: an empty list
      // would turn the filter off and show every customer's rows.
      customer_id: [customer.id],
    },
    exp: Math.floor(Date.now() / 1000) + 10 * 60, // 10 minute expiration
  };

  return jwt.sign(payload, METABASE_SECRET_KEY);
}

const app = express();

app.set("view engine", "pug");
app.set("views", __dirname + "/views");
// The default session store keeps sessions in memory, which is only for
// development. Use a persistent store in production.
app.use(
  session({
    secret: process.env.SESSION_SECRET || "replace-this-secret",
    resave: false,
    saveUninitialized: false,
  }),
);
app.use(express.urlencoded({ extended: false }));

// Make the signed-in user available to the routes and the views.
app.use((req, res, next) => {
  req.user = findUser(req.session.username);
  res.locals.user = req.user;
  next();
});

function checkAuth(req, res, next) {
  if (req.user) {
    return next();
  }
  return res.redirect("/login");
}

// The home page shows a public embed, which needs the Metabase URL.
app.get("/", (req, res) =>
  res.render("index", { metabaseSiteUrl: METABASE_SITE_URL }),
);

// A basic login for the demo. Replace it with your app's own authentication.
app
  .route("/login")
  .get((req, res) => res.render("login"))
  .post((req, res) => {
    const { username, password } = req.body;
    const user = findUser(username);

    if (!user || user.password !== password) {
      return res.status(401).render("login", {
        error: "That username and password didn't match. Try again.",
      });
    }

    req.session.regenerate(() => {
      req.session.username = user.username;
      res.redirect("/analytics");
    });
  });

app.post("/logout", (req, res) => {
  req.session.destroy(() => res.redirect("/"));
});

app.get("/analytics", checkAuth, (req, res) => {
  // Every user of one customer gets the same locked value, so they all see
  // that customer's data.
  const customer = CUSTOMERS.find(({ id }) => id === req.user.customerId);

  res.render("dashboard", {
    customer,
    metabaseSiteUrl: METABASE_SITE_URL,
    token: signDashboardToken(customer),
  });
});

if (require.main === module) {
  app.listen(PORT, () => {
    console.log("Example app listening on port " + PORT + "!");
  });
}

module.exports = {
  app,
  METABASE_SITE_URL,
  METABASE_SECRET_KEY,
  METABASE_DASHBOARD_ID,
};
