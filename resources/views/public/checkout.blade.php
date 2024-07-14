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
          echo "fbq('init', '586907076711934');";   // akun 1, 2, 3
          echo "fbq('init', '1278491429470122');";  // akun 4
          $pixel_id = "1278491429470122";
        }
      } else {
        echo "fbq('init', '586907076711934');";   // akun 1, 2, 3
        echo "fbq('init', '1278491429470122');";  // akun 4
        $pixel_id = "1278491429470122";
      }
      ?>
      fbq('track', 'InitiateCheckout');
      window.loadedPixel = []
    </script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{$pixel_id}}&ev=InitiateCheckout&noscript=1" /></noscript>
  <!-- End Meta Pixel Code -->
  

  @if(false)
    <!-- Tiktok Analytic Code -->
    <script>
      !function (w, d, t) {
        w.TiktokAnalyticsObject=t;
        var ttq=w[t]=w[t]||[];
        ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};
        for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);
        ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};
        var o=document.createElement("script");
        o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;
        var a=document.getElementsByTagName("script")[0];
        a.parentNode.insertBefore(o,a)};
      
        ttq.load('CJ86D3BC77UC183801KG');
        ttq.track('InitiateCheckout');
        ttq.page();
      }(window, document, 'ttq');
    </script>
    <!-- End Tiktok Analytic Code -->
  @endif

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

  <?php
    $uri = explode('?', url()->full());
    if(!empty($uri[1])){
      $uri_param = '?'.$uri[1];
    } else {
      $uri_param = '';
    }
  ?>

  <form method="post" action="{{ route('donate.payment_info', $program->slug).$uri_param }}">
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
