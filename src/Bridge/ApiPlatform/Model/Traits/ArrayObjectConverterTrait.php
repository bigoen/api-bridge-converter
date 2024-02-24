<?php

declare(strict_types=1);

namespace Bigoen\ApiBridgeConverter\Bridge\ApiPlatform\Model\Traits;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
trait ArrayObjectConverterTrait
{
    use \Bigoen\ApiBridgeConverter\Model\Traits\ArrayObjectConverterTrait;

    public static string $atId = 'jsonldId';
    public static string $atType = 'jsonldType';

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
        if (
            isset($arr['@id'], $arr['@type'])
            && $accessor->isWritable($model, self::$atId)
            && $accessor->isWritable($model, self::$atType)
        ) {
            $accessor->setValue($model, self::$atId, $arr['@id']);
            $accessor->setValue($model, self::$atType, $arr['@type']);
        }

        return $model;
    }

    public static function objectToArray(object $model, array $convertProperties = []): array
    {
        $propertyInfo = self::getPropertyInfo();
        $accessor = self::getPropertyAccessor();
        $arr = [];
        foreach ($propertyInfo->getProperties(\get_class($model)) as $property) {
            if ($accessor->isReadable($model, $property)) {
                $arr[$property] = $accessor->getValue($model, $property);
            }
        }
        if (
            $accessor->isReadable($model, self::$atId)
            && $accessor->isReadable($model, self::$atType)
        ) {
            $arr['@id'] = $accessor->getValue($model, self::$atId);
            $arr['@type'] = $accessor->getValue($model, self::$atType);
        }

        return self::convertProperties($convertProperties, $arr);
    }
}
