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

    public function __construct(public string $property, public string $apiProperty)
    {
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
