<?php

namespace Tests\Util;

class TestUtil
{
    public static function objToArray($obj) {
        //only process if it's an object or array being passed to the function
        if (self::isEnum($obj)) {
            return $obj->value;
        } else if (is_object($obj) || is_array($obj)) {
            $ret = (array) $obj;
            foreach($ret as &$item) {
                //recursively process EACH element regardless of type
                $item = self::objToArray($item);
            }
            return $ret;
        }
        //otherwise (i.e. for scalar values) return without modification
        else {
            return $obj;
        }
    }

    public static function unsetKeys(array $keys, array&... $arrays): void
    {
        foreach ($arrays as &$array) {
            foreach ($keys as $key) {
                unset($array[$key]);
            }
        }
    }

    private static function isEnum($variable): bool {
        if (is_object($variable)) {
            $class = get_class($variable);
            return enum_exists($class) && $variable instanceof $class;
        }
        return false;
    }
}
