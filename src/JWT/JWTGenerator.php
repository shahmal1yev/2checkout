<?php

namespace TwoCheckout\JWT;

use TwoCheckout\Interfaces\JWT\JWTConfigInterface;
use TwoCheckout\Interfaces\JWT\JWTGeneratorInterface;

class JWTGenerator implements JWTGeneratorInterface
{
    protected JWTConfigInterface $config;

    public function __construct(JWTConfigInterface $config)
    {
        $this->config = $config->validated();
    }

    public function generate(string $secretKey = null): string
    {
        $algorithm = $this->config->getAlgorithm();
        $content = $this->prepareContent();
        $signature = $this->prepareSignature($algorithm, $content, $secretKey);
        $token = "$content.$signature";

        return $token;
    }

    protected function prepareSignature(string $algorithm, string $content, ?string $secretKey): string
    {
        $signature = hash_hmac(
            $algorithm,
            $content,
            $secretKey,
            true
        );

        $signatureBase64UrlEncoded = base64_url_encode($signature);

        return $signatureBase64UrlEncoded;
    }

    protected function prepareHeader(): array
    {
        $header = [
            'alg' => $this->config->getAlgorithmAlias(),
            'typ' => 'JWT'
        ];

        return $header;
    }

    protected function preparePayload(): array
    {
        $payload = [
            'sub' => $this->config->getSubject(),
            'iat' => time(),
            'exp' => $this->config->getExpiration(),
        ];

        return $payload;
    }

    protected function prepareEncodedHeader(): string
    {
        $headers = $this->prepareHeader();
        $stringHeader = json_encode($headers, JSON_UNESCAPED_SLASHES);
        $base64UrlHeader = base64_url_encode($stringHeader);

        return $base64UrlHeader;
    }

    protected function prepareEncodedPayload(): string
    {
        $payload = $this->preparePayload();
        $stringPayload = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $base64UrlPayload = base64_url_encode($stringPayload);

        return $base64UrlPayload;
    }

    protected function prepareContent(): string
    {
        $base64UrlHeader = $this->prepareEncodedHeader();
        $base64UrlPayload = $this->prepareEncodedPayload();
        $content = "$base64UrlHeader.$base64UrlPayload";

        return $content;
    }
}