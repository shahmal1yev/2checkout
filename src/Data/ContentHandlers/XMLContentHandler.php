<?php

namespace TwoCheckout\Data\ContentHandlers;

use SimpleXMLElement;
use TwoCheckout\Exceptions\Data\ContentHandlerException;
use TwoCheckout\Interfaces\Data\ContentHandlerInterface;

class XMLContentHandler implements ContentHandlerInterface
{
    /**
     * @throws ContentHandlerException
     */
    public function decode(string $data): object
    {
        $object = simplexml_load_string(
            $data,
            "SimpleXMLElement",
            512,
            LIBXML_NOCDATA
        );

        if ($object !== false)
            return $object;

        throw new ContentHandlerException("An error occurred while decoding JSON: Invalid XML data.");
    }

    /**
     * @throws ContentHandlerException
     */
    public function encode(object $data): string
    {
        if ($data instanceof SimpleXMLElement)
            return $data->asXML();

        throw new ContentHandlerException("Data must be an instance of SimpleXMLElement.");
    }
}