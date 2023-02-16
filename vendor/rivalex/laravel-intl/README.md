# Laravel Intl

[![Build Status](https://scrutinizer-ci.com/g/rivalex/laravel-intl/badges/build.png?b=rivalex)](https://scrutinizer-ci.com/g/rivalex/laravel-intl/build-status/rivalex)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rivalex/laravel-intl/badges/quality-score.png?b=rivalex)](https://scrutinizer-ci.com/g/rivalex/laravel-intl/?branch=rivalex)
[![Latest Stable Version](https://poser.pugx.org/rivalex/laravel-intl/v/stable)](https://packagist.org/packages/rivalex/laravel-intl)
[![Total Downloads](https://poser.pugx.org/rivalex/laravel-intl/downloads)](https://packagist.org/packages/rivalex/laravel-intl)
[![License](https://poser.pugx.org/rivalex/laravel-intl/license)](https://packagist.org/packages/rivalex/laravel-intl)

Easy to use internationalization functions for Laravel 8 and Lumen based on various libraries for easy retrieval of
localized values and formatting of numeric values into their localized patterns.

### This is a fork of [Propaganistas/Laravel-Intl](https://github.com/Propaganistas/Laravel-Intl) Package tha is been archived. Thanks to [Propaganistas](https://github.com/Propaganistas) for grate Work!!!

### Overview

* [Installation](#installation)
* [Usage](#usage)
    * [Country](#country)
    * [Currency](#currency)
    * [Date](#date)
    * [Language](#language)
    * [Number](#number)
* [Changing locales](#changing-locales)
    
### Installation

Run the following command to install the latest version of the package

```bash
composer require rivalex/laravel-intl
```

#### Laravel
If you don't use auto-discovery, open up your app config and add the Service Provider to the `$providers` array:

 ```php
'providers' => [
    ...
    Rivalex\LaravelIntl\IntlServiceProvider::class,
],
```
#### Adding Aliases
Now add the alias

#### Adding Aliases
Register facade on your `config/app.php` file.

 ```php
'aliases' => [
    // ...
    'Country' => Rivalex\LaravelIntl\Facades\Country::class,
    'Currency' => Rivalex\LaravelIntl\Facades\Currency::class,
    'Language' => Rivalex\LaravelIntl\Facades\Language::class,
    'Number' => Rivalex\LaravelIntl\Facades\Number::class,
],
```

 ```php
'aliases' => [
    // ...
    'Country' => Rivalex\LaravelIntl\Facades\Country::class,
    'Currency' => Rivalex\LaravelIntl\Facades\Currency::class,
    'Language' => Rivalex\LaravelIntl\Facades\Language::class,
    'Number' => Rivalex\LaravelIntl\Facades\Number::class,
],
```
#### Lumen
In `bootstrap/app.php`, register the Service Provider

 ```php
$app->register(Rivalex\LaravelIntl\IntlServiceProvider::class);
```

### Usage

> Note: **always** use the helper functions or Facades, or make use of dependency injection.

#### Country

Output localized country names.
```php
use Rivalex\LaravelIntl\Facades\Country;

// Application locale: nl
Country::name('US'); // Verenigde Staten
Country::all(); // ['US' => 'Verenigde Staten', 'BE' => 'België', ...]
```

```php
// Application locale: en
country('US'); // United States
country()->all(); // ['US' => 'United States', 'BE' => 'Belgium', ...]
```

#### Currency

Output localized currency names and format currency amounts into their localized pattern.

```php
use Rivalex\LaravelIntl\Facades\Currency;

// Application locale: nl
Currency::name('USD'); // Amerikaanse dollar
Currency::symbol('USD'); // $
Currency::format(1000, 'USD'); // $ 1.000,00
Currency::formatAccounting(-1234, 'USD'); // (US$ 1.234,00)
Currency::all(); // ['USD' => 'Amerikaanse dollar', 'EUR' => 'Euro', ...]
```

```php
// Application locale: en
currency('USD'); // US Dollar
currency()->symbol('USD'); // $
currency(1000, 'USD'); // $1,000.00
currency()->all(); // ['USD' => 'US Dollar', 'EUR' => 'Euro', ...]
```

Parse localized values into native PHP numbers.

```php
use Rivalex\LaravelIntl\Facades\Currency;

// Application locale: nl
Currency::parse('€ 1.234,50'); // 1234.5
```

```php
// Application locale: nl
currency()->parse('€ 1.234,50'); // 1234.5
```

#### Date

Just use `Illuminate\Support\Facades\Date`.

Additional methods are also available to output localized common date formats. E.g. `toShortDateString()`:

* Locale "en": 1/31/2018
* Locale "nl": 31-01-2018

````php
use Illuminate\Support\Facades\Date;

$date = Date::now(); // or carbon()->now()

$date->toShortDateString();
$date->toMediumDateString();
$date->toLongDateString();
$date->toFullDateString();

$date->toShortTimeString();
$date->toMediumTimeString();
$date->toLongTimeString();
$date->toFullTimeString();

$date->toShortDatetimeString();
$date->toMediumDatetimeString();
$date->toLongDatetimeString();
$date->toFullDatetimeString();
````

#### Language

Output localized language names.

```php
use Rivalex\LaravelIntl\Facades\Language;

// Application locale: nl
Language::name('en'); // Engels
Language::all(); // ['en' => 'Engels', 'nl' => 'Nederlands', ...]
```

```php
// Application locale: en
language('en'); // English
language()->all(); // ['en' => 'English', 'nl' => 'Dutch', ...]
```

#### Number

Output localized numeric values into their localized pattern.

```php
use Rivalex\LaravelIntl\Facades\Number;

// Application locale: en
Number::format(1000); // '1,000'
Number::percent('0.75'); // '75%'
```

```php
// Application locale: fr
number(1000); // '1 000'
number()->percent('0.75'); // '75 %'
```

Parse localized values into native PHP numbers.

```php
use Rivalex\LaravelIntl\Facades\Number;

// Application locale: fr
Number::parse('1 000'); // 1000
number()->parse('1,5'); // 1.5
```

### Changing locales

Ever feel the need to use a locale other than the current application locale? You can temporarily use another locale by using the `usingLocale()` method.

```php
country()->name('US'); // United States

country()->usingLocale('nl', function($country) {
    return $country->name('US');
}); // Verenigde Staten

country()->name('US'); // United States
```

Alternatively, you can force each component individually to the preferred locale for the remainder of the application by calling the `setLocale()` on the helper function or Facade.
Usually you'd set this in the `boot()` method of a *ServiceProvider*.
