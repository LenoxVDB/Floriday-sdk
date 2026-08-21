<?php

namespace Lennord\FloridaySdk\Commands;

use Illuminate\Console\Command;

class FloridaySdkCommand extends Command
{
    public $signature = 'floriday-sdk';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
