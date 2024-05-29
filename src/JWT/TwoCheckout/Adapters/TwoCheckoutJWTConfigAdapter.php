<?php

namespace TwoCheckout\JWT\TwoCheckout\Adapters;

use Exception;
use LogicException;
use TwoCheckout\Interfaces\JWT\JWTConfigInterface;
use TwoCheckout\Interfaces\JWT\TwoCheckout\TwoCheckoutJWTConfigInterface;

class TwoCheckoutJWTConfigAdapter implements JWTConfigInterface
{
    protected TwoCheckoutJWTConfigInterface $config;

    public function __construct(TwoCheckoutJWTConfigInterface $twoCheckoutJWTConfig)
    {
        $this->config = $twoCheckoutJWTConfig;
    }

    public function getSubject(): string
    {
        return $this->config->getMerchantID();
    }

    public function getAlgorithm(): string
    {
        $algorithmInterface = $this->config->getAlgorithm();
        $algorithm = $algorithmInterface->getAlgorithm();

        return $algorithm;
    }

    public function getAlgorithmAlias(): string
    {
        $algorithmInterface = $this->config->getAlgorithm();
        $alias = $algorithmInterface->getAlias();

        return $alias;
    }

    public function getExpiration(): int
    {
        $expiration = $this->config->getExpiration();

        return $expiration->getTimestamp();
    }

    public function validated(): JWTConfigInterface
    {
        $this->getSubject();
        $this->getAlgorithm();
        $this->getAlgorithmAlias();
        $this->getExpiration();

        return $this;
    }
}