<?php

namespace TwoCheckout\Interfaces\HTTP;

use TwoCheckout\Interfaces\Data\ContentHandlerInterface;

interface RequestInterface
{
    public function setHeader(string $path, $value): RequestInterface;
    public function setField(string $path, $value): RequestInterface;
    public function setQueryParam(string $path, $value): RequestInterface;
    public function setMethod(string $method): RequestInterface;
    public function setUri(string $uri): RequestInterface;
    public function getHeaders(): array;
    public function getMethod(): string;
    public function getEncoder(): ContentHandlerInterface;
    public function getDecoder(): ContentHandlerInterface;
    public function getBody(): object;
    public function getUri(): string;
    public function getQueryParams($encode = true);
}