<?php

namespace TwoCheckout\ConvertPlus\BuyLink;

use InvalidArgumentException;
use TwoCheckout\Commerce\Enum\CurrencyEnum;
use TwoCheckout\Commerce\Interfaces\ConvertPlus\BuyLink\BuyLinkBuilderInterface;
use TwoCheckout\Commerce\Interfaces\ConvertPlus\Entities\ProductInterface;
use TwoCheckout\ConvertPlus\Enums\RedirectTypeEnum;
use TwoCheckout\Data\ContentHandlers\JSONContentHandler;
use TwoCheckout\HTTP\Request;

class BuyLinkBuilder extends Request implements BuyLinkBuilderInterface
{
    protected array $products = [];

    public function __construct(string $jwtToken)
    {
        parent::__construct(new JSONContentHandler);

        $this->setUri('/encrypt/generate/signature')
            ->setMethod('POST')
            ->setHeader('Accept', 'application/json')
            ->setHeader('Content-Type', 'application/json')
            ->setHeader('merchant-token', $jwtToken);
    }

    public function setMerchant(string $merchant): BuyLinkBuilderInterface
    {
        $this->setQueryParam('merchant', $merchant)
            ->setField('merchant', $merchant);

        return $this;
    }

    public function setDynamic(bool $dynamic): BuyLinkBuilderInterface
    {
        $this->setQueryParam('dynamic', "$dynamic")
            ->setField('dynamic', "$dynamic");

        return $this;
    }

    public function enableTestMode(): BuyLinkBuilderInterface
    {
        $this->setQueryParam('test', '1')
            ->setField('test', '1');

        return $this;
    }

    public function setCurrency(string $currency): BuyLinkBuilderInterface
    {
        if (! CurrencyEnum::isValid($currency))
            throw new InvalidArgumentException("'$currency' is not a valid currency code.");

        $this->setQueryParam('currency', $currency)
            ->setField('currency', $currency);

        return $this;
    }

    public function addProduct(ProductInterface $product): BuyLinkBuilderInterface
    {
        $this->products[] = $product;

        return $this;
    }

    public function setRedirect(string $url, string $type): BuyLinkBuilderInterface
    {
        if (! filter_var($url, FILTER_VALIDATE_URL))
            throw new InvalidArgumentException("'$url' is not a valid URL.");

        if (! RedirectTypeEnum::isValid($type))
            throw new InvalidArgumentException("'$type' is not a valid redirect type.");

        $this->setQueryParam('return-url', $url)
            ->setQueryParam('return-type', $type)
            ->setField('return-url', $url)
            ->setField('return-type', $type);

        return $this;
    }

    public function build(): BuyLinkBuilderInterface
    {
        $this->setProducts();

        return $this;
    }

    protected function setProducts(): void
    {
        $this->setProductNames();
        $this->setProductQuantities();
        $this->setProductPrices();
    }

    protected function setProductNames(): void
    {
        $this->setProductData('prod', 'getName');
    }

    protected function setProductQuantities(): void
    {
        $this->setProductData('qty', 'getQuantity');
    }

    protected function setProductPrices(): void
    {
        $this->setProductData('price', 'getPrice');
    }

    protected function setProductData(string $key, string $method): void
    {
        $data = array_reduce($this->products, function ($carry, ProductInterface $product) use ($method) {
            $carry .= $product->$method() . ';';

            return $carry;
        });

        $editedData = rtrim($data, ';');

        if (empty($editedData))
            return;


        $this->setQueryParam($key, $editedData)
            ->setField($key, $editedData);
    }
}