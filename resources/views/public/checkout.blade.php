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
          echo "fbq('init', '1278491429470122');";  // akun 4
          $pixel_id = "1278491429470122";
        }
      } else {
        echo "fbq('init', '1278491429470122');";  // akun 4
        $pixel_id = "1278491429470122";
      }
      ?>
      fbq('track', 'InitiateCheckout');
      window.loadedPixel = []
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{$pixel_id}}&ev=InitiateCheckout&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->
  
    @if(true)
    <!-- Tiktok Analytic Code -->
    <script>
        !function (w, d, t) {
            w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(
            var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script")
            ;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};

            ttq.load('D08VCQ3C77U1QSDFHDA0');
            ttq.page();
            ttq.track('InitiateCheckout');
        }(window, document, 'ttq');
    </script>
    <!-- End Tiktok Analytic Code -->
    @endif

    @if(false)
    <!-- Tiktok Analytic Code -->
    <script>
        !function (w, d, t) {
            w.TiktokAnalyticsObject=t;var ttq2=w[t]=w[t]||[];ttq2.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq2.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq2.methods.length;i++)ttq2.setAndDefer(ttq2,ttq2.methods[i]);ttq2.instance=function(t){for(
            var e=ttq2._i[t]||[],n=0;n<ttq2.methods.length;n++)ttq2.setAndDefer(e,ttq2.methods[n]);return e},ttq2.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq2._i=ttq2._i||{},ttq2._i[e]=[],ttq2._i[e]._u=r,ttq2._t=ttq2._t||{},ttq2._t[e]=+new Date,ttq2._o=ttq2._o||{},ttq2._o[e]=n||{};n=document.createElement("script")
            ;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};

            ttq2.load('CURFVTRC77UC0U8BC0R0');
            ttq2.page();
            ttq2.track('InitiateCheckout');
        }(window, document, 'ttq');
    </script>
    <!-- End Tiktok Analytic Code -->
    @endif

    @if(false)
    <!-- Tiktok Analytic Code -->
    <script>
        !function (w, d, t) {
            w.TiktokAnalyticsObject=t;var ttq3=w[t]=w[t]||[];ttq3.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq3.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq3.methods.length;i++)ttq3.setAndDefer(ttq3,ttq3.methods[i]);ttq3.instance=function(t){for(
            var e=ttq3._i[t]||[],n=0;n<ttq3.methods.length;n++)ttq3.setAndDefer(e,ttq3.methods[n]);return e},ttq3.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq3._i=ttq3._i||{},ttq3._i[e]=[],ttq3._i[e]._u=r,ttq3._t=ttq3._t||{},ttq3._t[e]=+new Date,ttq3._o=ttq3._o||{},ttq3._o[e]=n||{};n=document.createElement("script")
            ;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};

            ttq3.load('CS4C9ORC77U61CV20FI0');
            ttq3.page();
            ttq3.track('InitiateCheckout');
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
    <input type="hidden" name="fingerprint" id="fingerprint">
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
          <input type="text" name="fullname" class="form-control fs-14 form-payment" value="{{ request('name') ?? '' }}" placeholder="Nama Lengkap" required />
        </div>
        <div class="form-input mt-2">
          <input type="text" name="telp" class="form-control fs-14 form-payment" value="{{ request('telp') ?? '' }}" placeholder="Nomor Telpon : 08....." required />
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
          Nominal di atas sudah termasuk 5% donasi operasional Bantubersama. Jika nominal donasi tidak sesuai dengan angka unik yang tertera maka kami catat sebagai akad infak umum.
        </div>
      </div>
    </section>
    <!-- payment method section end -->

    <!-- cart popup start -->
    <div class="cart-popup">
      <button type="submit" id="donateBtn" class="btn donate-btn">Lanjut Pembayaran</button>
    </div>
    <!-- cart popup end -->
  </form>
@endsection


@section('content_modal')

@endsection


@section('js_plugins')

@endsection


@section('js_inline')
<script>
(function () {
    // elemen yang penting
    const donateBtn = document.getElementById("donateBtn");
    const fingerprintInput = document.getElementById("fingerprint");

    if (!donateBtn || !fingerprintInput) {
        return;
    }

    // disable tombol sampai fingerprint siap (atau gagal)
    donateBtn.disabled = true;

    // callback untuk menjalankan fingerprint setelah library tersedia
    function runFingerprint() {
        try {
            // FingerprintJS API sama untuk v3/v4: FingerprintJS.load().then(fp => fp.get())
            FingerprintJS.load()
                .then(fp => fp.get())
                .then(result => {
                    fingerprintInput.value = result.visitorId || "";
                    console.log("Fingerprint berhasil diambil:", result.visitorId);
                    donateBtn.disabled = false;
                })
                .catch(err => {
                    console.error("Fingerprint gagal diambil (runtime):", err);
                    donateBtn.disabled = false;
                });
        } catch (err) {
            console.error("FingerprintJS tersedia tapi error saat eksekusi:", err);
            donateBtn.disabled = false;
        }
    }

    // fungsi untuk memuat library secara dinamis (fallback ke jsDelivr v3)
    function loadFingerprintLibAndRun() {
        // jika sudah ada, langsung jalankan
        if (typeof FingerprintJS !== "undefined") {
            return runFingerprint();
        }

        // coba load IIFE v4 global (resmi)
        const urls = [
            "https://openfpcdn.io/fingerprintjs/v4/iife.min.js",                         // official v4 iife
            "https://cdn.jsdelivr.net/npm/@fingerprintjs/fingerprintjs@3/dist/fp.min.js" // fallback v3
        ];

        // sequential load: coba url[0], kalau gagal coba url[1]
        const head = document.getElementsByTagName('head')[0];

        function tryLoad(index) {
            if (index >= urls.length) {
                donateBtn.disabled = false;
                return;
            }

            const s = document.createElement('script');
            s.src = urls[index];
            s.async = true;
            s.onload = function () {
                // delay microtask untuk memastikan global didefinisikan
                setTimeout(() => {
                    if (typeof FingerprintJS !== "undefined") {
                        runFingerprint();
                    } else {
                        // kalau tetap undefined, coba next CDN
                        tryLoad(index + 1);
                    }
                }, 0);
            };
            s.onerror = function () {
                tryLoad(index + 1);
            };
            head.appendChild(s);
        }

        tryLoad(0);
    }

    // Jalankan hanya setelah DOM siap
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", loadFingerprintLibAndRun);
    } else {
        loadFingerprintLibAndRun();
    }

    // juga tangani submit: jika fingerprint belum terisi, cegah submit
    const form = donateBtn.closest('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (!fingerprintInput.value) {
                e.preventDefault();
                alert('Sedang memverifikasi perangkat Anda. Tunggu sebentar lalu coba lagi.');
            }
        });
    }
})();
</script>
@endsection

