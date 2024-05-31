<?php

namespace TwoCheckout\ConvertPlus\Enums;

use TwoCheckout\Abstracts\EnumAbstract;

class RedirectTypeEnum extends EnumAbstract
{
    public const HEADER = 'header';
    public const REDIRECT = 'redirect';

    protected static array $allowedValues = [
        self::HEADER,
        self::REDIRECT,
    ];
}