@extends('user_view.resources.layouts.app')
@section('title', 'Daftar Laporan Saya')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">📋 Daftar Laporan Anda</h2>

            @if($laporan->isEmpty())
                <p class="text-center text-gray-500">Belum ada laporan yang Anda buat.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($laporan as $dt)
                        <div class="bg-white shadow-md rounded-lg p-5 border border-gray-300 hover:shadow-lg transition-shadow">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $dt->nama_barang }} ({{ $dt->kode_barang }})</h3>
                            <p class="text-sm text-gray-600"><strong>Merk:</strong> {{ $dt->merk_barang }}</p>
                            <p class="text-sm text-gray-600"><strong>Lokasi:</strong> {{ $dt->lokasi_barang }}</p>
                            <p class="text-sm text-gray-600"><strong>Status Laporan:</strong> 
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $dt->status == 'diterima' ? 'bg-green-100 text-green-700' : ($dt->status == 'diproses' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($dt->status) }}
                                </span>
                            </p>
                            <p class="text-sm text-gray-600"><strong>Tanggal Laporan:</strong> {{ \Carbon\Carbon::parse($dt->tanggal_laporan)->format('d M Y') }}</p>

                            @if($dt->bukti_laporan)
                                <div class="mt-3">
                                    <strong class="text-sm text-gray-700">Bukti Laporan:</strong>
                                    <div class="mt-2 w-full h-40 overflow-hidden rounded-mdS">
                                        <img src="{{ asset('storage/' . $dt->bukti_laporan) }}" alt="Bukti Laporan" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
