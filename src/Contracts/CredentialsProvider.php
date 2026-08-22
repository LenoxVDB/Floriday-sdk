<?php

namespace Lennord\FloridaySdk\Contracts;

interface CredentialsProvider
{
    public function getBearerToken(): string;

    public function getApiToken(): string;
}
