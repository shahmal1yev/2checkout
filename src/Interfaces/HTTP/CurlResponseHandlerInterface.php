<?php

namespace TwoCheckout\Interfaces\HTTP;

interface CurlResponseHandlerInterface
{
    public function handle(int $statusCode, object $headers, object $body): object;
}