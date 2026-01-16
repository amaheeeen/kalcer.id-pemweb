<?php

namespace App\Helpers;

class FormatHelper
{
    public static function shortNumber($num)
    {
        if ($num < 1000) return $num;
        
        if ($num < 1000000) {
            return number_format($num / 1000, 1) . 'K'; // 12.5K
        }
        
        return number_format($num / 1000000, 1) . 'M'; // 1.2M
    }
}