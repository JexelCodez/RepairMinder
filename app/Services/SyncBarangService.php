<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SyncBarangService
{
    protected $client;
    protected $apiUrl;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 30, // Atur timeout sesuai kebutuhan
        ]);
        // Ganti dengan URL API aplikasi A
        $this->apiUrl = 'http://zaikotrack-main.test/api/barang';
    }

    public function syncBarang()
    {
        try {
            // Mengambil data barang dari API A
            $response = $this->client->get($this->apiUrl);

            if ($response->getStatusCode() !== 200) {
                Log::error('Failed to fetch barang data. Status code: ' . $response->getStatusCode());
                return false;
            }

            $data = json_decode($response->getBody()->getContents(), true);

            if (!isset($data['data']) || !is_array($data['data'])) {
                Log::error('Invalid data format received from API A.');
                return false;
            }

            $count = 0;

            // Lakukan operasi sinkronisasi ke database aplikasi B
            foreach ($data['data'] as $barang) {
                // Validasi data yang diterima
                if (!isset($barang['id_barang'], $barang['nama_barang'], $barang['merek'], $barang['stok_barang'], $barang['kode_barang'], $barang['qrcode_image'], $barang['jenis_barang']['id_jenis_barang'])) {
                    Log::warning('Incomplete data for barang: ' . json_encode($barang));
                    continue;
                }

                // Proses untuk menyimpan data ke DB aplikasi B
                \App\Models\Barang::updateOrCreate(
                    ['id_barang' => $barang['id_barang']],
                    [
                        'nama_barang' => $barang['nama_barang'],
                        'merek' => $barang['merek'],
                        'stok_barang' => $barang['stok_barang'],
                        'kode_barang' => $barang['kode_barang'],
                        'qrcode_image' => $barang['qrcode_image'],
                        'id_jenis_barang' => $barang['jenis_barang']['id_jenis_barang'],
                    ]
                );

                $count++;
            }

            Log::info("SyncBarangService: Sukses menyinkronisasi {$count} barang.");
            return true;
        } catch (\Exception $e) {
            Log::error('Error syncing barang: ' . $e->getMessage());
            return false;
        }
    }
}