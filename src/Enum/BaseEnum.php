<?php

namespace Zantolov\AppBundle\Enum;

abstract class BaseEnum
{

    protected static $labels = array();

    static function getConstants()
    {
        $oClass = new \ReflectionClass(static::class);
        return $oClass->getConstants();
    }


    /**
     * @param $id
     * @return mixed|null
     */
    public static function getLabelById($id)
    {
        if (isset(static::$labels[$id])) {
            return static::$labels[$id];
        }

        return null;
    }


    /**
     * @return array
     */
    public static function getOptions($objects = false)
    {
        $constants = self::getConstants();
        $keys = array_values($constants);

        $return = array();
        foreach ($keys as $key) {
            $value = self::getLabelById($key);
            if (empty($value)) {
                continue;
            }
            if ($objects) {
                $return[] = array('name' => $value, 'id' => $key);
            } else {
                $return[$key] = $value;
            }
        }
        return $return;
    }

}