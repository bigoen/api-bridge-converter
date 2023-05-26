<?php

declare(strict_types=1);

namespace Bigoen\ApiBridgeConverter\Model;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
interface ConvertPropertyInterface
{
    public function convert(array $arr): array;
}
