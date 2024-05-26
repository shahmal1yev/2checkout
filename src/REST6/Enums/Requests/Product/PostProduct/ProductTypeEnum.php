<?php

namespace TwoCheckout\REST6\Enums\Requests\Product\PostProduct;

use TwoCheckout\Abstracts\EnumAbstract;

class ProductTypeEnum extends EnumAbstract
{
    public const REGULAR = 'REGULAR';
    public const BUNDLE = 'BUNDLE';

    protected static array $allowedValues = [
        self::REGULAR,
        self::BUNDLE
    ];
}