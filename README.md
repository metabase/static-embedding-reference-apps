# Metabase static embedding: reference web apps

This repo contains example applications for common web frameworks to demonstrate how [Metabase](https://www.metabase.com/) dashboards can be embedded in your application using the **static embedding feature**.

For interactive embedding, check out the following resources:
* [Interactive embedding quick start](https://www.metabase.com/learn/customer-facing-analytics/interactive-embedding-quick-start)
* [Sample repo for the quick start](https://github.com/metabase/metabase-nodejs-express-interactive-embedding-sample)
* [Sample with React](https://github.com/metabase/sso-examples/tree/master/app-embed-example)

If you are unsure about what embedding feature is best for you, check out our [live demos](https://www.metabase.com/embedding-demo).


## About this repo

This README will walk you through getting Metabase up and running, as well as a simple web app, to show you a live example of an embedded dashboard.

Here's a simple Metabase dashboard in action:

![Simple Metabase dashboard embedded in Node application](/static/img/metabase_node_embed.gif)

## Free Static embedding 

Embedding Metabase charts will always be free, but we include a "Powered by Metabase" graphic when using the open source version.

If you'd like to remove the "Powered by Metabase" attribution, check out our [paid plans](https://www.metabase.com/pricing/). In addition to removing the banner, you can also customize the font and hide the download buttons for questions. 

With [interactive embedding](#interactive-embedding), you can add dashboards, charts, or even the entire Metabase interface into your own app with a fully branded experience.

## Prerequisites

- Java version 11 + [Install OpenJDK](https://openjdk.java.net/install/).

## Set up Metabase

We'll first need to set up a running instance of Metabase to serve the embedded dashboards.

1. Shut down Metabase if you already have it running on your machine.
2. Download the [JAR file for Metabase OSS](https://www.metabase.com/start/oss/jar).
3. Open up a terminal and clone this repo to your machine.
4. Move the `metabase.jar` file from your downloads to the `/embedding-reference-apps/metabase` directory.
5. `cd` into `/embedding-reference-apps/metabase`.
6. Run the JAR file:
    ```
    java -jar metabase.jar
    ```
7. [Run any example](#running-the-apps) depending on the programming language you choose.

## Enable embedding

Once you've got Metabase running (on port 3000 by default):

1. Go to [`localhost:3000`](localhost:3000) in your browser.
2. Log into Metabase.
    - User: plucky@admin.com
    - Password: Metabase123
3. Go to **Admin settings** > **Embedding** and click the toggle.

## Running the apps

To see an embedded Metabase dashboard in action, [set up Metabase](#set-up-metabase), and follow the instructions in the README for the relevant app. If you're not sure which one to try, check out the [Node app](/node/README.md).

- [Node](/node/README.md)

- [Django](/django/embedded_analytics/README.md)

- [Rails](/rails/embedded_analytics/README.md)

- [Laravel](/laravel/embedded_analytics/README.md)

## Embedding charts or dashboards

There are different ways to embed Metabase charts or dashboards in web applications.

### Public embeds

The [public embedding](https://www.metabase.com/docs/latest/questions/sharing/public-links#public-embeds) method is to simply use the public URLs inside of an iframe, or really anywhere you can insert HTML. The embedded dashboard has a secure URL, so a user can only look at the contents of the dashboard being shared. An end user never has information they can use to modify the URL and gain access to any other resources on your Metabase instance.

### Static embeds

With [static embedding](https://www.metabase.com/docs/latest/embedding/static-embedding), embedded questions and dashboards are be signed using a secret key. This allows you to build dynamic dashboards with a [parameter that can be locked down](https://www.metabase.com/docs/latest/embedding/static-embedding-parameters#restricting-data-in-a-static-embed) on the client side.  You should sign embedded questions and dashboards on your server, which allows you to embed dashboards accessible to specific organizations, accounts, or users.

The web applications go into more detail about embedding, and provide examples. Start up the [Node app](/node/README.md) and explore the app.

### Interactive embedding

Interactive embedding puts the entire Metabase app inside your app, so you can include the query builder in addition to your charts and dashboards. Interactive embedding isn't covered in this repo. Check out the [interactive embedding demo](https://embedding-demo.metabase.com/) instead.

## Docs and articles

- [Embedding overview](https://www.metabase.com/docs/latest/embedding/introduction)
- [Publishing data visualizations to the web](https://www.metabase.com/learn/customer-facing-analytics/embedding-charts-and-dashboards)
- [Public sharing](https://www.metabase.com/docs/latest/questions/sharing/public-links)

## Run into trouble?

Check out the [Metabase discussion forum](https://discourse.metabase.com/) and search for your issue. If you don't find any answers, please [create an issue](https://github.com/metabase/embedding-reference-apps/issues/new/choose) for this repository.
