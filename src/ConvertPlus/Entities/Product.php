<?php

namespace TwoCheckout\ConvertPlus\Entities;

use InvalidArgumentException;
use TwoCheckout\Commerce\Interfaces\ConvertPlus\Entities\ProductInterface;

class Product implements ProductInterface
{
    protected string $name;
    protected string $quantity;
    protected $price;
    protected string $type;

    public function setPrice($price): ProductInterface
    {
        if (! is_numeric($price))
            throw new InvalidArgumentException("'$price' must be numeric'");

        $price = round(floatval($price), 2);

        if ($price < 0)
            throw new InvalidArgumentException("'$price' must be greater than 0");

        $this->price = $price;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setQuantity(int $quantity): ProductInterface
    {
        if ($quantity < 0)
            throw new InvalidArgumentException("'$quantity' must be greater than 0");

        $this->quantity = $quantity;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setName(string $name): ProductInterface
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}