<?php

namespace Lennord\FloridaySdk\Http\Modules;

use Lennord\FloridaySdk\FloridaySdk;
use Illuminate\Http\Client\Response;

class TradeItems
{
    /**
     * The constructor.
     */
    public function __construct(
        private readonly FloridaySdk $sdk,
    )
    {
    }

    /**
     * Retrieves all trade items.
     *
     * @return Response Returns a JSON response containing the trade items data.
     * If successful, returns the trade items list.
     * If failed, returns a 500 error with the error message.
     */
    public function getAll(): Response
    {
        return $this->sdk->http->get('/trade-items');
    }
}
