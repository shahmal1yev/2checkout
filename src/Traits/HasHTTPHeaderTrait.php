<?php

namespace TwoCheckout\Traits;

trait HasHTTPHeaderTrait
{
    public function parseHeaders(string $stringHeaders): object
    {
        $headerLines = explode("\r\n", $stringHeaders);
        foreach($headerLines as $headerLine)
        {
            $headerParts = explode(": ", $headerLine, 2);
            $headerName = trim(current($headerParts));
            $headerValue = trim(next($headerParts));

            $headers[$headerName] = $headerValue;
        }

        return (object) $headers;
    }
}