<?php

namespace TwoCheckout\Interfaces\JWT;

interface JWTAlgorithmInterface
{
    public function getAlias(): string;
    public function getAlgorithm(): string;
}