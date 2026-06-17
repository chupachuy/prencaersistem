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

    public static function sanitize($value)
    {
        return htmlspecialchars(strip_tags(trim($value)));
    }

    // OPT-06: Métodos mencionados en AGENTS.md que faltaban
    public static function numeric($value)
    {
        return is_numeric($value);
    }

    public static function integer($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false && (int)$value >= 0;
    }

    public static function date($value, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $value);
        return $d && $d->format($format) === $value;
    }
}
