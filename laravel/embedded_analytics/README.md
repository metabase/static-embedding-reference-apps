# Laravel app

This Laravel application demonstrates a simple, barebones Metabase dashboard embedded in a web application.

## Prerequisites

- **Metabase**. You should have already completed the setup detailed in the [README](../../README.md) for this repository, which shows you how to get an instance of Metabase up and running in the [metabase](./metabase) directory of this repository.

- **Composer**. You'll need [Composer](https://getcomposer.org/) to manage PHP packages.

- **Laravel**. You'll need to have [Laravel](https://laravel.com/) installed on your machine.

## Set up the Laravel application

1. Start a new terminal session, change into this directory, and install the application dependencies using the command `composer install`. (The package `lcobucci/jwt` has already been added.)

Note: if you just installed Composer using the default filename for Composer to this directory, you may need to run:

```shell
php composer.phar install
```

2. Generate an application key. First, create a .env file by copying the example env file.

```shell
cp .env.example .env
```

Then generate the key.

```shell
php artisan key:generate
```

This command will write the key to your new .env file.

3. To start the application, run:

```shell
php artisan serve
```

4. Open your browser to [localhost:8000](http://localhost:8000) to see the app in action. 

Explore the app to learn more about embedding Metabase charts and dashboards in applications. You can also check out the links to more documentation in the parent repository's main [README](../../README.md).

## Example embedding code

- **Code**. You can find example code for embedding Metabase in [routes/web.php](routes/web.php).

- **View**. You can find an example view in [resources/views/welcome.blade.php](resources/views/welcome.blade.php).
