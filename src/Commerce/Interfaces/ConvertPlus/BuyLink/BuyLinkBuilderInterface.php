<?php

namespace TwoCheckout\Commerce\Interfaces\ConvertPlus\BuyLink;

use TwoCheckout\Commerce\Enum\CurrencyEnum;
use TwoCheckout\Commerce\Interfaces\ConvertPlus\Entities\ProductInterface;
use TwoCheckout\Interfaces\BuilderInterface;
use TwoCheckout\Interfaces\HTTP\RequestInterface;

interface BuyLinkBuilderInterface extends BuilderInterface, RequestInterface
{
    public function setMerchant(string $merchant): BuyLinkBuilderInterface;

    public function setDynamic(bool $dynamic): BuyLinkBuilderInterface;

    public function enableTestMode(): BuyLinkBuilderInterface;
    public function setCurrency(string $currency): BuyLinkBuilderInterface;

    public function addProduct(ProductInterface $product): BuyLinkBuilderInterface;
    public function setRedirect(string $url, string $type): BuyLinkBuilderInterface;

}