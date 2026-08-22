<?php

namespace Lennord\FloridaySdk\Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Lennord\FloridaySdk\Http\Modules\Oauth;

class OauthTest extends TestCase
{
    public function test_fetch_token_posts_form_params_to_configured_oauth_url_and_returns_response(): void
    {
        Config::set('floridaysdk.oauth_url', 'https://login.example/oauth/token');
        Config::set('floridaysdk.client', 'cid');
        Config::set('floridaysdk.secret', 'sec');
        Config::set('floridaysdk.scope', 'sc');

        Http::fake([
            'https://login.example/oauth/token' => Http::response(['access_token' => 'abc'], 200)
        ]);

        $oauth = new Oauth();
        $res = $oauth->fetchToken();

        $this->assertSame('abc', $res->json('access_token'));

        Http::assertSent(function ($request) {
            return $request->method() === 'POST'
                && $request->url() === 'https://login.example/oauth/token'
                && $request->hasHeader('Accept', 'application/json')
                && $request['client_id'] === 'cid'
                && $request['client_secret'] === 'sec'
                && $request['scope'] === 'sc'
                && $request['grant_type'] === 'client_credentials';
        });
    }

    public function test_fetch_token_throws_on_non_2xx(): void
    {
        Config::set('floridaysdk.oauth_url', 'https://login.example/oauth/token');
        Http::fake([
            'https://login.example/oauth/token' => Http::response(['error' => 'invalid'], 401)
        ]);

        $oauth = new Oauth();

        $this->expectException(RequestException::class);
        $oauth->fetchToken();
    }
}
