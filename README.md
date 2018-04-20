<p align="center">
    <img src="https://railt.org/img/logo-dark.svg" alt="Railt" />
</p>

<p align="center">
    <a href="https://travis-ci.org/railt/carbon-extension"><img src="https://travis-ci.org/railt/carbon-extension.svg?branch=master" alt="Travis CI" /></a>
    <a href="https://styleci.io/repos/101227474?branch=master"><img src="https://styleci.io/repos/101227474/shield?b=master" alt="StyleCI" /></a>
    <a href="https://packagist.org/packages/railt/carbon-extension"><img src="https://poser.pugx.org/railt/carbon-extension/version" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/railt/carbon-extension"><img src="https://poser.pugx.org/railt/carbon-extension/v/unstable" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/railt/carbon-extension/master/LICENSE"><img src="https://poser.pugx.org/railt/carbon-extension/license" alt="License MIT"></a>
</p>

# Carbon Extension

## Installation

- `composer require railt/carbon-extension`
- Add extension to your appplication:

```php
use Railt\Foundation\Application;
use Railt\CarbonExtension\Extension;

$app = new Application();

$app->extend(Extension::class); // Here
```

### Laravel

In that case, if you use [Laravel Service Provider](https://github.com/railt/laravel-provider) 
this extension can be added as follows:

Open the `railt.php` configuration file and add:

```php
'extensions' => [
    // ...
    \Railt\CarbonExtension\Extension::class, // Here
]
```

### Symfony

In that case, if you use [Symfony Bundle](https://github.com/railt/symfony-bundle) 
this extension can be added as follows:

Open the `config.yml` configuration file and add:

```yml
railt:
    extensions:
        - Railt\CarbonExtension\Extension # Here
```

## Usage

The Carbon GraphQL Object is the following structure:

```graphql
type Carbon {
    date(format: CarbonFormat = RFC3339): CarbonDateTime!
    diff: String!
}
```

You can use it within your types:

```graphql
type YourExampleType {
    id: ID!
    some: String!
    createdAt: Carbon!
    updatedAt: Carbon
}
```

### Resolving

In order to correctly return data - just pass the date (or Carbon) object:

```php
public function resolver(): array
{
    return [
        'id' => 42,
        'some' => 'Example',
        'createdAt' => new \DateTime(), // @see http://php.net/manual/en/class.datetime.php
        'updatedAt' => Carbon::now(),   // Or using Carbon: https://carbon.nesbot.com/docs/
    ];
}
```

### Request

The request might look like this:

```graphql
{
    example {
        id
        some
        createdAt {
            date(format: DATE_COOKIE)
        }
        updatedAt {
            date
            diff
        }
    }
}
```

The response is as follows:

```json
{
    "example": {
        "id": 42,
        "some": "Example",
        "createdAt": {
            "date": "Monday, 15-Aug-05 15:52:01 UTC"
        },
        "updatedAt": {
            "date": "2005-08-15T15:52:01+00:00",
            "stringable": "5 days ago"
        }
    }
}
```
