# Fluent HTTP API Wrapper

Use this library to quickly build wrappers for HTTP APIs

## Installation

Add this to your PHP project with composer:

```shell
composer require johnpbloch/fluent-http-api
```

## Usage

### Customize Configuration

**The following is only necessary if you are building a library meant for distribution!**

If you are incorporating this in a codebase meant to be used as a library (i.e. as a dependency in other projects), you should create your own extensions of the `\JohnPBloch\FluentApi\Config` and `\JohnPBloch\FluentApi\Endpoint` classes and  ensure that initializing the configuration object for your library stores that configuration in your custom base endpoint:

```php
// Custom Endpoint:

class MyEndpoint extends \JohnPBloch\FluentApi\Endpoint
{
    public static function setConfig(\JohnPBloch\FluentApi\Config $config): \JohnPBloch\FluentApi\Config
    {
        return self::$config = $config;
    }

    protected function getConfig() : \JohnPBloch\FluentApi\Config {
        return self::$config;
    }
}

// Custom Config

class MyConfig extends \JohnPBloch\FluentApi\Config
{
    protected function setConfigOnBaseEndpoint(): static
    {
        return MyEndpoint::setConfig($this);
    }
}
```

### Authorization

This package provides a number of methods of adding authorization to requests. ***NOTE:*** This library does not handle authentication, it assumes you already have credentials available to use. The following methods of authorization are available:

- [Basic Auth](#basic-auth)
- [Digest Auth](#digest-auth)
- [NTLM Auth](#ntlm-auth)
- [Bearer Token](#bearer-token-auth)
- [Header Key/Value](#header-keyvalue-auth)
- [Query Key/Value](#query-keyvalue-auth)
- [Cookie-based auth](#cookie-based-auth)

##### Basic Auth

```php
use JohnPBloch\FluentApi\Auth\BasicAuth;
$auth = new BasicAuth('username', 'password');
```

##### Digest Auth

```php
use JohnPBloch\FluentApi\Auth\DigestAuth;
$auth = new DigestAuth('username', 'password', 'digest');
```

##### NTLM Auth

```php
use JohnPBloch\FluentApi\Auth\NtlmAuth;
$auth = new NtlmAuth('username', 'password', 'ntlm');
```

##### Bearer Token Auth

Only include the token, not the `Bearer ` prefix.

```php
use JohnPBloch\FluentApi\Auth\BearerAuth;
$auth = new BearerAuth('token');
```

##### Header Key/Value Auth

```php
use JohnPBloch\FluentApi\Auth\HeaderAuth;
$auth = new HeaderAuth('X-Header-Name', 'header value');
```

##### Query Key/Value Auth

```php
use JohnPBloch\FluentApi\Auth\QueryVariableAuth;
$auth = new QueryVariableAuth('query_var_name', 'token');
```

##### Cookie-based Auth

```php
use JohnPBloch\FluentApi\Auth\CookieAuth;
$auth = new CookieAuth('cookie-name', 'cookie-value', 'domain.com');
```

### Initialize Configuration

Before using this library, you will have to initialize the configuration object. If you extended the Config object for packaging in a library, use that config object instead of `\JohnPBloch\FluentApi\Config`.

```php
use JohnPBloch\FluentApi\Config;

Config::initialize('https://api.some-domain.com/base-path/', $auth)
```

### Make Requests

Use the `method()` method to set the HTTP request method (e.g. GET, POST, etc.)

Use the `path()` method to set the path relative to the configured base uri.

The `send()` method will send the request and return a PSR-7 compatible Guzzle response.

Additionally, the Endpoint class extends the Laravel `Fluent` class and can add generic data to the request using fluent methods. By default, `GET`, `POST`, and `PUT` will automatically add fluent attributes to the request. `GET` data will be added to the query variables; `POST` and `PUT` requests will set fluent attributes to body data as a `application/x-www-form-urlencoded` request. If you need to further adjust the request, you will need to extend Endpoint to further adjust the config data before sending.

For example, a simple GET request:

```php
use JohnPBloch\FluentApi\Endpoint;

$response = Endpoint::make()
    ->method('GET')
    ->path('resource/path')
    ->send();
```

Or a Post Request:

```php
use JohnPBloch\FluentApi\Endpoint;

$response = Endpoint::make()
    ->method('POST')
    ->path('article')
    ->title('This Article Will Change Your Life')
    ->content('<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo lacus et justo dictum, in imperdiet metus sagittis. Integer vestibulum justo quis tortor venenatis, a tempus justo pulvinar. Duis feugiat id orci ac condimentum. Fusce pellentesque dapibus tempus. Nulla nisi turpis, luctus sit amet enim sed, vulputate malesuada erat. Quisque varius quam eget sapien lobortis, ut vulputate nisl elementum. Proin sollicitudin eu ipsum vel mattis.</p>')
    ->send();
```

#### Overriding Configuration Settings

If there is a specific configuration option you need to override, you can extend the Endpoint class and set public or protected methods to override values. There are two types of override methods: `setRequestConfig*` and `mergeRequestConfig*`. For each one, the remaining text after `Config` will determine the configuration key that will be set. The text will be snake-cased before using. So for example, a method named `setRequestConfigFormParams()` will set the return value to `form_params` in Guzzle options.

`setRequestConfig*` methods will get the current Guzzle option as the only parameter and whatever the method returns will get set to the Guzzle options.

`mergeRequestConfig*` methods will merge the return value into the existing (possibly empty) value in the Guzzle options. There is no input for these methods.

Before sending the Guzzle request, the Endpoint will call `beforeSend($options)`. `beforeSend` can return either an options array or a `\Psr\Http\Message\ResponseInterface` object. If it returns an options array, the request will send as expected. If it returns a response object, it will be returned as-is without sending the request or running the `afterSend` method.

After sending the Guzzle request, the response object will call `afterSend()` with the response. `afterSend()` must return a response as well, and that response will be returned by `send()`.

## License

Licensed under MIT license.
