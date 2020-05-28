# Node application

This Node application demonstrates a simple, barebones Metabase dashboard embedded in a web application.

## Prerequisites

- **Metabase**. You should have already completed the setup detailed in the [README](../README.md) for this repository, which shows you how to get an instance of Metabase up and running in the [metabase](../metabase) directory of this repository.

- **Node**. You'll need [Node](https://nodejs.org/en/) installed on your machine to run the application.

- **Yarn**. You'll need the [Yarn package manager](https://classic.yarnpkg.com/en/) to install the application's dependencies.

## Set up the Node application

1. In a new terminal session, `cd` into this directory. 

2. Run `yarn install` to install the application's dependencies.

3. Once the application dependencies are installed, run:

```shell
node index.js
```

4. Open your browser to [localhost:3001](http://localhost:3001).

Explore the app to learn more about embedding Metabase charts and dashboards in applications. You can also check out the links to more documentation in the parent repository's main [README](../README.md).

## Example embedding code

You can find example code for Metabase embeds for:

- **Charts**. See [views/chart.pug](views/chart.pug).
- **Dashboards**. See [views/dashboard.pug](views/dashboard.pug).

