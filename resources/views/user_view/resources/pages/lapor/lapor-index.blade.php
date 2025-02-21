@extends('user_view.resources.layouts.app')
@section('title', 'Daftar Laporan Saya')

@push('custom-css')
    <style>
        /* Styling untuk tab */
        .nav-tabs .nav-link {
            color: #555;
            border-radius: 8px 8px 0 0;
            transition: background 0.3s, color 0.3s;
        }

        .nav-tabs .nav-link.active {
            background-color: var(--custom-btn-bg-color);
            color: var(--white-color);
            font: var(--title-font-family);
        }

        .nav-tabs .nav-link:hover {
            background: var(--custom-btn-bg-hover-color);
            color: var(--white-color);
        }

        /* Styling untuk kontainer laporan */
        .tab-content {
            min-height: 300px;
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 8px 8px;
            background: white;
        }

        /* Styling jika tidak ada laporan */
        .no-laporan {
            text-align: center;
            color: #6b7280;
            font-size: 1rem;
            margin-top: 20px;
        }
    </style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">📋 Daftar Laporan Anda</h2>

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4" id="laporanTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#pending">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#processed">Processed</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#done">Done</a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="pending">
                    @include('user_view.resources.partials.laporan.laporan-tab', ['laporan' => $laporanPending])
                </div>
                <div class="tab-pane fade" id="processed">
                    @include('user_view.resources.partials.laporan.laporan-tab', ['laporan' => $laporanProcessed])
                </div>
                <div class="tab-pane fade" id="done">
                    @include('user_view.resources.partials.laporan.laporan-tab', ['laporan' => $laporanDone])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-js')
    <script>
        $(document).ready(function () {
            $('#laporanTabs a').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
            });
        });
    </script>
@endpush
