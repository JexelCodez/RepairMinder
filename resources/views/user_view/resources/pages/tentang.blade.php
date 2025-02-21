@extends('user_view.resources.layouts.app')
@section('title', 'Tentang REMI')


@section('content')
    <main>
        <!-- bekas nav -->
        <!-- bekas header -->

        <section class="about-section section-padding" id="section_2">
            <div class="container">
                <div class="row">

                <div class="col-lg-8 col-12 mx-auto">
                    <div class="pb-5 mb-5">
                        <h1 class="text-center">Frequently Asked Questions (FAQ)</h1>
                        <h6 class="text-center">Pertanyaan yang sering ditanyakan</h6>

                        <div class="section-title-wrap mb-4 mt-5">
                            <h4 class="section-title">Apa itu REMI?</h4>
                        </div>
                        <p>REMI (RepairMinder) adalah sistem manajemen pemeliharaan dan perbaikan aset sekolah yang membantu mencatat, memantau, serta mengelola jadwal pemeliharaan dan laporan kerusakan aset secara efisien.</p>

                        <div class="section-title-wrap mb-4">
                            <h4 class="section-title">Siapa yang dapat menggunakan REMI?</h4>
                        </div>
                        <p>REMI dapat digunakan oleh admin, teknisi, dan pengguna umum seperti staf sekolah atau guru yang ingin melaporkan kerusakan aset.</p>

                        <div class="section-title-wrap mb-4">
                            <h4 class="section-title">Apa saja fitur utama dalam REMI?</h4>
                        </div>
                        <ul>
                            <li>Manajemen aset sekolah</li>
                            <li>Penjadwalan pemeliharaan aset</li>
                            <li>Pelaporan dan pemantauan perbaikan</li>
                            <li>Dashboard monitoring</li>
                            <li>Notifikasi otomatis</li>
                            <li>Hak akses berdasarkan zona</li>
                        </ul>

                        <div class="section-title-wrap mb-4">
                            <h4 class="section-title">Bagaimana cara login ke REMI?</h4>
                        </div>
                        <p>Pengguna dapat login menggunakan email dan password yang telah didaftarkan oleh admin. Jika mengalami kendala, hubungi admin atau teknisi.</p>

                        <div class="section-title-wrap mb-4">
                            <h4 class="section-title">Apa itu sistem hak akses berdasarkan zona?</h4>
                        </div>
                        <p>Admin hanya bisa mengakses data dan dashboard yang sesuai dengan jurusan atau zona yang mereka kelola. Ini bertujuan untuk menjaga keamanan dan memastikan data hanya dikelola oleh pihak yang berwenang.</p>

                        <div class="section-title-wrap mb-4">
                            <h4 class="section-title">Bagaimana cara mengubah password?</h4>
                        </div>
                        <p>Pengguna dapat menghubungi teknisi melalui nomor yang telah disediakan. Silakan cek halaman kontak untuk informasi lebih lanjut.</p>

                        <div class="section-title-wrap mb-4">
                            <h4 class="section-title">Bagaimana cara melaporkan kerusakan aset?</h4>
                        </div>
                        <p>Pengguna dapat melaporkan kerusakan dengan mengisi form laporan di menu <strong>Laporan Kerusakan</strong>, mencantumkan deskripsi masalah dan gambar pendukung.</p>

                        <div class="section-title-wrap mb-4">
                            <h4 class="section-title">Bagaimana status perbaikan dilacak?</h4>
                        </div>
                        <p>Setelah laporan kerusakan diajukan, teknisi akan menangani perbaikan, dan statusnya dapat dipantau di daftar laporan pelapor.</p>

                        <div class="section-title-wrap mb-4">
                            <h4 class="section-title">Apa yang harus dilakukan jika perbaikan belum selesai dalam waktu lama?</h4>
                        </div>
                        <p>Jika perbaikan mengalami keterlambatan, admin atau teknisi terkait dapat memberikan pembaruan status atau menghubungi teknisi yang bertanggung jawab.</p>

                        <div class="section-title-wrap mb-4">
                            <h4 class="section-title">Apakah REMI terhubung dengan sistem lain?</h4>
                        </div>
                        <p>Ya, REMI dapat mengimpor data aset dari API eksternal untuk mempermudah manajemen aset.</p>

                        <div class="section-title-wrap mb-4">
                            <h4 class="section-title">Bagaimana keamanan data pengguna dalam REMI?</h4>
                        </div>
                        <p>REMI menggunakan sistem autentikasi berbasis peran, pembatasan akses zona, serta enkripsi data untuk menjaga keamanan pengguna.</p>

                        <div class="section-title-wrap mb-4">
                            <h4 class="section-title">Bagaimana jika terjadi error atau bug pada sistem?</h4>
                        </div>
                        <p>Jika terjadi error, pengguna dapat melaporkannya ke admin atau tim teknis melalui menu bantuan atau kontak yang tersedia.</p>

                        <div class="text-center">
                            <img src="icons/maskot.png" class="about-image mt-5 img-fluid" alt="gambar siswa sija betulin alat">
                        </div>
                    </div>
                </div>

                    <!-- <div class="col-lg-12 col-12">
                        <div class="section-title-wrap mb-5">
                            <h4 class="section-title">Tim Teknisi Kami</h4>
                        </div>
                    </div> -->

                    <!-- <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                        <div class="team-thumb bg-white shadow-lg">
                            <img src="images/profile/cute-smiling-woan-outdoor-portrait.jpg"
                                class="about-image img-fluid" alt="teknisi 1">

                            <div class="team-info">
                                <h4 class="mb-2">
                                    Taylor
                                    <img src="images/verified.png" class="verified-image img-fluid" alt="">
                                </h4>

                                <span class="badge">Modeling</span>

                                <span class="badge">Fashion</span>
                            </div>

                            <div class="social-share">
                                <ul class="social-icon">
                                    <li class="social-icon-item">
                                        <a href="#" class="social-icon-link bi-twitter"></a>
                                    </li>

                                    <li class="social-icon-item">
                                        <a href="#" class="social-icon-link bi-facebook"></a>
                                    </li>

                                    <li class="social-icon-item">
                                        <a href="#" class="social-icon-link bi-pinterest"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div> -->

                    <!-- <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                        <div class="team-thumb bg-white shadow-lg">
                            <img src="images/profile/handsome-asian-man-lisening-music-through-headphones.jpg"
                                class="about-image img-fluid" alt="teknisi 2">

                            <div class="team-info">
                                <h4 class="mb-2">
                                    William
                                    <img src="images/verified.png" class="verified-image img-fluid" alt="">
                                </h4>

                                <span class="badge">Creative</span>

                                <span class="badge">Design</span>
                            </div>

                            <div class="social-share">
                                <ul class="social-icon">
                                    <li class="social-icon-item">
                                        <a href="#" class="social-icon-link bi-twitter"></a>
                                    </li>

                                    <li class="social-icon-item">
                                        <a href="#" class="social-icon-link bi-facebook"></a>
                                    </li>

                                    <li class="social-icon-item">
                                        <a href="#" class="social-icon-link bi-pinterest"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div> -->

                    <!-- <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0 mb-md-0">
                        <div class="team-thumb bg-white shadow-lg">
                            <img src="images/profile/smart-attractive-asian-glasses-male-stading-smile-with-freshness-joyful-casual-blue-shirt-portrait-white-background.jpg"
                                class="about-image img-fluid" alt="teknisi 3">

                            <div class="team-info">
                                <h4 class="mb-2">
                                    Chan
                                    <img src="images/verified.png" class="verified-image img-fluid" alt="">
                                </h4>

                                <span class="badge">Education</span>
                            </div>

                            <div class="social-share">
                                <ul class="social-icon">
                                    <li class="social-icon-item">
                                        <a href="#" class="social-icon-link bi-linkedin"></a>
                                    </li>

                                    <li class="social-icon-item">
                                        <a href="#" class="social-icon-link bi-whatsapp"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div> -->

                    <!-- <div class="col-lg-3 col-md-6 col-12">
                        <div class="team-thumb bg-white shadow-lg">
                            <img src="images/profile/smiling-business-woman-with-flded-hands-against-white-wall-toothy-smile-crossed-arms.jpg"
                                class="about-image img-fluid" alt="teknisi 4">

                            <div class="team-info">
                                <h4 class="mb-2">
                                    Candice
                                    <img src="images/verified.png" class="verified-image img-fluid" alt="">
                                </h4>

                                <span class="badge">Storytelling</span>

                                <span class="badge">Business</span>
                            </div>

                            <div class="social-share">
                                <ul class="social-icon">
                                    <li class="social-icon-item">
                                        <a href="#" class="social-icon-link bi-twitter"></a>
                                    </li>

                                    <li class="social-icon-item">
                                        <a href="#" class="social-icon-link bi-facebook"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div> -->

                </div>
            </div>
        </section>
    </main>

    <!-- bekas footer -->
    
    @endsection

    <!-- JAVASCRIPT FILES -->