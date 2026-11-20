# Laravel app

This Laravel application shows a Metabase dashboard as a [guest embed](https://www.metabase.com/docs/latest/embedding/guest-embedding), with each customer limited to its own data.

- The page loads Metabase's `embed.js` and renders a `<metabase-dashboard>` component.
- The server signs a token for each page load. People who view the dashboard don't need a Metabase account.
- The token locks the dashboard's **Customer ID** filter to the customer of the signed-in user, so every user of one customer sees that customer's data, and nobody sees another customer's data.

For comparison, the app's home page also shows a [public embed](https://www.metabase.com/docs/latest/embedding/public-links#public-embeds): a public link in an iframe, with no token and no restriction on the data.

## Prerequisites

- **Metabase**. Complete the setup in this repository's main [README](../../README.md), which gets Metabase running at `localhost:3000` from the [metabase](../../metabase) directory.

- **PHP**. You'll need [PHP](https://www.php.net/) 8.3 or later, with the SQLite extension that PHP includes by default.

- **Composer**. You'll need [Composer](https://getcomposer.org/) to manage PHP packages.

## Set up the Laravel application

1. In a new terminal session, `cd` into this directory.

2. Install the dependencies and set up the app:

   ```shell
   composer run setup
   ```

   This command installs the PHP packages, copies `.env.example` to `.env`, generates an application key, and creates the SQLite database with the demo customers and users.

3. Start the application:

   ```shell
   php artisan serve
   ```

4. Open your browser to [localhost:8000](http://localhost:8000).

## Try it out

Go to [localhost:8000/analytics](http://localhost:8000/analytics) and sign in. The password for each demo user is `password`.

| Email               | Customer      | Locked Customer ID |
| ------------------- | ------------- | ------------------ |
| `alice@example.com` | Acme Corp     | 5                  |
| `bob@example.com`   | Acme Corp     | 5                  |
| `carol@example.com` | Bluebird Labs | 12                 |

Alice and Bob belong to the same customer, so they see the same dashboard. Carol belongs to another customer, so she sees different numbers. The **Account ID** column of the Invoices table on the dashboard shows which customer the rows belong to.

## How it works

- [app/Http/Controllers/AnalyticsController.php](app/Http/Controllers/AnalyticsController.php) looks up the signed-in user's customer and signs the token. The customer's ID goes in the token's `params`, keyed by the filter's slug:

  ```php
  $payload = [
      'resource' => ['dashboard' => config('services.metabase.dashboard_id')],
      'params' => ['customer_id' => [$customer->id]],
      'exp' => time() + 10 * 60, // 10 minute expiration
  ];
  $token = JWT::encode($payload, config('services.metabase.secret_key'), 'HS256');
  ```

- [resources/views/dashboard.blade.php](resources/views/dashboard.blade.php) loads `embed.js`, sets `isGuest: true`, and passes the token to `<metabase-dashboard>`.
- [app/Models/Customer.php](app/Models/Customer.php) and [app/Models/User.php](app/Models/User.php) link each user to a customer.
- [config/services.php](config/services.php) reads the Metabase URL, the embedding secret key, and the dashboard's ID from `.env`.

In Metabase, the dashboard's **Customer ID** filter is set to **Locked**. Metabase only accepts a value for a locked filter from a signed token, and refuses a token that leaves the filter out. A user can't change the value from the browser, because they don't have the secret key. See [locked parameters](https://www.metabase.com/docs/latest/embedding/guest-embedding#locked-parameters).

Every user has to belong to a customer, so the app never signs a token without a customer's ID. An empty list turns a locked filter off, which would show every customer's rows.

## Run the tests

```shell
php artisan test
```

The tests in [tests/Feature/DashboardEmbedTest.php](tests/Feature/DashboardEmbedTest.php) check that the token locks the ID of the signed-in user's customer, and that the request can't pick another customer.

## Use your own Metabase

1. In your Metabase, go to **Embedding hub > Security** and turn on **Enable embedding**. Copy the secret key for guest embeds.
2. Open your dashboard, click the **Share** icon, and select **Embed**. Under **Authentication**, select **Guest**.
3. Under **Parameters**, set the filter that identifies the customer to **Locked**, then click **Publish**.
4. Set your Metabase's settings in the `.env` file, then restart the app:

   ```shell
   METABASE_SITE_URL=https://your-metabase.example.com
   METABASE_SECRET_KEY=YOUR_SECRET_KEY
   METABASE_DASHBOARD_ID=10
   ```

5. If your locked filter's slug isn't `customer_id`, change the key in [app/Http/Controllers/AnalyticsController.php](app/Http/Controllers/AnalyticsController.php).
6. The public embeds on the home page use public links from the demo Metabase. To show your own, change the `src` of the iframes in [resources/views/welcome.blade.php](resources/views/welcome.blade.php).

## Charts don't work

Check that the embedding secret key in **Embedding hub > Security** matches `METABASE_SECRET_KEY` in your `.env` file. If the keys don't match, copy the key from Metabase into `.env` and restart the app.

## Docs

- [Guest embeds](https://www.metabase.com/docs/latest/embedding/guest-embedding): how guest embeds work, and how to refresh tokens without reloading the page.
- [Embedding parameters](https://www.metabase.com/docs/latest/embedding/parameters#restrict-data-on-guest-embeds): how locked parameters restrict data.
- [Tenants](https://www.metabase.com/docs/latest/embedding/tenants): on Pro and Enterprise plans, another way to give each customer its own data. Your customers' users sign in to Metabase with SSO, and permissions restrict the data, so you can offer drill-through and the query builder too.
- [Securing embedded Metabase](https://www.metabase.com/docs/latest/embedding/securing-embeds).
