@extends('layouts.public', [
    'second_title' => '',
])


@section('css_plugins')
    <!-- swiper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/css/vendors/swiper-bundle.min.css" />

    <!-- Meta Pixel Code -->
    {{-- disable meta pixel code --}}
    {{-- <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq.disablePushState = true;
        fbq('init', '1278491429470122');
        fbq('track', 'PageView');
        window.loadedPixel = []
    </script> --}}
    <!-- End Meta Pixel Code -->
@endsection


@section('css_inline')
    <style type="text/css">
        input:focus::-webkit-input-placeholder {
            -webkit-transform: translateY(-125%);
            /*   font-size: 75%; */
            opacity: 0.05
        }

        input.imitatefocus::-webkit-input-placeholder {
            -webkit-transform: translateY(-125%);
            opacity: 0.05
        }
    </style>
@endsection


@section('content')
    <!-- header start -->
    <header class="section-t-space pt-0">
        <!-- <div class="custom-container"> -->
        <div class="header-panel bg-me header-title">
            <!-- <div class="header-title"> -->
            <a href="{{ url('/') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="#fff">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
            <h2 class="fs-16">Syarat & Ketentuan</h2>
            <!-- </div> -->
        </div>
        <!-- </div> -->
    </header>
    <!-- header end -->

    <!-- About Us Content Section -->
    <section class="about-content py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-11 col-md-11">
                    <!-- Introduction Paragraph -->
                    <div class="mb-3 px-lg-2">
                        <p class="text-justify lh-lg mb-4 text-slate-500" style="text-align: justify;">
                            Bantubersama menetapkan Ketentuan Umum dan Khusus terkait program yang dibuat oleh Campaigner
                            dengan tujuan untuk menjaga keaslian, kebenaran, dan keamanan setiap program yang dipublikasi
                            di situs Bantubersama.com.
                        </p>
                    </div>
                    <div class="mb-3 card-body p-2">
                        <div class="d-flex align-items-center mb-3">
                            <h3 class="card-title fw-bold text-primary mb-0">Apa itu Campaigner?</h3>
                        </div>
                        <p class="mb-0 lh-lg text-slate-500" style="text-align: justify;">
                            Campaigner adalah orang / lembaga yang bertanggung jawab dalam suatu kegiatan atau program dalam
                            konteks penggalangan dana. Campaigner yang mengawasi dan mengelola seluruh proses penggalangan
                            dana untuk memastikan semuanya berjalan lancar dan sesuai rencana sampai kegiatan atau program
                            terlaksana dengan baik.
                        </p>
                    </div>

                    <div class="card-body p-2">
                        <div class="d-flex align-items-center mb-3">
                            <h3 class="card-title fw-bold text-primary mb-0">A. Ketentuan Umum untuk Campaigner BantuBersama
                            </h3>
                        </div>

                        <div class="ms-4 mb-3 text-slate-500"> <!-- Indentasi 1 level -->
                            <p class="mb-2 text-slate-500 fw-bold">Verifikasi akun</p>

                            <ul class="list-unstyled lh-lg ps-2 text-slate-500">
                                <li class="mb-2 d-flex">
                                    <span class="badge-circle">1</span>
                                    Campaigner diwajibkan untuk mengisi informasi akurat guna melakukan
                                    verifikasi akun.
                                </li>
                                <li class="mb-2 d-flex">
                                    <span class="badge-circle">2</span>
                                    Campaigner harus memberikan informasi yang diminta oleh Bantu Bersama
                                    untuk memenuhi persyaratan data hukum yang diperlukan.
                                </li>
                            </ul>
                        </div>

                        <div class="ms-4 mb-3 text-slate-700"> <!-- Indentasi 1 level -->
                            <p class="mb-2 text-slate-500 fw-bold">Program</p>

                            <ul class="list-unstyled lh-lg ps-2 text-slate-500">
                                <li class="mb-2 d-flex">
                                    <span class="badge-circle">1</span>
                                    Campaigner menjamin bahwa semua teks, foto, dan video yang diunggah ke situs
                                    BantuBersama adalah benar dan dapat dipertanggungjawabkan.
                                </li>
                                <li class="mb-2 d-flex">
                                    <span class="badge-circle">2</span>
                                    Campaigner bertanggung jawab sepenuhnya terhadap program yang diajukan, termasuk
                                    pengelolaan dana hingga tahap pelaksanaan program bagi penerima manfaat.
                                </li>
                                <li class="mb-2 d-flex">
                                    <span class="badge-circle">3</span>
                                    Campaigner wajib melakukan pengawasan dan evaluasi terhadap program yang telah diajukan.
                                </li>
                                <li class="mb-2 d-flex">
                                    <span class="badge-circle">4</span>
                                    Campaigner harus memberikan laporan terkini mengenai pelaksanaan program dengan cara
                                    yang transparan dan dapat dipercaya di situs BantuBersama.
                                </li>
                                <li class="mb-2 d-flex">
                                    <span class="badge-circle">5</span>
                                    Campaigner wajib memberikan penjelasan atau laporan melalui situs jika pelaksanaan
                                    program menyimpang dari rencana.
                                </li>
                            </ul>


                        </div>

                        <div class="ms-4 mb-3 text-slate-700"> <!-- Indentasi 1 level -->
                            <p class="mb-2 text-slate-500 fw-bold">Program dilarang jika mengandung unsur:</p>

                            <ul class="list-unstyled lh-lg ps-2 text-slate-500">
                                <li class="mb-2 d-flex">
                                    <span class="badge-circle">I</span>
                                    Pornografi, konten dewasa, dan unsur seksual
                                </li>
                                <li class="mb-2 d-flex">
                                    <span class="badge-circle">II</span>
                                    Terkait narkotika dan zat berbahaya lainnya
                                </li>
                                <li class="mb-2 d-flex">
                                    <span class="badge-circle">III</span>
                                    Berkaitan dengan perjudian, taruhan, togel, lotre, atau undian
                                </li>
                                <li class="mb-2 d-flex">
                                    <span class="badge-circle">IV</span>
                                    Radikalisme, terorisme, dan isu SARA
                                </li>
                                <li class="mb-2 d-flex">
                                    <span class="badge-circle">V</span>
                                    Konten atau aktivitas yang tidak dapat diterima oleh pengelola Bantu Bersama
                                </li>
                            </ul>

                        </div>
                    </div>

                    <div class="card-body p-2">
                        <div class="d-flex align-items-center mb-3">
                            <h3 class="card-title fw-bold text-primary mb-0">B. Ketentuan Khusus untuk Campaigner
                                BantuBersama
                            </h3>
                        </div>
                        <div class="ms-2 mb-3">
                            <ul class="list-unstyled lh-lg ps-2 text-slate-500">
                                <li class="mb-2 d-flex">
                                    <span class="badge-circle">1</span>
                                    Jika ada perjanjian kerjasama khusus antara Campaigner dan pengelola Bantu Bersama, maka
                                    pengelola akan bertindak sesuai dengan ketentuan dalam perjanjian tersebut.
                                </li>
                                <li class="mb-2 d-flex">
                                    <span class="badge-circle">2</span>
                                    Tim Bantu Bersama berhak untuk menolak atau menunda pencairan donasi jika:
                                </li>
                                <ul class="ps-5 text-slate-500">
                                    <!-- First Main Item -->
                                    <li class="mb-3 d-flex align-items-start">
                                        <span class="badge-circle">I</span>
                                        <div>
                                            Campaigner belum memberikan informasi atau pembaruan yang diperlukan
                                        </div>
                                    </li>
                                    <li class="mb-3 d-flex align-items-start">
                                        <span class="badge-circle">II</span>
                                        <div>
                                            Campaigner tidak memberikan data yang sesuai untuk validasi
                                        </div>
                                    </li>
                                    <li class="mb-3 d-flex align-items-start">
                                        <span class="badge-circle">III</span>
                                        <div>
                                            Campaigner sulit dihubungi
                                        </div>
                                    </li>
                                    <li class="mb-3 d-flex align-items-start">
                                        <span class="badge-circle">IV</span>
                                        <div>
                                            Campaigner terbukti menggunakan konten kampanye yang tidak orisinal atau berasal
                                            dari pihak lain tanpa izin.
                                        </div>
                                    </li>
                                    <li class="mb-3 d-flex align-items-start">
                                        <span class="badge-circle">V</span>
                                        <div>
                                            Campaigner bertanggung jawab sepenuhnya atas pelaksanaan program, penggunaan
                                            dana donasi, dan semua hal yang terkait dengan program tersebut.
                                        </div>
                                    </li>
                                </ul>
                            </ul>
                        </div>
                    </div>

                    <div class="card-body p-2">
                        <div class="d-flex align-items-center mb-3">
                            <h3 class="card-title fw-bold text-primary mb-0">Catatan Khusus
                            </h3>
                        </div>
                        <div class="ms-2 mb-3 text-slate-700">
                            <p class="mb-0 lh-lg text-slate-500" style="text-align: justify;">
                                Campaigner setuju untuk bertanggung jawab jika terjadi penyalahgunaan dana program yang
                                telah diterima atau tindakan melanggar hukum lainnya, dan bersedia menerima tuntutan hukum
                                jika hal tersebut terjadi. Oleh karena itu, Campaigner menyatakan dan setuju untuk mengganti
                                semua kerugian yang dialami oleh pengelola Bantu Bersama, termasuk membebaskan pengelola
                                dari tuntutan hukum di masa mendatang terkait hal-hal berikut, tetapi tidak terbatas pada:
                            </p>
                            <ul class="list-unstyled ps-0 mt-2 text-slate-500">
                                <li class="mb-2 d-flex align-items-start lh-lg">
                                    <span class="badge-circle">i</span>
                                    <div>Pelaksanaan program yang tidak selesai atau tidak sesuai dengan apa yang
                                        disampaikan kepada pengelola maupun yang tertera di situs</div>
                                </li>
                                <li class="mb-2 d-flex align-items-start lh-lg">
                                    <span class="badge-circle">ii</span>
                                    <div>Penggelapan dana dan/atau penyalahgunaan donasi yang telah ditransfer oleh
                                        pengelola kepada pemilik program</div>
                                </li>
                                <li class="mb-2 d-flex align-items-start lh-lg">
                                    <span class="badge-circle">iii</span>
                                    <div>Kelalaian dalam pengelolaan program yang telah diajukan</div>
                                </li>
                                <li class="mb-2 d-flex align-items-start lh-lg">
                                    <span class="badge-circle">iv</span>
                                    <div>Tindakan melanggar hukum lainnya yang terjadi selama proses penggalangan dana
                                        dan/atau implementasi program</div>
                                </li>
                            </ul>

                            <p class="mb-0 lh-lg text-slate-500" style="text-align: justify;">
                                Campaigner bersedia untuk mengikuti ketentuan yang ditetapkan oleh pengelola Bantu Bersama
                                jika terjadi hal-hal berikut, tetapi tidak terbatas pada:
                            </p>

                            <ul class="ps-0 text-slate-500">
                                <li class="mb-3 d-flex align-items-start lh-lg">
                                    <span class="badge-circle">i</span>
                                    <div>Jika donasi telah melebihi target penghimpunan</div>
                                </li>
                                <li class="mb-3 d-flex align-items-start lh-lg">
                                    <span class="badge-circle">ii</span>
                                    <div>Jika kebutuhan dana telah terpenuhi dan disalurkan sepenuhnya namun masih
                                        terdapat sisa donasi</div>
                                </li>
                                <li class="mb-3 d-flex align-items-start lh-lg">
                                    <span class="badge-circle">iii</span>
                                    <div>Jika tidak ada lagi biaya yang diperlukan untuk program yang diajukan</div>
                                </li>
                                <li class="mb-3 d-flex align-items-start lh-lg">
                                    <span class="badge-circle">iv</span>
                                    <div>Jika dana yang diberikan tidak disalurkan sesuai mestinya</div>
                                </li>
                                <li class="mb-3 d-flex align-items-start lh-lg">
                                    <span class="badge-circle">v</span>
                                    <div>Jika Campaigner atau penerima manfaat tidak dapat dihubungi</div>
                                </li>
                                <li class="mb-3 d-flex align-items-start lh-lg">
                                    <span class="badge-circle">vi</span>
                                    <div>Khusus untuk program bantuan kesehatan, jika penerima manfaat telah meninggal
                                        dunia dan tidak memiliki tanggungan biaya pengobatan.</div>
                                </li>
                            </ul>
                            <p class="mb-0 lh-lg text-slate-500" style="text-align: justify;">
                                Dalam hal ini, kelebihan dana akan dialihkan untuk program lain yang menggalang dana melalui
                                Bantu Bersama sesuai dengan kebutuhan dan kategori penggunaan dana. Hal ini dilakukan agar
                                lebih banyak penerima manfaat dapat terbantu.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer section start -->
    <section class="empty-section section-t-space section-b-space pb-0">
        <div class="custom-container footer pb-3 pt-3">
            <div class="fw-medium text-grey pt-2 fs-14">
                <a class="text-grey" href="{{ route('aboutus') }}">Tentang Kami</a> |
                <a class="text-grey" href="{{ route('termsandcondition') }}">Syarat & Ketentuan</a> |
                <a class="text-grey" href="{{ route('questionscenter') }}">Pusat Bantuan</a>
            </div>
            <div class="mt-3 text-grey fs-14">
                Temukan kami di <br>
                <div class="socmed mb-3 mt-1">
                    <a rel="noreferrer" href="https://www.facebook.com/profile.php?id=100091563649667" target="_blank"
                        class="me-2 socmed-item rounded-circle">
                        <svg class="mx-auto" width="12" height="12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9 .002L7.443 0C5.695 0 4.565 1.16 4.565 2.953v1.362H3.001a.245.245 0 00-.245.245v1.973c0 .135.11.244.245.244h1.564v4.978c0 .135.11.245.245.245h2.041c.136 0 .245-.11.245-.245V6.777h1.83c.135 0 .244-.11.244-.244V4.56a.245.245 0 00-.244-.245h-1.83V3.16c0-.555.132-.837.855-.837h1.048c.135 0 .245-.11.245-.245V.247A.245.245 0 009 .002z"
                                fill="currentColor"></path>
                        </svg>
                        <span class="screen-reader-text">Facebook</span>
                    </a>
                    <a rel="noreferrer" href="https://www.instagram.com/bantubersamacom/" target="_blank"
                        class="me-2 socmed-item rounded-circle">
                        <svg class="mx-auto" width="12" height="12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.688 0H3.311A3.315 3.315 0 000 3.312v5.376A3.315 3.315 0 003.311 12h5.377A3.315 3.315 0 0012 8.688V3.312A3.315 3.315 0 008.688 0zm2.247 8.688a2.25 2.25 0 01-2.247 2.247H3.311a2.25 2.25 0 01-2.246-2.247V3.312A2.25 2.25 0 013.31 1.065h5.377a2.25 2.25 0 012.247 2.247v5.376z"
                                fill="currentColor"></path>
                            <path
                                d="M6 2.906a3.096 3.096 0 00-3.092 3.092A3.095 3.095 0 006 9.09a3.095 3.095 0 003.092-3.092A3.096 3.096 0 006 2.906zm0 5.12a2.03 2.03 0 01-2.028-2.028A2.03 2.03 0 016 3.971a2.03 2.03 0 012.027 2.027A2.03 2.03 0 016 8.025zM9.222 2.004a.784.784 0 00-.781.78.787.787 0 00.78.78.788.788 0 00.553-.227.784.784 0 00-.552-1.333z"
                                fill="currentColor"></path>
                        </svg>
                        <span class="screen-reader-text">Instagram</span>
                    </a>
                    <a rel="noreferrer" href="https://twitter.com/bantubersamacom" target="_blank"
                        class="me-2 socmed-item rounded-circle">
                        <svg class="mx-auto" width="12" height="12" fill="none"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                            <g>
                                <path
                                    d="M14.356 4.742A6.547 6.547 0 0016 3.039a6.85 6.85 0 01-1.89.518 3.263 3.263 0 001.443-1.813 6.563 6.563 0 01-2.08.794A3.28 3.28 0 007.8 4.781c0 .26.022.51.076.748a9.287 9.287 0 01-6.761-3.432 3.308 3.308 0 00-.45 1.658c0 1.136.585 2.143 1.458 2.726A3.242 3.242 0 01.64 6.077v.036a3.296 3.296 0 002.628 3.224 3.262 3.262 0 01-.86.108c-.21 0-.422-.012-.62-.056a3.312 3.312 0 003.064 2.285 6.593 6.593 0 01-4.067 1.399c-.269 0-.527-.012-.785-.045A9.237 9.237 0 005.032 14.5c5.789 0 9.561-4.83 9.324-9.758z"
                                    fill="currentColor"></path>
                            </g>
                        </svg>
                        <span class="screen-reader-text">Twitter</span>
                    </a>
                    <a rel="noreferrer" href="https://www.youtube.com/@Bantubersama-de2vi" target="_blank"
                        class="socmed-item rounded-circle">
                        <svg class="mx-auto" xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                            viewBox="0 0 24 24">
                            <path
                                d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"
                                fill="currentColor"></path>
                        </svg>
                        <span class="screen-reader-text">Youtube</span>
                    </a>
                </div>
            </div>
            <div class="fs-14 fw-normal text-grey mt-3">
                Copyright © 2023 Yayasan Bantu Beramal Bersama
            </div>
        </div>
    </section>
    <!-- footer section end -->

    <style>
        .hover-bg-lightblue {
            width: 50px;
            /* Set the width */
            height: 50px;
            /* Set the height to be the same as width */
        }

        .hover-bg-lightblue:hover {
            background-color: #d0e3ff !important;
            /* Warna biru muda */
            transition: background-color 0.2s ease-in-out;
        }

        .text-slate-500 {
            color: #4d5756;
        }

        .text-slate-700 {
            color: #323938;
        }

        ol {
            list-style-type: none;
            counter-reset: item;
            padding-left: 0;
        }

        ol li {
            counter-increment: item;
            margin-bottom: 0.5rem;
        }

        ol li:before {
            content: counter(item) ".";
            color: #64748b;
            /* Warna slate-500 */
            margin-right: 0.5rem;
        }

        .badge-circle {
            width: 26px !important;
            height: 26px !important;
            aspect-ratio: 1 !important;
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            margin-top: 4px;
            margin-right: 8px;
        }
    </style>
@endsection


@section('js_plugins')
    <!-- JQuery -->
    <script src="{{ asset('public/js/jquery-3.6.4.min.js') }}"></script>
    <!-- bootstrap js -->
    <script src="{{ asset('public') }}/js/bootstrap.bundle.min.js"></script>

    <!-- swiper js -->
    <script src="{{ asset('public') }}/js/swiper-bundle.min.js"></script>
    <script src="{{ asset('public') }}/js/custom-swiper.js"></script>
    <!-- lazy load -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-lazyload@1.9.7/jquery.lazyload.min.js"></script>
@endsection
