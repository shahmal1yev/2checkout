<?php

namespace TwoCheckout\REST6\Interfaces\Requests\Orders;

use TwoCheckout\REST6\Interfaces\Requests\RestRequestInterface;

interface GetOrderInterface extends RestRequestInterface
{
    public function withExternalReference(string $externalReference): GetOrderInterface;
}