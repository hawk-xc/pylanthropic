@extends('layouts.public', [
    'second_title' => 'Qurban - BANTUBERSAMA.com',
    'meta_desc'    => 'Qurban Bantusesama adalah platform pembelian qurban secara online',
    'image'        => 'promo-qurban-bantubersama.png',
])


@section('css_plugins')
    <!-- Meta Pixel Code -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq.disablePushState = true;
      <?php 
      if(isset($_GET['a'])) {
        if($_GET['a']=='il1') {
          echo "fbq('init', '1352154889054298');";
          $pixel_id = "1352154889054298";
        } elseif($_GET['a']=='bb4' || $_GET['a']=='BB4') {
          echo "fbq('init', '1278491429470122');";
          $pixel_id = "1278491429470122";
        } elseif($_GET['a']=='bb1' || $_GET['a']=='bb2' || $_GET['a']=='bb3') {
          echo "fbq('init', '586907076711934');";
          $pixel_id = "586907076711934";
        } else {
          echo "fbq('init', '586907076711934');";
          echo "fbq('init', '1278491429470122');";
          $pixel_id = "2596008717326722";
        }
      } else {
        echo "fbq('init', '586907076711934');";
        echo "fbq('init', '1278491429470122');";
        $pixel_id = "586907076711934";
      }
      ?>
      fbq('track', 'ViewContent');
      window.loadedPixel = []
    </script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{$pixel_id}}&ev=ViewContent&noscript=1" /></noscript>
  <!-- End Meta Pixel Code -->
@endsection


@section('css_inline')
  <style type="text/css">
    .top-section {
        margin-top: -16px;
        position: relative;
        background-color: white;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
        padding-top: 20px;
    }
    .img-product {
        width: 100% !important;
        max-width: 100%;
        height: auto;
        border-radius: 16px;
    }
    .btn-buy {
        padding : 6px 22px 5px 22px !important;
        --bs-btn-border-radius: 10px !important;
        background-color: #2ba1ef !important;
    }
    .box-product {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
    }
    .box-product .box-desc {
        padding: 0px 10px 10px 10px;
    }
    .text-title {
        font-size: 15px;
        font-weight: 600;
        margin-top: 12px;
        padding-top: 12px;
    }
    .text-desc {
        font-size: 11px;
        font-weight: 400;
        color: #6A6A6A;
        margin-left: 3px;
    }
    .text-price {
        font-weight: 600;
        font-size: 15px;
        color: #2ba1ef;
        padding-top: 4px;
    }
    .text-discount {
        color: #9ca3af;
        text-decoration: line-through !important;
        font-size: 11px;
        font-weight: 300;
        margin-bottom: 6px;
    }
    .discount-percent {
        margin-left: 3px;
        padding: 3px 5px;
        background-color: #FFE97A;
        border-radius: 4px 4px 4px 4px;
        color: #EC3814;
        font-size: 12px;
        font-weight: 600;
    }
    .info-timeline li {
        margin-bottom: 10px !important; 
    }
    .info-timeline li::before {
        top: 22px;
    }
    .badge-promo {
        --bs-bg-opacity: 1;
        background-color: #2ba1ef !important;
        font-size: 13px;
        padding: 5px 10px !important;
        margin-top: 5px;
    }
  </style>
@endsection


@section('content')
  <div class="">
    <img alt="Pengajuan Program Bantusesama.com" class="h-auto w-100" src="{{ asset('public/images/qurban/promo-qurban-bantubersama.png') }}">
  </div>
  <!-- header end -->

  <!-- Detail section start  -->
  <section class="pb-2 top-section">
    <div class="custom-container text-center">
        <h4 class="donate-collect">Qurban di Bantusesama</h4>
        <div class="short-desc mt-1">
            <span class="badge badge-promo">Sesuai Syariat</span>
            <span class="badge badge-promo">Terpercaya</span>
            <span class="badge badge-promo">Transparan</span>
            <span class="badge badge-promo">Amanah</span>
        </div>
    </div>
  </section>
  <!-- filter section end  -->
    
    <?php
      $uri = explode('?', url()->full());
      if(!empty($uri[1])){
        $uri_param = '?'.$uri[1];
      } else {
        $uri_param = '';
      }
    ?>
    
  <!-- Program Detail section start -->
  <section class="py-20 selection-product">
    <div class="custom-container">
      <div class="text-center fw-bold fs-18 mb-2 pb-1">Pilihan Hewan Qurban</div>
      <div class="row g-2">
        <div class="col-6">
          <div class="box-product">
            <a href="{{ route('payment', 1).$uri_param }}">
              <img class="img-product" src="{{ asset('public/images/qurban/kambing-qurban-bantubersama.png') }}" alt="Qurban Kambing di Bantusesama">
            </a>
            <div class="box-desc">
                <div class="mt-2">
                    <span class="text-title">Kambing</span>
                    <span class="text-desc">19-24kg</span>
                </div>
                <div class="mt-1">
                    <span class="text-price">Rp1.499.000</span>
                    <span class="discount-percent">15%</span>
                </div>
                <div class="text-discount">Rp 1.750.000</div>
                <a href="{{ route('payment', 1).$uri_param }}" class="btn donate-btn btn-buy w-100">Beli Sekarang</a>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="box-product">
            <a href="{{ route('payment', 2).$uri_param }}">
              <img class="img-product" src="{{ asset('public/images/qurban/domba-qurban-bantubersama.png') }}" alt="Qurban Domba di Bantusesama">
            </a>
            <div class="box-desc">
                <div class="mt-2">
                    <span class="text-title">Domba</span>
                    <span class="text-desc">24-29kg</span>
                </div>
                <div class="mt-1">
                    <span class="text-price">Rp2.300.000</span>
                    <span class="discount-percent">12%</span>
                </div>
                <div class="text-discount">Rp2.650.000</div>
                <a href="{{ route('payment', 2).$uri_param }}" class="btn donate-btn btn-buy w-100">Beli Sekarang</a>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="box-product">
            <a href="{{ route('payment', 3).$uri_param }}">
              <img class="img-product" src="{{ asset('public/images/qurban/sapi17-qurban-bantubersama.png') }}" alt="Qurban Sapi 1/7 Bantusesama">
            </a>
            <div class="box-desc">
                <div class="mt-2">
                    <span class="text-title">Sapi 1/7</span>
                    <span class="text-desc">29-37kg</span>
                </div>
                <div class="mt-1">
                    <span class="text-price">Rp1.950.000</span>
                    <span class="discount-percent">19%</span>
                </div>
                <div class="text-discount">Rp2.400.000</div>
                <a href="{{ route('payment', 3).$uri_param }}" class="btn donate-btn btn-buy w-100">Beli Sekarang</a>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="box-product">
            <a href="{{ route('payment', 4).$uri_param }}">
              <img class="img-product" src="{{ asset('public/images/qurban/sapi-qurban-bantubersama.png') }}" alt="Qurban Sapi Bantusesama">
            </a>
            <div class="box-desc">
                <div class="mt-2">
                    <span class="text-title">Sapi Utuh</span>
                    <span class="text-desc">200-300kg</span>
                </div>
                <div class="mt-1">
                    <span class="text-price">Rp12.999.999</span>
                    <span class="discount-percent">11%</span>
                </div>
                <div class="text-discount">Rp14.500.000</div>
                <a href="{{ route('payment', 4).$uri_param }}" class="btn donate-btn btn-buy w-100">Beli Sekarang</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Program Detail section end -->

  <section class="empty-section section-t-space section-b-space pb-0 pt-3">
    <div class="custom-container space-empty pb-2"></div>
  </section>

  <!-- Detail section start  -->
  <section class="pb-2">
    <div class="custom-container text-center">
      <h4 class="donate-collect mb-3">Dokumentasi Qurban</h4>
      <div class="row g-1">
          <div class="col-6">
              <img class="img-product" src="{{ asset('public/images/qurban/2.png') }}" alt="Dokumentasi Qurban 1 Bantusesama">
          </div>
          <div class="col-6">
              <img class="img-product" src="{{ asset('public/images/qurban/5.png') }}" alt="Dokumentasi Qurban 2 Bantusesama">
          </div>
          <div class="col-6">
              <img class="img-product" src="{{ asset('public/images/qurban/7.png') }}" alt="Dokumentasi Qurban 3 Bantusesama">
          </div>
          <div class="col-6">
              <img class="img-product" src="{{ asset('public/images/qurban/8.png') }}" alt="Dokumentasi Qurban 4 Bantusesama">
          </div>
          <div class="col-6">
              <img class="img-product" src="{{ asset('public/images/qurban/9.png') }}" alt="Dokumentasi Qurban 5 Bantusesama">
          </div>
          <div class="col-6">
              <img class="img-product" src="{{ asset('public/images/qurban/10.png') }}" alt="Dokumentasi Qurban 6 Bantusesama">
          </div>
          <div class="col-6">
              <img class="img-product" src="{{ asset('public/images/qurban/12.png') }}" alt="Dokumentasi Qurban 7 Bantusesama">
          </div>
          <div class="col-6">
              <img class="img-product" src="{{ asset('public/images/qurban/13.png') }}" alt="Dokumentasi Qurban 8 Bantusesama">
          </div>
          <div class="col-6">
              <img class="img-product" src="{{ asset('public/images/qurban/14.png') }}" alt="Dokumentasi Qurban 9 Bantusesama">
          </div>
          <div class="col-6">
              <img class="img-product" src="{{ asset('public/images/qurban/15.png') }}" alt="Dokumentasi Qurban 10 Bantusesama">
          </div>
      </div>
    </div>
  </section>
  <!-- filter section end  -->

  <section class="empty-section section-t-space section-b-space pb-0 pt-3">
    <div class="custom-container space-empty pb-2"></div>
  </section>

  <!-- Detail section start  -->
  <section class="pb-2">
    <div class="custom-container text-center">
      <h4 class="donate-collect mb-3">Proses Qurban di Bantusesama</h4>
      <ul class="info-timeline">
        <li>
          <div class="content-preview1">
            <div class="info-head">
              <div class="info-box justify-content-start">
                  <div class="pt-1">
                      Pilih jenis hewan qurbanmu dan lakukan pembayaran
                  </div>
              </div>
            </div>
          </div>
        </li>
        <li>
            <div class="content-preview1">
                <div class="info-head">
                    <div class="info-box justify-content-start">
                        <div class="pt-1">
                            Kamu akan mendapatkan notifikasi status hewan qurban saat dibeli, disembelih dan didistribusikan melalui WhatsApp
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="content-preview1">
                <div class="info-head">
                    <div class="info-box justify-content-start">
                        <div class="pt-1">
                            Kamu akan menerima sertifikat qurban sebagai bukti sah pembelian hewan qurban yang akan dikirimkan melalui WhatsApp
                        </div>
                    </div>
                </div>
            </div>
        </li>
      </ul>
    </div>
  </section>
  <!-- filter section end  -->


  <!-- Footer section start -->
  <section class="empty-section section-t-space section-b-space pb-0 pt-3">
    <div class="custom-container footer pb-3 pt-3">
      <div class="fw-medium text-grey pt-2 fs-14">
        <a class="text-grey" href="">Tentang Kami</a> | 
        <a class="text-grey" href="">Syarat & Ketentuan</a> | 
        <a class="text-grey" href="">Pusat Bantuan</a>
      </div>
      <div class="mt-3 text-grey fs-14">
        Temukan kami di <br>
        <div class="socmed mb-3 mt-1">
          <a rel="noreferrer" href="https://www.facebook.com/profile.php?id=100091563649667" target="_blank" class="me-2 socmed-item rounded-circle">
              <svg class="mx-auto" width="12" height="12" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M9 .002L7.443 0C5.695 0 4.565 1.16 4.565 2.953v1.362H3.001a.245.245 0 00-.245.245v1.973c0 .135.11.244.245.244h1.564v4.978c0 .135.11.245.245.245h2.041c.136 0 .245-.11.245-.245V6.777h1.83c.135 0 .244-.11.244-.244V4.56a.245.245 0 00-.244-.245h-1.83V3.16c0-.555.132-.837.855-.837h1.048c.135 0 .245-.11.245-.245V.247A.245.245 0 009 .002z" fill="currentColor"></path>
              </svg>
              <span class="screen-reader-text">Facebook</span>
          </a>
          <a rel="noreferrer" href="https://www.instagram.com/bantubersamacom/" target="_blank" class="me-2 socmed-item rounded-circle">
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
          <a rel="noreferrer" href="https://twitter.com/bantubersamacom" target="_blank" class="me-2 socmed-item rounded-circle">
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
          <a rel="noreferrer" href="https://www.youtube.com/@Bantusesama-de2vi" target="_blank" class="socmed-item rounded-circle">
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
      <div class="fs-14 fw-normal text-grey mt-3">
        Copyright © 2024 Bantusesama
      </div>
    </div>
  </section>
  <!-- footer section end -->
@endsection


@section('content_modal')


@endsection


@section('js_plugins')
  
@endsection


@section('js_inline')
  <script type="text/javascript">
  
  </script>
@endsection
