<?php

namespace TwoCheckout\Commerce\Enum;

use TwoCheckout\Abstracts\EnumAbstract;

class EnvironmentEnum extends EnumAbstract
{
    public const PRODUCTION = 'https://secure.2checkout.com';

    protected static array $allowedValues = [
        self::PRODUCTION
    ];
}