<?php

namespace App\Jobs;

use App\Services\SyncBarangService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncBarangJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $syncBarangService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SyncBarangService $syncBarangService)
    {
        $this->syncBarangService = $syncBarangService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('SyncBarangJob: Memulai sinkronisasi barang.');
        $result = $this->syncBarangService->syncBarang();

        if ($result) {
            Log::info('SyncBarangJob: Sinkronisasi selesai dengan sukses.');
        } else {
            Log::error('SyncBarangJob: Sinkronisasi gagal.');
        }
    }
}