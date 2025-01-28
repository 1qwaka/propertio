<?php

namespace App\Persistence\Converters;

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
