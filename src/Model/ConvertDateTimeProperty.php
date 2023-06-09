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

    public function __construct(public string $property, public string $format = \DateTimeInterface::ISO8601, public ?\DateTimeZone $timezone = null)
    {
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
            $value = \DateTime::createFromFormat($this->format, $strValue, $this->timezone);
            $accessor->setValue($arr, $property, $value);
        }

        return $arr;
    }
}
