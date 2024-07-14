@extends('layouts.public', [
    'second_title'    => 'Metode Pembayaran'
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
      fbq('track', 'AddPaymentInfo');
      window.loadedPixel = []
    </script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{$pixel_id}}&ev=AddPaymentInfo&noscript=1" /></noscript>
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
        ttq.track('AddPaymentInfo');
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
      <a href="{{ route('donate.amount', $program->slug) }}">
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
        <input type="hidden" name="payment_type" value="" id="payment_type">
        <h3 class="fw-semibold section-t-space mb-2 pb-1 fs-15">Transfer Bank (Verifikasi manual 1x24jam)</h3>
        <ul class="payment-list">
          @foreach($payment_transfer as $pt)
          <li class="cart-add-box payment-card-box gap-0 mt-2">
            <a href="#" class="w-100 payment-type" data-payment="{{ $pt->key }}">
              <div class="payment-detail">
                <div class="add-img">
                  <img class="img" src="{{ asset('public/images/payment').'/'.$pt->img }}" alt="{{ $pt->name }}" />
                </div>
                <div class="add-content">
                  <div>
                    <h5 class="fw-medium fs-15">{{ $pt->name }}</h5>
                  </div>
                </div>
              </div>
            </a>
          </li>
          @endforeach
        </ul>

        <h3 class="fw-semibold mb-2 pb-1 fs-15 mt-4">Pembayaran Instan (Otomatis & Cepat)</h3>
        <ul class="payment-list">
          @foreach($payment_instant as $pi)
          <li class="cart-add-box payment-card-box gap-0 mt-2">
            <a href="#" class="w-100 payment-type" data-payment="{{ $pi->key }}">
              <div class="payment-detail">
                <div class="add-img">
                  <img class="img" src="{{ asset('public/images/payment').'/'.$pi->img }}" alt="{{ $pi->name }}" />
                </div>
                <div class="add-content">
                  <div>
                    <h5 class="fw-medium fs-15">{{ $pi->name }}</h5>
                  </div>
                </div>
              </div>
            </a>
          </li>
          @endforeach
        </ul>

        <h3 class="fw-semibold mb-2 pb-1 fs-15 mt-4">Virtual Account (Verifikasi otomatis)</h3>
        <ul class="payment-list">
          @foreach($payment_va as $va)
          <li class="cart-add-box payment-card-box gap-0 mt-2">
            <a href="#" class="w-100 payment-type" data-payment="{{ $va->key }}">
              <div class="payment-detail">
                <div class="add-img">
                  <img class="img" src="{{ asset('public/images/payment').'/'.$va->img }}" alt="{{ $va->name }}" />
                </div>
                <div class="add-content">
                  <div>
                    <h5 class="fw-medium fs-15">{{ $va->name }}</h5>
                  </div>
                </div>
              </div>
            </a>
          </li>
          @endforeach
        </ul>
      <!-- </form> -->
    </div>
  </section>
  <!-- payment method section end -->

  <?php
    $uri = explode('?', url()->full());
    if(!empty($uri[1])){
      $uri_param = '?'.$uri[1];
    } else {
      $uri_param = '';
    }
  ?>
  <input type="hidden" id="uri" value="{{ $uri_param }}">
@endsection


@section('content_modal')

@endsection


@section('js_plugins')
  <!-- JQuery -->
  <script src="{{ asset('public/js/jquery-3.6.4.min.js') }}"></script>
@endsection


@section('js_inline')
  <script type="text/javascript">
    $(".payment-type").on("click", function() {
      payment = $(this).attr("data-payment");
      // $("#payment_type").val(payment);
      // $("#frm-checkout").trigger('submit');

      window.location.href = "{{ url('/').'/'.$program->slug.'/checkout/'.$nominal }}/"+payment+$('#uri').val();
    });
  </script>
@endsection
