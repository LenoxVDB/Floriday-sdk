<?php

namespace Lennord\FloridaySdk\Resources\Base;

use Lennord\FloridaySdk\FloridayConnector;

class BaseResource
{
    /**
     * The constructor.
     */
    public function __construct(
        protected FloridayConnector $connector,
    )
    {
    }

    /**
     * Added the X-Api-Key header on the request
     */
    public function withApiKey(string $key): static
    {
        $this->connector->headers()->add(
            'X-Api-Key',
            $key
        );

        return $this;
    }
}
