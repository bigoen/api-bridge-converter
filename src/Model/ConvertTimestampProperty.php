<?php

declare(strict_types=1);

namespace Bigoen\ApiBridgeConverter\Model;

use Bigoen\ApiBridgeConverter\Model\Traits\PropertyAccessorTrait;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class ConvertTimestampProperty implements ConvertPropertyInterface
{
    use PropertyAccessorTrait;

    public ?string $property = null;

    public static function new(string $property): self
    {
        $object = new self();
        $object->property = $property;

        return $object;
    }

    public function convert(array $arr): array
    {
        $accessor = self::getPropertyAccessor();
        $property = $this->property;
        if (!$accessor->isReadable($arr, $property)) {
            return $arr;
        }
        $intValue = $accessor->getValue($arr, $property);
        if (\is_int($intValue)) {
            $value = (new \DateTime())->setTimestamp($intValue);
            $accessor->setValue($arr, $property, $value);
        }

        return $arr;
    }
}
