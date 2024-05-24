<?php

namespace TwoCheckout\Data\ContentHandlers;

use JsonException;
use TwoCheckout\Exceptions\Data\ContentHandlerException;
use TwoCheckout\Interfaces\Data\ContentHandlerInterface;

class JSONContentHandler implements ContentHandlerInterface
{
    /**
     * @throws ContentHandlerException
     */
    public function decode(string $data): object
    {
        try
        {

            $json = json_decode(
                $data,
                false,
                512,
                JSON_THROW_ON_ERROR
            );

            if (! is_object($json))
                $json = (object) ['result' => $json];

            return $json;
        }
        catch (JsonException $e)
        {
            throw new ContentHandlerException(
                "An error occurred while decoding JSON: {$e->getMessage()}",
                0,
                $e
            );
        }
    }

    /**
     * @throws JsonException
     */
    public function encode(object $data): string
    {
        return json_encode(
            $data,
            JSON_THROW_ON_ERROR
        );
    }
}