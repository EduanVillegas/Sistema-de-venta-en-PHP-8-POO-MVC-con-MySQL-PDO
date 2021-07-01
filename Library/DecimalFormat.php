<?php
class DecimalFormat {
    public static function number_format($number){
        return number_format($number, 2, '.', ',') ;
    }
}