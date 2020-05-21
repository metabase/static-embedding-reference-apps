# Django app

This Django application demonstrates a simple, barebones Metabase dashboard embedded in a web application.

## Prerequisites

- **Metabase**. You should have already completed the setup detailed in the [README](../README.md) for this repository, which shows you how to get an instance of Metabase up and running in the [metabase](../metabase) directory of this repository.

- **Django**. You'll need to have [Django installed](https://docs.djangoproject.com/en/3.0/topics/install/) on your machine.

## Set up the Django application

1. In a new terminal session, `cd` into [/embedded-analytics](/embedded-analytics).

2. Install the application's dependencies:

```shell
pip install -r requirements.txt
```

3. Once the dependencies are installed, start the application by running: 

```shell
./manage.py runserver
```

4. Open your browser to [localhost:8000](http://localhost:8000).

Explore the app to learn more about embedding Metabase charts and dashboards in applications. You can also check out the links to more documentation in the parent repository's main [README](../../README.md).

## Example embedding code

You can find example code for embedding Metabase in [user_stats/views.py](user_stats/views.py).
