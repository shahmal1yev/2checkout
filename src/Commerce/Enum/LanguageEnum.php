<?php

namespace TwoCheckout\Commerce\Enum;

use TwoCheckout\Abstracts\EnumAbstract;

class LanguageEnum extends EnumAbstract
{
    public const ENGLISH = 'en';

    protected static array $allowedValues = [
        self::ENGLISH
    ];
}