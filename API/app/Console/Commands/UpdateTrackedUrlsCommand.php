<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AppModules\TrackedUrlUpdate\TrackedUrlUpdater;

class UpdateTrackedUrlsCommand extends Command {
    protected $signature = 'update-tracked-urls';
    protected $description = 'Updates the tracked url images.';
    private $urlUpdater;
    
    public function __construct(TrackedUrlUpdater $urlUpdater) {
        parent::__construct();
        $this->urlUpdater = $urlUpdater;
    }
    
    public function handle(): void {
        $this->urlUpdater->updateTrackedUrls();
    }
}
