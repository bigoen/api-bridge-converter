<?php

declare(strict_types=1);

namespace Bigoen\ApiBridgeConverter\Model\Traits;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
trait PropertyAccessorTrait
{
    protected static ?PropertyAccessor $propertyAccessor = null;

    public static function getPropertyAccessor(): PropertyAccessor
    {
        if (!self::$propertyAccessor instanceof PropertyAccessor) {
            self::$propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        return self::$propertyAccessor;
    }
}
