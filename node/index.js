const express = require("express");
const session = require("express-session");
const bodyParser = require("body-parser");
const jwt = require("jsonwebtoken");

const PORT = process.env["PORT"] ? parseInt(process.env["PORT"]) : 3001;

// these should match the settings in your Metabase instance
const MB_SITE_URL = process.env["MB_SITE_URL"] || "http://localhost:3000";
const MB_EMBEDDING_SECRET_KEY = process.env["MB_EMBEDDING_SECRET_KEY"];

// the dashboard ID of a dashboard that has a `user_id` parameter
const DASHBOARD_ID = parseInt(process.env["DASHBOARD_ID"]);

if (!MB_EMBEDDING_SECRET_KEY) {
  throw new Error("Please set MB_EMBEDDING_SECRET_KEY.");
}
if (typeof DASHBOARD_ID !== "number" || isNaN(DASHBOARD_ID)) {
  throw new Error("Please set DASHBOARD_ID.");
}

const app = express();

app.set("view engine", "pug");
app.use(session({ secret: "FIXME", resave: false, saveUninitialized: true }));
app.use(bodyParser.urlencoded({ extended: false }));

app.get("/", (req, res) => {
  const userId = req.session.userId;
  if (userId == null) {
    res.render("login");
  } else {
    // construct our unsigned JWT token payload
    const unsignedToken = {
      resource: { dashboard: DASHBOARD_ID },
      params: { user_id: userId }
    };
    // sign the JWT token with our secret key
    const signedToken = jwt.sign(unsignedToken, MB_EMBEDDING_SECRET_KEY);
    // construct the URL of the iframe to be displayed
    const iframeUrl = `${MB_SITE_URL}/embed/dashboard/${signedToken}`;

    res.render("index", { userId: userId, iframeUrl: iframeUrl });
  }
});

app.post("/login", (req, res) => {
  // in a real application you'd validate a username and password here, of course
  req.session.userId = req.body.userId;
  res.redirect("/");
});

app.get("/logout", (req, res) => {
  delete req.session.userId;
  res.redirect("/");
});

app.listen(PORT, () => {
  console.log("Example app listening on port " + PORT + "!");
});
