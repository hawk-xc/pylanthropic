@extends('layouts.public', [
    'second_title'    => 'Nominal Donasi'
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
          //echo "fbq('init', '586907076711934');";   // akun 1, 2, 3
          echo "fbq('init', '1278491429470122');";  // akun 4
          $pixel_id = "1278491429470122";
        }
      } else {
        //echo "fbq('init', '586907076711934');";   // akun 1, 2, 3
        echo "fbq('init', '1278491429470122');";  // akun 4
        $pixel_id = "1278491429470122";
      }
      ?>
      fbq('track', 'Lead');
      window.loadedPixel = []
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{$pixel_id}}&ev=Lead&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->
  

    <!-- Tiktok Analytic Code -->
    <script>
        !function (w, d, t) {
            w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(
            var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script")
            ;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};

            ttq.load('CR2P7SJC77U8E82N1D2G');
            ttq.page();
            ttq.track('AddToCart');
        }(window, document, 'ttq');
    </script>
    <!-- End Tiktok Analytic Code -->
    <!-- Tiktok Analytic Code -->
    <script>
        !function (w, d, t) {
            w.TiktokAnalyticsObject=t;var ttq2=w[t]=w[t]||[];ttq2.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq2.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq2.methods.length;i++)ttq2.setAndDefer(ttq2,ttq2.methods[i]);ttq2.instance=function(t){for(
            var e=ttq2._i[t]||[],n=0;n<ttq2.methods.length;n++)ttq2.setAndDefer(e,ttq2.methods[n]);return e},ttq2.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq2._i=ttq2._i||{},ttq2._i[e]=[],ttq2._i[e]._u=r,ttq2._t=ttq2._t||{},ttq2._t[e]=+new Date,ttq2._o=ttq2._o||{},ttq2._o[e]=n||{};n=document.createElement("script")
            ;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};

            ttq2.load('CTDP1L3C77U0ROHK9HPG');
            ttq2.page();
            ttq2.track('AddToCart');
        }(window, document, 'ttq');
    </script>
    <!-- End Tiktok Analytic Code -->
    <!-- Tiktok Analytic Code -->
    <!-- <script>
        !function (w, d, t) {
            w.TiktokAnalyticsObject=t;var ttq3=w[t]=w[t]||[];ttq3.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq3.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq3.methods.length;i++)ttq3.setAndDefer(ttq3,ttq3.methods[i]);ttq3.instance=function(t){for(
            var e=ttq3._i[t]||[],n=0;n<ttq3.methods.length;n++)ttq3.setAndDefer(e,ttq3.methods[n]);return e},ttq3.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq3._i=ttq3._i||{},ttq3._i[e]=[],ttq3._i[e]._u=r,ttq3._t=ttq3._t||{},ttq3._t[e]=+new Date,ttq3._o=ttq3._o||{},ttq3._o[e]=n||{};n=document.createElement("script")
            ;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};

            ttq3.load('CS4C9ORC77U61CV20FI0');
            ttq3.page();
            ttq3.track('AddToCart');
        }(window, document, 'ttq');
    </script> -->
    <!-- End Tiktok Analytic Code -->

@endsection


@section('css_inline')
    
@endsection

