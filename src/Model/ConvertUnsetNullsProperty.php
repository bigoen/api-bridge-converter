<?php

declare(strict_types=1);

namespace Bigoen\ApiBridgeConverter\Model;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class ConvertUnsetNullsProperty implements ConvertPropertyInterface
{
    public function convert(array $arr): array
    {
        foreach ($arr as $key => $value) {
            if (null !== $value) {
                continue;
            }
            unset($arr[$key]);
        }

        return $arr;
    }
}
