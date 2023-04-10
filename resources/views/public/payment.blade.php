@extends('layouts.public', [
    'second_title'    => 'Metode Pembayaran'
])


@section('css_plugins')
    
@endsection


@section('css_inline')
    
@endsection

@section('content')
  <!-- header start -->
  <header class="section-t-space pt-0">
    <div class="header-panel bg-me header-title">
      <a href="{{ route('donate.amount', $slug) }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="#fff">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
      </a>
      <h2 class="fs-16">Pilih Metode Pembayaran</h2>
    </div>
  </header>
  <!-- header end -->

  <!-- payment method section start -->
  <section class="payment method section-lg-b-space pt-0">
    <div class="custom-container">
      <h3 class="fw-semibold section-t-space mb-2 pb-1 fs-15">Transfer Bank (Verifikasi manual 1x24jam)</h3>
      <ul class="payment-list">
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/bca.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">Transfer BCA</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/bsi.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">Transfer BSI</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/mandiri.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">Transfer Mandiri</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/bri.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">Transfer BRI</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
      </ul>

      <h3 class="fw-semibold mb-2 pb-1 fs-15 mt-4">Pembayaran Instan (Otomatis & Cepat)</h3>
      <ul class="payment-list">
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/qris.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">QRIS</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/gopay.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">GO-PAY</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/shopeepayqris.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">ShopeePay</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/dana.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">DANA</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/qris-ovo.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">OVO</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/linkaja.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">LinkAja</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
      </ul>

      <h3 class="fw-semibold mb-2 pb-1 fs-15 mt-4">Virtual Account (Verifikasi otomatis)</h3>
      <ul class="payment-list">
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/bca.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">BCA Virtual Account</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/bri.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">BRI Virtual Account</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/mandiri.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">Mandiri Virtual Account</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/bni.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">BNI Virtual Account</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/bsi.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">BSI Virtual Account</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/permata.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">Permata Virtual Account</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <a href="{{ route('donate.checkout', $slug) }}" class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/cimb.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">CIMB Virtual Account</h5>
                </div>
              </div>
            </div>
          </a>
        </li>
      </ul>
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
