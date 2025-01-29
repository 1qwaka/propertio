<?php

namespace App\Persistence\Converters;

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
            $res[\Str::snake($key)] = $val;
        }
        return $filter
            ? array_filter($res, fn($v) => $v !== null)
            : $res;
    }
}
