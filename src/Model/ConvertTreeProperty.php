<?php

declare(strict_types=1);

namespace Bigoen\ApiBridgeConverter\Model;

use Bigoen\ApiBridgeConverter\Model\Traits\ArrayObjectConverterTrait;
use Bigoen\ApiBridgeConverter\Model\Traits\PropertyAccessorTrait;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class ConvertTreeProperty implements ConvertPropertyInterface
{
    use PropertyAccessorTrait;
    use ArrayObjectConverterTrait;

    public ?string $property = null;
    public ?bool $isArray = null;
    public ?string $deep = null;
    public ?string $itemClass = null;
    public ?array $items = null;
    public array $convertProperties = [];

    public static function new(
        string $property,
        bool $isArray,
        ?string $deep = null,
        ?string $itemClass = null,
        ?array $items = null,
        array $convertProperties = []
    ): self {
        $object = new self();
        $object->property = $property;
        $object->isArray = $isArray;
        $object->deep = $deep ?? $property;
        $object->itemClass = $itemClass;
        $object->items = $items;
        $object->convertProperties = $convertProperties;

        return $object;
    }

    public function convert(array $arr): array
    {
        $accessor = self::getPropertyAccessor();
        $property = $this->property;
        $deep = $this->deep;
        $convertValues = $this->items;
        $subConvertProperties = $this->convertProperties;
        $haveSubConvertProperties = \count($subConvertProperties) > 0;
        if (false !== strpos($deep, '[]') && $accessor->isWritable($arr, $property)) {
            $items = [];
            $subArr = $accessor->getValue($arr, $property) ?? [];
            foreach ($subArr as $key => $item) {
                $deepKey = str_replace('[]', "[$key]", $deep);
                $accessValue = $accessor->getValue($arr, $deepKey);
                if (null !== $accessValue && isset($convertValues[$accessValue])) {
                    if (\is_string($item)) {
                        $items[] = $convertValues[$accessValue];
                    } elseif (\is_array($item)) {
                        if (true === $haveSubConvertProperties) {
                            $item = self::convertProperties($subConvertProperties, $item);
                        }
                        $items[] = self::arrayToObject($convertValues[$accessValue], $item);
                    }
                } elseif (\is_array($item)) {
                    if (true === $haveSubConvertProperties) {
                        $item = self::convertProperties($subConvertProperties, $item);
                    }
                    if (\is_string($this->itemClass)) {
                        $items[] = self::arrayToObject(new $this->itemClass(), $item);
                    }
                }
            }
            $accessor->setValue($arr, $property, $items);
        } else {
            if ($accessor->isReadable($arr, $deep) && $accessor->isWritable($arr, $property)) {
                $accessValue = $accessor->getValue($arr, $deep);
                $onlyConvertAccessValue = $accessor->getValue($arr, $property);
                if (null !== $accessValue && isset($convertValues[$accessValue])) {
                    $accessor->setValue(
                        $arr,
                        $property,
                        $convertValues[$accessValue]
                    );
                } elseif (null !== $onlyConvertAccessValue && \is_array($onlyConvertAccessValue)) {
                    if (true === $haveSubConvertProperties) {
                        $onlyConvertAccessValue = self::convertProperties($subConvertProperties, $onlyConvertAccessValue);
                    }
                    if (\is_string($this->itemClass)) {
                        $accessor->setValue(
                            $arr,
                            $property,
                            self::arrayToObject(new $this->itemClass(), $onlyConvertAccessValue)
                        );
                    }
                }
            }
        }

        return $arr;
    }
}
