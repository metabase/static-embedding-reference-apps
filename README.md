# Embedding Metabase dashboards: reference web apps

This repo contains example applications for common web frameworks to demonstrate how [Metabase](https://www.metabase.com/) dashboards can be embedded in your application.

This README will walk you through getting Metabase up and running, as well as a simple web app, to show you a live example of an embedded dashboard.

Here's a simple Metabase dashboard in action:

![Simple Metabase dashboard embedded in Node application](/static/img/metabase_node_embed.gif)

## Premium embedding

Embedding Metabase charts will always be free, but we include a "Powered by Metabase" graphic when using the open source version.

If you'd like to remove the "Powered by Metabase" attribution, check out our [Enterprise Edition](https://www.metabase.com/enterprise/). With full app embedding, you can add dashboards, charts, or even the entire Metabase interface into your own app with a fully branded experience.

## Prerequisites

- Java version 11 + [Install OpenJDK](https://openjdk.java.net/install/).

## Set up Metabase

We'll first need to set up a running instance of Metabase to serve the embedded dashboards.

1. If you already have an instance of Metabase running on your machine, shut it down. Then download and run Metabase according to the guides in our [install section](https://www.metabase.com/docs/latest/operations-guide/installing-metabase.html).

2. Open up a terminal and clone this repo to your machine.

3. `cd` into embedding-reference-apps.

3. Run any example depending on the programming language you choose.

## Running the apps

To see an embedded Metabase dashboard in action, [set up Metabase](#set-up-metabase), and follow the instructions in the README for the relevant app. If you're not sure which one to try, check out the [Node app](/node/README.md).

- [Node](/node/README.md)

- [Django](/django/embedded-analytics/README.md)

- [Rails](/rails/embedded-analytics/README.md)

- [Laravel](/laravel/embedded-analytics/README.md)

## Embedding charts or dashboards

There are two ways to embed charts or dashboards in web applications:

### Public embeds

The Public embedding method is to simply use the public URLs inside of an iframe, or really anywhere you can insert HTML. The embedded dashboard has a secure URL, so a user can only look at the contents of the dashboard being shared. An end user never has information they can use to modify the URL and gain access to any other resources on your Metabase instance.

### Signed embeds

With signed embedding, all embedded charts and dashboards have to be signed using a secret key. This allows you to build dynamic dashboards with a parameter that can be be locked down on the client side.  You should sign embedded charts and dashboards on your server, which allows you to embed dashboards accessible to specific organizations, accounts, or users.

The web applications go into more detail about embedding, and provide examples. Start up the [Node app](/node/README.md) and explore the app.

## Documentation on embeds

You can also learn more about embedding dashboards by reading [Metabase's documentation](https://www.metabase.com/docs/latest/).

- [Quick search: embed](https://www.metabase.com/search.html?query=embed).

Article links:

- [Embedding Metabase components in other applications](https://www.metabase.com/docs/latest/administration-guide/13-embedding.html)
- [Embedding all of Metabase in your web app](https://www.metabase.com/docs/latest/enterprise-guide/full-app-embedding.html)
- [Sharing and embedding dashboards or questions](https://www.metabase.com/docs/latest/administration-guide/12-public-links.html)

## Helpful info
Credentials for Metabase should auto-populate, but here they are for reference:

- user: plucky@admin.com
- password: Metabase123

## Run into trouble?

Check out the [Metabase discussion forum](https://discourse.metabase.com/) and search for your issue. If you don't find any answers, please [create an issue](https://github.com/metabase/embedding-reference-apps/issues/new/choose) for this repository.
