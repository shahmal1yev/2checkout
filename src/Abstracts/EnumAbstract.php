<?php

namespace TwoCheckout\Abstracts;

abstract class EnumAbstract
{
    private function __construct($value)
    {
    }

    public static function getValues(): array
    {
        return static::$allowedValues;
    }

    public static function isValid($value): bool
    {
        return in_array($value, static::$allowedValues);
    }
}