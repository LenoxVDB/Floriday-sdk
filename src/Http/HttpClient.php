<?php

namespace Lennord\FloridaySdk\Http;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Lennord\FloridaySdk\Contracts\CredentialsProvider;

class HttpClient
{
    private const string GET = 'GET';
    private const string POST = 'POST';

    public function __construct(
        private readonly CredentialsProvider $credentials,
    )
    {
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function get(string $url, array $options = []): Response
    {
        return $this->request(self::GET, $url, $options);
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function post(string $url, array $options = []): Response
    {
        return $this->request(self::POST, $url, $options);
    }

    /**
     * @throws ConnectionException
     * @throws RequestException
     */
    public function request(
        string $method,
        string $url,
        array  $options = [],
    ): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->credentials->getBearerToken(),
            'X-Api-Key' => $this->credentials->getApiToken(),
            'Content-Type' => 'application/json',
        ])->send(
            $method,
            config('floriday-sdk.base_api_url') . $url,
            $options
        );

        $response->throw();

        return $response;
    }
}
