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

    public function __construct(public string $property)
    {
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
