<?php

declare(strict_types=1);

namespace Bigoen\ApiBridgeConverter\Model;

use Bigoen\ApiBridgeConverter\Model\Traits\PropertyAccessorTrait;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class MoveToTopProperty implements ConvertPropertyInterface
{
    use PropertyAccessorTrait;

    public function __construct(public string $deep)
    {
    }

    public function convert(array $arr): array
    {
        $accessor = self::getPropertyAccessor();
        if (!$accessor->isReadable($arr, $this->deep)) {
            return $arr;
        }

        return $accessor->getValue($arr, $this->deep);
    }
}