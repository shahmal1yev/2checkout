<?php

namespace TwoCheckout\REST6\Interfaces\Requests;


use TwoCheckout\Interfaces\HTTP\RequestInterface;

interface RestRequestInterface extends RequestInterface
{
    public function build(): RestRequestInterface;
}