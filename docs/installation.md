# Installation

## Requirements

- PHP ^8.2
- Laravel 10
- [Lunar requirements](https://docs.lunarphp.io/core/installation.html#server-requirements)

## Installation

You can install the package via composer

```bash
composer require dystcz/lunar-api
```

Publish config files

> You will probably need them pretty bad

```bash
php artisan vendor:publish --provider="Dystcz\LunarApi\LunarApiServiceProvider" --tag="lunar-api"
```

Publish migrations

> Only in case you want to customize the database schema

```bash
php artisan vendor:publish --provider="Dystcz\LunarApi\LunarApiServiceProvider" --tag="lunar-api.migrations"
```

## Let's hit your first endpoint

Just visit `/api/v1/products` and you should see a list of products.

Hopefully everything went smooth so far. If not, please create an issue.

You can view the rest of the API routes by running `php artisan route:list --name=v1` in your terminal.
