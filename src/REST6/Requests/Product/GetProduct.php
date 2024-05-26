<?php

namespace TwoCheckout\REST6\Requests\Product;

use TwoCheckout\HTTP\Request;
use TwoCheckout\Interfaces\Data\ContentHandlerInterface;
use TwoCheckout\REST6\Exceptions\RequiredOptionArgumentMissingException;
use TwoCheckout\REST6\Interfaces\Requests\Product\GetProductInterface;
use TwoCheckout\REST6\Interfaces\Requests\RestRequestInterface;
use TwoCheckout\REST6\Traits\WithAuthentication;

class GetProduct extends Request implements GetProductInterface
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

    public function withName(string $value): GetProductInterface
    {
        $this->setQueryParam('Name', $value);

        return $this;
    }

    public function withTypes(array $types): GetProductInterface
    {
        $this->setQueryParam('Types', $types);

        return $this;
    }

    public function withEnabled(bool $enabled): GetProductInterface
    {
        $this->setQueryParam('Enabled', $enabled);

        return $this;
    }

    public function withGroupName(string $groupName): GetProductInterface
    {
        $this->setQueryParam('GroupName', $groupName);

        return $this;
    }

    public function withLimit(int $limit): GetProductInterface
    {
        $this->setQueryParam('Limit', $limit);

        return $this;
    }

    public function withPage(int $page): GetProductInterface
    {
        $this->setQueryParam('Page', $page);

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