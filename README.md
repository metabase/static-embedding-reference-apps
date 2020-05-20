# Embedding Metabase dashboards: reference web apps
This repo contains example applications for common web frameworks to demonstrate how [Metabase](https://www.metabase.com/) dashboards embed in web applications.

This README will walk you through getting Metabase up and running, as well as a simple web app, to show you a live example of an embedded dashboard.

Here's a simple Metabase dashboard in action:

![Simple Metabase dashboard embedded in Node application](/static/img/metabase_node_embed.gif)

# Prerequisites
- Java version 8.x or 11.x. [Install OpenJDK](https://openjdk.java.net/install/).
- The latest [Metabase jar](https://www.metabase.com/start/jar.html).

# Set up Metabase
You'll need a running instance of Metabase, instantiated from the [metabase](/metabase) directory in this repository. If you already have an instance of Metabase running on your machine, shut it down.

1. Open up a terminal and clone this repo to your machine.

2. `cd` into embedding-reference-apps/metabase/.

3. Run the downloaded Metabase jar. If you downloaded the Metabase jar to your downloads folder:

```bash
java -jar ~/Downloads/metabase.jar
```
> You must be in the **metabase** directory of the **embedding-reference-apps** directory when you run the above command. For example, if you installed the repository in your home folder, the filepath would be: ~/embedding-reference-apps/metabase/.

You might need to give `java` access to your Downloads folder, or alternatively you can move the Metabase jar to another directory, such as ~/embedding-reference-apps/metabase.

Metabase will log its progress in the terminal as the jar loads. Once you see the line, "Metabase Inititalization COMPLETE", open your browser to localhost:3000 to see that Metabase is up and running. 

# Running the apps

To see an embedded Metabase dashboard in action, follow the instructions in the README for the relevant app. If you're not sure which one to try, check out the [Node app](/node/README.md).

## Node
[Node app README](/node/README.md)

## Django
[Django app README](/django/README.md)

## Rails
[Rails app README](/rails/README.md)

For the rails application
`rails server -p 3001`
(Note that we're binding to a nonstandard port as Metabase also runs on 3000)

# About embedding

There are two ways to embed dashboards in web applications:

## Public Embeds
The Public embedding method is to simply use the public URLs inside of an iframe, or really anywhere you can insert HTML. The embedded dashboard has a secure URL, so a user can only look at the contents of the dashboard being shared. An end user never has information they can use to modify the URL and gain access to any other resources on your Metabase instance.

## Signed Embeds
With signed embedding, all embedded charts and dashboards have to be signed using a secret key. This should be done on your server, and allows you to embed dashboards filtered down to a specific user, organization or account. For example, we can take the same dashboard above and embed it. Additionally, it is easy to provide a per-user dashboard like you will see if you follow this link and log in with user: "admin" and password "admin"

An example of a specific User's dashboard
If you want to just embed a single chart, you can do that too. See the below example, which embeds chart #1, limited to user #1.

An example of a specific User's chart
We can also embed signed dashboards that are not limited to a single user. The below is a dashboard that can only be displayed if the embedding application signs the url with our secret key and can only be used in that application.

You can learn more about embedding dashboards by reading [Metabase's documentation](https://www.metabase.com/docs/latest/).

- [Quick search: embed](https://www.metabase.com/search.html?query=embed).

Article links:

- [Embedding Metabase in other applications](https://www.metabase.com/docs/latest/administration-guide/13-embedding.html)
- [Embedding all of Metabase in your web app](https://www.metabase.com/docs/latest/enterprise-guide/full-app-embedding.html)
- [Sharing and embedding dashboards or questions](https://www.metabase.com/docs/latest/administration-guide/12-public-links.html)

# Helpful info
Credentials should auto-populate in the web apps, but here they are for reference:

- user: plucky@admin.com
- password: Metabase123

# Learn more
