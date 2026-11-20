# Rails app

This Rails application shows a Metabase dashboard as a [guest embed](https://www.metabase.com/docs/latest/embedding/guest-embedding), with each customer limited to its own data.

- The page loads Metabase's `embed.js` and renders a `<metabase-dashboard>` component.
- The server signs a token for each page load. People who view the dashboard don't need a Metabase account.
- The token locks the dashboard's **Customer ID** filter to the customer of the signed-in user, so every user of one customer sees that customer's data, and nobody sees another customer's data.

For comparison, the app's home page also shows a [public embed](https://www.metabase.com/docs/latest/embedding/public-links#public-embeds): a public link in an iframe, with no token and no restriction on the data.

## Prerequisites

- **Metabase**. Complete the setup in this repository's main [README](../../README.md), which gets Metabase running at `localhost:3000` from the [metabase](../../metabase) directory.

- **Embedding enabled**. The demo Metabase comes with embedding turned on. To make sure, [check that embedding is enabled](../../README.md#check-that-embedding-is-enabled).

- **Ruby**. You'll need [Ruby](https://www.ruby-lang.org/en/) 3.2 or later. The app was last tested with the version in [.ruby-version](.ruby-version).

- **Bundler**. [Bundler](https://bundler.io/) installs the application's dependencies.

## Set up the Rails application

1. In a new terminal session, `cd` into this directory.

2. Install the application's dependencies:

   ```shell
   bundle install
   ```

3. Create the database. This step also adds the demo customers and users:

   ```shell
   bin/rails db:prepare
   ```

4. Start the application:

   ```shell
   bin/rails server -p 3001
   ```

   Metabase runs on port 3000, so the `-p 3001` puts the Rails server on another port.

5. Open your browser to [localhost:3001](http://localhost:3001).

## Try it out

Go to [localhost:3001/analytics](http://localhost:3001/analytics) and sign in. The password for each demo user is `password`.

| Email               | Customer      | Locked Customer ID |
| ------------------- | ------------- | ------------------ |
| `alice@example.com` | Acme Corp     | 5                  |
| `bob@example.com`   | Acme Corp     | 5                  |
| `carol@example.com` | Bluebird Labs | 12                 |

Alice and Bob belong to the same customer, so they see the same dashboard. Carol belongs to another customer, so she sees different numbers. The **Account ID** column of the Invoices table on the dashboard shows which customer the rows belong to.

## How it works

- [app/controllers/analytics_controller.rb](app/controllers/analytics_controller.rb) looks up the signed-in user's customer and signs the token. The customer's ID goes in the token's `params`, keyed by the filter's slug:

  ```ruby
  payload = {
    resource: { dashboard: metabase.dashboard_id },
    params: { customer_id: [ customer.id ] },
    exp: Time.now.to_i + 10 * 60 # 10 minute expiration
  }
  token = JWT.encode(payload, metabase.secret_key, "HS256")
  ```

- [app/views/analytics/show.html.erb](app/views/analytics/show.html.erb) loads `embed.js`, sets `isGuest: true`, and passes the token to `<metabase-dashboard>`.
- [app/models/customer.rb](app/models/customer.rb) and [app/models/user.rb](app/models/user.rb) link each user to a customer.
- [config/initializers/metabase.rb](config/initializers/metabase.rb) has the Metabase URL, the embedding secret key, and the dashboard's ID.

In Metabase, the dashboard's **Customer ID** filter is set to **Locked**. Metabase only accepts a value for a locked filter from a signed token, and refuses a token that leaves the filter out. A user can't change the value from the browser, because they don't have the secret key. See [locked parameters](https://www.metabase.com/docs/latest/embedding/guest-embedding#locked-parameters).

Every user has to belong to a customer, so the app never signs a token without a customer's ID. An empty list turns a locked filter off, which would show every customer's rows.

## Run the tests

```shell
bin/rails test
```

The tests in [test/controllers/analytics_controller_test.rb](test/controllers/analytics_controller_test.rb) check that the token locks the ID of the signed-in user's customer, and that the request can't pick another customer.

## Use your own Metabase

1. In your Metabase, go to **Embedding hub > Security** and turn on **Enable embedding** (called **Modular embedding** on Pro and Enterprise plans). Copy the secret key for guest embeds.
2. Open your dashboard, click the **Share** icon, and select **Embed**. Under **Authentication**, select **Guest**.
3. Under **Parameters**, set the filter that identifies the customer to **Locked**, then click **Publish**.
4. Start the app with your Metabase's settings:

   ```shell
   METABASE_SITE_URL="https://your-metabase.example.com" \
   METABASE_SECRET_KEY="YOUR_SECRET_KEY" \
   METABASE_DASHBOARD_ID=10 \
   bin/rails server -p 3001
   ```

5. If your locked filter's slug isn't `customer_id`, change the key in [app/controllers/analytics_controller.rb](app/controllers/analytics_controller.rb).
6. The public embeds on the home page use public links from the demo Metabase. To show your own, change the `src` of the iframes in [app/views/welcome/index.html.erb](app/views/welcome/index.html.erb).

## Charts don't work

Check that the embedding secret key in **Embedding hub > Security** matches the secret key in [config/initializers/metabase.rb](config/initializers/metabase.rb). If the keys don't match, copy the key from Metabase and pass it as the `METABASE_SECRET_KEY` environment variable.

## Docs

- [Guest embeds](https://www.metabase.com/docs/latest/embedding/guest-embedding): how guest embeds work, and how to refresh tokens without reloading the page.
- [Embedding parameters](https://www.metabase.com/docs/latest/embedding/parameters#restrict-data-on-guest-embeds): how locked parameters restrict data.
- [Tenants](https://www.metabase.com/docs/latest/embedding/tenants): on Pro and Enterprise plans, another way to give each customer its own data. Your customers' users sign in to Metabase with SSO, and permissions restrict the data, so you can offer drill-through and the query builder too.
- [Securing embedded Metabase](https://www.metabase.com/docs/latest/embedding/securing-embeds).
