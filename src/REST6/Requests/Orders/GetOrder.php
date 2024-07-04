<?php

namespace TwoCheckout\REST6\Requests\Orders;

use TwoCheckout\HTTP\Request;
use TwoCheckout\Interfaces\Data\ContentHandlerInterface;
use TwoCheckout\REST6\Exceptions\RequiredOptionArgumentMissingException;
use TwoCheckout\REST6\Interfaces\Requests\Orders\GetOrderInterface;
use TwoCheckout\REST6\Interfaces\Requests\RestRequestInterface;
use TwoCheckout\REST6\Traits\WithAuthentication;

class GetOrder extends Request implements GetOrderInterface
{
    use WithAuthentication;

    public function __construct(ContentHandlerInterface $contentHandler)
    {
        parent::__construct($contentHandler);

        $this->setMethod('GET')
            ->setUri('/order')
            ->setHeader('Content-Type', 'application/json')
            ->setHeader('Accept', 'application/json');
    }

    public function withExternalReference(string $externalReference): GetOrderInterface
    {
        $uri = sprintf("/orders/%s", $externalReference);
        $this->setUri($uri);

        return $this;
    }

    /**
     * @throws RequiredOptionArgumentMissingException
     */
    public function build(): RestRequestInterface
    {
        $authHeaderName = "X-Avangate-Authentication";

        if (! $this->headerExists($authHeaderName))
            throw new RequiredOptionArgumentMissingException(
                self::class . " has missing required headers: $authHeaderName"
            );

        return $this;
    }
}