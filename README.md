Api Bridge Converter
==
Install:
```
composer require bigoen/api-bridge-converter
```
Create your converter.
```php
<?php

declare(strict_types=1);

namespace Bigoen\ApiBridgeConverter\Model;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class ConvertUnsetsProperty implements ConvertPropertyInterface
{
    public array $properties = [];

    public static function new(array $properties = []): self
    {
        $object = new self();
        $object->properties = $properties;

        return $object;
    }

    public function convert(array $arr): array
    {
        foreach ($this->properties as $property) {
            unset($arr[$property]);
        }

        return $arr;
    }
}
```
Convert api values in tree.
```php
use Bigoen\ApiBridge\Bridge\ApiPlatform\HttpClient\JsonldClient;
use Bigoen\ApiBridgeConverter\Model\ConvertDateTimeProperty;
use Bigoen\ApiBridgeConverter\Model\ConvertTreeProperty;
use Bigoen\ApiBridgeConverter\Model\ConvertUnsetsProperty;
use Bigoen\ApiBridgeConverter\Model\ConvertProperty;

$categories = $this->entityManager
    ->getRepository(Category::class)
    ->findAllIndexById();
$tags = $this->entityManager
    ->getRepository(Tag::class)
    ->findAllIndexById();
// set converts.
$convertProperties = [
    new ConvertTreeProperty(
        '[category]',
        false,
        '[category][@id]',
        null,
        $this->getConvertValues('/api/categories/{id}', $categories)
    ),
    new ConvertTreeProperty(
        '[tags]',
        true,
        '[tags][][@id]',
        Tag::class,
        $this->getConvertValues('/api/tags/{id}', $tags),
        [
            new ConvertDateTimeProperty('[createdAt]'),
            new ConvertDateTimeProperty('[updatedAt]'),
        ]
    ),
    new ConvertDateTimeProperty('[createdAt]'),
    new ConvertDateTimeProperty('[updatedAt]'),
    // class property and api property.
    new ConvertProperty('[id]', '[_id]'),
];
// unsets for sending.
$sendingConvertProperties = [
    new ConvertUnsetsProperty(['id', 'name']),
];
/* @var $client JsonldClient */
$client->setConvertProperties($convertProperties)->setSendingConvertProperties($sendingConvertProperties);
```
Use in custom object.
```php
<?php

declare(strict_types=1);

namespace Bigoen\Test\Model\Input;

use Bigoen\ApiBridgeConverter\Model\ConvertUnsetNullsProperty;
use Bigoen\ApiBridgeConverter\Model\Traits\ArrayObjectConverterTrait;
use Bigoen\Test\Contracts\InputInterface;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
final class PaymentCardInput implements InputInterface
{
    use ArrayObjectConverterTrait;

    public function __construct(
        public string $cardHolderName,
        public string $cardNumber,
        public string $expireYear,
        public string $expireMonth,
        public string $cvc,
        public ?string $cardAlias = null,
        public ?bool $storeCardAfterSuccessPayment = null,
        public ?string $cardUserKey = null
    ) {
    }

    public function toArray(): array
    {
        return self::objectToArray($this, [
            new ConvertUnsetNullsProperty(),
        ]);
    }
}

```
Important: property and deep names details > https://symfony.com/doc/current/components/property_access.html
