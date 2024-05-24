<?php

namespace TwoCheckout\REST6\Interfaces\Requests\Product;

use TwoCheckout\REST6\Interfaces\HTTP\RestRequestInterface;

interface GetProductInterface extends RestRequestInterface
{
    public function withName(string $value): GetProductInterface;
    public function withTypes(array $types): GetProductInterface;
    public function withEnabled(bool $enabled): GetProductInterface;
    public function withGroupName(string $groupName): GetProductInterface;
    public function withLimit(int $limit): GetProductInterface;
    public function withPage(int $page): GetProductInterface;
}