@section('content')
  <!-- header start -->
  <header class="section-t-space pt-0">
    <!-- <div class="custom-container"> -->
      <div class="header-panel bg-me header-title">
        <!-- <div class="header-title"> -->
          <a href="/{{ $program->slug }}">
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
      <h3 class="fw-medium fs-15 mb-2 pb-1">Pilih Nonimal Donasi Terbaik Anda</h3>
      <!-- <form method="post" action="" id="frm-payment"> -->
        <!-- @csrf -->
        <input type="hidden" name="nominal" value="0" id="nominal">
        <ul class="payment-list section-lg-b-space">
          <!--<li class="cart-add-box payment-card-box gap-0 mt-2">-->
          <!--  <a href="#" class="container-fluid pe-0 sub_amount" data-nominal="30000">-->
          <!--    <div class="payment-detail">-->
          <!--      <div class="add-content">-->
          <!--        <div>-->
          <!--          <h5 class="fw-bold fs-16">Rp 30.000</h5>-->
          <!--        </div>-->
          <!--        <div class="float-end">-->
          <!--          <i class="ri-arrow-right-s-line fs-26"></i>-->
          <!--        </div>-->
          <!--      </div>-->
          <!--    </div>-->
          <!--  </a>-->
          <!--</li>-->
          <li class="cart-add-box payment-card-box gap-0 mt-2">
            <a href="#" class="container-fluid pe-0 sub_amount" data-nominal="50000">
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
          <li class="cart-add-box payment-card-box gap-0 mt-2">
            <a href="#" class="container-fluid pe-0 sub_amount" data-nominal="100000">
              <div class="payment-detail">
                <div class="add-content">
                  <div>
                    <h5 class="fw-bold fs-16">
                      Rp 100.000 
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" style="vertical-align: bottom!important;">
                          <path d="M8.687 4.121c1.03.228 1.895.754 2.633 1.5.226.228.474.435.68.623.25-.232.53-.487.806-.747 1.088-1.025 2.364-1.571 3.877-1.483 1.045.06 1.97.442 2.71 1.18 1.438 1.435 1.918 3.15 1.411 5.122-.26 1.008-.742 1.914-1.322 2.77-.906 1.336-2.018 2.49-3.207 3.574-1.146 1.045-2.367 1.998-3.616 2.916a.993.993 0 0 1-1.168.004l-2.11-1.514c-1.375-1.084-2.659-2.266-3.8-3.596-.747-.87-1.41-1.799-1.901-2.84-.36-.76-.608-1.551-.667-2.394-.08-1.151.22-2.202.856-3.162C4.971 4.415 6.74 3.692 8.687 4.121Z"fill="#3BA8DD"></path>
                      </svg>
                    </h5>
                  </div>
                  <div class="float-end">
                    <i class="ri-arrow-right-s-line fs-26"></i>
                  </div>
                </div>
              </div>
            </a>
          </li>
          <li class="cart-add-box payment-card-box gap-0 mt-2">
            <a href="#" class="container-fluid pe-0 sub_amount" data-nominal="200000">
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
          <li class="cart-add-box payment-card-box gap-0 mt-2">
            <a href="#" class="container-fluid pe-0 sub_amount" data-nominal="500000">
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
          <li class="cart-add-box payment-card-box gap-0 mt-2">
            <a href="#" class="container-fluid pe-0 sub_amount" data-nominal="1000000">
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
          <li class="cart-add-box payment-card-box gap-0 mt-2">
            <a href="#" class="container-fluid pe-0 sub_amount" data-nominal="2500000">
              <div class="payment-detail">
                <div class="add-content">
                  <div>
                    <h5 class="fw-bold fs-16">Rp 2.500.000</h5>
                  </div>
                  <div class="float-end">
                    <i class="ri-arrow-right-s-line fs-26"></i>
                  </div>
                </div>
              </div>
            </a>
          </li>
          <li class="cart-add-box payment-card-box gap-0 mt-2">
            <div class="container-fluid pe-0 payment-detail nominal-other">
              <div class="add-content">
                <div>
                  <h5 class="fw-bold fs-14">Nominal Lainnya</h5>
                  <div class="d-flex align-items-center my-2">
                    <span class="ph-rp fs-18 fw-bold">Rp</span>
                    <input class="form-nominal-other fs-18 fw-bold" id="rupiah" name="amount" placeholder="0" type="text" value=""/>
                  </div>
                  <div class="invalid-feedback">
                    Input nominal minimal 20.000
                  </div>
                  <h5 class=" fs-12 mb-2">Min. donasi sebesar Rp 20.000</h5>
                </div>
              </div>
            </div>
          </li>
        </ul>
      <!-- </form> -->
    </div>
  </section>
  <!-- payment method section end -->

  <!-- cart popup start -->
  <div class="cart-popup">
    <a href="#" class="btn donate-btn" data-nominal="0">Lanjut Pembayaran</a>
  </div>
  <!-- cart popup end -->

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
    var rupiah = document.getElementById("rupiah");
    rupiah.addEventListener("keyup", function(e) {
      // tambahkan 'Rp.' pada saat form di ketik
      // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
      rupiah.value = formatRupiah(this.value, "");
    });

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix) {
      var number_string = angka.replace(/[^,\d]/g, "").toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

      // tambahkan titik jika yang di input sudah menjadi angka ribuan
      if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
      }

      rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
      return prefix == undefined ? rupiah : rupiah ? "" + rupiah : "";
    }

    // validation nominal
    $(".donate-btn").on("click", function(){
      let nominal  = $("#rupiah").val();
      let nominal2 = nominal.replaceAll('.','');
      if(nominal2 <= 1000) {
        $("#rupiah").focus();
        $(".invalid-feedback").show();
      } else {
        gopayment(nominal2);
      }
    });

    $(".sub_amount").on("click", function() {
      nominal = $(this).attr("data-nominal");
      gopayment(nominal);
    });

    function gopayment(nominal) {
      if(nominal >= 20000){
        // nominal = nominal;
      } else if(nominal == 0) {
        nominal2 = $("#rupiah").val();
        nominal2 = parseInt(nominal2.replaceAll('.', ''));
        if(nominal2 < 20000) {
          nominal = 0;
        } else {
          nominal = nominal2;
        }
      } else {
        nominal = 0;
      }

      if(nominal==0) {
        alert('Tidak boleh dbawah Rp. 20.000');
      } else {
        // $("#nominal").val(nominal);
        // $("#frm-payment").trigger('submit');
        window.location.href = "{{ url('/').'/'.$program->slug.'/payment' }}/"+nominal+$('#uri').val();
      }
    }
  </script>
@endsection
