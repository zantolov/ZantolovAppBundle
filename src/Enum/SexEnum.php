<?php

namespace Zantolov\AppBundle\Enum;

class SexEnum extends BaseEnum
{
    const SEX_MALE = 1;
    const SEX_FEMALE = 2;

    protected static $labels = [
        self::SEX_FEMALE => 'sex.female',
        self::SEX_MALE   => 'sex.male'
    ];

    public static function getShortLabel($id){
        switch($id){
            case self::SEX_MALE: return 'sex.m';
            case self::SEX_FEMALE: return 'sex.f';
        }
    }

}