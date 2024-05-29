<?php

namespace TwoCheckout\JWT\Algorithms;

use TwoCheckout\Interfaces\JWT\JWTAlgorithmInterface;

class HS512 implements JWTAlgorithmInterface
{
    public function getAlgorithm(): string
    {
        return 'sha512';
    }

    public function getAlias(): string
    {
        return 'HS512';
    }
}