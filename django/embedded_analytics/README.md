# Django app

This Django application shows a Metabase dashboard as a [guest embed](https://www.metabase.com/docs/latest/embedding/guest-embedding), with each customer limited to its own data.

- The page loads Metabase's `embed.js` and renders a `<metabase-dashboard>` component.
- The server signs a token for each page load. People who view the dashboard don't need a Metabase account.
- The token locks the dashboard's **Customer ID** filter to the customer of the signed-in user, so every user of one customer sees that customer's data, and nobody sees another customer's data.

For comparison, the app's home page also shows a [public embed](https://www.metabase.com/docs/latest/embedding/public-links#public-embeds): a public link in an iframe, with no token and no restriction on the data.

## Prerequisites

- **Metabase**. Complete the setup in this repository's main [README](../../README.md), which gets Metabase running at `localhost:3000` from the [metabase](../../metabase) directory.

- **Embedding enabled**. The demo Metabase comes with embedding turned on. To make sure, [check that embedding is enabled](../../README.md#check-that-embedding-is-enabled).

- **Python**. You'll need [Python](https://www.python.org/) 3.10 or later.

## Set up the Django application

1. In a new terminal session, `cd` into this directory.

2. Create a virtual environment and install the application's dependencies:

   ```shell
   python3 -m venv .venv
   source .venv/bin/activate
   pip install -r requirements.txt
   ```

3. Create the database, and add the demo customers and users:

   ```shell
   python manage.py migrate
   python manage.py seed_demo
   ```

4. Start the application:

   ```shell
   python manage.py runserver
   ```

5. Open your browser to [localhost:8000](http://localhost:8000).

## Try it out

Go to [localhost:8000/analytics](http://localhost:8000/analytics/) and sign in. The password for each demo user is `password`.

| Username | Customer      | Locked Customer ID |
| -------- | ------------- | ------------------ |
| `alice`  | Acme Corp     | 5                  |
| `bob`    | Acme Corp     | 5                  |
| `carol`  | Bluebird Labs | 12                 |

Alice and Bob belong to the same customer, so they see the same dashboard. Carol belongs to another customer, so she sees different numbers. The **Account ID** column of the Invoices table on the dashboard shows which customer the rows belong to.

## How it works

- [user_stats/views.py](user_stats/views.py) looks up the signed-in user's customer and signs the token. The customer's ID goes in the token's `params`, keyed by the filter's slug:

  ```python
  payload = {
      "resource": {"dashboard": settings.METABASE_DASHBOARD_ID},
      "params": {"customer_id": [customer.id]},
      "exp": int(time.time()) + 10 * 60,  # 10 minute expiration
  }
  token = jwt.encode(payload, settings.METABASE_SECRET_KEY, algorithm="HS256")
  ```

- [user_stats/templates/user_stats/dashboard.html](user_stats/templates/user_stats/dashboard.html) loads `embed.js`, sets `isGuest: true`, and passes the token to `<metabase-dashboard>`.
- [user_stats/models.py](user_stats/models.py) has the `Customer` model and the `Membership` that links each user to a customer. To change them in Django's admin at `/admin`, first create an admin user with `python manage.py createsuperuser`.
- [embedded_analytics/settings.py](embedded_analytics/settings.py) has the Metabase URL, the embedding secret key, and the dashboard's ID.

In Metabase, the dashboard's **Customer ID** filter is set to **Locked**. Metabase only accepts a value for a locked filter from a signed token, and refuses a token that leaves the filter out. A user can't change the value from the browser, because they don't have the secret key. See [locked parameters](https://www.metabase.com/docs/latest/embedding/guest-embedding#locked-parameters).

The app never signs a token without a customer's ID. An empty list turns a locked filter off, which would show every customer's rows.

## Run the tests

```shell
python manage.py test
```

The tests in [user_stats/tests.py](user_stats/tests.py) check that the token locks the ID of the signed-in user's customer, and that the request can't pick another customer.

## Use your own Metabase

1. In your Metabase, go to **Embedding hub > Security** and turn on **Enable embedding** (called **Modular embedding** on Pro and Enterprise plans). Copy the secret key for guest embeds.
2. Open your dashboard, click the **Share** icon, and select **Embed**. Under **Authentication**, select **Guest**.
3. Under **Parameters**, set the filter that identifies the customer to **Locked**, then click **Publish**.
4. Start the app with your Metabase's settings:

   ```shell
   METABASE_SITE_URL="https://your-metabase.example.com" \
   METABASE_SECRET_KEY="YOUR_SECRET_KEY" \
   METABASE_DASHBOARD_ID=10 \
   python manage.py runserver
   ```

5. If your locked filter's slug isn't `customer_id`, change the key in [user_stats/views.py](user_stats/views.py).
6. The public embeds on the home page use public links from the demo Metabase. To show your own, change the `src` of the iframes in [user_stats/templates/user_stats/index.html](user_stats/templates/user_stats/index.html).

## Charts don't work

Check that the embedding secret key in **Embedding hub > Security** matches `METABASE_SECRET_KEY` in [embedded_analytics/settings.py](embedded_analytics/settings.py). If the keys don't match, copy the key from Metabase and pass it as the `METABASE_SECRET_KEY` environment variable.

## Minimal example

For a guest embed in a single file, without the login or the customers, see [minimal_example](../minimal_example).

## Docs

- [Guest embeds](https://www.metabase.com/docs/latest/embedding/guest-embedding): how guest embeds work, and how to refresh tokens without reloading the page.
- [Embedding parameters](https://www.metabase.com/docs/latest/embedding/parameters#restrict-data-on-guest-embeds): how locked parameters restrict data.
- [Tenants](https://www.metabase.com/docs/latest/embedding/tenants): on Pro and Enterprise plans, another way to give each customer its own data. Your customers' users sign in to Metabase with SSO, and permissions restrict the data, so you can offer drill-through and the query builder too.
- [Securing embedded Metabase](https://www.metabase.com/docs/latest/embedding/securing-embeds).
