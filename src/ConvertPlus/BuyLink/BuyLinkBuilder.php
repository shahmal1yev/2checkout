<?php

namespace TwoCheckout\ConvertPlus\BuyLink;

use InvalidArgumentException;
use TwoCheckout\Commerce\Enum\CurrencyEnum;
use TwoCheckout\Commerce\Interfaces\ConvertPlus\BuyLink\BuyLinkBuilderInterface;
use TwoCheckout\Commerce\Interfaces\ConvertPlus\Entities\ProductInterface;
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

    public function setMerchant(string $merchant)
    {
        $this->setQueryParam('merchant', $merchant)
            ->setField('merchant', $merchant);

        return $this;
    }

    public function setDynamic(bool $dynamic)
    {
        $this->setQueryParam('dynamic', "$dynamic")
            ->setField('dynamic', "$dynamic");

        return $this;
    }

    public function enableTestMode()
    {
        $this->setQueryParam('test', '1')
            ->setField('test', '1');

        return $this;
    }

    public function setCurrency(string $currency)
    {
        if (! CurrencyEnum::isValid($currency))
            throw new InvalidArgumentException("'$currency' is not a valid currency code.");

        $this->setQueryParam('currency', $currency)
            ->setField('currency', $currency);

        return $this;
    }

    public function addProduct(ProductInterface $product)
    {
        $this->products[] = $product;

        return $this;
    }

    public function build()
    {
        $this->setProducts();

        return $this;
    }

    protected function setProducts()
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

    protected function setProductData(string $key, string $method)
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