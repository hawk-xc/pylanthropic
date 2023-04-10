@extends('layouts.public', [
    'second_title'    => 'Home'
])


@section('css_plugins')
    <!-- swiper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/css/vendors/swiper-bundle.min.css" />
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
    <!-- search section starts -->
    <section class="search-section pt-3">
      <div class="custom-container">
        <form class="auth-form search-head" target="_blank">
          <div class="form-group">
            <a href="index.html">
              <img class="img-fluid" src="{{ asset('public') }}/images/logo/bantubersama-logo-light.png">
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
    <section class="banner-section section-t-space">
      <div class="custom-container">
        <div class="swiper banner1">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <img class="img-fluid banner-img" src="{{ asset('public') }}/images/banner/bisanolongyatim.jpeg" alt="banner1" />
            </div>

            <div class="swiper-slide">
              <div class="home-banner2">
                <img class="img-fluid banner-img" src="{{ asset('public') }}/images/banner/airminumbersih.jpeg" alt="banner2" />
              </div>
            </div>

            <div class="swiper-slide">
              <img class="img-fluid banner-img" src="{{ asset('public') }}/images/banner/banner1.jpg" alt="banner3" />
            </div>
            <div class="swiper-pagination"></div>
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
            <div class="swiper-slide">
              <a href="{{ route('program.list') }}" class="food-categories">
                <img class="img-fluid categories-img" src="{{ asset('public') }}/images/categories/sociology.png" alt="pizza" />
              </a>
              <h6 class="fs-12">Kemanusiaan</h6>
            </div>
            <div class="swiper-slide">
              <a href="{{ route('program.list') }}" class="food-categories">
                <img class="img-fluid categories-img" src="{{ asset('public') }}/images/categories/scholarship.png" alt="boritto" />
              </a>
              <h6 class="fs-12">Pendidikan</h6>
            </div>
            <div class="swiper-slide">
              <a href="{{ route('program.list') }}" class="food-categories">
                <img class="img-fluid categories-img" src="{{ asset('public') }}/images/categories/ambulance.png" alt="hotdog" />
              </a>
              <h6 class="fs-12">Kesehatan</h6>
            </div>
            <div class="swiper-slide">
              <a href="{{ route('program.list') }}" class="food-categories">
                <img class="img-fluid categories-img" src="{{ asset('public') }}/images/categories/taj-mahal.png" alt="burger" />
              </a>
              <h6 class="fs-12">Rumah Ibadah</h6>
            </div>
            <div class="swiper-slide">
              <a href="{{ route('program.list') }}" class="food-categories">
                <img class="img-fluid categories-img" src="{{ asset('public') }}/images/categories/wheelchair.png" alt="noodles" />
              </a>
              <h6 class="fs-12">Difabel</h6>
            </div>
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
            <div class="swiper-slide">
              <a href="/slug" class="">
                <div class="product-box">
                  <img class="img-fluid rounded-top" src="{{ asset('public') }}/images/program/a.jpeg" alt="p1" />
                  <div class="product-box-detail product-box-bg">
                    <h5 class="two-line mt-1 mb-1">Penuhi Kebutuhan Beras Untuk Santri Yatim, Dhuafa</h5>
                    <ul class="timing mt-2 mb-3">
                      <li class="fs-11 lh-16">Yayasan Jamhariyah <span class="star"><i class="ri-star-s-fill"></i></span></li>
                    </ul>
                    <div class="progress mt-1" role="progressbar" aria-label="Basic example" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                      <div class="progress-bar" style="width: 65%"></div>
                    </div>
                    <div class="bottom-panel">
                      <div class="pe-0 fw-semibold fs-11 lh-16">Rp 2.812.758.634</div>
                      <div class="fw-semibold fs-11 lh-16 text-end">56</div>
                    </div>
                    <div class="bottom-panel mt-0">
                      <div class="pe-0 fw-light fs-10">Donasi Terkumpul</div>
                      <div class="fw-light fs-10 text-end">Hari Lagi</div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="swiper-slide">
              <a href="/slug" class="">
                <div class="product-box">
                  <img class="img-fluid rounded-top" src="{{ asset('public') }}/images/program/c.jpg" alt="p1" />
                  <div class="product-box-detail product-box-bg">
                    <h5 class="two-line mt-1 mb-1">Penuhi Kebutuhan Beras Untuk Santri Yatim, Dhuafa</h5>
                    <ul class="timing mt-2 mb-3">
                      <li class="fs-11 lh-16">Yayasan Jamhariyah <span class="star"><i class="ri-star-s-fill"></i></span></li>
                    </ul>
                    <div class="progress mt-1" role="progressbar" aria-label="Basic example" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                      <div class="progress-bar" style="width: 85%"></div>
                    </div>
                    <div class="bottom-panel">
                      <div class="pe-0 fw-semibold fs-11 lh-16">Rp 2.812.758.634</div>
                      <div class="fw-semibold fs-11 lh-16 text-end">56</div>
                    </div>
                    <div class="bottom-panel mt-0">
                      <div class="pe-0 fw-light fs-10">Donasi Terkumpul</div>
                      <div class="fw-light fs-10 text-end">Hari Lagi</div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="swiper-slide">
              <a href="/slug" class="">
                <div class="product-box">
                  <img class="img-fluid rounded-top" src="{{ asset('public') }}/images/program/b.jpg" alt="p1" />
                  <div class="product-box-detail product-box-bg">
                    <h5 class="two-line mt-1 mb-1">Penuhi Kebutuhan Beras Untuk Santri Yatim, Dhuafa</h5>
                    <ul class="timing mt-2 mb-3">
                      <li class="fs-11 lh-16">Yayasan Jamhariyah <span class="star"><i class="ri-star-s-fill"></i></span></li>
                    </ul>
                    <div class="progress mt-1" role="progressbar" aria-label="Basic example" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                      <div class="progress-bar" style="width: 95%"></div>
                    </div>
                    <div class="bottom-panel">
                      <div class="pe-0 fw-semibold fs-11 lh-16">Rp 2.812.758.634</div>
                      <div class="fw-semibold fs-11 lh-16 text-end">56</div>
                    </div>
                    <div class="bottom-panel mt-0">
                      <div class="pe-0 fw-light fs-10">Donasi Terkumpul</div>
                      <div class="fw-light fs-10 text-end">Hari Lagi</div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="swiper-slide">
              <a href="/slug" class="">
                <div class="product-box">
                  <img class="img-fluid rounded-top" src="{{ asset('public') }}/images/program/d.jpeg" alt="p1" />
                  <div class="product-box-detail product-box-bg">
                    <h5 class="two-line mt-1 mb-1">Penuhi Kebutuhan Beras Untuk Santri Yatim, Dhuafa</h5>
                    <ul class="timing mt-2 mb-3">
                      <li class="fs-11 lh-16">Yayasan Jamhariyah <span class="star"><i class="ri-star-s-fill"></i></span></li>
                    </ul>
                    <div class="progress mt-1" role="progressbar" aria-label="Basic example" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                      <div class="progress-bar" style="width: 45%"></div>
                    </div>
                    <div class="bottom-panel">
                      <div class="pe-0 fw-semibold fs-11 lh-16">Rp 2.812.758.634</div>
                      <div class="fw-semibold fs-11 lh-16 text-end">56</div>
                    </div>
                    <div class="bottom-panel mt-0">
                      <div class="pe-0 fw-light fs-10">Donasi Terkumpul</div>
                      <div class="fw-light fs-10 text-end">Hari Lagi</div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="swiper-slide">
              <a href="/slug" class="">
                <div class="product-box">
                  <img class="img-fluid rounded-top" src="{{ asset('public') }}/images/program/e.jpg" alt="p1" />
                  <div class="product-box-detail product-box-bg">
                    <h5 class="two-line mt-1 mb-1">Penuhi Kebutuhan Beras Untuk Santri Yatim, Dhuafa</h5>
                    <ul class="timing mt-2 mb-3">
                      <li class="fs-11 lh-16">Yayasan Jamhariyah <span class="star"><i class="ri-star-s-fill"></i></span></li>
                    </ul>
                    <div class="progress mt-1" role="progressbar" aria-label="Basic example" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                      <div class="progress-bar" style="width: 89%"></div>
                    </div>
                    <div class="bottom-panel">
                      <div class="pe-0 fw-semibold fs-11 lh-16">Rp 2.812.758.634</div>
                      <div class="fw-semibold fs-11 lh-16 text-end">56</div>
                    </div>
                    <div class="bottom-panel mt-0">
                      <div class="pe-0 fw-light fs-10">Donasi Terkumpul</div>
                      <div class="fw-light fs-10 text-end">Hari Lagi</div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- product section start -->

    <!-- Banner section start -->
    <section class="empty-section section-t-space section-b-space">
      <div class="custom-container">
        <a href="#" target="_blank">
          <img class="img-fluid" src="{{ asset('public') }}/images/banner/banner1.jpg">
        </a>
      </div>
    </section>
    <!-- Banner section end -->

    <!-- Program Newest section start -->
    <section class="section-t-space">
      <div class="custom-container">
        <div class="title">
          <h3 class="mt-0">Terbaru di Bantubersama</h3>
          <a href="{{ route('program.list') }}">Semua</a>
        </div>
        <div class="row gy-3">
          <div class="col-12">
            <div class="vertical-product-box">
              <div class="vertical-box-img">
                <a href="/slug">
                  <img class="img-fluid img" src="{{ asset('public') }}/images/program/a.jpeg" alt="vp5" />
                </a>
              </div>

              <div class="vertical-box-details">
                <a href="/slug">
                  <div class="vertical-box-head">
                    <div class="restaurant">
                      <h5 class="two-line fs-13">Penuhi Kebutuhan Beras Untuk Santri Yatim, Dhuafa, Difabel Dipedalaman</h5>
                    </div>

                    <h6 class="rating-star mt-2 mb-3">
                      Yayasan Jamhariyah <span class="star"><i class="ri-star-s-fill"></i></span>
                    </h6>

                    <div class="progress mt-1 mb-2" role="progressbar" aria-label="Basic example" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                      <div class="progress-bar" style="width: 85%"></div>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                      <div class="fw-semibold fs-11 pe-0 lh-20">Rp 2.812.758.634</div>
                      <div class="fw-semibold fs-11 text-end ps-1 lh-20">56</div>
                    </div>
                    <div class="d-flex justify-content-between">
                      <div class="fw-light fs-10 pe-0">Donasi Terkumpul</div>
                      <div class="fw-light fs-10 text-end ps-1">Hari Lagi</div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="vertical-product-box">
              <div class="vertical-box-img">
                <a href="/slug">
                  <img class="img-fluid img" src="{{ asset('public') }}/images/program/b.jpg" alt="vp5" />
                </a>
              </div>

              <div class="vertical-box-details">
                <a href="/slug">
                  <div class="vertical-box-head">
                    <div class="restaurant">
                      <h5 class="two-line fs-13">Penuhi Kebutuhan Beras Untuk Santri Yatim, Dhuafa, Difabel Dipedalaman</h5>
                    </div>

                    <h6 class="rating-star mt-2 mb-3">
                      Yayasan Jamhariyah <span class="star"><i class="ri-star-s-fill"></i></span>
                    </h6>

                    <div class="progress mt-1 mb-2" role="progressbar" aria-label="Basic example" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                      <div class="progress-bar" style="width: 65%"></div>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                      <div class="fw-semibold fs-11 pe-0 lh-20">Rp 2.812.758.634</div>
                      <div class="fw-semibold fs-11 text-end ps-1 lh-20">56</div>
                    </div>
                    <div class="d-flex justify-content-between">
                      <div class="fw-light fs-10 pe-0">Donasi Terkumpul</div>
                      <div class="fw-light fs-10 text-end ps-1">Hari Lagi</div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="vertical-product-box">
              <div class="vertical-box-img">
                <a href="/slug">
                  <img class="img-fluid img" src="{{ asset('public') }}/images/program/c.jpg" alt="vp5" />
                </a>
              </div>

              <div class="vertical-box-details">
                <a href="/slug">
                  <div class="vertical-box-head">
                    <div class="restaurant">
                      <h5 class="two-line fs-13">Penuhi Kebutuhan Beras Untuk Santri Yatim, Dhuafa, Difabel Dipedalaman</h5>
                    </div>

                    <h6 class="rating-star mt-2 mb-3">
                      Yayasan Jamhariyah <span class="star"><i class="ri-star-s-fill"></i></span>
                    </h6>

                    <div class="progress mt-1 mb-2" role="progressbar" aria-label="Basic example" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                      <div class="progress-bar" style="width: 35%"></div>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                      <div class="fw-semibold fs-11 pe-0 lh-20">Rp 2.812.758.634</div>
                      <div class="fw-semibold fs-11 text-end ps-1 lh-20">56</div>
                    </div>
                    <div class="d-flex justify-content-between">
                      <div class="fw-light fs-10 pe-0">Donasi Terkumpul</div>
                      <div class="fw-light fs-10 text-end ps-1">Hari Lagi</div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="vertical-product-box">
              <div class="vertical-box-img">
                <a href="/slug">
                  <img class="img-fluid img" src="{{ asset('public') }}/images/program/d.jpeg" alt="vp5" />
                </a>
              </div>

              <div class="vertical-box-details">
                <a href="/slug">
                  <div class="vertical-box-head">
                    <div class="restaurant">
                      <h5 class="two-line fs-13">Penuhi Kebutuhan Beras Untuk Santri Yatim, Dhuafa, Difabel Dipedalaman</h5>
                    </div>

                    <h6 class="rating-star mt-2 mb-3">
                      Yayasan Jamhariyah <span class="star"><i class="ri-star-s-fill"></i></span>
                    </h6>

                    <div class="progress mt-1 mb-2" role="progressbar" aria-label="Basic example" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                      <div class="progress-bar" style="width: 75%"></div>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                      <div class="fw-semibold fs-11 pe-0 lh-20">Rp 2.812.758.634</div>
                      <div class="fw-semibold fs-11 text-end ps-1 lh-20">56</div>
                    </div>
                    <div class="d-flex justify-content-between">
                      <div class="fw-light fs-10 pe-0">Donasi Terkumpul</div>
                      <div class="fw-light fs-10 text-end ps-1">Hari Lagi</div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="vertical-product-box">
              <div class="vertical-box-img">
                <a href="/slug">
                  <img class="img-fluid img" src="{{ asset('public') }}/images/program/e.jpg" alt="vp5" />
                </a>
              </div>

              <div class="vertical-box-details">
                <a href="/slug">
                  <div class="vertical-box-head">
                    <div class="restaurant">
                      <h5 class="two-line fs-13">Penuhi Kebutuhan Beras Untuk Santri Yatim, Dhuafa, Difabel Dipedalaman</h5>
                    </div>

                    <h6 class="rating-star mt-2 mb-3">
                      Yayasan Jamhariyah <span class="star"><i class="ri-star-s-fill"></i></span>
                    </h6>

                    <div class="progress mt-1 mb-2" role="progressbar" aria-label="Basic example" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                      <div class="progress-bar" style="width: 55%"></div>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                      <div class="fw-semibold fs-11 pe-0 lh-20">Rp 2.812.758.634</div>
                      <div class="fw-semibold fs-11 text-end ps-1 lh-20">56</div>
                    </div>
                    <div class="d-flex justify-content-between">
                      <div class="fw-light fs-10 pe-0">Donasi Terkumpul</div>
                      <div class="fw-light fs-10 text-end ps-1">Hari Lagi</div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Explore Restaurants section end -->

    <!-- Footer section start -->
    <section class="empty-section section-t-space section-b-space pb-0">
      <div class="custom-container footer pb-4 pt-4">
        <div class="fw-medium text-grey pt-2">
          <!-- <a class="text-grey" href="">Tentang Kami</a> |  -->
          <a class="text-grey" href="">Syarat & Ketentuan</a> | 
          <a class="text-grey" href="">Pusat Bantuan</a>
        </div>
        <div class="mt-4 text-grey">
          Temukan kami di <br>
          <div class="socmed mb-4 mt-2">
            <a rel="noreferrer" href="https://www.facebook.com/bantubersama/" target="_blank" class="me-2 socmed-item rounded-circle">
                <svg class="mx-auto" width="12" height="12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 .002L7.443 0C5.695 0 4.565 1.16 4.565 2.953v1.362H3.001a.245.245 0 00-.245.245v1.973c0 .135.11.244.245.244h1.564v4.978c0 .135.11.245.245.245h2.041c.136 0 .245-.11.245-.245V6.777h1.83c.135 0 .244-.11.244-.244V4.56a.245.245 0 00-.244-.245h-1.83V3.16c0-.555.132-.837.855-.837h1.048c.135 0 .245-.11.245-.245V.247A.245.245 0 009 .002z" fill="currentColor"></path>
                </svg>
                <span class="screen-reader-text">Facebook</span>
            </a>
            <a rel="noreferrer" href="https://www.instagram.com/bantubersama" target="_blank" class="me-2 socmed-item rounded-circle">
                <svg class="mx-auto" width="12" height="12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M8.688 0H3.311A3.315 3.315 0 000 3.312v5.376A3.315 3.315 0 003.311 12h5.377A3.315 3.315 0 0012 8.688V3.312A3.315 3.315 0 008.688 0zm2.247 8.688a2.25 2.25 0 01-2.247 2.247H3.311a2.25 2.25 0 01-2.246-2.247V3.312A2.25 2.25 0 013.31 1.065h5.377a2.25 2.25 0 012.247 2.247v5.376z"
                        fill="currentColor"
                    ></path>
                    <path
                        d="M6 2.906a3.096 3.096 0 00-3.092 3.092A3.095 3.095 0 006 9.09a3.095 3.095 0 003.092-3.092A3.096 3.096 0 006 2.906zm0 5.12a2.03 2.03 0 01-2.028-2.028A2.03 2.03 0 016 3.971a2.03 2.03 0 012.027 2.027A2.03 2.03 0 016 8.025zM9.222 2.004a.784.784 0 00-.781.78.787.787 0 00.78.78.788.788 0 00.553-.227.784.784 0 00-.552-1.333z"
                        fill="currentColor"
                    ></path>
                </svg>
                <span class="screen-reader-text">Instagram</span>
            </a>
            <a rel="noreferrer" href="https://twitter.com/bantubersama" target="_blank" class="me-2 socmed-item rounded-circle">
                <svg class="mx-auto" width="12" height="12" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                    <g>
                        <path
                            d="M14.356 4.742A6.547 6.547 0 0016 3.039a6.85 6.85 0 01-1.89.518 3.263 3.263 0 001.443-1.813 6.563 6.563 0 01-2.08.794A3.28 3.28 0 007.8 4.781c0 .26.022.51.076.748a9.287 9.287 0 01-6.761-3.432 3.308 3.308 0 00-.45 1.658c0 1.136.585 2.143 1.458 2.726A3.242 3.242 0 01.64 6.077v.036a3.296 3.296 0 002.628 3.224 3.262 3.262 0 01-.86.108c-.21 0-.422-.012-.62-.056a3.312 3.312 0 003.064 2.285 6.593 6.593 0 01-4.067 1.399c-.269 0-.527-.012-.785-.045A9.237 9.237 0 005.032 14.5c5.789 0 9.561-4.83 9.324-9.758z"
                            fill="currentColor"
                        ></path>
                    </g>
                </svg>
                <span class="screen-reader-text">Twitter</span>
            </a>
            <a rel="noreferrer" href="https://www.youtube.com/bantubersama" target="_blank" class="socmed-item rounded-circle">
                <svg class="mx-auto" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24">
                    <path
                        d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"
                        fill="currentColor"
                    ></path>
                </svg>
                <span class="screen-reader-text">Youtube</span>
            </a>
        </div>

        </div>
        <div class="fw-normal text-grey mt-3">
          Copyright © 2023 Yayasan Bantu Bersama Indonesia
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
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="fixed1" checked />
                      </div>
                    </div>
                    <div class="auth-form search-form">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed2">Kemanusiaan</label>
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="fixed2" />
                      </div>
                    </div>
                  </div>
                  <div class="order-type">
                    <div class="auth-form search-form">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed1">Pendidikan</label>
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="fixed1" />
                      </div>
                    </div>
                    <div class="auth-form search-form">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed2">Kesehatan</label>
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="fixed2" />
                      </div>
                    </div>
                  </div>
                  <div class="order-type">
                    <div class="auth-form search-form">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed1">Rumad Ibadah</label>
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="fixed1" />
                      </div>
                    </div>
                    <div class="auth-form search-form">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed2">Difabel</label>
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="fixed2" />
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
                        <input class="form-check-input" type="radio" name="RadioDefault" id="fixed3" checked />
                      </div>
                    </div>
                    <div class="auth-form search-form section-b-space">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed4">Segera Berakhir</label>
                        <input class="form-check-input" type="radio" name="RadioDefault" id="fixed4" />
                      </div>
                    </div>
                  </div>
                  <div class="order-type">
                    <div class="auth-form search-form">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed3">Donasi Terbanyak</label>
                        <input class="form-check-input" type="radio" name="RadioDefault" id="fixed3" />
                      </div>
                    </div>
                    <div class="auth-form search-form section-b-space">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed4">Donasi Sedikit</label>
                        <input class="form-check-input" type="radio" name="RadioDefault" id="fixed4" />
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </section>

          <div class="footer-modal d-flex">
            <a href="index.html" class="btn btn-link btn-inline mt-0 w-50">Reset Filter</a>
            <a href="{{ route('program.list') }}" class="theme-btn btn btn-inline mt-0 w-50">Terapkan</a>
          </div>
        </div>
      </div>
    </div>
    <!-- filter offcanvas end -->

    <!-- pwa install app popup start -->
    <div class="offcanvas offcanvas-bottom addtohome-popup theme-offcanvas" tabindex="-1" id="offcanvas">
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      <div class="offcanvas-body small">
        <div class="app-info">
          <img src="{{ asset('public') }}/images/logo/bantubersama-logo-light.png" class="img-fluid" alt="" />
          <div class="content">
            <h3>Bantubersama</h3>
            <a href="#">www.bantubersama.com</a>
          </div>
        </div>
        <a href="#!" class="btn theme-btn-me install-app btn-inline home-screen-btn m-0" id="installApp">Add to Home
          Screen</a>
      </div>
    </div>
    <!-- pwa install app popup start -->
