<?php

namespace TwoCheckout\Commerce\Interfaces\Requests;


interface BuyLinkInterface extends CommerceRequestInterface
{
    public function addProduct(string $productID, array $prices, int $quantity): BuyLinkInterface;
    public function withLockedExpressMethod(): BuyLinkInterface;
    public function withoutLockedExpressMethod(): BuyLinkInterface;
    public function withLanguage(string $language): BuyLinkInterface;
}