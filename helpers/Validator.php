<?php
class Validator
{
    public static function required($value)
    {
        return !empty(trim($value));
    }

    public static function email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function min($value, $min)
    {
        return strlen(trim($value)) >= $min;
    }

    public static function max($value, $max)
    {
        return strlen(trim($value)) <= $max;
    }

    public static function santize($value)
    {
        return htmlspecialchars(strip_tags(trim($value)));
    }
}
