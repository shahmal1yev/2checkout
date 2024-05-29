<?php

namespace TwoCheckout\Interfaces\JWT;

interface JWTConfigInterface
{
    public function getSubject(): string;
    public function getAlgorithm(): string;
    public function getAlgorithmAlias(): string;
    public function getExpiration(): int;
    public function validated(): JWTConfigInterface;
}