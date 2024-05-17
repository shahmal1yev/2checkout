<?php

namespace TwoCheckout\Interfaces\Data;

interface ContentHandlerInterface
{
    public function encode(object $data): string;
    public function decode(string $data): object;
}