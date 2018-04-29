<p align="center">
    <img src="https://railt.org/img/logo-dark.svg" alt="Railt" />
</p>

<p align="center">
    <a href="https://travis-ci.org/railt/carbon-extension"><img src="https://travis-ci.org/railt/carbon-extension.svg?branch=master" alt="Travis CI" /></a>
    <a href="https://styleci.io/repos/130360795?branch=master"><img src="https://styleci.io/repos/130360795/shield?b=master" alt="StyleCI" /></a>
    <a href="https://scrutinizer-ci.com/g/railt/carbon-extension/?branch=master"><img src="https://scrutinizer-ci.com/g/railt/carbon-extension/badges/coverage.png?b=master" alt="Code coverage" /></a>
    <a href="https://scrutinizer-ci.com/g/railt/carbon-extension/?branch=master"><img src="https://scrutinizer-ci.com/g/railt/carbon-extension/badges/quality-score.png?b=master" alt="Scrutinizer CI" /></a>
    <a href="https://packagist.org/packages/railt/carbon-extension"><img src="https://poser.pugx.org/railt/carbon-extension/version" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/railt/carbon-extension"><img src="https://poser.pugx.org/railt/carbon-extension/v/unstable" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/railt/carbon-extension/master/LICENSE"><img src="https://poser.pugx.org/railt/carbon-extension/license" alt="License MIT"></a>
</p>

# Carbon Extension

**Table of contents**
- [Installation](#installation)
    - [Laravel](#laravel)
    - [Symfony](#symfony)
- [Output](#output)
    - [Formats](#output-formats)
- [Input](#input)
    - [Formats](#input-formats)

## Installation

- `composer require railt/carbon-extension`
- Add extension to your application:

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

## Output

You can use it within your types. After you add type `Carbon`, 
two optional arguments appear in the field which is defined by this type.

Those. Client will see the following scheme:

```graphql
# Definition
type YourExampleType {
    id: ID!
    some: String!
    createdAt: Carbon!
}

# What the client will see
type YourExampleType {
    id: ID!
    some: String!
    createdAt(
        """
        An argument that matches the date of the time difference. 
        If a `NULL` is passed (or the value is not passed), 
        then the current time is taken.
        """
        diff: Carbon = null,
        
        """
        An argument that provides a format of the given value that 
        are contained in a CarbonFormat enumeration type.
        """
        format: CarbonFormat = RFC3339
    ): Carbon!
}
```

### Output formats

The return value can correspond to one of the valid formats defined in the 
`CarbonFormat` enumeration. In order to specify what date format in the 
response you want to see - it should be passed as the value of the `format` argument.

```graphql
{
    example {
        createdAt(format: COOKIE) 
        # createdAt field date will return in the COOKIE format.
    }
}
```

Below is a list of valid `CarbonFormat` enum formats:

- **ISO8601** - ISO-8601 date format.
> Example: `2005-08-15T15:52:01+00:00`
> Note: This format is an alias of the RFC 3339 specification:
> ISO8601: https://www.iso.org/iso-8601-date-and-time-format.html
> RFC3339: https://www.ietf.org/rfc/rfc3339.txt

- **RFC822** - RFC 822 date format.
> Example: `Mon, 15 Aug 05 15:52:01 +0000`

- **RFC850** - RFC 850 date format.
> Example: `Monday, 15-Aug-05 15:52:01 UTC`

- **RFC1036** - RFC 1036 date format.
> Example: `Mon, 15 Aug 05 15:52:01 +0000`

- **RFC1123** - RFC 1123 date format.
> Example: `Mon, 15 Aug 2005 15:52:01 +0000`

- **RFC2822** - RFC 2822 date format.
> Example: `Mon, 15 Aug 2005 15:52:01 +0000`

- **RFC3339** - RFC 3339 date format.
> Example: `2005-08-15T15:52:01+00:00`
> Note: This format is an alias of the ISO-8601 specification:
> RFC3339: https://www.ietf.org/rfc/rfc3339.txt
> ISO8601: https://www.iso.org/iso-8601-date-and-time-format.html

- **RFC3339_EXTENDED** - RFC 3339 date format. In contrast to the usual RFC3339 additionally contains milliseconds.
> Example: `2005-08-15T15:52:01.000+00:00`

- **RFC7231** - RFC 7231 date format.
> Example: `Mon, 15 Aug 2005 15:52:01 GMT`

- **COOKIE** - HTTP Cookies date format.
> Example: `Monday, 15-Aug-2005 15:52:01 UTC`

- **DATE_TIME** - Simple DateTime format.
> Example: `2005-08-15 15:52:01`

- **DATE** - Simple Date format.
> Example: `2005-08-15`

- **TIME** - Simple Time format.
> Example: `15:52:01`

- **RSS** - RSS date format.
> Example: `Mon, 15 Aug 2005 15:52:01 +0000`

- **W3C** - World Wide Web Consortium date format.
> Example: `2005-08-15T15:52:01+00:00`

- **HUMAN_READABLE** - Human readable string.
> Example: `2 days ago`


In order to correctly return data - just pass the date type.

> Note: The "createdAt" field should provide datetime compatible type, like:
> 1) DateTime object: http://php.net/manual/en/class.datetime.php
> 2) Carbon object: https://carbon.nesbot.com/docs/
> 3) String datetime format
> 4) Integer timestamp

```php
public function resolver(): array
{
    return [
        'id' => 42,
        'some' => 'Example',
        'createdAt' => '2018-04-28T17:55:27+00:00', // Yesterday
    ];
}
```

The request might look like this:

```graphql
{
    example {
        id
        some
        createdAt(format: COOKIE)
        diff1: createdAt(format: HUMAN_READABLE, diff: "5 days ago")
        diff2: createdAt(diff: "tomorrow")
    }
}
```

The response is as follows:

```json
{
    "example": {
        "id": 42,
        "some": "Example",
        "createdAt": "Saturday, 28-Apr-2018 17:55:27 GMT+0000",
        "diff1": "3 days after",
        "diff2": "2018-04-30T00:00:00+00:00"
    }
}
```


## Input

A scalar `Carbon` type can be passed as an argument to any field.
In this case it will be coerced into `Carbon` PHP object.

```graphql
# Definition
type Example {
    field(arg: Carbon!): String
}
```

```php
// Resolver
// Note: "$arg" argument definition similar with "$input->get('arg')"
public function handle(\DateTimeInterface $arg)
{
    return $arg->format(\DateTime::RFC3339);
}
```

```graphql
# Query
{
    field(arg: "now")
}
```

```json
{
    "field": "2018-04-29T17:55:27+00:00"
}
```

### Input formats

As the admissible input values, the [following formats](http://php.net/manual/en/datetime.formats.php) are allowed:

- [Time Formats](http://php.net/manual/en/datetime.formats.time.php)
- [Date Formats](http://php.net/manual/en/datetime.formats.date.php)
- [Compound Formats](http://php.net/manual/en/datetime.formats.compound.php)
- [Relative Formats](http://php.net/manual/en/datetime.formats.relative.php)