@endsection


@section('js_plugins')
    <!-- bootstrap js -->
    <script src="{{ asset('public') }}/js/bootstrap.bundle.min.js"></script>

    <!-- swiper js -->
    <script src="{{ asset('public') }}/js/swiper-bundle.min.js"></script>
    <script src="{{ asset('public') }}/js/custom-swiper.js"></script>

    <!-- homescreen popup js -->
    <script src="{{ asset('public') }}/js/homescreen-popup.js"></script>
    
    <!-- PWA offcanvas popup js -->
    <script src="{{ asset('public') }}/js/offcanvas-popup.js"></script>
@endsection


@section('js_inline')
    <script type="text/javascript">
      const texts = ['Gandakan sedekah disaat Ramadhan', 'Cari Program Kebaikan... ','Anak Yatim...','Beasiswa Santri...', 'Rumah Tahfidz...'];
      const input = document.querySelector('#inputkey');
      const animationWorker = function (input, texts) {
        this.input              = input;
        this.defaultPlaceholder = this.input.getAttribute('placeholder');
        this.texts              = texts;
        this.curTextNum         = 0;
        this.curPlaceholder     = '';
        this.blinkCounter       = 0;
        this.animationFrameId   = 0;
        this.animationActive    = false;
        this.input.setAttribute('placeholder',this.curPlaceholder);

        this.switch = (timeout) => {
          this.input.classList.add('imitatefocus');
          setTimeout(
            () => { 
              this.input.classList.remove('imitatefocus');
              if (this.curTextNum == 0) 
                this.input.setAttribute('placeholder',this.defaultPlaceholder);
              else
                this.input.setAttribute('placeholder',this.curPlaceholder);

              setTimeout(
                () => { 
                  this.input.setAttribute('placeholder',this.curPlaceholder);
                  if(this.animationActive) 
                    this.animationFrameId = window.requestAnimationFrame(this.animate)}, 
                timeout);
            }, 
            timeout);
        }

        this.animate = () => {
          if(!this.animationActive) return;
          let curPlaceholderFullText = this.texts[this.curTextNum];
          let timeout = 600; // lama kedip kursor akhir text setelah selesai text
          if (this.curPlaceholder == curPlaceholderFullText+'|' && this.blinkCounter==3) {
            this.blinkCounter = 0;
            this.curTextNum = (this.curTextNum >= this.texts.length-1)? 0 : this.curTextNum+1;
            this.curPlaceholder = '|';
            this.switch(400); // waktu setelah selesai mau lanjut ke text berikutnya
            return;
          }
          else if (this.curPlaceholder == curPlaceholderFullText+'|' && this.blinkCounter<3) {
            this.curPlaceholder = curPlaceholderFullText;
            this.blinkCounter++;
          }
          else if (this.curPlaceholder == curPlaceholderFullText && this.blinkCounter<3) {
            this.curPlaceholder = this.curPlaceholder+'|';
          }
          else {
            this.curPlaceholder = curPlaceholderFullText
              .split('')
              .slice(0,this.curPlaceholder.length+1)
              .join('') + '|';
            timeout = 180; // kecepatan mengetik
          }
          this.input.setAttribute('placeholder',this.curPlaceholder);
          setTimeout(
            () => { if(this.animationActive) this.animationFrameId = window.requestAnimationFrame(this.animate)}, 
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
          if(e.target.value == '') setTimeout( aw.start, 400);
        });
      });
    </script>
@endsection
