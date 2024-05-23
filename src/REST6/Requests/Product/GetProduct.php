<?php

namespace TwoCheckout\REST6\Requests\Product;

use TwoCheckout\HTTP\Request;
use TwoCheckout\Interfaces\Data\ContentHandlerInterface;
use TwoCheckout\REST6\Interfaces\HTTP\RestRequestInterface;
use TwoCheckout\REST6\Traits\WithAuthentication;

class GetProduct extends Request implements RestRequestInterface
{
    use WithAuthentication;

    public function __construct(ContentHandlerInterface $contentHandler)
    {
        parent::__construct($contentHandler);

        $this->setMethod('GET')
            ->setUri('/products')
            ->setHeader('Content-Type', 'application/json')
            ->setHeader('Accept', 'application/json');
    }

    public function setName(string $value): RestRequestInterface
    {
        $this->setQueryParam("Name", $value);

        return $this;
    }
}