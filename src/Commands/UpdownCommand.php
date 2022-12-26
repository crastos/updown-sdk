<?php

namespace Crastos\Updown\Commands;

use Illuminate\Console\Command;

class UpdownCommand extends Command
{
    public $signature = 'updown';

    public $description = 'Control the updown.io service';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
