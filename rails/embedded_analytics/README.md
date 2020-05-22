# Rails app

This Rails application demonstrates a simple, barebones Metabase dashboard embedded in a web application.

## Prerequisites

- **Metabase**. You should have already completed the setup detailed in the [README](../../README.md) for this repository, which shows you how to get an instance of Metabase up and running in the [metabase](../metabase) directory of this repository.

- **Ruby**. You'll need to have [Ruby](https://www.ruby-lang.org/en/) installed on your machine.

- **Bundler**. [Bundler][https://bundler.io/) will install the application's dependencies.

# Set up the Rails application

1. In a new terminal session, `cd` into this directory.

2. Run `bundle install` to download the application's dependencies.

3. Once the application dependencies are installed, run:

```shell
rails server -p 3001
```
Because Metabase runs on port 3000, we need to include the `-p 3001` to bind the Rails server to a nonstandard port.

4. Open your browser to [localhost:3001](http://localhost:3001).

Explore the app to learn more about embedding Metabase charts and dashboards in applications. You can also check out the links to more documentation in the parent repository's main [README](../../README.md).

## Example embedding code

You can find example code for embedding Metabase in the [app/controllers](app/controllers) and [app/views/stats](app/views/stats) directories.
