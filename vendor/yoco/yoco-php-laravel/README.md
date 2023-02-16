# yoco-php-laravel

This Laravel package is a Laravel-friendly library which wraps the [Yoco PHP](https://github.com/YocoOpen/yoco-php) client library to provide access to the Yoco Online Payments API from PHP. It does this by providing a ChargeController and associated routes that can be called from the JavaScript frontend.

This library is used in conjunction with the frontend Web SDK described [here](https://developer.yoco.com/online/getting-started) 
which securely captures the customer's card details and generates the required token.
 
Put simply, the process is: 

  1. Securely capture card details using the Web SDK, which then provides a charge token
  1. Send the charge token to your Laravel backend application and finalise the charge via the `ChargeController` deployed by this library

A full sample implementation of the Web SDK in vanilla PHP and Laravel is available [here](https://github.com/YocoOpen/yoco-web-sdk-sample-php).
  
For more information on the APIs, you can refer to the [Yoco API Docs](https://developer.yoco.com/online/api-reference/api-overview).

## Requirements

- PHP 7.0 and later
- Laravel 7+

## Installation

Install the library into your Laravel application with [Composer](http://getcomposer.org/) and publish core files:

```bash
# Composer install
composer require yoco/yoco-php-laravel
# Publish config and charge controller
php artisan vendor:publish --tag=yoco
```

The above installation will add the files `/config/yoco.php` and `/app/Http/Controllers/ChargeController.php` 
to your source tree and also add the route `/yoco/charge` to your route list.

### Setup your keys

Edit the file `/config/yoco.php` and set the secret and public keys you received from the Yoco Business Portal.

### Making a purchase

A simple example is as follows:

  * The frontend Web SDK has been used to capture the card details, amount and currency as 
  described in the [SDK docs](https://developer.yoco.com/online/getting-started).
  * The frontend makes an AJAX request to your charge route `/yoco/charge`, which is processed by your `ChargeController`.
  * The result will be returned to your frontend as JSON.

## Yoco API Documentation

See the [API docs](https://developer.yoco.com/online/api-reference/api-overview).


