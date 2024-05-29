<?php

namespace TwoCheckout\ConvertPlus\Enums;

use TwoCheckout\Abstracts\EnumAbstract;

class EnvironmentEnum extends EnumAbstract
{
    public const PRODUCTION = 'https://secure.2checkout.com/checkout/api';

    protected static array $allowedValues = [
        self::PRODUCTION
    ];
}