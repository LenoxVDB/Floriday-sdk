<?php

namespace Lennord\FloridaySdk;

use Lennord\FloridaySdk\Contracts\CredentialsProvider;
use Lennord\FloridaySdk\Http\HttpClient;
use Lennord\FloridaySdk\Http\Modules\Batch;
use Lennord\FloridaySdk\Http\Modules\Identities;
use Lennord\FloridaySdk\Http\Modules\Oauth;
use Lennord\FloridaySdk\Http\Modules\TradeItems;
use Lennord\FloridaySdk\Http\Modules\Warehouses;

class FloridaySdk

{
    public readonly HttpClient $http;
    public readonly Batch $batch;
    public readonly Oauth $oauth;
    public readonly TradeItems $tradeItems;
    public readonly Identities $identity;
    public readonly Warehouses $warehouse;

    public function __construct(
        CredentialsProvider $credentials,
    )
    {
        $this->http = new HttpClient($credentials);

        $this->registerModules();
    }

    /**
     * Initialize all modules and inject FloridaySdk into them.
     */
    private function registerModules(): void
    {
        $this->oauth = new Oauth();
        $this->batch = new Batch($this);
        $this->tradeItems = new TradeItems($this);
        $this->identity = new Identities($this);
        $this->warehouse = new Warehouses($this);
    }
}
