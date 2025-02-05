<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Laporan;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;

class LaporController extends Controller
{

    public function index()
    {
        
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
        
        // Validasi data laporan
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'merk_barang' => 'required|string|max:255',
            'kode_barang' => 'required|string|max:255',
            'deskripsi_laporan' => 'required|string',
            'lokasi_barang' => 'required|string|max:255',
            'bukti_laporan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        // Jika ada file bukti laporan, simpan file tersebut
        if ($request->hasFile('bukti_laporan')) {
            $filePath = $request->file('bukti_laporan')->store('bukti_laporan', 'public');
            $validatedData['bukti_laporan'] = $filePath;
        }
    
        // Tambahkan data user dan tanggal laporan
        $validatedData['id_user'] = $userId;
        $validatedData['tanggal_laporan'] = now();
    
        // Simpan laporan baru
        $laporan = Laporan::create($validatedData);
    
        // Cari inventaris berdasarkan kode_barang
        $inventaris = Inventaris::where('kode_barang', $validatedData['kode_barang'])->first();
    
        // Jika inventaris ditemukan, update kondisi barang menjadi "rusak"
        if ($inventaris) {
            $inventaris->updateKondisiBarang('rusak');
        }

            // 🚀 Notifikasi ke semua user dengan role "teknisi"
        $teknisiUsers = User::where('role', 'teknisi')->get();
        foreach ($teknisiUsers as $user) {
            Notification::make()
                ->title('Laporan Baru')
                ->body("Laporan untuk barang {$laporan->nama_barang} telah dibuat.")
                ->sendToDatabase($user);
        }

        Alert::success('Laporan', 'Berhasil dikirim!');
    
        return redirect()->route('home')->with('success', 'Laporan berhasil dikirim dan status inventaris diperbarui.');
    }
}
