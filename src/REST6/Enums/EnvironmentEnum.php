<?php

namespace TwoCheckout\REST6\Enums;

use TwoCheckout\Abstracts\EnumAbstract;

class EnvironmentEnum extends EnumAbstract
{
    public const PRODUCTION = 'https://api.2checkout.com/rest/6.0';

    protected static array $allowedValues = [
        self::PRODUCTION
    ];
}