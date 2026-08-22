<?php

namespace Lennord\FloridaySdk\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Lennord\FloridaySdk\FloridaySdk;

abstract class GenerateFloridayTokenCommand extends Command
{
    protected string $token;

    public function __construct(
        protected readonly FloridaySdk $sdk,
    )
    {
        parent::__construct();
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function handle(): int
    {
        $response = $this->sdk->oauth->fetchToken();

        $token = $response->json('access_token');

        if (!is_string($token)) {
            $this->error('No access token was returned.');

            return self::FAILURE;
        }

        $this->token = $token;

        return $this->handleToken();
    }

    abstract protected function handleToken(): int;
}
