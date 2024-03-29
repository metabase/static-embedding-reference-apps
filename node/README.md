# Node application

This Node application demonstrates a simple, barebones Metabase dashboard embedded in a web application using static embedding.

## Prerequisites

- **Metabase**. [Install and run](../README.md#set-up-metabase) a fresh Metabase in the `metabase` directory of this repository.

- **Enable embedding**. Make sure to [enable embedding](../README.md#enable-embedding) in your Metabase.

- **Node**. You'll need [Node](https://nodejs.org/en/) installed on your machine to run the application.

- **Yarn**. You'll need the [Yarn package manager](https://classic.yarnpkg.com/en/) to install the application's dependencies.

## Set up the Node application

1. In a new terminal session, `cd` into this directory. 

2. Run `yarn install` to install the application's dependencies.

3. Once the application dependencies are installed, run `yarn start` or:

```shell
node index.js
```

4. Open your browser to [localhost:3001](http://localhost:3001) (or the port where this application is running).

Explore the app to learn more about embedding Metabase charts and dashboards in applications. You can also check out the links to more documentation in the parent repository's main [README](../README.md).

## Charts don't work

1. Go to your Metabase at `localhost:3000`.
2. Click on the **gear** icon > **Admin settings**.
3. Click **Embedding > **Standalone embeds**.
4. Check that the embedding secret key matches the embedding key in `index.js`. 
5. If the keys don't match, copy the secret key from your **Admin settings** and replace the secret key in the `index.js` file.

If that doesn't work, try cloning a fresh repo and running a new JAR file.

## Example embedding code

You can find example code for Metabase embeds for:

- **Charts**. See [views/chart.pug](views/chart.pug).
- **Dashboards**. See [views/dashboard.pug](views/dashboard.pug).
