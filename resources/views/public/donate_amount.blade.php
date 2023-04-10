@extends('layouts.public', [
    'second_title'    => 'Nominal Donasi'
])


@section('css_plugins')
    
@endsection


@section('css_inline')
    
@endsection

@section('content')
  <!-- header start -->
  <header class="section-t-space pt-0">
    <!-- <div class="custom-container"> -->
      <div class="header-panel bg-me header-title">
        <!-- <div class="header-title"> -->
          <a href="/{{ $slug }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="#fff">
              <line x1="19" y1="12" x2="5" y2="12"></line>
              <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
          </a>
          <h2 class="fs-16">Pilih Nonimal Donasi</h2>
        <!-- </div> -->
      </div>
    <!-- </div> -->
  </header>
  <!-- header end -->

  <!-- payment method section start -->
  <section class="payment method section-lg-b-space">
    <div class="custom-container">
      <h3 class="fw-medium fs-15">Pilih Nonimal Donasi Terbaik Anda</h3>
    
      <ul class="payment-list section-lg-b-space">
        <li class="cart-add-box payment-card-box gap-0 mt-3">
          <a href="{{ route('donate.payment', $slug) }}" class="container-fluid pe-0">
            <div class="payment-detail">
              <div class="add-content">
                <div>
                  <h5 class="fw-bold fs-16">Rp 30.000</h5>
                </div>
                <div class="float-end">
                  <i class="ri-arrow-right-s-line fs-26"></i>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-3">
          <a href="{{ route('donate.payment', $slug) }}" class="container-fluid pe-0">
            <div class="payment-detail">
              <div class="add-content">
                <div>
                  <h5 class="fw-bold fs-16">Rp 50.000</h5>
                </div>
                <div class="float-end">
                  <i class="ri-arrow-right-s-line fs-26"></i>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-3">
          <a href="{{ route('donate.payment', $slug) }}" class="container-fluid pe-0">
            <div class="payment-detail">
              <div class="add-content">
                <div>
                  <h5 class="fw-bold fs-16">Rp 100.000</h5>
                </div>
                <div class="float-end">
                  <i class="ri-arrow-right-s-line fs-26"></i>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-3">
          <a href="{{ route('donate.payment', $slug) }}" class="container-fluid pe-0">
            <div class="payment-detail">
              <div class="add-content">
                <div>
                  <h5 class="fw-bold fs-16">Rp 200.000</h5>
                </div>
                <div class="float-end">
                  <i class="ri-arrow-right-s-line fs-26"></i>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-3">
          <a href="{{ route('donate.payment', $slug) }}" class="container-fluid pe-0">
            <div class="payment-detail">
              <div class="add-content">
                <div>
                  <h5 class="fw-bold fs-16">Rp 500.000</h5>
                </div>
                <div class="float-end">
                  <i class="ri-arrow-right-s-line fs-26"></i>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-3">
          <a href="{{ route('donate.payment', $slug) }}" class="container-fluid pe-0">
            <div class="payment-detail">
              <div class="add-content">
                <div>
                  <h5 class="fw-bold fs-16">Rp 1.000.000</h5>
                </div>
                <div class="float-end">
                  <i class="ri-arrow-right-s-line fs-26"></i>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-3">
          <div class="container-fluid pe-0 payment-detail nominal-other">
            <div class="add-content">
              <div>
                <h5 class="fw-bold fs-14">Nominal Lainnya</h5>
                <div class="d-flex align-items-center my-2">
                  <span class="ph-rp fs-18 fw-bold">Rp</span>
                  <input class="form-nominal-other fs-18 fw-bold" name="amount" placeholder="0" type="text" value=""/>
                </div>
                <h5 class=" fs-12 mb-2">Min. donasi sebesar Rp 10.000</h5>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </section>
  <!-- payment method section end -->

  <!-- cart popup start -->
  <div class="cart-popup">
    <a href="{{ route('donate.payment', $slug) }}" class="btn btn-warning donate-btn">Lanjut Pembayaran</a>
  </div>
  <!-- cart popup end -->
@endsection


@section('content_modal')

@endsection


@section('js_plugins')

@endsection


@section('js_inline')
    
@endsection
