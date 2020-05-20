# embedding-reference-apps
This repo contains example applications for common web frameworks to demonstrate how Metabase charts embed in web applications.

Here's a simple Metabase dashboard in action:

![Simple Metabase dashboard embedded in Node application](/static/img/metabase_node_embed.gif)

This README will walk you through getting Metabase up and running, as well as a simple web app, to show you a live example of an embedded dashboard.

# Prerequisites
- Java version 8.x or 11.x. [Install OpenJDK](https://openjdk.java.net/install/)
- The latest [Metabase jar](https://www.metabase.com/start/jar.html)

# Set up Metabase
You'll need a running instance of Metabase, instantiated from the metabase directory in this repository. So if you already have an instance of Metabase running on your machine, shut it down.

1. Open up a terminal and clone this repo to your machine.

2. `cd` into embedding-reference-apps/metabase/

3. Run the downloaded Metabase jar. If you downloaded the Metabase jar to your downloads folder:

```bash
java -jar ~/Downloads/metabase.jar
```
> You must be in the **metabase** directory of the **embedding-reference-apps** directory when you run the above command. For example, if you installed the repository in your home folder, the filepath would be: ~/embedding-reference-apps/metabase/.

You might need to give `java` access to your Downloads folder, or alternatively you can move the Metabase jar to another directory, such as ~/embedding-reference-apps/metabase.

Metabase will log its progress in the terminal as the jar loads. Once you see the line, "Metabase Inititalization COMPLETE", open your browser to localhost:3000 to see that Metabase is up and running. 

There is no need to sign up or sign in; you just need a running instance of Metabase to process the embedded charts. But here are some credentials for an admin if you want to log in and poke around:

- user: plucky@admin.com
- password: Metabase123

# Running the apps

To See an embedded Metabase dashboard in action, follow the instructions in the README for the relevant app. If you're not sure which one to try, check out the [Node app](/node/README.md).

## Node
[Node app README](/node/README.md)

## Django
[Django app README](/django/README.md)

## Rails
[Rails app README](/rails/README.md)

For the rails application
`rails server -p 3001`
(Note that we're binding to a nonstandard port as Metabase also runs on 3000)

# Learn more
[Embedding Metabase in other applications](https://www.metabase.com/docs/latest/administration-guide/13-embedding.html)
[Embedding all of Metabase in your web app](https://www.metabase.com/docs/latest/enterprise-guide/full-app-embedding.html)
[Sharing and embedding dashboards or questions](https://www.metabase.com/docs/latest/administration-guide/12-public-links.html)
