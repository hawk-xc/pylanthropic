@extends('layouts.public', [
    'second_title' => ucwords($program->title),
    'meta_desc'    => ucwords($program->short_desc),
    'image'        => $program->image,
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
      fbq('init', '1278491429470122');
      fbq('init', '1352154889054298');
      fbq('track', 'Donate');
      window.loadedPixel = []
    </script>
  <!-- End Meta Pixel Code -->

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
    fbq('init', '');
    fbq('track', 'Donate');
  </script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=3496745097262004&ev=Donate&noscript=1" /></noscript>
  <!-- End Meta Pixel Code -->
  
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
      ttq.track('Donate');
      ttq.page();
    }(window, document, 'ttq');
  </script>
  <!-- End Tiktok Analytic Code -->
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
      @if( $transaction->status=='success' )
        <h5 class="fw-medium fs-15 mt-4" id="desc_title">Terimakasih atas donasi Anda</h5>
      @else
        <h5 class="fw-medium fs-15 mt-4" id="desc_title">Selangkah lagi selesaikan donasi Anda</h5>
      @endif
      <ul class="payment-list">
        <li class="cart-add-box payment-card-box gap-0 mt-2">
          <div class="w-100">
            @if($payment->type=='transfer')
              <div class="payment-detail pb-0">
                <div class="add-img">
                  <img class="img" src="{{ asset('public/images/payment/'.$payment->img) }}" alt="mastercard" />
                </div>
                <div class="add-content">
                  <h5 class="fw-medium fs-16">{{ $payment->target_number }}</h5>
                </div>
                <a href="#" class="fw-semibold color-me copy" data-copy="{{ $payment->target_number }}">Salin</a>
              </div>
              <div class="fs-14 fw-medium text-secondary pb-1">Transfer a/n {{ $payment->target_desc }}</div>
            @elseif( ($payment->type=='virtual_account' || $payment->type=='instant') && $payment->key!='qris' )
              <div class="payment-detail">
                <div class="add-img">
                  <img class="img" src="{{ asset('public/images/payment/'.$payment->img) }}" alt="mastercard" />
                </div>
                <div class="add-content">
                  <h5 class="fw-medium fs-16">{{ $payment->name }}</h5>
                </div>
                <!-- <a href="#" class="fw-semibold color-me">Salin</a> -->
              </div>
              <!-- <div class="fs-14 fw-medium text-secondary pb-1">{{ $payment->name }}</div> -->
            @elseif($payment->key=='qris')
              <div class="py-2 text-center">
                <img src="{{ asset('public/images/payment/qris_bri.png') }}">
              </div>
              <div class="text-center pb-1">
                <a class="fw-semibold color-me" href="#" id="download_qris">Download QRIS</a>
              </div>
            @else
              <!-- debit / credit -->
            @endif
          </div>
        </li>
        @if($payment->type=='transfer' || $payment->key=='qris')
          <div class="fs-15 fw-medium my-3">Donasi sebesar</div>
          <li class="cart-add-box payment-card-box gap-0 mt-1">
            <div class="w-100">
              <div class="payment-detail">
                  <div class="add-content">
                    <h5 class="fw-semibold fs-18">{{ trim($nominal_show) }}<span class="highlight-digit">{{ $nominal_show2 }}</span></h5>
                  </div>
                  <a href="#" class="fw-semibold color-me copy" data-copy="{{ $nominal }}">Salin</a>
              </div>
            </div>
          </li>
        @else
          <li class="cart-add-box payment-card-box gap-0 mt-2">
            <div class="w-100">
              <div class="payment-detail">
                <div class="d-flex justify-content-between align-items-center w-100">
                  <div class="fs-16">Donasi sebesar</div>
                  <h5 class="fw-semibold fs-18">{{ trim($nominal_show) }}<span class="highlight-digit">{{ $nominal_show2 }}</span></h5>
                </div>
              </div>
            </div>
          </li>
          <div class="fs-16 mt-4 text-center">Atau bisa menggunakan QRIS untuk semua Jenis Bank dan E-Wallet</div>
          <li class="cart-add-box payment-card-box gap-0 mt-2">
            <div class="w-100">
                <div class="py-2 text-center">
                <img src="{{ asset('public/images/payment/qris_bri.png') }}">
              </div>
              <div class="text-center pb-1">
                <a class="fw-semibold color-me" href="#" id="download_qris">Download QRIS</a>
              </div>
            </div>
          </li>
        @endif
      </ul>
      @if($payment->type!='transfer2')
        @if( $transaction->status!='success' )
          <div class="alert alert-warning disclaimer-detail mt-2 mb-1">
            <strong class="">Penting!</strong> Pastikan nominal transfer hingga 3 digit terakhir
          </div>
        @endif
        <em class="fs-12 text-secondary">* Akan didonasikan hingga 3 digit terakhir</em>
      @endif

      <hr class="mb-4">
      <div class="fs-14 lh-16 d-flex justify-content-between">
        <div>Pembayaran sebelum</div>
        <div><strong class="fs-15">{{ $paid_before==0 ? 'Selesai' : $paid_before }}</strong></div>
      </div>
      <div class="fs-14 lh-16 d-flex justify-content-between mt-2 pt-1">
        <div>Nomor Invoice</div>
        <div class="fs-15">{{ $transaction->invoice_number }}</div>
      </div>
      <div class="fs-14 lh-16 d-flex justify-content-between mt-2 pt-1">
        <div>Status Pembayaran</div>
        <div class="fs-15 fw-semibold" id="status_paid">
          @if($transaction->status=='draft')
            Belum diterima
          @elseif($transaction->status=='success')
            Sudah Dibayar
          @else
            Dibatalkan
          @endif
        </div>
      </div>
        @if( $transaction->status!='success' )
            <button class="btn share-btn mt-4 w-100" id="checkStatus">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                    <polyline points="9 11 12 14 22 4"></polyline>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
                Cek status pembayaran
            </button>
        @else
            <br>
        @endif
      <button class="btn donate-btn mt-2 w-100 share-btn" id="shareprogram">
        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" class="mr-2 w-4">
          <path d="M17.5 10A3.5 3.5 0 1 0 14 6.5c0 .43-.203.86-.595 1.037L10.034 9.07c-.427.194-.924.052-1.283-.25a3.5 3.5 0 1 0-.2 5.517c.38-.275.885-.381 1.297-.156l3.585 1.955c.412.225.597.707.572 1.176a3.5 3.5 0 1 0 1.445-2.649c-.38.275-.886.381-1.298.156l-3.585-1.955c-.412-.225-.597-.707-.572-1.176.003-.062.005-.125.005-.188 0-.43.203-.86.595-1.037l3.371-1.533c.428-.194.924-.052 1.283.25.609.512 1.394.82 2.251.82Z" fill="#fff"></path>
        </svg>
        Bagikan Program Ini
      </button>
      <div class="alert alert-secondary disclaimer-detail mt-4">
        Jika ada kendala pada transaksi ini bisa <a href="https://wa.me/6281352521934" target="_blank">lapor pada kami</a>
      </div>
      <a href="{{ url('/') }}" class="btn  donate-btn mt-2 w-100">
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
  <!-- pwa install app popup start -->
  <div class="offcanvas offcanvas-bottom addtohome-popup theme-offcanvas rounded-top-8" tabindex="-1" id="offcanvas">
    <div class="offcanvas-body text-center rounded-top-8">
      <h2 class="fw-bold mb-4 d-flex fs-16 justify-content-between">
        <span>Bagikan Program</span>
        <button type="button" class="btn-share-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </h2>
      <div class="d-flex flex-wrap">
        <button aria-label="facebook" class="btn-icon-share">
            <svg viewBox="0 0 64 64" width="36" height="36">
                <rect width="64" height="64" rx="10" ry="10" fill="#3b5998"></rect>
                <path d="M34.1,47V33.3h4.6l0.7-5.3h-5.3v-3.4c0-1.5,0.4-2.6,2.6-2.6l2.8,0v-4.8c-0.5-0.1-2.2-0.2-4.1-0.2 c-4.1,0-6.9,2.5-6.9,7V28H24v5.3h4.6V47H34.1z" fill="white"></path>
            </svg>
            <span class="mt-2 fs-14 lh-24">Facebook</span>
        </button>
        <button aria-label="twitter" class="btn-icon-share">
            <svg viewBox="0 0 64 64" width="36" height="36">
                <rect width="64" height="64" rx="10" ry="10" fill="#00aced"></rect>
                <path d="M48,22.1c-1.2,0.5-2.4,0.9-3.8,1c1.4-0.8,2.4-2.1,2.9-3.6c-1.3,0.8-2.7,1.3-4.2,1.6 C41.7,19.8,40,19,38.2,19c-3.6,0-6.6,2.9-6.6,6.6c0,0.5,0.1,1,0.2,1.5c-5.5-0.3-10.3-2.9-13.5-6.9c-0.6,1-0.9,2.1-0.9,3.3 c0,2.3,1.2,4.3,2.9,5.5c-1.1,0-2.1-0.3-3-0.8c0,0,0,0.1,0,0.1c0,3.2,2.3,5.8,5.3,6.4c-0.6,0.1-1.1,0.2-1.7,0.2c-0.4,0-0.8,0-1.2-0.1 c0.8,2.6,3.3,4.5,6.1,4.6c-2.2,1.8-5.1,2.8-8.2,2.8c-0.5,0-1.1,0-1.6-0.1c2.9,1.9,6.4,2.9,10.1,2.9c12.1,0,18.7-10,18.7-18.7 c0-0.3,0-0.6,0-0.8C46,24.5,47.1,23.4,48,22.1z"
                    fill="white"
                ></path>
            </svg>
            <span class="mt-2 fs-14 lh-24">Twitter</span>
        </button>
        <button aria-label="whatsapp" class="btn-icon-share">
            <svg viewBox="0 0 64 64" width="36" height="36">
                <rect width="64" height="64" rx="10" ry="10" fill="#25D366"></rect>
                <path d="m42.32286,33.93287c-0.5178,-0.2589 -3.04726,-1.49644 -3.52105,-1.66732c-0.4712,-0.17346 -0.81554,-0.2589 -1.15987,0.2589c-0.34175,0.51004 -1.33075,1.66474 -1.63108,2.00648c-0.30032,0.33658 -0.60064,0.36247 -1.11327,0.12945c-0.5178,-0.2589 -2.17994,-0.80259 -4.14759,-2.56312c-1.53269,-1.37217 -2.56312,-3.05503 -2.86603,-3.57283c-0.30033,-0.5178 -0.03366,-0.80259 0.22524,-1.06149c0.23301,-0.23301 0.5178,-0.59547 0.7767,-0.90616c0.25372,-0.31068 0.33657,-0.5178 0.51262,-0.85437c0.17088,-0.36246 0.08544,-0.64725 -0.04402,-0.90615c-0.12945,-0.2589 -1.15987,-2.79613 -1.58964,-3.80584c-0.41424,-1.00971 -0.84142,-0.88027 -1.15987,-0.88027c-0.29773,-0.02588 -0.64208,-0.02588 -0.98382,-0.02588c-0.34693,0 -0.90616,0.12945 -1.37736,0.62136c-0.4712,0.5178 -1.80194,1.76053 -1.80194,4.27186c0,2.51134 1.84596,4.945 2.10227,5.30747c0.2589,0.33657 3.63497,5.51458 8.80262,7.74113c1.23237,0.5178 2.1903,0.82848 2.94111,1.08738c1.23237,0.38836 2.35599,0.33657 3.24402,0.20712c0.99159,-0.15534 3.04985,-1.24272 3.47963,-2.45956c0.44013,-1.21683 0.44013,-2.22654 0.31068,-2.45955c-0.12945,-0.23301 -0.46601,-0.36247 -0.98382,-0.59548m-9.40068,12.84407l-0.02589,0c-3.05503,0 -6.08417,-0.82849 -8.72495,-2.38189l-0.62136,-0.37023l-6.47252,1.68286l1.73463,-6.29129l-0.41424,-0.64725c-1.70875,-2.71846 -2.6149,-5.85116 -2.6149,-9.07706c0,-9.39809 7.68934,-17.06155 17.15993,-17.06155c4.58253,0 8.88029,1.78642 12.11655,5.02268c3.23625,3.21036 5.02267,7.50812 5.02267,12.06476c-0.0078,9.3981 -7.69712,17.06155 -17.14699,17.06155m14.58906,-31.58846c-3.93529,-3.80584 -9.1133,-5.95471 -14.62789,-5.95471c-11.36055,0 -20.60848,9.2065 -20.61625,20.52564c0,3.61684 0.94757,7.14565 2.75211,10.26282l-2.92557,10.63564l10.93337,-2.85309c3.0136,1.63108 6.4052,2.4958 9.85634,2.49839l0.01037,0c11.36574,0 20.61884,-9.2091 20.62403,-20.53082c0,-5.48093 -2.14111,-10.64081 -6.03239,-14.51915"
                    fill="white"
                ></path>
            </svg>
            <span class="mt-2 fs-14 lh-24">Whatsapp</span>
        </button>
        <button aria-label="telegram" class="btn-icon-share">
            <svg viewBox="0 0 64 64" width="36" height="36">
                <rect width="64" height="64" rx="10" ry="10" fill="#37aee2"></rect>
                <path d="m45.90873,15.44335c-0.6901,-0.0281 -1.37668,0.14048 -1.96142,0.41265c-0.84989,0.32661 -8.63939,3.33986 -16.5237,6.39174c-3.9685,1.53296 -7.93349,3.06593 -10.98537,4.24067c-3.05012,1.1765 -5.34694,2.05098 -5.4681,2.09312c-0.80775,0.28096 -1.89996,0.63566 -2.82712,1.72788c-0.23354,0.27218 -0.46884,0.62161 -0.58825,1.10275c-0.11941,0.48114 -0.06673,1.09222 0.16682,1.5716c0.46533,0.96052 1.25376,1.35737 2.18443,1.71383c3.09051,0.99037 6.28638,1.93508 8.93263,2.8236c0.97632,3.44171 1.91401,6.89571 2.84116,10.34268c0.30554,0.69185 0.97105,0.94823 1.65764,0.95525l-0.00351,0.03512c0,0 0.53908,0.05268 1.06412,-0.07375c0.52679,-0.12292 1.18879,-0.42846 1.79109,-0.99212c0.662,-0.62161 2.45836,-2.38812 3.47683,-3.38552l7.6736,5.66477l0.06146,0.03512c0,0 0.84989,0.59703 2.09312,0.68132c0.62161,0.04214 1.4399,-0.07726 2.14229,-0.59176c0.70766,-0.51626 1.1765,-1.34683 1.396,-2.29506c0.65673,-2.86224 5.00979,-23.57745 5.75257,-27.00686l-0.02107,0.08077c0.51977,-1.93157 0.32837,-3.70159 -0.87096,-4.74991c-0.60054,-0.52152 -1.2924,-0.7498 -1.98425,-0.77965l0,0.00176zm-0.2072,3.29069c0.04741,0.0439 0.0439,0.0439 0.00351,0.04741c-0.01229,-0.00351 0.14048,0.2072 -0.15804,1.32576l-0.01229,0.04214l-0.00878,0.03863c-0.75858,3.50668 -5.15554,24.40802 -5.74203,26.96472c-0.08077,0.34417 -0.11414,0.31959 -0.09482,0.29852c-0.1756,-0.02634 -0.50045,-0.16506 -0.52679,-0.1756l-13.13468,-9.70175c4.4988,-4.33199 9.09945,-8.25307 13.744,-12.43229c0.8218,-0.41265 0.68483,-1.68573 -0.29852,-1.70681c-1.04305,0.24584 -1.92279,0.99564 -2.8798,1.47502c-5.49971,3.2626 -11.11882,6.13186 -16.55882,9.49279c-2.792,-0.97105 -5.57873,-1.77704 -8.15298,-2.57601c2.2336,-0.89555 4.00889,-1.55579 5.75608,-2.23009c3.05188,-1.1765 7.01687,-2.7042 10.98537,-4.24067c7.94051,-3.06944 15.92667,-6.16346 16.62028,-6.43037l0.05619,-0.02283l0.05268,-0.02283c0.19316,-0.0878 0.30378,-0.09658 0.35471,-0.10009c0,0 -0.01756,-0.05795 -0.00351,-0.04566l-0.00176,0zm-20.91715,22.0638l2.16687,1.60145c-0.93418,0.91311 -1.81743,1.77353 -2.45485,2.38812l0.28798,-3.98957"
                    fill="white"
                ></path>
            </svg>
            <span class="mt-2 fs-14 lh-24">Telegram</span>
        </button>
        <button aria-label="line" class="btn-icon-share">
            <svg viewBox="0 0 64 64" width="36" height="36">
                <rect width="64" height="64" rx="10" ry="10" fill="#00b800"></rect>
                <path d="M52.62 30.138c0 3.693-1.432 7.019-4.42 10.296h.001c-4.326 4.979-14 11.044-16.201 11.972-2.2.927-1.876-.591-1.786-1.112l.294-1.765c.069-.527.142-1.343-.066-1.865-.232-.574-1.146-.872-1.817-1.016-9.909-1.31-17.245-8.238-17.245-16.51 0-9.226 9.251-16.733 20.62-16.733 11.37 0 20.62 7.507 20.62 16.733zM27.81 25.68h-1.446a.402.402 0 0 0-.402.401v8.985c0 .221.18.4.402.4h1.446a.401.401 0 0 0 .402-.4v-8.985a.402.402 0 0 0-.402-.401zm9.956 0H36.32a.402.402 0 0 0-.402.401v5.338L31.8 25.858a.39.39 0 0 0-.031-.04l-.002-.003-.024-.025-.008-.007a.313.313 0 0 0-.032-.026.255.255 0 0 1-.021-.014l-.012-.007-.021-.012-.013-.006-.023-.01-.013-.005-.024-.008-.014-.003-.023-.005-.017-.002-.021-.003-.021-.002h-1.46a.402.402 0 0 0-.402.401v8.985c0 .221.18.4.402.4h1.446a.401.401 0 0 0 .402-.4v-5.337l4.123 5.568c.028.04.063.072.101.099l.004.003a.236.236 0 0 0 .025.015l.012.006.019.01a.154.154 0 0 1 .019.008l.012.004.028.01.005.001a.442.442 0 0 0 .104.013h1.446a.4.4 0 0 0 .401-.4v-8.985a.402.402 0 0 0-.401-.401zm-13.442 7.537h-3.93v-7.136a.401.401 0 0 0-.401-.401h-1.447a.4.4 0 0 0-.401.401v8.984a.392.392 0 0 0 .123.29c.072.068.17.111.278.111h5.778a.4.4 0 0 0 .401-.401v-1.447a.401.401 0 0 0-.401-.401zm21.429-5.287c.222 0 .401-.18.401-.402v-1.446a.401.401 0 0 0-.401-.402h-5.778a.398.398 0 0 0-.279.113l-.005.004-.006.008a.397.397 0 0 0-.111.276v8.984c0 .108.043.206.112.278l.005.006a.401.401 0 0 0 .284.117h5.778a.4.4 0 0 0 .401-.401v-1.447a.401.401 0 0 0-.401-.401h-3.93v-1.519h3.93c.222 0 .401-.18.401-.402V29.85a.401.401 0 0 0-.401-.402h-3.93V27.93h3.93z"
                    fill="white"
                ></path>
            </svg>
            <span class="mt-2 fs-14 lh-24">Line</span>
        </button>
        <button aria-label="linkedin" class="btn-icon-share">
            <svg viewBox="0 0 64 64" width="36" height="36">
                <rect width="64" height="64" rx="10" ry="10" fill="#007fb1"></rect>
                <path d="M20.4,44h5.4V26.6h-5.4V44z M23.1,18c-1.7,0-3.1,1.4-3.1,3.1c0,1.7,1.4,3.1,3.1,3.1 c1.7,0,3.1-1.4,3.1-3.1C26.2,19.4,24.8,18,23.1,18z M39.5,26.2c-2.6,0-4.4,1.4-5.1,2.8h-0.1v-2.4h-5.2V44h5.4v-8.6 c0-2.3,0.4-4.5,3.2-4.5c2.8,0,2.8,2.6,2.8,4.6V44H46v-9.5C46,29.8,45,26.2,39.5,26.2z"
                    fill="white"
                ></path>
            </svg>
            <span class="mt-2 fs-14 lh-24">LinkedIn</span>
        </button>
        <button aria-label="email" class="btn-icon-share">
            <svg viewBox="0 0 64 64" width="36" height="36">
                <rect width="64" height="64" rx="10" ry="10" fill="#7f7f7f"></rect>
                <path d="M17,22v20h30V22H17z M41.1,25L32,32.1L22.9,25H41.1z M20,39V26.6l12,9.3l12-9.3V39H20z" fill="white"></path>
            </svg>
            <span class="mt-2 fs-14 lh-24">Email</span>
        </button>
        <button title="" type="button" class="btn-icon-share" data-clipboard-text="{{ url('/').'/'.$program->slug }}">
            <div class="icon-copy-url" style="width: 36px; height: 36px; border-radius: 5px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
            </div>
            <span class="mt-2 fs-14 lh-24">Salin URL</span>
        </button>
      </div>

    </div>
  </div>
  <!-- pwa install app popup start -->
@endsection


@section('js_plugins')
  <!-- JQuery -->
  <script src="{{ asset('public/js/jquery-3.6.4.min.js') }}"></script>
  
  <!-- bootstrap js -->
  <script src="{{ asset('public') }}/js/bootstrap.bundle.min.js"></script>
@endsection


@section('js_inline')
  @if( ($payment->type=='virtual_account' || $payment->type=='instant') && $transaction->status=='draft' && $payment->key!='qris' )
    <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ env('MID_CLIENT_KEY') }}"></script>

    <script type="text/javascript">
      $(document).ready(function() {
        snapPay();
      })

      setTimeout(function() {
          snapPay()
      }, 500)

      function snapPay() {
        snap.pay('{{ $token_midtrans }}', {
          onSuccess: function(result) {
              window.history.back();
          },
          onPending: function(result) {
              window.history.back();
          },
          onError: function(result) {
              window.history.back();
          }
        });
      }
    </script>
  @endif

  <script type="text/javascript">
    $("#download_qris").on("click", function() {
      var link = document.createElement("a");
      link.setAttribute('download', '');
      link.href = "{{ asset('public/images/payment/qris_bri.png') }}";
      document.body.appendChild(link);
      link.click();
      link.remove();
    });

    $(".copy").on("click", function() {
      let data_copy = $(this).attr('data-copy');
      navigator.clipboard.writeText(data_copy);
    });

    $("#checkStatus").on("click", function() {
      $.ajax({
        type: "POST",
        url: "{{ route('donate.status.check', $transaction->invoice_number) }}",
        data: {
          "_token": "{{ csrf_token() }}"
        },
        success: function(data)
        {
          console.log(data);
          if(data!='no') {
            $('#status_paid').html(data);
            if(data=='Sudah Dibayar') {
              $('#desc_title').html('Terimakasih atas donasi Anda');
            }
          }
        }
      });
    });


    $("#shareprogram").on("click", function() {
      var myOffcanvas = document.getElementById("offcanvas");
      var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
      bsOffcanvas.show();
    });

    // action link share
    $(".btn-icon-share").on("click", function() {
      let name = $(this).attr('aria-label');
      let uri  = "{{ url('/').'/'.$program->slug }}";
      let txt2 = 'Jangan biarkan mereka merasa sendirian! Yuk bantu bersama yang membutuhkan bantuan, dengan klik';
      let utm  = 'utm_source=';
      let utm2 = 'utm_source%3Dsocialsharing_donor_web_null%26utm_medium%3Dshare_campaign_whatsapp%26utm_campaign%3Dshare_detail_campaign';
      if(name=='facebook'){
        var url = encodeURI('https://www.facebook.com/sharer/sharer.php?u='+uri+'?'+utm+'&quote%3D'+txt2);
        window.open(url, 'name', 'width=600,height=400');
      } else if(name=='twitter') {
        let url = encodeURI('https://twitter.com/intent/tweet?url='+uri+'?'+utm+'&text='+txt2);
        window.open(url, 'name', 'width=600,height=400');
      } else if(name=='whatsapp') {
        let url = encodeURI('https://api.whatsapp.com/send?phone=&text='+txt2+' '+uri+'?'+utm);
        window.open(url, 'name', 'width=600,height=400');
      } else if(name=='telegram') {
        let url = encodeURI('https://telegram.me/share/url?url='+uri+'&text={{ $program->title }}');
        window.open(url, 'name', 'width=600,height=400');
      } else if(name=='line') {
        let url = encodeURI('https://social-plugins.line.me/lineit/share?url='+uri+'?'+utm+'&text='+txt2);
        window.open(url, 'name', 'width=600,height=400');
      } else if(name=='linkedin') {
        let url = encodeURI('https://www.linkedin.com/shareArticle?url='+uri+'&mini=true&title={{ $program->title }}&summary={{ $program->short_desc }}&source={{ url("/") }}');
        window.open(url, 'name', 'width=600,height=400');
      } else if(name=='email') {
        let url = encodeURI('mailto:Bantubersama.com<contact@bantubersama.com>?subject={{ $program->title }}&body='+txt2+' '+uri);
        window.open(url);
      } else {
        let link_share = $(this).attr('data-clipboard-text');
        navigator.clipboard.writeText(link_share);
        $('#copyUrlToast').toast({
          animation: false,
          delay: 3000
        });
        $('#copyUrlToast').toast('show');
      }
      
    });
  </script>
@endsection
