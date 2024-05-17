<?php

namespace TwoCheckout\HTTP;

use TwoCheckout\Helpers\Arr;
use TwoCheckout\Interfaces\Data\ContentHandlerInterface;
use TwoCheckout\Interfaces\HTTP\RequestInterface;

class Request implements RequestInterface
{
    protected array $request;
    protected ContentHandlerInterface $contentHandler;
    
    public function __construct(ContentHandlerInterface $contentHandler)
    {
        $this->request = [];
        $this->request['headers'] = [];
        $this->request['body'] = [];
        $this->request['method'] = '';
        $this->request['uri'] = '';
        $this->request['queryParameters'] = [];
        $this->contentHandler = $contentHandler;
    }

    public function setHeader(string $path, $value): RequestInterface
    {
        Arr::set($this->request['headers'], $path, $value);
        
        return $this;
    }
    
    public function getHeaders(): array
    {
        $headers = [];

        foreach($this->request['headers'] as $key => $value)
            $headers[] = "$key: $value";

        return $headers;
    }
    
    public function setMethod(string $method): RequestInterface
    {
        $this->request['method'] = $method;
        
        return $this;
    }

    public function getMethod(): string
    {
        return $this->request['method'];
    }

    public function getEncoder(): ContentHandlerInterface
    {
        return $this->contentHandler;
    }

    public function getDecoder(): ContentHandlerInterface
    {
        return $this->contentHandler;
    }

    public function setField(string $path, $value): RequestInterface
    {
        Arr::set($this->request['body'], $path, $value);

        return $this;
    }

    public function getBody(): object
    {
        return (object) $this->request['body'];
    }

    public function setUri(string $uri): RequestInterface
    {
        $this->request['uri'] = $uri;

        return $this;
    }

    public function getUri(): string
    {
        return $this->request['uri'];
    }

    public function setQueryParam(string $path, $value): RequestInterface
    {
        Arr::set($this->request['queryParameters'], $path, $value);

        return $this;
    }

    public function getQueryParams($encode = true)
    {
        if ($encode)
            return http_build_query($this->request['queryParameters']);

        return $this->request['queryParameters'];
    }
}