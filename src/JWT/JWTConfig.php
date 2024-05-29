<?php

namespace TwoCheckout\JWT;

use TwoCheckout\Interfaces\JWT\JWTConfigInterface;

class JWTConfig implements JWTConfigInterface
{
    protected string $subject;
    protected string $algorithm;
    protected string $expiration;

    public function setSubject(string $sub): JWTConfigInterface
    {
        $this->subject = $sub;

        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setAlgorithm(string $algorithm): JWTConfigInterface
    {
        $this->algorithm = $algorithm;

        return $this;
    }

    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    public function setExpiration(int $exp): JWTConfigInterface
    {
        $this->expiration = $exp;

        return $this;
    }

    public function getExpiration(): int
    {
        return $this->expiration;
    }

    public function getHeader(): array
    {
        return [
            'alg' => $this->algorithm,
            'typ' => 'JWT',
        ];
    }

    public function getPayload(): array
    {
        return [
            'sub' => $this->subject,
            'iat' => time(),
            'exp' => $this->expiration,
        ];
    }

    public function getAlgorithmAlias(): string
    {
        return $this->algorithm;
    }
}