<?php

namespace TwoCheckout\REST6\Enums;

use InvalidArgumentException;

class Environment
{
    public static function __callStatic($name, $arguments)
    {
        $name = strtoupper($name);

        if (! static::isset($name))
            throw new InvalidArgumentException("'$name' is not a valid environment.");

        return static::all()[$name];
    }

    public static function isset($name): bool
    {
        $name = strtoupper($name);

        return (isset(static::all()[$name]));
    }

    public static function all(): array
    {
        return [
            'PRODUCTION' => 'https://api.2checkout.com/rest/6.0'
        ];
    }
}