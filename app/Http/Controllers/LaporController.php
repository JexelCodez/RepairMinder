<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporController extends Controller
{
    public function create(Request $request)
    {
        return view('user_view.resources.pages.lapor.lapor-create', [
            'nama_barang' => $request->get('nama_barang'),
            'merk_barang' => $request->get('merk_barang'),
            'kode_barang' => $request->get('kode_barang'),
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

    Laporan::create($validatedData);

    return redirect()->route('home')->with('success', 'Laporan berhasil dikirim.');
}

}
