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
      <a href="{{ route('donate.payment', $slug) }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="#fff">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
      </a>
      <h2 class="fs-16">Verifikasi Pembayaran</h2>
    </div>
  </header>
  <!-- header end -->

  <!-- payment method section start -->
  <section class="payment method section-lg-b-space pt-0">
    <div class="custom-container">
      <h5 class="fw-medium fs-15 mt-4">Isi Nominal Donasi</h5>
      <div class="d-flex align-items-center mt-2 pt-1">
        <span class="ph-rp fs-18 fw-bold">Rp</span>
        <input class="form-nominal-other fs-18 fw-bold" name="amount" placeholder="0" type="text" value="50.000"/>
      </div>
      <ul class="payment-list mb-4">
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <div class="w-100">
            <div class="payment-detail">
              <div class="add-img">
                <img class="img" src="{{ asset('public') }}/images/payment/bca.png" alt="mastercard" />
              </div>
              <div class="add-content">
                <div>
                  <h5 class="fw-medium fs-15">BCA Virtual Account</h5>
                </div>
              </div>
              <a href="payment.html" class="fw-semibold color-me">Ganti</a>
            </div>
          </div>
        </li>
      </ul>
      <hr>
      <div class="form-input mt-4">
        <input type="text" class="form-control fs-14 form-payment" placeholder="Nama Lengkap" />
      </div>
      <div class="form-input mt-2">
        <input type="text" class="form-control fs-14 form-payment" placeholder="Nomor Telpon" />
      </div>
      <label class="alert alert-avail-contact disclaimer-detail">
        <input type="checkbox" name="" class="me-2" checked>
        Saya bersedia dihubungi melalui Whatsapp 
      </label>
      <div class="hide-name-form">
        <div class="fw-medium fs-15">
          Sembunyikan nama saya<br>
          <span class="text-secondary">(<em>Orang Baik</em>)</span>
        </div>
        <div class="switch-btn">
          <input type="checkbox" name="anonim" />
        </div>
      </div>
      <div class="form-input">
        <label class="fw-medium fs-15 mb-1 pb-1">Tulis pesan dan do'a (opsional)</label>
        <textarea name="doa" rows="5" class="form-control fs-14 lh-20 form-payment" placeholder="Tulis pesan dan do'a  untuk diri sendiri atau penggalang dana agar dilihat dan diamini oleh orang baik lainnya"></textarea>
      </div>
      <div class="alert alert-secondary disclaimer-detail mt-4">
        Nominal di atas sudah termasuk 5% donasi operasional Yayasan Bantu Bersama. Jika nominal donasi tidak sesuai dengan angka unik yang tertera maka kami catat sebagai akad infak.
      </div>
    </div>
  </section>
  <!-- payment method section end -->

  <!-- cart popup start -->
  <div class="cart-popup">
    <a href="{{ route('donate.payment_info', $slug) }}" class="btn btn-warning donate-btn">Lanjut Pembayaran</a>
  </div>
  <!-- cart popup end -->
@endsection


@section('content_modal')

@endsection


@section('js_plugins')

@endsection


@section('js_inline')
    
@endsection
