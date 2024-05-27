<?php

namespace TwoCheckout\Commerce\Enum;

use TwoCheckout\Abstracts\EnumAbstract;

class CurrencyEnum extends EnumAbstract
{
    public const USD = 'USD';
    public const EUR = 'EUR';

    protected static array $allowedValues = [
        self::USD,
        self::EUR
    ];
}