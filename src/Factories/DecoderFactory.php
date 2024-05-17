<?php

namespace TwoCheckout\Factories;

use TwoCheckout\Data\ContentHandlers\JSONContentHandler;
use TwoCheckout\Data\ContentHandlers\XMLContentHandler;
use TwoCheckout\Exceptions\Data\UnknownDataFormat;

class DecoderFactory
{
    /**
     * @throws UnknownDataFormat
     */
    public static function make(string $contentType)
    {
        switch ($contentType)
        {
            case 'application/json':
                return new JSONContentHandler;

            case 'application/xml':
                return new XMLContentHandler;

            default:
                throw new UnknownDataFormat("Unknown data type: '$contentType'");
        }
    }
}