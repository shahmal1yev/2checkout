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

    public function setPrice($price)
    {
        $allowedTypes = ['integer', 'double'];

        if (! in_array(gettype($price), $allowedTypes))
            throw new InvalidArgumentException("'$price' must be numeric");

        if ($price < 0)
            throw new InvalidArgumentException("'$price' must be greater than 0");

        $this->price = $price;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setQuantity(int $quantity)
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

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}