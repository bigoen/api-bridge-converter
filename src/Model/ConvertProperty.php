<?php

declare(strict_types=1);

namespace Bigoen\ApiBridgeConverter\Model;

use Bigoen\ApiBridgeConverter\Model\Traits\PropertyAccessorTrait;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class ConvertProperty implements ConvertPropertyInterface
{
    use PropertyAccessorTrait;

    public ?string $property = null;
    public ?string $apiProperty = null;

    public static function new(string $property, string $apiProperty): self
    {
        $object = new self();
        $object->property = $property;
        $object->apiProperty = $apiProperty;

        return $object;
    }

    public function convert(array $arr): array
    {
        $accessor = self::getPropertyAccessor();
        $apiProperty = $this->apiProperty;
        if (!$accessor->isReadable($arr, $apiProperty)) {
            return $arr;
        }
        $value = $accessor->getValue($arr, $apiProperty);
        $accessor->setValue($arr, $this->property, $value);

        return $arr;
    }
}
