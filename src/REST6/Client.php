<?php

namespace TwoCheckout\REST6;

use TwoCheckout\Exceptions\HTTP\CurlException;
use TwoCheckout\Interfaces\HTTP\CurlResponseHandlerInterface;
use TwoCheckout\REST6\Enums\Environment;
use TwoCheckout\REST6\Interfaces\HTTP\RestRequestInterface;
use TwoCheckout\Traits\HasHTTPHeaderTrait;

class Client
{
    use HasHTTPHeaderTrait;

    protected RestRequestInterface $request;
    protected CurlResponseHandlerInterface $responseHandler;

    public function __construct(
        RestRequestInterface $request,
        CurlResponseHandlerInterface $curlResponseHandler
    )
    {
        $this->request = $request;
        $this->responseHandler = $curlResponseHandler;
    }

    /**
     * @throws CurlException
     */
    public function execute(string $environment): object
    {
        $handle = curl_init();

        $fullUrl = $this->getFullUrl($environment);
        $method = $this->request->getMethod();
        $headers = $this->request->getHeaders();
        $body = $this->request->getBody();
        $encodedBody = $this->request->getEncoder()->encode($body);

        curl_setopt_array($handle, [
            CURLOPT_URL => $fullUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $encodedBody,
        ]);

        $response = curl_exec($handle);

        if ($response === false)
            throw new CurlException("An error occurred while trying to execute the request: " . curl_error($handle));

        $headerSize = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        $headers = $this->parseHeaders(substr($response, 0, $headerSize));
        $body = $this->request->getDecoder()->decode(substr($response, $headerSize));

        $statusCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        return $this->responseHandler->handle(
            $statusCode,
            $headers,
            $body
        );
    }

    protected function getFullUrl($environment): string
    {
        $url = Environment::$environment();
        $uri = $this->request->getUri();
        $queryParameters = $this->request->getQueryParams();

        return $url . $uri . "?$queryParameters";
    }
}