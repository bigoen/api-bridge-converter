<?php

declare(strict_types=1);

namespace Bigoen\ApiBridgeConverter\Model\Traits;

use Bigoen\ApiBridgeConverter\Model\ConvertPropertyInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
trait ArrayObjectConverterTrait
{
    use PropertyAccessorTrait;
    use PropertyInfoTrait;

    public static function arrayToObject(mixed $model, array $arr, array $convertProperties = []): mixed
    {
        $accessor = self::getPropertyAccessor();
        $arr = self::convertProperties($convertProperties, $arr);
        foreach ($arr as $property => $value) {
            if ($accessor->isWritable($model, $property)) {
                $propertyValue = $accessor->getValue($model, $property);
                if ($propertyValue instanceof Collection && \is_array($value)) {
                    foreach ($value as $data) {
                        $propertyValue->add($data);
                    }
                } else {
                    try {
                        $accessor->setValue($model, $property, $value);
                    } catch (InvalidArgumentException) {
                    }
                }
            }
        }

        return $model;
    }

    public static function objectToArray(?object $model, array $convertProperties = []): array
    {
        if (null === $model) {
            return [];
        }
        $propertyInfo = self::getPropertyInfo();
        $accessor = self::getPropertyAccessor();
        $arr = [];
        foreach ($propertyInfo->getProperties(\get_class($model)) as $property) {
            if ($accessor->isReadable($model, $property)) {
                $arr[$property] = $accessor->getValue($model, $property);
            }
        }

        return self::convertProperties($convertProperties, $arr);
    }

    public static function convertProperties(array $convertProperties, array $arr): array
    {
        /** @var ConvertPropertyInterface $convertProperty */
        foreach ($convertProperties as $convertProperty) {
            $arr = $convertProperty->convert($arr);
        }

        return $arr;
    }
}
