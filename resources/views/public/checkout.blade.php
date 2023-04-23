@extends('layouts.public', [
    'second_title'    => 'Checkout'
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
      fbq('init', '2596008717326722');
    fbq('init', '586907076711934');
      fbq('track', 'InitiateCheckout');
      window.loadedPixel = []
    </script>
  <!-- End Meta Pixel Code -->
@endsection


@section('css_inline')
    
@endsection

@section('content')
  <!-- header start -->
  <header class="section-t-space pt-0">
    <div class="header-panel bg-me header-title">
      <a href="{{ route('donate.payment', ['slug'=>$program->slug, 'nominal'=>$nominal]) }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="#fff">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
      </a>
      <h2 class="fs-16">Verifikasi Pembayaran</h2>
    </div>
  </header>
  <!-- header end -->

  <form method="post" action="{{ route('donate.payment_info', $program->slug) }}">
    @csrf
    <!-- payment method section start -->
    <section class="payment method section-lg-b-space pt-0">
      <div class="custom-container">
        <h5 class="fw-medium fs-15 mt-4">Isi Nominal Donasi</h5>
        <div class="d-flex align-items-center mt-2 pt-1">
          <span class="ph-rp fs-18 fw-bold">Rp</span>
          <input class="form-nominal-other fs-18 fw-bold" name="nominal" placeholder="0" type="text" value="{{ number_format($nominal) }}" readonly required />
          <input type="hidden" name="type" value="{{ $payment->key }}" required>
          <input type="hidden" name="slug" value="{{ $program->slug }}" required>
        </div>
        <ul class="payment-list mb-4">
          <li class="cart-add-box payment-card-box gap-0 mt-2">
            <div class="w-100">
              <div class="payment-detail">
                <div class="add-img">
                  <img class="img" src="{{ asset('public/images/payment/'.$payment->img) }}" alt="{{ $payment->name }}" />
                </div>
                <div class="add-content">
                  @if($payment->type=='transfer')
                    <h5 class="fw-medium fs-15">{{ $payment->target_number }}</h5>
                  @else
                    <h5 class="fw-medium fs-15">{{ $payment->name }}</h5>
                  @endif
                </div>
                <a href="{{ route('donate.payment', ['slug'=>$program->slug, 'nominal'=>$nominal]) }}" class="fw-semibold color-me">Ganti</a>
              </div>
            </div>
          </li>
        </ul>
        <hr>
        <div class="form-input mt-4">
          <input type="text" name="fullname" class="form-control fs-14 form-payment" placeholder="Nama Lengkap" required />
        </div>
        <div class="form-input mt-2">
          <input type="text" name="telp" class="form-control fs-14 form-payment" placeholder="Nomor Telpon : 08....." required />
        </div>
        <label class="alert alert-avail-contact disclaimer-detail mt-2">
          <input type="checkbox" name="want_to_contact" class="me-2" checked>
          Saya bersedia dihubungi melalui Whatsapp 
        </label>
        <div class="hide-name-form">
          <div class="fw-medium fs-15">
            Sembunyikan nama saya
            <!-- <br><span class="text-secondary">(<em>Orang Baik</em>)</span> -->
          </div>
          <div class="switch-btn">
            <input type="checkbox" class="text-bottom" name="anonim" />
          </div>
        </div>
        <div class="form-input">
          <label class="fw-medium fs-15 mb-1 pb-1">Tulis pesan dan do'a <span class="text-muted">(opsional)</span></label>
          <textarea name="doa" rows="5" class="form-control fs-14 lh-20 form-payment" placeholder="Tulis pesan dan do'a  untuk diri sendiri atau penggalang dana agar dilihat dan diamini oleh orang baik lainnya"></textarea>
        </div>
        <div class="alert alert-secondary disclaimer-detail mt-4">
          Nominal di atas sudah termasuk 5% donasi operasional Yayasan Bantu Bersama Sejahtera. Jika nominal donasi tidak sesuai dengan angka unik yang tertera maka kami catat sebagai akad infak.
        </div>
      </div>
    </section>
    <!-- payment method section end -->

    <!-- cart popup start -->
    <div class="cart-popup">
      <button type="submit" class="btn donate-btn">Lanjut Pembayaran</button>
    </div>
    <!-- cart popup end -->
  </form>
@endsection


@section('content_modal')

@endsection


@section('js_plugins')

@endsection


@section('js_inline')

@endsection
