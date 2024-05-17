<?php

namespace TwoCheckout\Traits;

trait HasHTTPHeaderTrait
{
    public function parseHeaders(string $stringHeaders): object
    {
        $headers = [];
        foreach(explode("\r\n", $stringHeaders) as $headerLine)
            foreach(explode(": ", $headerLine) as $header => $value)
                $headers[$header] = trim($value);

        return (object) $headers;
    }
}