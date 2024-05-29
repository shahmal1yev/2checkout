<?php

namespace TwoCheckout\Commerce\Interfaces\ConvertPlus\Entities;

interface ProductInterface
{
    public function getPrice();
    public function getQuantity(): int;
    public function getName(): string;
}