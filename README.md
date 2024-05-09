# Lunar API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dystcz/lunar-api.svg?style=flat-square)](https://packagist.org/packages/dystcz/lunar-api)
[![Total Downloads](https://img.shields.io/packagist/dt/dystcz/lunar-api.svg?style=flat-square)](https://packagist.org/packages/dystcz/lunar-api)
![GitHub Actions](https://github.com/dystcz/lunar-api/actions/workflows/tests.yaml/badge.svg)

> [!CAUTION]
> This package is currently under heavy development. It is already used in production, however, you should proceed with caution.

This package introduces an API layer for the Lunar ecommerce package.
It connects the Lunar backend with your SPA, other applications,
and possibly with a mobile app.

The main aim is to provide a solid foundation for your e-commerce project,
giving you a head start while maintaining flexibility so you can
easily build features to meet your project's needs.

## Requirements

- PHP ^8.2
- Laravel 11
- [Lunar requirements](https://docs.lunarphp.io/core/installation.html#server-requirements)

## Documentation

- [v1.0.0-alpha documentation](https://lunar-api.docs.dy.st/)

### Testing

```bash
composer test
```

### Compatible packages

- [Reviews](https://github.com/dystcz/lunar-api-reviews) (Adds user reviews functionality)
- [Product Views](https://github.com/dystcz/lunar-api-product-views)
 (Store unique product views in Redis)
- [Product Stock Notifications](https://github.com/dystcz/lunar-api-product-notifications)
 (Notify users when product is in stock again)
- [Newsletter](https://github.com/dystcz/lunar-api-newsletter)
 (Newsletter sign up with support for Mailchimp / Mailcoach / Brevo)
- [Stripe Payment Adapter](https://github.com/dystcz/lunar-api-stripe-adapter)
- [Mollie Payment Adapter](https://github.com/pixelpillow/lunar-api-mollie-adapter)
- [PayPal Adapter](https://github.com/dystcz/lunar-api-paypal-adapter) [🚧]

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email dev@dy.st instead of using the issue tracker.

## Credits

- [All Contributors](../../contributors)
- [Lunar](https://github.com/lunarphp/lunar) for providing awesome e-commerce package
- [Laravel JSON:API](https://github.com/laravel-json-api/laravel)
 which is a brilliant JSON:API layer for Laravel applications

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
