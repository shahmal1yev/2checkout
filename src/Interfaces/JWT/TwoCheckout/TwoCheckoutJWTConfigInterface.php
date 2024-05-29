<?php

namespace TwoCheckout\Interfaces\JWT\TwoCheckout;

use DateTimeInterface;
use TwoCheckout\Interfaces\JWT\JWTAlgorithmInterface;

interface TwoCheckoutJWTConfigInterface
{
    public function setExpiration(DateTimeInterface $expiration): TwoCheckoutJWTConfigInterface;
    public function getExpiration(): DateTimeInterface;
    public function setMerchantID(string $merchantID): TwoCheckoutJWTConfigInterface;
    public function getMerchantID(): string;
    public function setAlgorithm(JWTAlgorithmInterface $algorithm): TwoCheckoutJWTConfigInterface;
    public function getAlgorithm(): JWTAlgorithmInterface;
}