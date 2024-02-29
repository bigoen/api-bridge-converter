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

    public function __construct(public string $property, public string|array $apiProperty)
    {
    }

    public function convert(array $arr): array
    {
        $accessor = self::getPropertyAccessor();
        $apiProperties = \is_array($this->apiProperty) ? $this->apiProperty : [$this->apiProperty];
        foreach ($apiProperties as $apiProperty) {
            if ($value = $accessor->getValue($arr, $apiProperty)) {
                $accessor->setValue($arr, $this->property, $value);
            } elseif (isset($arr[$apiProperty])) {
                $arr[$this->property] = $arr[$apiProperty];
                unset($arr[$apiProperty]);
            }
        }

        return $arr;
    }
}
