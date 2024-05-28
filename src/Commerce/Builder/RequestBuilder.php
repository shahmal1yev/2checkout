<?php

namespace TwoCheckout\Commerce\Builder;

use LogicException;
use TwoCheckout\HTTP\Request;
use TwoCheckout\Interfaces\Data\ContentHandlerInterface;

class RequestBuilder extends Request
{
    public function __construct()
    {
        unset($this->contentHandler);

        $this->request = [];
        $this->request['headers'] = [];
        $this->request['body'] = [];
        $this->request['method'] = '';
        $this->request['uri'] = '';
        $this->request['queryParameters'] = [];
    }

    public function setContentHandler($contentHandler)
    {
        throw new LogicException(self::class  . " has not been implemented yet");
    }

    public function getDecoder(): ContentHandlerInterface
    {
        throw new LogicException(self::class  . " has not been implemented yet");
    }

    public function getEncoder(): ContentHandlerInterface
    {
        throw new LogicException(self::class  . " has not been implemented yet");
    }
}