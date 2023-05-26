<?php

declare(strict_types=1);

namespace Bigoen\ApiBridgeConverter\Model;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class ConvertUnsetsProperty implements ConvertPropertyInterface
{
    public function __construct(public array $properties = [])
    {
    }

    public function convert(array $arr): array
    {
        foreach ($this->properties as $property) {
            unset($arr[$property]);
        }

        return $arr;
    }
}
