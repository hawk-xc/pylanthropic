@extends('layouts.public', [
    'second_title' => '',
])


@section('css_plugins')
    <!-- swiper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/css/vendors/swiper-bundle.min.css" />

    <!-- Meta Pixel Code -->
    <script>
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
    </script>
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

        .swiper-wrapper {
            align-items: stretch;
        }

        .swiper-slide {
            height: auto;
        }

        .product-box {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-box-detail {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-box-detail .progress {
            margin-bottom: auto;
        }

        .vertical-product-box {
            display: flex;
        }

        .vertical-box-details,
        .vertical-box-details>a {
            height: 100%;
        }

        .vertical-box-head {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .vertical-box-head .progress {
            margin-bottom: auto;
        }

        .program-image {
            max-height: 90px;
            background-color: #eee;
        }
    </style>
@endsection



@section('content')
    <!-- search section starts -->
    <section class="search-section pt-3">
        <div class="custom-container">
            <form class="auth-form search-head" method="get" action="#" id="search">
                <div class="form-group header-navbar">
                    <a href="{{ url('/') }}" class="logo-navbar">
                        <img class="" src="{{ asset('Logo Bantubersama.png') }}">
                    </a>
                    <div class="form-input">
                        <input type="text" class="form-control search typewrite" id="inputkey" placeholder="" />
                        <i class="ri-search-line search-icon color-me"></i>
                    </div>

                    <a href="#search-filter" class="btn filter-button mt-0" data-bs-toggle="modal">
                        <i class="ri-equalizer-line color-me"></i>
                    </a>
                </div>
            </form>
        </div>
    </section>
    <!-- search section end -->

    <!-- banner section start -->
    <section class="banner-section section-t-space pt-3">
        <div class="custom-container">
            <div class="swiper banner1">
                <div class="swiper-wrapper">
                    @foreach ($slider as $vs)
                        <div class="swiper-slide">
                            <img class="img-fluid banner-img" src="{{ asset('public/images/banner') . '/' . $vs->image }}"
                                alt="{{ $vs->name }}" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- banner section end -->

    <!-- Categories section start -->
    <section class="section-t-space">
        <div class="custom-container">
            <div class="title">
                <h3 class="mt-0">Kategori</h3>
                <!-- <a href="categories.html">Semua</a> -->
            </div>

            <div class="swiper categories">
                <div class="swiper-wrapper ratio_square">
                    @foreach ($category as $vc)
                        <div class="swiper-slide">
                            <a href="{{ route('program.list') . '/?kategori=' . $vc->slug }}" class="food-categories">
                                <img class="img-fluid categories-img"
                                    src="{{ asset('public/images/categories') . '/' . $vc->icon }}"
                                    alt="{{ ucwords($vc->name) }}" />
                            </a>
                            <h6 class="fs-12">{{ ucwords($vc->name) }}</h6>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- Food Categories section end -->

    <!-- Program Selection section start -->
    <section class="section-t-space">
        <div class="custom-container">
            <div class="title">
                <h3 class="mt-0">Pilihan Bantubersama</h3>
                <a href="{{ route('program.list') }}">Semua</a>
            </div>
            <div class="swiper products pt-0 pb-2">
                <div class="swiper-wrapper">
                    @foreach ($selected as $vsl)
                        <div class="swiper-slide">
                            <a href="{{ url('/') . '/' . $vsl->slug }}" class="">
                                <div class="product-box">
                                    <img class="img-fluid rounded-top lazyload program-image"
                                        data-original="{{ asset('public/images/program') . '/' . $vsl->thumbnail }}"
                                        alt="{{ ucwords($vsl->title) }}" />
                                    <div class="product-box-detail product-box-bg">
                                        <h5 class="two-line mt-1 mb-1 fs-11 lh-14">{{ ucwords($vsl->title) }}</h5>
                                        <ul class="timing mt-2 mb-2">
                                            <li class="fs-11 lh-14">
                                                {{ ucwords($vsl->name) }}
                                                @if ($vsl->status == 'verified' || $vsl->status == 'verif_org')
                                                    <span class="star"><i class="ri-star-s-fill"></i></span>
                                                @endif
                                            </li>
                                        </ul>
                                        <div class="progress mt-1" role="progressbar" aria-label="Basic example"
                                            aria-valuenow="86" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                                            <div class="progress-bar"
                                                style="width: {{ ceil(($vsl->sum_amount / $vsl->nominal_approved) * 100) }}%">
                                            </div>
                                        </div>
                                        <div class="bottom-panel">
                                            <div class="pe-0 fw-semibold fs-11 lh-16">Rp
                                                {{ str_replace(',', '.', number_format($vsl->sum_amount)) }}</div>
                                            <div class="fw-semibold fs-11 lh-16 text-end">
                                                {{ now()->diffInDays(substr($vsl->end_date, 0, 10)) }}
                                            </div>
                                        </div>
                                        <div class="bottom-panel mt-0">
                                            <div class="fw-light fs-10 lh-14 pe-0">Donasi Terkumpul</div>
                                            <div class="fw-light fs-10 lh-14 text-end">Hari Lagi</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- Program Selection section start -->

    <!-- Program Urgent section start -->
    <section class="section-t-space pt-3">
        <div class="custom-container pt-3 pb-2 mt-0" style="background-color: #fff9f5;">
            <div class="title">
                <h3 class="mt-0">Penggalangan Dana Mendesak</h3>
                <a href="{{ route('program.list') }}">Semua</a>
            </div>
            <div class="swiper products pt-0 pb-2">
                <div class="swiper-wrapper">
                    @foreach ($urgent as $vu)
                        <div class="swiper-slide">
                            <a href="{{ url('/') . '/' . $vu->slug }}" class="">
                                <div class="product-box">
                                    <img id="program-image" class="img-fluid rounded-top lazyload"
                                        data-original="{{ asset('public/images/program') . '/' . $vu->thumbnail }}"
                                        alt="{{ ucwords($vu->title) }}" />
                                    <div class="product-box-detail product-box-bg">
                                        <h5 class="two-line mt-1 mb-1 fs-11 lh-14">{{ ucwords($vu->title) }}</h5>
                                        <ul class="timing mt-2 mb-2">
                                            <li class="fs-11 lh-14">
                                                {{ ucwords($vu->name) }}
                                                @if ($vu->status == 'verified' || $vu->status == 'verif_org')
                                                    <span class="star"><i class="ri-star-s-fill"></i></span>
                                                @endif
                                            </li>
                                        </ul>
                                        <div class="progress mt-1" role="progressbar" aria-label="Basic example"
                                            aria-valuenow="86" aria-valuemin="0" aria-valuemax="100"
                                            style="height: 5px">
                                            <div class="progress-bar"
                                                style="width: {{ ceil(($vu->sum_amount / $vu->nominal_approved) * 100) }}%">
                                            </div>
                                        </div>
                                        <div class="bottom-panel">
                                            <div class="pe-0 fw-semibold fs-11 lh-16">Rp
                                                {{ str_replace(',', '.', number_format($vu->sum_amount)) }}</div>
                                            <div class="fw-semibold fs-11 lh-16 text-end">
                                                {{ now()->diffInDays(substr($vu->end_date, 0, 10)) }}
                                            </div>
                                        </div>
                                        <div class="bottom-panel mt-0">
                                            <div class="fw-light fs-10 lh-14 pe-0">Donasi Terkumpul</div>
                                            <div class="fw-light fs-10 lh-14 text-end">Hari Lagi</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- Program Urgent section start -->

    <!-- Banner section start -->
    <section class="empty-section section-t-space section-b-space pt-3">
        <div class="custom-container">
            <a href="https://wa.me/628155555849" target="_blank">
                <img class="img-fluid lazyload"
                    data-original="{{ asset('public/images/banner/Banner campaign 2 terang 580x280.jpg') }}">
            </a>
        </div>
    </section>
    <!-- Banner section end -->

    <!-- Program Newest section start -->
    <section class="section-t-space pt-3">
        <div class="custom-container">
            <div class="title">
                <h3 class="mt-0">Terbaru di Bantubersama</h3>
                <a href="{{ route('program.list') }}">Semua</a>
            </div>
            <div class="row gy-2">
                @foreach ($newest as $vn)
                    <div class="col-12">
                        <div class="vertical-product-box">
                            <div class="vertical-box-img">
                                <a href="{{ url('/') . '/' . $vn->slug }}">
                                    <img class="img-fluid img lazyload"
                                        data-original="{{ asset('public/images/program') . '/' . $vn->thumbnail }}"
                                        alt="{{ ucwords($vn->title) }}" />
                                </a>
                            </div>
                            <div class="vertical-box-details">
                                <a href="{{ url('/') . '/' . $vn->slug }}">
                                    <div class="vertical-box-head">
                                        <div class="restaurant">
                                            <h5 class="two-line fs-11 lh-14">{{ ucwords($vn->title) }}</h5>
                                        </div>

                                        <h6 class="rating-star mt-1 mb-1 fs-11">
                                            {{ ucwords($vn->name) }}
                                            @if ($vn->status == 'verified' || $vn->status == 'verif_org')
                                                <span class="star"><i class="ri-star-s-fill"></i></span>
                                            @endif
                                        </h6>

                                        <div class="progress mt-1 mb-2" role="progressbar" aria-label="Basic example"
                                            aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"
                                            style="height: 5px">
                                            <div class="progress-bar"
                                                style="width: {{ ceil(($vn->sum_amount / $vn->nominal_approved) * 100) }}%">
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between mt-2">
                                            <div class="fw-semibold fs-11 pe-0 lh-16">Rp
                                                {{ str_replace(',', '.', number_format($vn->sum_amount)) }}</div>
                                            <div class="fw-semibold fs-11 text-end ps-1 lh-16">
                                                {{ now()->diffInDays(substr($vn->end_date, 0, 10)) }}
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="fw-light fs-10 lh-14 pe-0">Donasi Terkumpul</div>
                                            <div class="fw-light fs-10 lh-14 text-end ps-1">Hari Lagi</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Explore Restaurants section end -->

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

    <!-- filter offcanvas start -->
    <div class="modal search-filter" id="search-filter" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-title">
                    <h3 class="fw-semibold">Filter</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <section class="section-b-space pt-0">
                    <div class="custom-container">
                        <section class="pt-0">
                            <div class="order-options">
                                <h3 class="mt-3 mb-3 dark-text fw-semibold">Kategori</h3>
                                <div class="order-type">
                                    <div class="auth-form search-form">
                                        <div class="form-check">
                                            <label class="form-check-label" for="fixed1">Semua</label>
                                            <input class="form-check-input" type="radio" name="kategori"
                                                value="semua" checked />
                                        </div>
                                    </div>
                                    <div class="auth-form search-form">
                                        <div class="form-check">
                                            <label class="form-check-label" for="fixed2">Kemanusiaan</label>
                                            <input class="form-check-input" type="radio" name="kategori"
                                                value="kemanusiaan" />
                                        </div>
                                    </div>
                                </div>
                                <div class="order-type">
                                    <div class="auth-form search-form">
                                        <div class="form-check">
                                            <label class="form-check-label" for="fixed1">Pendidikan</label>
                                            <input class="form-check-input" type="radio" name="kategori"
                                                value="pendidikan" />
                                        </div>
                                    </div>
                                    <div class="auth-form search-form">
                                        <div class="form-check">
                                            <label class="form-check-label" for="fixed2">Kesehatan</label>
                                            <input class="form-check-input" type="radio" name="kategori"
                                                value="kesehatan" />
                                        </div>
                                    </div>
                                </div>
                                <div class="order-type">
                                    <div class="auth-form search-form">
                                        <div class="form-check">
                                            <label class="form-check-label" for="fixed1">Rumah Ibadah</label>
                                            <input class="form-check-input" type="radio" name="kategori"
                                                value="rumah_ibadah" />
                                        </div>
                                    </div>
                                    <div class="auth-form search-form">
                                        <div class="form-check">
                                            <label class="form-check-label" for="fixed2">Difabel</label>
                                            <input class="form-check-input" type="radio" name="kategori"
                                                value="difabel" />
                                        </div>
                                    </div>
                                </div>
                                <div class="order-type">
                                    <!-- <div class="auth-form search-form">
                              <div class="form-check">
                                <label class="form-check-label" for="fixed1">Sosial</label>
                                <input class="form-check-input" type="radio" name="kategori" value="sosial" />
                              </div>
                            </div> -->
                                    <div class="auth-form search-form">
                                        <div class="form-check">
                                            <label class="form-check-label" for="fixed1">Bencana Alam</label>
                                            <input class="form-check-input" type="radio" name="kategori"
                                                value="bencana_alam" />
                                        </div>
                                    </div>
                                    <div class="auth-form search-form">
                                        <div class="form-check">
                                            <label class="form-check-label" for="fixed2">Kemanusiaan</label>
                                            <input class="form-check-input" type="radio" name="kategori"
                                                value="kemanusiaan" />
                                        </div>
                                    </div>
                                </div>
                                <div class="order-type">
                                    <div class="auth-form search-form">
                                        <div class="form-check">
                                            <label class="form-check-label" for="fixed2">Infrastruktur</label>
                                            <input class="form-check-input" type="radio" name="kategori"
                                                value="infrastruktur" />
                                        </div>
                                    </div>
                                    <div class="auth-form search-form">
                                        <div class="form-check">
                                            <label class="form-check-label" for="fixed2">Lainnya</label>
                                            <input class="form-check-input" type="radio" name="kategori"
                                                value="lainnya" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="pt-0 section-lg-b-space">
                            <div class="order-options">
                                <h3 class="mb-3 dark-text fw-semibold">Urut Berdasarkan</h3>
                                <div class="order-type">
                                    <div class="auth-form search-form">
                                        <div class="form-check">
                                            <label class="form-check-label" for="fixed3">Tanggal Terbaru</label>
                                            <input class="form-check-input" type="radio" name="sort"
                                                value="terbaru" checked />
                                        </div>
                                    </div>
                                    <div class="auth-form search-form section-b-space">
                                        <div class="form-check">
                                            <label class="form-check-label" for="fixed4">Segera Berakhir</label>
                                            <input class="form-check-input" type="radio" name="sort"
                                                value="segera_berakhir" />
                                        </div>
                                    </div>
                                </div>
                                <div class="order-type">
                                    <div class="auth-form search-form">
                                        <div class="form-check">
                                            <label class="form-check-label" for="fixed3">Donasi Terbanyak</label>
                                            <input class="form-check-input" type="radio" name="sort"
                                                value="terbanyak" />
                                        </div>
                                    </div>
                                    <div class="auth-form search-form section-b-space">
                                        <div class="form-check">
                                            <label class="form-check-label" for="fixed4">Donasi Sedikit</label>
                                            <input class="form-check-input" type="radio" name="sort"
                                                value="sedikit" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </section>

                <div class="footer-modal d-flex">
                    <a href="#" class="btn btn-link btn-inline mt-0 w-50">Reset Filter</a>
                    <a href="#" class="theme-btn btn btn-inline mt-0 w-50" id="apply">Apply</a>
                </div>
            </div>
        </div>
    </div>
    <!-- filter offcanvas end -->

    <!-- pwa install app popup start -->
    <!-- <div class="offcanvas offcanvas-bottom addtohome-popup theme-offcanvas" tabindex="-1" id="offcanvas">
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              <div class="offcanvas-body small">
                <div class="app-info">
                  <img src="{{ asset('Logo Bantubersama.png') }}" class="img-fluid" alt="" />
                  <div class="content">
                    <h3>Bantubersama</h3>
                    <a href="#">www.bantubersama.com</a>
                  </div>
                </div>
                <a href="#!" class="btn theme-btn-me install-app btn-inline home-screen-btn m-0" id="installApp">Add to Home
                  Screen</a>
              </div>
            </div> -->
    <!-- pwa install app popup start -->
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


@section('js_inline')
    <script type="text/javascript">
        $("img.lazyload").lazyload({
            threshold: 900,
            skip_invisible: false,
        });

        const texts = ['Gandakan sedekah disaat Ramadhan', 'Cari Program Kebaikan... ', 'Anak Yatim...',
            'Beasiswa Santri...', 'Rumah Tahfidz...'
        ];
        const input = document.querySelector('#inputkey');
        const animationWorker = function(input, texts) {
            this.input = input;
            this.defaultPlaceholder = this.input.getAttribute('placeholder');
            this.texts = texts;
            this.curTextNum = 0;
            this.curPlaceholder = '';
            this.blinkCounter = 0;
            this.animationFrameId = 0;
            this.animationActive = false;
            this.input.setAttribute('placeholder', this.curPlaceholder);

            this.switch = (timeout) => {
                this.input.classList.add('imitatefocus');
                setTimeout(
                    () => {
                        this.input.classList.remove('imitatefocus');
                        if (this.curTextNum == 0)
                            this.input.setAttribute('placeholder', this.defaultPlaceholder);
                        else
                            this.input.setAttribute('placeholder', this.curPlaceholder);

                        setTimeout(
                            () => {
                                this.input.setAttribute('placeholder', this.curPlaceholder);
                                if (this.animationActive)
                                    this.animationFrameId = window.requestAnimationFrame(this.animate)
                            },
                            timeout);
                    },
                    timeout);
            }

            this.animate = () => {
                if (!this.animationActive) return;
                let curPlaceholderFullText = this.texts[this.curTextNum];
                let timeout = 600; // lama kedip kursor akhir text setelah selesai text
                if (this.curPlaceholder == curPlaceholderFullText + '|' && this.blinkCounter == 3) {
                    this.blinkCounter = 0;
                    this.curTextNum = (this.curTextNum >= this.texts.length - 1) ? 0 : this.curTextNum + 1;
                    this.curPlaceholder = '|';
                    this.switch(400); // waktu setelah selesai mau lanjut ke text berikutnya
                    return;
                } else if (this.curPlaceholder == curPlaceholderFullText + '|' && this.blinkCounter < 3) {
                    this.curPlaceholder = curPlaceholderFullText;
                    this.blinkCounter++;
                } else if (this.curPlaceholder == curPlaceholderFullText && this.blinkCounter < 3) {
                    this.curPlaceholder = this.curPlaceholder + '|';
                } else {
                    this.curPlaceholder = curPlaceholderFullText
                        .split('')
                        .slice(0, this.curPlaceholder.length + 1)
                        .join('') + '|';
                    timeout = 180; // kecepatan mengetik
                }
                this.input.setAttribute('placeholder', this.curPlaceholder);
                setTimeout(
                    () => {
                        if (this.animationActive) this.animationFrameId = window.requestAnimationFrame(this
                            .animate)
                    },
                    timeout);
            }

            this.stop = () => {
                this.animationActive = false;
                window.cancelAnimationFrame(this.animationFrameId);
            }

            this.start = () => {
                this.animationActive = true;
                this.animationFrameId = window.requestAnimationFrame(this.animate);
                return this;
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            let aw = new animationWorker(input, texts).start();
            input.addEventListener("focus", (e) => aw.stop());
            input.addEventListener("blur", (e) => {
                aw = new animationWorker(input, texts);
                if (e.target.value == '') setTimeout(aw.start, 400);
            });
        });
        // end typing in search

        // show add to home screen
        // window.addEventListener("load", (event) => {
        //   var myOffcanvas = document.getElementById("offcanvas");
        //   var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
        //   bsOffcanvas.show();
        // });

        // Apply Filter
        $("#apply").on("click", function() {
            let kategori = $('input[name=kategori]:checked').val();
            let sort = $('input[name=sort]:checked').val();
            window.location.href = "{{ url('/') }}/programs/?kategori=" + kategori + "&sort=" + sort;
        });

        $('#search').on("submit", function(e) {
            let keys = $('#inputkey').val();
            window.location.href = "{{ url('/') }}/programs/?key=" + keys;
            return false;
        });
    </script>
@endsection
