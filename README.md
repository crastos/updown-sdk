# updown.io SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/crastos/updown-sdk.svg?style=flat-square)](https://packagist.org/packages/crastos/updown-sdk)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/crastos/updown-sdk/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/crastos/updown-sdk/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/crastos/updown-sdk/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/crastos/updown-sdk/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/crastos/updown-sdk.svg?style=flat-square)](https://packagist.org/packages/crastos/updown-sdk)

I got bored so I wrote an SDK for updown.io.

It's functional but still a work-in-progress.

```php
use function \Crastos\Updown\updown;

updown($api_key)->checks->all()->toJson();
# => [{"token":"a1b2","url":"https://example.com", ... }]
```

## Installation

You can install the package via composer:

```sh
composer require crastos/updown-sdk
```

This package requires an HTTP client as well. You can view supported clients via composer:

```sh
composer suggest crastos/updown-sdk
```

## Usage

The fastest way to get up and running is with the helper function.

```php
use function \Crastos\Updown\updown;

updown($api_key)->checks->all()->toJson();
# => [{"token":"a1b2","url":"https://example.com", ... }]
```

Under the hood, this will instantiate an HTTP client and the SDK class which will navigate the API path and return the appropriate endpoint as needed. You can do this manually as well if you prefer to have more control over what's going on.

```php
$client = new \Crastos\Updown\Http\Client($api_key);
$updown = new \Crastos\Updown\Updown($client);

/** @var \Crastos\Updown\Http\Endpoints\Checks */
$endpoint = $updown->checks;

/** @var \Crastos\Updown\Http\Resources\ResourceRepository<int, \Crastos\Updown\Http\Resources\Check> */
$repository = $endpoint->all();

/** @var \Crastos\Updown\Http\Resources\Check */
$resource = $repository->first();

$resource->toJson();
# => {"token":"a1b2","url":"https://example.com", ... }
```

### Laravel

Add updown.io secret to .env

```sh
# /.env
UPDOWN_SERVER_SECRET=asdf1234
```

Add updown.io secret to config/services.php

```php
# config/services.php
[
    // ...
    'updown' => [
        'secret' => env('UPDOWN_SERVER_SECRET'),
    ],
]
```

Now you can access the SDK via `app('updown')`.

```php
app('updown')->checks->all()->json()
# => [{"token":"a1b2","url":"https://example.com", ... }]
```

