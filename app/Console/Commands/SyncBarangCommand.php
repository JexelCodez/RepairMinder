<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncBarangJob;

class SyncBarangCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:barang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi barang dari API A ke aplikasi B';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SyncBarangJob::dispatch();
        $this->info('SyncBarangJob telah didispatch.');
    }
}