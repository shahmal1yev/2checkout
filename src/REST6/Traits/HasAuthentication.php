<?php

namespace TwoCheckout\REST6\Traits;

use DateTime;
use DateTimeZone;
use Exception;
use TwoCheckout\Interfaces\HTTP\RequestInterface;

trait HasAuthentication
{
    /**
     * @throws Exception
     */
    public function setAuthenticationHeader(string $code, string $secretKey): RequestInterface
    {
        if (! $this instanceof RequestInterface)
            throw new \LogicException("You must implement ".RequestInterface::class." for this trait");

        $date = $this->prepareDate();
        $hashContent = $this->prepareHashContent($code, $date);
        $hash = $this->prepareHash($hashContent, $secretKey);

        $headerContent = $this->prepareContent($code, $date, $hash);

        $this->setHeader("X-Avangate-Authentication", $headerContent);

        return $this;
    }

    /**
     * @throws Exception
     */
    private function prepareDate(): string
    {
        $date = new DateTime("now", new DateTimeZone("UTC"));

        return $date->format("Y-m-d H:i:s");
    }

    private function prepareHashContent(string $code, string $date): string
    {
        $codeLength = strlen($code);
        $dateLength = strlen($date);

        return $codeLength . $code . $dateLength . $date;
    }

    private function prepareHash(string $data, string $secretKey): string
    {
        return hash_hmac(
            "md5",
            $data,
            $secretKey
        );
    }

    private function prepareContent(string $code, string $date, string $hash): string
    {
        return "code='$code' date='$date' hash='$hash'";
    }
}