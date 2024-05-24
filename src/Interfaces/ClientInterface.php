<?php

namespace TwoCheckout\Interfaces;

interface ClientInterface
{
    public function execute(string $environment): object;
}