<?php

namespace Lennord\FloridaySdk\Resources\Token;


use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;

class TokenRequest extends Request implements HasBody
{
    use HasFormBody;

    /**
     * @inheritDoc
     */
    public ?bool $allowBaseUrlOverride = true;

    /**
     * @inheritDoc
     */
    protected Method $method = Method::POST;

    /**
     * @inheritDoc
     */
    public function resolveEndpoint(): string
    {
        return config('floriday-sdk.oauth_url');
    }

    /**
     * @inheritDoc
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function defaultBody(): array
    {
        return [
            'grant_type' => 'client_credentials',
            'client_id' => config('floriday-sdk.client'),
            'client_secret' => config('floriday-sdk.secret'),
            'scope' => config('floriday-sdk.scope')
        ];
    }
}
