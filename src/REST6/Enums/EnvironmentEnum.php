<?php

namespace TwoCheckout\REST6\Enums;

use InvalidArgumentException;
use TwoCheckout\REST6\Abstracts\EnumAbstract;

class EnvironmentEnum extends EnumAbstract
{
    public const PRODUCTION = 'REGULAR';

    protected static array $allowedValues = [
        self::PRODUCTION
    ];
}