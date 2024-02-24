<?php

declare(strict_types=1);

namespace Bigoen\ApiBridgeConverter\Model;

use Bigoen\ApiBridgeConverter\Model\Traits\ArrayObjectConverterTrait;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class ConvertArrayToObjectProperty implements ConvertPropertyInterface
{
    use ArrayObjectConverterTrait;

    public function __construct(public string $property, public string $class, public array $convertProperties = [])
    {
    }

    public function convert(array $arr): array
    {
        $accessor = self::getPropertyAccessor();
        $property = $this->property;
        if (!$accessor->isReadable($arr, $property)) {
            return $arr;
        }
        $arrValue = $accessor->getValue($arr, $property);
        if (\is_array($arrValue)) {
            $value = self::arrayToObject(new $this->class(), $arrValue, $this->convertProperties);
            $accessor->setValue($arr, $property, $value);
        }

        return $arr;
    }
}