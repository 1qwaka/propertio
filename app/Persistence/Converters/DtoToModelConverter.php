<?php

namespace App\Persistence\Converters;

use Illuminate\Support\Str;

/**
 * Helper class to convert DTO objects to array
 * for passing to eloquent model methods
 * like update, create, etc.
 */
class DtoToModelConverter
{
    public static function toArray(object $dto, bool $filter = true): array
    {
        $res = array();
        foreach (get_object_vars($dto) as $key => $val) {
            $res[Str::snake($key)] = self::isEnum($val) ? $val->value : $val;
        }
        return $filter
            ? array_filter($res, fn($v) => $v !== null)
            : $res;
    }

    private static function isEnum($variable): bool {
        if (is_object($variable)) {
            $class = get_class($variable);
            return enum_exists($class) && $variable instanceof $class;
        }
        return false;
    }
}
