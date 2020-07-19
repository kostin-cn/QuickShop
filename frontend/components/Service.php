<?php

namespace frontend\components;

class Service
{
    //    public static function formatPrice($price): string
    public static function formatPrice($price)
    {
        return number_format($price, 0, '.', ' ') . ' <span class="icon-rur"></span>';
    }

//    public static function formatWeight($weight): string
    public static function formatWeight($weight)
    {
        return $weight . ' г.';
    }

    public static function orderStatus($status)
    {
        $array = [
            0 => 'Ожидает',
            1 => 'Обработан',
        ];
        return $array[$status];
    }
}