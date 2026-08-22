<?php

namespace Lennord\FloridaySdk\Http;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Lennord\FloridaySdk\Contracts\CredentialsProvider;

/**
 * Lightweight HTTP client used by the SDK to communicate with the Floriday API.
 *
 * This client centralizes headers, authentication, and base URL handling so that
 * individual modules can focus on their domain logic.
 */
class HttpClient
{
    private const string GET = 'GET';
    private const string POST = 'POST';

    /**
     * The constructor.
     */
    public function __construct(
        private readonly CredentialsProvider $credentials,
    )
    {
    }

    /**
     * Send a GET request to the Floriday API.
     *
     * @param string $url Relative endpoint path (e.g. "/trade-items").
     * @param array $options Extra request options passed to Laravel HTTP client (e.g. 'query', 'json').
     *
     * @return Response The HTTP response from Floriday.
     *
     * @throws RequestException When the response has a client or server error status.
     * @throws ConnectionException When the request cannot reach the server.
     */
    public function get(string $url, array $options = []): Response
    {
        return $this->request(self::GET, $url, $options);
    }

    /**
     * Send a POST request to the Floriday API.
     *
     * @param string $url Relative endpoint path (e.g. "/batches").
     * @param array $options Extra request options (e.g. 'json' body, 'query').
     *
     * @return Response The HTTP response from Floriday.
     *
     * @throws RequestException When the response has a client or server error status.
     * @throws ConnectionException When the request cannot reach the server.
     */
    public function post(string $url, array $options = []): Response
    {
        return $this->request(self::POST, $url, $options);
    }

    /**
     * Send a request to the Floriday API with shared SDK headers.
     *
     * Headers include the bearer token and API key from the configured
     * `CredentialsProvider`, and the base URL is resolved from
     * `config('floriday-sdk.base_api_url')`.
     *
     * @param string $method HTTP method (e.g. GET, POST).
     * @param string $url Relative endpoint path (e.g. "/identities").
     * @param array $options Extra request options passed directly to Laravel's HTTP client.
     *
     * @return Response The HTTP response from Floriday.
     *
     * @throws ConnectionException When the request cannot reach the server.
     * @throws RequestException When the response has a client or server error status.
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
