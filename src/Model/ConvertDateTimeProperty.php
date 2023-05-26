<?php

declare(strict_types=1);

namespace Bigoen\ApiBridgeConverter\Model;

use Bigoen\ApiBridgeConverter\Model\Traits\PropertyAccessorTrait;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class ConvertDateTimeProperty implements ConvertPropertyInterface
{
    use PropertyAccessorTrait;

    public ?string $property = null;
    public ?string $format = null;

    public static function new(string $property, string $format = \DateTimeInterface::ISO8601): self
    {
        $object = new self();
        $object->property = $property;
        $object->format = $format;

        return $object;
    }

    public function convert(array $arr): array
    {
        $accessor = self::getPropertyAccessor();
        $property = $this->property;
        if (!$accessor->isReadable($arr, $property)) {
            return $arr;
        }
        $strValue = $accessor->getValue($arr, $property);
        if (\is_string($strValue)) {
            $value = \DateTime::createFromFormat($this->format, $strValue);
            $accessor->setValue($arr, $property, $value);
        }

        return $arr;
    }
}
