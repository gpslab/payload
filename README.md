[![Latest Stable Version](https://img.shields.io/packagist/v/gpslab/payload.svg?maxAge=3600&label=stable)](https://packagist.org/packages/gpslab/payload)
[![Total Downloads](https://img.shields.io/packagist/dt/gpslab/payload.svg?maxAge=3600)](https://packagist.org/packages/gpslab/payload)
[![Build Status](https://img.shields.io/travis/gpslab/payload.svg?maxAge=3600)](https://travis-ci.org/gpslab/payload)
[![Coverage Status](https://img.shields.io/coveralls/gpslab/payload.svg?maxAge=3600)](https://coveralls.io/github/gpslab/payload?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/gpslab/payload.svg?maxAge=3600)](https://scrutinizer-ci.com/g/gpslab/payload/?branch=master)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/5f0a79de-cc65-4e9b-b9ab-bcb16aedcdec.svg?maxAge=3600&label=SLInsight)](https://insight.sensiolabs.com/projects/5f0a79de-cc65-4e9b-b9ab-bcb16aedcdec)
[![StyleCI](https://styleci.io/repos/92380867/shield?branch=master)](https://styleci.io/repos/92380867)
[![License](https://img.shields.io/packagist/l/gpslab/payload.svg?maxAge=3600)](https://github.com/gpslab/payload)

# The simple infrastructure component for create payload message

## Installation

Pretty simple with [Composer](http://packagist.org), run:

```sh
composer require gpslab/payload
```

## Usage

This library automatically fill the properties of the object with payload data.

For example, create a simple message

```php
class SimpleMessage extends PayloadMessage
{
    public $id = 0;

    public $name = '';
}
```

Fill the message

```php
$message = new SimpleMessage([
    'id' => 123,
    'name' => 'foo',
]);

$message->id; // 123
$message->name; // foo
$message->payload(); // ['id' => 123, 'name' => 'foo']
```

> **Note**
>
> All fields specified in the payload must exist.

### Protected properties

You can use protected properties for data. It's convenient to make the properties as read-only.

```php
class SimpleMessage extends PayloadMessage
{
    protected $id = 0;

    protected $name = '';

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }
}
```

Fill the message

```php
$message = new SimpleMessage([
    'id' => 123,
    'name' => 'foo',
]);

$message->id(); // 123
$message->name(); // foo
$message->payload(); // ['id' => 123, 'name' => 'foo']
```

> **Note**
>
> For fill private properties you must use setters.

### Property setters

You can mark properties as private and use setters for fill it. This will ensure the security of data and control their
type. You can mark the setters as protected to close the class from changes from the outside.

```php
class SimpleMessage extends PayloadMessage
{
    private $id = 0;

    private $name = '';

    public function id(): integer
    {
        return $this->id;
    }

    protected function setId(integer $id)
    {
        $this->id = $id;
    }

    public function name(): string
    {
        return $this->name;
    }

    protected function setName(string $name)
    {
        $this->name = $name;
    }
}
```

Fill the message

```php
$message = new SimpleMessage([
    'id' => 123,
    'name' => 'foo',
]);

$message->id(); // 123
$message->name(); // foo
$message->payload(); // ['id' => 123, 'name' => 'foo']
```

### CQRS

You can use payload in [CQRS](https://github.com/gpslab/cqrs) infrastructure.

Command to rename contact:

```php
class RenameContactCommand extends PayloadCommand
{
    public $contact_id = 0;

    public $new_name = '';
}
```

Query for get contact by identity:

```php
class ContactByIdentityQuery extends PayloadQuery
{
    public $id = 0;
}
```

### Domain Events

You can use payload in [Domain Events](https://github.com/gpslab/domain-event).

Event, contact was renamed:

```php
class RenamedContactEvent extends PayloadDomainEvent
{
    public $contact_id = 0;

    public $old_name = '';

    public $new_name = '';
}
```


### Serialize

You can serialize messages with Symfony [serializer](https://symfony.com/doc/current/components/serializer.html)
component. For do it you can use `PayloadNormalizer` or `TypedPayloadNormalizer` and
[encode](https://symfony.com/doc/current/components/serializer.html#encoders) result to JSON, XML, YAML, CSV, etc.

* `PayloadNormalizer` - can be used only for one class because he does not distinguish messages;
* `TypedPayloadNormalizer` - adds to the normalized data the type of message received from `MessageTypeResolver` service.

You can use `ClassNameMessageTypeResolver` as a simplify resolver. It use the last part of class name as a messages type.

* `\Acme\Demo\SomeMessage` converted to `SomeMessage`
* `\Acme_Demo_SomeMessage` converted to `SomeMessage`

Be careful with the use of this resolver and do not named message classes equally in different namespace.

## License

This bundle is under the [MIT license](http://opensource.org/licenses/MIT). See the complete license in the file: LICENSE
