<?php

namespace TwoCheckout\Interfaces\JWT;

interface JWTGeneratorInterface
{
    public function generate(string $secretKey = null): string;
}