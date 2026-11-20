# Node application

This Node application shows a Metabase dashboard as a [guest embed](https://www.metabase.com/docs/latest/embedding/guest-embedding), with each customer limited to its own data.

- The page loads Metabase's `embed.js` and renders a `<metabase-dashboard>` component.
- The server signs a token for each page load. People who view the dashboard don't need a Metabase account.
- The token locks the dashboard's **Customer ID** filter to the customer of the signed-in user, so every user of one customer sees that customer's data, and nobody sees another customer's data.

For comparison, the app's home page also shows a [public embed](https://www.metabase.com/docs/latest/embedding/public-links#public-embeds): a public link in an iframe, with no token and no restriction on the data.

## Prerequisites

- **Metabase**. Complete the setup in this repository's main [README](../README.md#set-up-metabase), which gets Metabase running at `localhost:3000` from the `metabase` directory.

- **Node**. You'll need [Node](https://nodejs.org/en/) 20 or later, which includes npm.

To run Metabase and the app in containers instead, see [Run with Docker](#run-with-docker).

## Set up the Node application

1. In a new terminal session, `cd` into this directory.

2. Run `npm install` to install the application's dependencies.

3. Once the application dependencies are installed, run `npm start` or:

   ```shell
   node index.js
   ```

4. Open your browser to [localhost:3001](http://localhost:3001) (or the port where this application is running).

## Try it out

Go to [localhost:3001/analytics](http://localhost:3001/analytics) and sign in. The password for each demo user is `password`.

| Username | Customer      | Locked Customer ID |
| -------- | ------------- | ------------------ |
| `alice`  | Acme Corp     | 5                  |
| `bob`    | Acme Corp     | 5                  |
| `carol`  | Bluebird Labs | 12                 |

Alice and Bob belong to the same customer, so they see the same dashboard. Carol belongs to another customer, so she sees different numbers. The **Account ID** column of the Invoices table on the dashboard shows which customer the rows belong to.

## How it works

- [index.js](index.js) looks up the signed-in user's customer and signs the token. The customer's ID goes in the token's `params`, keyed by the filter's slug:

  ```javascript
  const payload = {
    resource: { dashboard: METABASE_DASHBOARD_ID },
    params: { customer_id: [customer.id] },
    exp: Math.floor(Date.now() / 1000) + 10 * 60, // 10 minute expiration
  };
  const token = jwt.sign(payload, METABASE_SECRET_KEY);
  ```

  The same file has the Metabase URL, the embedding secret key, the dashboard's ID, and the demo customers and users.

- [views/dashboard.pug](views/dashboard.pug) loads `embed.js`, sets `isGuest: true`, and passes the token to `<metabase-dashboard>`.

In Metabase, the dashboard's **Customer ID** filter is set to **Locked**. Metabase only accepts a value for a locked filter from a signed token, and refuses a token that leaves the filter out. A user can't change the value from the browser, because they don't have the secret key. See [locked parameters](https://www.metabase.com/docs/latest/embedding/guest-embedding#locked-parameters).

Every user belongs to a customer, so the app never signs a token without a customer's ID. An empty list turns a locked filter off, which would show every customer's rows.

## Run the tests

```shell
npm test
```

The tests in [test/app.test.js](test/app.test.js) check that the token locks the ID of the signed-in user's customer, and that the request can't pick another customer.

## Use your own Metabase

1. In your Metabase, go to **Embedding hub > Security** and turn on **Enable embedding** (called **Modular embedding** on Pro and Enterprise plans). Copy the secret key for guest embeds.
2. Open your dashboard, click the **Share** icon, and select **Embed**. Under **Authentication**, select **Guest**.
3. Under **Parameters**, set the filter that identifies the customer to **Locked**, then click **Publish**.
4. Start the app with your Metabase's settings:

   ```shell
   METABASE_SITE_URL="https://your-metabase.example.com" \
   METABASE_SECRET_KEY="YOUR_SECRET_KEY" \
   METABASE_DASHBOARD_ID=10 \
   node index.js
   ```

5. If your locked filter's slug isn't `customer_id`, change the key in [index.js](index.js).
6. The public embeds on the home page use public links from the demo Metabase. To show your own, change the `src` of the iframes in [views/index.pug](views/index.pug).

## Run with Docker

To run both Metabase and this app in containers, you'll need [Docker](https://www.docker.com/). From this directory, run:

```shell
docker compose up
```

Metabase takes a minute or so to start. Then open your browser to [localhost:8080](http://localhost:8080). Metabase runs at [localhost:3000](http://localhost:3000) and uses the application database in the `metabase` directory of this repository.

## Charts don't work

Check that the embedding secret key in **Embedding hub > Security** matches `METABASE_SECRET_KEY` in [index.js](index.js). If the keys don't match, copy the key from Metabase and pass it as the `METABASE_SECRET_KEY` environment variable.

If that doesn't work, try cloning a fresh repo and running a new JAR file.

## Docs

- [Guest embeds](https://www.metabase.com/docs/latest/embedding/guest-embedding): how guest embeds work, and how to refresh tokens without reloading the page.
- [Embedding parameters](https://www.metabase.com/docs/latest/embedding/parameters#restrict-data-on-guest-embeds): how locked parameters restrict data.
- [Tenants](https://www.metabase.com/docs/latest/embedding/tenants): on Pro and Enterprise plans, another way to give each customer its own data. Your customers' users sign in to Metabase with SSO, and permissions restrict the data, so you can offer drill-through and the query builder too.
- [Securing embedded Metabase](https://www.metabase.com/docs/latest/embedding/securing-embeds).
