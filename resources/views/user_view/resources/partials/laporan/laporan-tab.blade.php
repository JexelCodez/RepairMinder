@if($laporan->isEmpty())
    <p class="no-laporan">Tidak ada laporan dalam kategori ini.</p>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 min-h-[300px]">
        @foreach($laporan as $dt)
            <div class="bg-white shadow-md rounded-lg p-5 border border-gray-300 hover:shadow-lg transition-shadow">
                <!-- Layout Flex untuk memisahkan Detail & Bukti -->
                <div class="flex justify-between items-start">
                    <!-- Kolom kiri: Detail Laporan -->
                    <div class="w-1/2 pr-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $dt->nama_barang }} ({{ $dt->kode_barang }})
                        </h3>
                        <p class="text-sm text-gray-600">
                            <strong>Lokasi:</strong> {{ $dt->lokasi_barang }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <strong>Status Laporan:</strong>
                            <span class="badge 
                                @if($dt->status == 'pending') bg-warning 
                                @elseif($dt->status == 'processed') bg-primary 
                                @elseif($dt->status == 'done') bg-success 
                                @endif">
                                {{ ucfirst($dt->status) }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-600">
                            <strong>Tanggal Laporan:</strong> {{ \Carbon\Carbon::parse($dt->tanggal_laporan)->format('d M Y') }}
                        </p>
                    </div>

                    <!-- Kolom kanan: Bukti Laporan -->
                    <div class="w-1/2 text-right">
                        @if($dt->bukti_laporan)
                            <strong class="text-sm text-gray-700 block mb-1">Bukti Laporan:</strong>
                            <div class="w-32 h-32 mx-auto overflow-hidden rounded-md">
                                <img src="{{ asset('storage/' . $dt->bukti_laporan) }}" alt="Bukti Laporan" class="object-cover w-full h-full img-thumbnail">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Section Hasil Laporan -->
                @if($dt->status == 'done' && !empty($dt->hasil_laporan))
                    <hr class="my-4">
                    <div class="mt-3 p-3 bg-green-100 border border-green-400 rounded">
                        <strong class="text-sm text-green-800">Hasil Laporan:</strong>
                        <p class="text-sm text-gray-700 mt-1">{{ $dt->hasil_laporan }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif
