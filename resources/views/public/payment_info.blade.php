@extends('layouts.public', [
    'second_title'    => 'Checkout'
])


@section('css_plugins')
    
@endsection


@section('css_inline')
    
@endsection

@section('content')
  <!-- header start -->
  <header class="section-t-space pt-0">
    <div class="header-panel bg-me header-title">
      <a href="#">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="#fff">
          <!-- <line x1="19" y1="12" x2="5" y2="12"></line> -->
          <!-- <polyline points="12 19 5 12 12 5"></polyline> -->
        </svg>
      </a>
      <h2 class="fs-16">Instruksi Pembayaran</h2>
    </div>
  </header>
  <!-- header end -->

  <!-- payment method section start -->
  <section class="payment method section-lg-b-space pt-0">
    <div class="custom-container">
      <h5 class="fw-medium fs-15 mt-4">Selangkah lagi selesaikan donasi Anda</h5>
      <ul class="payment-list">
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <div class="w-100">
            <div class="payment-detail pb-0">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/bca.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <h5 class="fw-semibold fs-16">0123400055000</h5>
              </div>
              <a href="#" class="fw-semibold color-me">Salin</a>
            </div>
            <div class="fs-14 fw-medium text-secondary pb-1">a/n Yayasan Bantu Bersama Indonesia</div>
          </div>
        </li>
        <div class="fs-15 fw-medium my-3">Donasi sebesar</div>
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <div class="w-100">
            <div class="payment-detail">
              <div class="add-content">
                <h5 class="fw-semibold fs-18">Rp 50.<span class="highlight-digit">113</span></h5>
              </div>
              <a href="#" class="fw-semibold color-me">Salin</a>
            </div>
          </div>
        </li>  
      </ul>
      <div class="alert alert-warning disclaimer-detail mt-2 mb-1">
        <strong class="">Penting!</strong> Pastikan nominal transfer hingga 3 digit terakhir
      </div>
      <em class="fs-12 text-secondary">* Akan didonasikan hingga 3 digit terakhir</em>
      <hr class="mb-4">
      <div class="fs-14 lh-16 d-flex justify-content-between">
        <div>Waktu pembayaran sebelum</div>
        <div><strong class="fs-15">29 Mar 2023 16:55 WIB</strong></div>
      </div>
      <div class="fs-14 lh-16 d-flex justify-content-between mt-2 pt-1">
        <div>Nomor Invoice</div>
        <div class="fs-15">INV-23032911135</div>
      </div>
      <div class="fs-14 lh-16 d-flex justify-content-between mt-2 pt-1">
        <div>Status Pembayaran</div>
        <div class="fs-15 fw-semibold">Belum diterima</div>
      </div>
      <button class="btn btn-outline-warning share-btn mt-4 w-100">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
          <polyline points="9 11 12 14 22 4"></polyline>
          <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
        </svg>
        Cek status pembayaran
      </button>
      <button class="btn btn-warning donate-btn mt-2 w-100">
        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" class="mr-2 w-4">
          <path d="M17.5 10A3.5 3.5 0 1 0 14 6.5c0 .43-.203.86-.595 1.037L10.034 9.07c-.427.194-.924.052-1.283-.25a3.5 3.5 0 1 0-.2 5.517c.38-.275.885-.381 1.297-.156l3.585 1.955c.412.225.597.707.572 1.176a3.5 3.5 0 1 0 1.445-2.649c-.38.275-.886.381-1.298.156l-3.585-1.955c-.412-.225-.597-.707-.572-1.176.003-.062.005-.125.005-.188 0-.43.203-.86.595-1.037l3.371-1.533c.428-.194.924-.052 1.283.25.609.512 1.394.82 2.251.82Z" fill="#fff"></path>
        </svg>
        Bagikan Program Ini
      </button>
      <div class="alert alert-secondary disclaimer-detail mt-4">
        Jika ada kendala pada transaksi ini bisa <a href="#">lapor pada kami</a>
      </div>
      <a href="{{ url('/') }}" class="btn btn-warning donate-btn mt-2 w-100">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-orange-400">
          <rect x="3" y="3" width="7" height="7"></rect>
          <rect x="14" y="3" width="7" height="7"></rect>
          <rect x="14" y="14" width="7" height="7"></rect>
          <rect x="3" y="14" width="7" height="7"></rect>
        </svg>
        Lihat Program Lainnya
      </a>
    </div>
  </section>
  <!-- payment method section end -->
@endsection


@section('content_modal')

@endsection


@section('js_plugins')

@endsection


@section('js_inline')
    
@endsection
