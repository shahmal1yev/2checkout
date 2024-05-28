<?php

namespace TwoCheckout\Commerce\Interfaces\Requests;

use TwoCheckout\Interfaces\HTTP\RequestInterface;

interface CommerceRequestInterface extends RequestInterface
{
    public function build(string $environment): string;
}