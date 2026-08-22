<?php

namespace Lennord\FloridaySdk\Tests;

use Illuminate\Support\Facades\Http;
use Lennord\FloridaySdk\Commands\GenerateFloridayTokenCommand;
use Lennord\FloridaySdk\FloridaySdk;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateFloridayTokenCommandTest extends TestCase
{
    protected function makeCommand(FloridaySdk $sdk): TestTokenCommand
    {
        app()->instance(FloridaySdk::class, $sdk);
        return app(TestTokenCommand::class);
    }

    public function test_runs_the_abstract_command_and_calls_handle_token_on_success(): void
    {
        Http::fake(['https://login.test/oauth/token' => Http::response(['access_token' => 'ttt'], 200)]);

        $sdk = new FloridaySdk($this->fakeCredentials());
        $command = $this->makeCommand($sdk);

        // Run via Symfony to initialize IO
        $application = new Application();
        $command->setApplication($application);
        $command->setLaravel($this->app);
        $tester = new CommandTester($command);
        $exitCode = $tester->execute([]);

        $this->assertSame(0, $exitCode);
        $this->assertSame(1, $command->handled);
        $this->assertSame('ttt', $command->seenToken);
    }

    public function test_returns_failure_when_no_access_token_returned(): void
    {
        Http::fake(['https://login.test/oauth/token' => Http::response(['nope' => true], 200)]);

        $sdk = new FloridaySdk($this->fakeCredentials());
        $command = $this->makeCommand($sdk);

        $application = new Application();
        $command->setApplication($application);
        $command->setLaravel($this->app);
        $tester = new CommandTester($command);
        $exitCode = $tester->execute([]);
        $this->assertSame(1, $exitCode);
    }
}

class TestTokenCommand extends GenerateFloridayTokenCommand
{
    protected $signature = 'test:token';
    protected $description = 'Test token command';

    public int $handled = 0;
    public ?string $seenToken = null;

    protected function handleToken(): int
    {
        $this->handled++;
        $this->seenToken = $this->token;
        return self::SUCCESS;
    }
}
