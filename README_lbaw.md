# LBAW's framework

## Introduction

This README describes how to set up the development environment for LBAW.

These instructions address the development with a local environment (with PHP installed) and Docker containers for PostgreSQL and pgAdmin.

- [LBAW's framework](#lbaws-framework)
  - [Introduction](#introduction)
  - [Installing the software dependencies](#installing-the-software-dependencies)
  - [Setting up the development repository](#setting-up-the-development-repository)
  - [Installing local PHP dependencies](#installing-local-php-dependencies)
  - [Working with PostgreSQL](#working-with-postgresql)
  - [Developing the project](#developing-the-project)
  - [Laravel code structure](#laravel-code-structure)
    - [1) Routes](#1-routes)
    - [2) Controllers](#2-controllers)
    - [3) Database and Models](#3-database-and-models)
    - [4) Policies](#4-policies)
    - [5) Views](#5-views)
    - [6) CSS](#6-css)
    - [7) JavaScript](#7-javascript)
    - [8) Configuration](#8-configuration)
  - [Publishing your image](#publishing-your-image)
  - [Testing your image](#testing-your-image)


## Installing the software dependencies

To prepare your computer for development, you need to install:

* [PHP](https://www.php.net/) version 8.3 or higher
* [Composer](https://getcomposer.org/) version 2.2 or higher

We recommend using an **Ubuntu** distribution (24.04 or newer) that ships with these versions.

Install the required software with:

```bash
sudo apt update
sudo apt install git composer php8.3 php8.3-mbstring php8.3-xml php8.3-pgsql php8.3-curl
```

On macOS, install using [Homebrew](https://brew.sh/):

```bash
brew install php@8.3 composer
```

If you use [Windows WSL](https://learn.microsoft.com/en-us/windows/wsl/install), ensure you are using Ubuntu 24.04 inside WSL. Previous versions do not provide the required packages. After setting up WSL, follow the Ubuntu instructions above.


## Setting up the development repository

**Important**: Only one group member should perform these steps.

First, ensure you have both repositories in the same folder:

* Your group's repository
* The demo repository (template-laravel)

Follow these steps to set up your development environment:

```bash
# Clone your group repository
# Replace YYYY with the year (e.g., 2024) and XX with your group number
git clone https://gitlab.up.pt/lbaw/lbawYYYY/lbawYYXX.git

# Clone the LBAW project skeleton
git clone https://gitlab.up.pt/lbaw/template-laravel.git

# Remove the Git folder from the demo folder
rm -rf template-laravel/.git

# Preserve the existing README.md
mv template-laravel/README.md template-laravel/README_lbaw.md

# Go to your repository
cd lbawYYXX

# Switch to main branch
git checkout main

# Copy all demo files
cp -r ../template-laravel/. .

# Add the new files to your repository
git add .
git commit -m "Base Laravel structure"
git push origin main
```

After these steps:

1. You'll have the project skeleton in your local machine
2. You can remove the `template-laravel` directory

For team collaboration:

1. Only one group member should perform the above steps and push changes
2. Other group members should then clone the updated repository:

```bash
git clone https://gitlab.up.pt/lbaw/lbawYYYY/lbawYYXX.git
```

3. Each group member must create their own `.env` file:

```bash
cp .env.thingy .env
```

The `.env` file contains configuration settings and is not tracked by Git (see [.gitignore](.gitignore)).


## Installing local PHP dependencies

After setting up your repository, install all local dependencies required for development:

```bash
composer update
```

If the installation fails:

1. Check your Composer version (should be 2 or above): `composer --version`
2. If you see errors about missing PHP extensions, ensure they are enabled in your [php.ini file](https://www.php.net/manual/en/configuration.file.php) file


## Working with PostgreSQL

The _Docker Compose_ file provided sets up **PostgreSQL** and **pgAdmin4** as local Docker containers.

Start the containers from your project root:

```bash
docker compose up -d
```

Stop the containers when needed:

```bash
docker compose down
```

Open your browser and navigate to `http://localhost:4321` to access pgAdmin4.

Depending on your installation setup, you might need to use the IP address from the virtual machine providing docker instead of `localhost`.

On first use, add a local database connection with these settings:

```
hostname: postgres
username: postgres
password: pg!password
```

Use `postgres` as hostname (not `localhost`) because _Docker Compose_ creates an internal DNS entry for container communication.


## Developing the project

You're all set up to start developing the project.
The provided skeleton includes a basic todo list application -- **Thingy**, which you'll modify to implement your project.

Start the development server from your project root:

```bash
# Seed database from the SQL file
# Required: first run and after database script changes
php artisan db:seed

# Start the development server
php artisan serve
```

Access the application at `http://localhost:8000`

* Username: admin@example.com
* Password: 1234

These credentials are created when seeding the database.

To stop the server: Press `Ctrl-C`


## Laravel code structure

Before you start, familiarize yourself with [Laravel's documentation](https://laravel.com/docs/10.x).

A typical web request in Laravel involves several components. Here are the key concepts.


### 1) Routes

Laravel processes web pages through its [routing](https://laravel.com/docs/10.x/routing) mechanism.
Routes are defined in `routes/web.php`. Example:

```php
Route::get('cards/{id}', [CardController::class, 'show']);
```

This route:

* Handles GET requests to `cards/{id}`
* Uses the parameter `id`
* Calls the `show` method of `CardController`


### 2) Controllers

[Controllers](https://laravel.com/docs/10.x/controllers) group related request handling logic into a single class.
Controllers are normally defined in the `app/Http/Controllers` folder.

```php
class CardController extends Controller
{
    /**
     * Show the card for a given id
     */
    public function show(string $id): View
    {
        // Get the card.
        $card = Card::findOrFail($id);

        // Check if the current user can see (show) the card
        $this->authorize('show', $card);

        // Use the pages.card template to display the card
        return view('pages.card', [
            'card' => $card
        ]);
    }
}
```

This particular controller contains a `show` method that:

* Receives an `id` from a route
* Searches for a card in the database
* Checks if the user has permission to view the card
* Returns a view with the card data


### 3) Database and Models

To access the database, we will use the query builder capabilities of [Eloquent](https://laravel.com/docs/10.x/eloquent) but the initial database seeding will still be done using raw SQL (the script that creates the tables can be found in `database/thingy-seed.sql`).

One important aspect is that **we won't be using migrations in LBAW projects**.

Here is an example of Eloquent's query building syntax:

```php
$card = Card::findOrFail($id);
```

This line tells Eloquent to fetch a card from the database with a certain `id` (the primary key of the table).
The result will be an object of the class `Card` defined in `app/Models/Card.php`.
This class extends the `Model` class and contains information about the relation between the `card` tables and other tables:

```php
/**
 * Get the user that owns the card
 */
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

/**
 * Get the items for the card
 */
public function items(): HasMany
{
    return $this->hasMany(Item::class);
}
```

### 4) Policies

[Policies](https://laravel.com/docs/10.x/authorization#writing-policies) define which actions a user can take.
You can find policies inside the `app/Policies` folder.
For example, in the `CardPolicy.php` file we defined a `show` method that only allows a certain user to view a card if that user is the card owner:

```php
/**
 * Determine if a given card can be shown to a user
 */
public function show(User $user, Card $card): bool
{
    // Only a card owner can see a card.
    return $user->id === $card->user_id;
}
```

In this example:

* `$user` and `$card` are models that represent their respective tables
* `$id` and `$user_id` are columns automatically mapped into those models

To use this policy inside the `CardController`:

```php
$this->authorize('show', $card);
```

As you can see, there is no need to pass the current user.

If you name the controllers following the expected pattern (e.g., `CardPolicy` for the `Card` model), Laravel will [auto-discover the policies](https://laravel.com/docs/10.x/authorization#policy-auto-discovery). If you do not use the expected naming pattern, you will need to manually register the policies ([see the documentation](https://laravel.com/docs/10.x/authorization#registering-policies)).


### 5) Views

A controller only needs to return HTML code for it to be sent to the browser.
However we will be using [Blade](https://laravel.com/docs/10.x/blade) templates to make this task easier:

```php
return view('pages.card', ['card' => $card]);
```

In this example:

* `pages.card` refers to a blade template at `resources/views/pages/card.blade.php`
* The second parameter contains the data we are injecting into the template

Templates can extend other templates:

```php
@extends('layouts.app')
```

The base template (`resources/views/layouts/app.blade.php`) serves as the foundation for all pages.
Inside this template, the place where the page template is introduced is identified by the following command:

```php
@yield('content')
```

Besides the `pages` and `layouts` template folders, we also have a `partials` folder where small snippets of HTML code can be saved to be reused in other pages.


### 6) CSS

The easiest way to use CSS is just to edit the CSS file found at `public/css/app.css`.
You can have multiple CSS files to better organize your style definitions.


### 7) JavaScript

To add JavaScript into your project, just edit the file found at `public/js/app.js`.


### 8) Configuration

Laravel configurations are acquired from environment variables through:

* The environment where Laravel process starts
* The `.env` file in the project root

The `.env` file can set or override environment variables from the current context.
You will need to update these variables, especially those for database access (prefixed with `DB_`).

**Important**: You must manually create a schema that matches your group's username.

Environment Files:

* `.env`: Use for local development
* `.env.production`: Bundled with Docker image, uses production database

Note that you can use the remote database locally by updating your `.env` file accordingly.

If you change the configuration, clear Laravel's cache with:

```bash
php artisan route:clear
php artisan cache:clear
php artisan config:clear
```

## Publishing your image

To deploy your project, we'll create a container image using the [Dockerfile](Dockerfile) in your repository, which specifies how to package your application and its dependencies. This image will then be published to GitLab's Container Registry where it can be accessed for deployment and evaluation. The following steps guide you through this process.

You need to have Docker installed to publish your project image for deployment.

**Note for ARM CPU users**: You must explicitly build an AMD64 Docker image. Follow [this guide](https://docs.docker.com/build/building/multi-platform/) to create a multi-platform builder and update your `upload_image.sh` file.

You should keep your git main branch functional and regularly deploy your code as a Docker image. This image will be used to test and evaluate your project.

**Important**: Before building your docker image, configure your `.env.production` file with your group's `db.fe.up.pt` credentials:

```bash
DB_CONNECTION=pgsql
DB_HOST=db.fe.up.pt
DB_PORT=5432
DB_SCHEMA=lbawYYXX
DB_DATABASE=lbawYYXX
DB_USERNAME=lbawYYXX
DB_PASSWORD=password
```

Images must be published to Gitlab's Container Registry, available from the side menu option `Deploy > Container Registry`.

Publishing steps:

1. Login to GitLab's Container Registry (using FEUP VPN/network):

```bash
docker login gitlab.up.pt:5050 # Username is upXXXXX@up.pt
```

2. Configure `upload_image.sh` with your image name:

```bash
IMAGE_NAME=gitlab.up.pt:5050/lbaw/lbawYYYY/lbawYYXX # Replace with your group's image name
```

3. Build and upload from the project's root:

```bash
./upload_image.sh
```

Maintain one image per group. All team members can update the image after logging in to GitLab's registry.


## Testing your image

After publishing, you can test your image locally using:

```bash
docker run -d --name lbawYYXX -p 8001:80 gitlab.up.pt:5050/lbaw/lbawYYYY/lbawYYXX
```

This command:

* Starts a Docker container named `lbawYYXX` with your published image (`-d` runs it in the background)
* Maps port 8001 on your machine to port 80 in the container
* Your application will be available at `http://localhost:8001`

While running your container, you can use another terminal to run a shell inside the container:

```bash
docker exec -it lbawYYXX bash
```

Inside the container you may, for example, see the content of the web server logs:

```bash
# Follow error logs
root@2804d54698c0:/# tail -f /var/log/nginx/error.log

# Follow access logs
root@2804d54698c0:/# tail -f /var/log/nginx/access.log
```

To stop and remove the container:

```bash
docker stop lbawYYXX
docker rm lbawYYXX
```

---
-- LBAW, 2024
