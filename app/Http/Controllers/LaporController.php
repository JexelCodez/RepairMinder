<?php

namespace App\Http\Controllers;

use App\Filament\DKV\Resources\LaporanDKVResource;
use App\Models\Inventaris;
use App\Models\Laporan;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\LaporanResource;
use App\Filament\Sarpras\Resources\LaporanSarprasResource;
use App\Models\InventarisDKV;
use App\Models\InventarisSarpras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;

class LaporController extends Controller
{

    public function index()
    {
        $userId = Auth::user()->id;
        $laporan = Laporan::where('id_user', $userId)->get();
        return view('user_view.resources.pages.lapor.lapor-index', compact('laporan'));
    }

    public function create(Request $request)
    {
        return view('user_view.resources.pages.lapor.lapor-create', [
            'nama_barang' => $request->get('nama_barang'),
            'merk_barang' => $request->get('merk_barang'),
            'kode_barang' => $request->get('kode_barang'),
            'lokasi_barang' => $request->get('lokasi_barang'),
        ]);
    }

    public function store(Request $request)
{
    $userId = Auth::user()->id;
    
    $validatedData = $request->validate([
        'nama_barang' => 'required|string|max:255',
        'merk_barang' => 'required|string|max:255',
        'kode_barang' => 'required|string|max:255',
        'deskripsi_laporan' => 'required|string',
        'lokasi_barang' => 'required|string|max:255',
        'bukti_laporan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    if ($request->hasFile('bukti_laporan')) {
        $filePath = $request->file('bukti_laporan')->store('bukti_laporan', 'public');
        $validatedData['bukti_laporan'] = $filePath;
    }

    $validatedData['id_user'] = $userId;
    $validatedData['tanggal_laporan'] = now();

    $laporan = Laporan::create($validatedData);

        // Cari dari model mana kode_barang ini berasal
        $inventaris = Inventaris::where('kode_barang', $validatedData['kode_barang'])->first();
        $inventarisDkv = InventarisDKV::where('kode_barang', $validatedData['kode_barang'])->first();
        $inventarisSarpras = InventarisSarpras::where('kode_barang', $validatedData['kode_barang'])->first();
    
        // Jika inventaris ditemukan, update kondisi barang menjadi "rusak"
        if ($inventaris) {
            $inventaris->updateKondisiBarang('rusak');
        } elseif ($inventarisDkv) {
            $inventarisDkv->updateKondisiBarang('rusak');
        } elseif ($inventarisSarpras) {
            $inventarisSarpras->updateKondisiBarang('rusak');
        }

    // 🚀 Notifikasi ke user yang sesuai dengan kode_barang
    $teknisiUsers = collect();

    if (InventarisDKV::where('kode_barang', $validatedData['kode_barang'])->exists()) {
        $teknisiUsers = User::where('role', 'admin')
            ->orWhere(fn($query) => 
                $query->where('role', 'teknisi')
                      ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'dkv'))
            )->get();
        
        // Tentukan panel untuk route DKV, misalnya panel slugnya 'dkv'
        $laporUrl = LaporanDKVResource::getUrl('view', ['record' => $laporan], panel: 'dKV');
    } elseif (InventarisSarpras::where('kode_barang', $validatedData['kode_barang'])->exists()) {
        $teknisiUsers = User::where('role', 'admin')
            ->orWhere(fn($query) => 
                $query->where('role', 'teknisi')
                      ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sarpras'))
            )->get();
    
        $laporUrl = LaporanSarprasResource::getUrl('view', ['record' => $laporan], panel: 'sarpras');
    } else {
        $teknisiUsers = User::where('role', 'admin')
            ->orWhere(fn($query) =>
                $query->where('role', 'teknisi')
                      ->whereHas('zoneUser', fn($q) => $q->where('zone_name', 'sija'))
            )->get();
    
        $laporUrl = LaporanResource::getUrl('view', ['record' => $laporan], panel: 'admin');
    }

    if ($inventaris) {
        $inventaris->updateKondisiBarang('rusak');
    } elseif ($inventarisDkv) {
        $inventarisDkv->updateKondisiBarang('rusak');
    } elseif ($inventarisSarpras) {
        $inventarisSarpras->updateKondisiBarang('rusak');
    }

    foreach ($teknisiUsers as $user) {
        Notification::make()
            ->title('🔔 Laporan Baru')
            ->color('warning')
            ->body("📌 Laporan untuk {$laporan->nama_barang} ({$laporan->kode_barang}) telah dibuat. Klik untuk melihat detail.")
            ->actions([
                Action::make('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url($laporUrl),
            ])
            ->sendToDatabase($user);
    }


    Alert::success('Laporan', 'Berhasil dikirim!');
    
    return redirect()->route('lapor.index')->with('success', 'Laporan berhasil dikirim dan status inventaris diperbarui.');
}

}
