<?php

namespace TwoCheckout\JWT\TwoCheckout;

use DateInterval;
use DateTime;
use DateTimeInterface;
use InvalidArgumentException;
use LogicException;
use TwoCheckout\Interfaces\JWT\JWTAlgorithmInterface;
use TwoCheckout\Interfaces\JWT\TwoCheckout\TwoCheckoutJWTConfigInterface;

class TwoCheckoutJWTConfig implements TwoCheckoutJWTConfigInterface
{
    protected string $merchantID;
    protected DateTimeInterface $expiration;
    protected JWTAlgorithmInterface $algorithm;

    public function getExpiration(): DateTimeInterface
    {
        return $this->expiration;
    }

    public function getMerchantID(): string
    {
        return $this->merchantID;
    }

    public function setExpiration(DateTimeInterface $expiration): TwoCheckoutJWTConfigInterface
    {
        $now = new DateTime();

        if ($expiration <= $now)
            throw new InvalidArgumentException("'\$expiration' must greater than current time");

        $this->expiration = $expiration;

        return $this;
    }

    public function setMerchantID(string $merchantID): TwoCheckoutJWTConfigInterface
    {
        $this->merchantID = $merchantID;

        return $this;
    }

    public function setAlgorithm(JWTAlgorithmInterface $algorithm): TwoCheckoutJWTConfigInterface
    {
        $this->algorithm = $algorithm;

        return $this;
    }

    public function getAlgorithm(): JWTAlgorithmInterface
    {
        return $this->algorithm;
    }
}