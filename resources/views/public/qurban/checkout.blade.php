@extends('layouts.public', [
    'second_title' => 'Checkout',
    'meta_desc'    => 'Qurban Bantubersama adalah platform pembelian qurban secara online',
    'image'        => 'promo-qurban-bantubersama.png',
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
          $pixel_id = "2596008717326722";
        }
      } else {
        echo "fbq('init', '586907076711934');";   // akun 1, 2, 3
        echo "fbq('init', '1278491429470122');";  // akun 4
        $pixel_id = "2596008717326722";
      }
      ?>
      fbq('track', 'InitiateCheckout');
      window.loadedPixel = []
    </script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{$pixel_id}}&ev=InitiateCheckout&noscript=1" /></noscript>
  <!-- End Meta Pixel Code -->
@endsection


@section('css_inline')
  <style type="text/css">
    .alert-checkout {
        background-color: #e5f5ff;
        color: #6b7280;
    }
    .text-title {
        font-size: 16px;
        font-weight: 600;
    }
    .text-desc {
        font-size: 11px;
        font-weight: 400;
        color: #6A6A6A;
    }
    .img-selected {
        width: 62px;
        height: auto;
        border-radius: 8px;
        margin-right: 12px;
        justify-content: center;
        align-items: center;
    }
    .text-selected {
        width: calc(100% - 62px - 12px - 92px);
        justify-content: space-between;
        align-items: center;
        position: relative;
    }
    .btn-qty {
        
    }
    .btn-min {
        cursor:pointer;
        padding: 4px 8px;
    }
    .btn-plus {
        cursor:pointer;
        padding: 4px 8px;
    }
    .btn-qty img {
        width: 16px;
        height: 16px;
    }
    #val-qty {
        min-width: 25px;
        padding: 4px 2px;
        text-align: center;
    }
    .inp-invalid {
        border-color: #dc3545 !important;
    }
  </style>
@endsection

@section('content')
  <!-- header start -->
  <header class="section-t-space pt-0">
    <div class="header-panel bg-me header-title">
      <a href="{{ route('payment', $id) }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="#fff">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
      </a>
      <h2 class="fs-16">Data Pequrban</h2>
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

  <form method="post" action="{{ route('qurban.submit', $id).$uri_param }}" onsubmit="return validateMyForm();">
    @csrf
    <!-- payment method section start -->
    <section class="payment method section-lg-b-space pt-0">
      <div class="custom-container">
        <h5 class="fw-medium fs-15 mt-4">Pilihan Hewan Qurban</h5>
        <div class="d-flex align-items-center mt-2 pt-1">
            <?php
            if($id==1){
                $title = 'Kambing';
                $desc  = 'Berat 19-24kg';
                $img   = 'kambing-qurban-bantubersama.png';
                $price = 1499000;
            } elseif($id==2) {
                $title = 'Domba';
                $desc  = 'Berat 24-29kg';
                $img   = 'domba-qurban-bantubersama.png';
                $price = 2300000;
            } elseif($id==3) {
                $title = 'Sapi 1/7';
                $desc  = 'Berat 29-37kg';
                $img   = 'sapi17-qurban-bantubersama.png';
                $price = 1950000;
            } else {
                $title = 'Sapi Utuh';
                $desc  = 'Berat 200-300kg';
                $img   = 'sapi-qurban-bantubersama.png';
                $price = 12999999;
            }
            ?>
            
            <img src="{{ asset('public/images/qurban/'.$img) }}" class="img-selected" />
            <div class="text-selected">
                <div class="text-title">{{ $title }}</div>
                <div class="text-desc">{{ $desc }}</div>
            </div>
            <div class="d-flex flex-wrap rounded border text-end">
              <div class="btn-qty btn-min">
                  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAJUlEQVR4nGNgGAWjYBSMAtKA9sL3/6mBGQbMglEwCkbBKGDACgA+5mIpVB6edwAAAABJRU5ErkJggg==">
              </div>
              <div id="val-qty" class="">1</div>
              <div class="btn-qty btn-plus">
                  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAPElEQVR4nGNgGFFAe+H7/yA8agFOoD0aRISA9sgOIm2oZmphBrpbMPTjgBigPWoBIaA9GkQDHkQMgxEAAAjuzzlKaQhjAAAAAElFTkSuQmCC">
              </div>
            </div>
        </div>
        <div class="d-flex align-items-center mt-2 pt-1">
          <span class="ph-rp fs-18 fw-bold">Total</span>
          <input class="form-nominal-other fs-18 fw-bold" name="nominal" placeholder="0" type="text" value="Rp {{ number_format($price) }}" readonly required />
          <input type="hidden" name="type" value="{{ $payment->key }}" required>
          <input type="hidden" name="qty" value="1" required>
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
                <a href="{{ route('payment', $id) }}" class="fw-semibold color-me">Ganti</a>
              </div>
            </div>
          </li>
        </ul>
        <hr>
        <div class="form-input mt-4">
          <input type="text" name="fullname" class="form-control fs-14 form-payment" placeholder="Nama Lengkap. Min 3 huruf" required />
        </div>
        <div class="form-input mt-2">
          <input type="text" name="telp" class="form-control fs-14 form-payment" id="telp" placeholder="Nomor Telpon : 08....." required />
        </div>
        <div class="form-input mt-2">
          <label class="fw-medium fs-15 mb-1 pb-1">Nama Orang yg Berqurban</label>
          <textarea name="doa" rows="3" class="form-control fs-14 lh-20 form-payment" placeholder="Tulis nama Orang yang berqurban, jika lebih dari 1 pisahkan dengan koma"></textarea>
        </div>
        <div class="alert alert-checkout disclaimer-detail mt-4">
            Jika terjadi kendala, kami mohon izin mengganti dengan jenis hewan kurban yang tersedia di lapangan.  
        </div>
      </div>
    </section>
    <!-- payment method section end -->

    <!-- cart popup start -->
    <div class="cart-popup">
      <button type="submit" class="btn donate-btn" id="btn_submit">Lanjut Pembayaran</button>
    </div>
    <!-- cart popup end -->
  </form>
@endsection


@section('content_modal')

@endsection


@section('js_plugins')
  <script src="{{ asset('public/js/jquery-3.6.4.min.js') }}"></script>
@endsection


@section('js_inline')
    <script type="text/javascript">
        $(".btn-min").on("click", function() {
            val_qty = parseInt($('#val-qty').text());
            if(val_qty<2) {
                val_qty = 1;
            } else {
                val_qty = val_qty-1;
            }
            $('#val-qty').html(val_qty);
            $('input[name="qty"]').val(val_qty);
            price = {{ $price }} * val_qty;
            price = price.toString();
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(price)) { 
                price = price.replace(rgx, '$1' + ',' + '$2'); 
            }
            $('input[name="nominal"]').val('Rp '+price);
        });
        $(".btn-plus").on("click", function() {
            val_qty = parseInt($('#val-qty').text());
            if(val_qty>89) {
                val_qty = 99;
            } else {
                val_qty = val_qty+1;
            }
            $('#val-qty').html(val_qty);
            $('input[name="qty"]').val(val_qty);
            price = {{ $price }} * val_qty;
            price = price.toString();
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(price)) { 
                price = price.replace(rgx, '$1' + ',' + '$2'); 
            }
            $('input[name="nominal"]').val('Rp '+price);
        });
        
        var telp = document.getElementById("telp");
        telp.addEventListener("keyup", function(e) {
          telp.value = formatTelp(this.value, "");
        });
        /* Fungsi formatRupiah */
        function formatTelp(angka, prefix) {
          var number_string = angka.replace(/[^,\d]/g, "").toString(),
          split = number_string.split(","),
    
          rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
          return split;
        }
        
        // $(".btn_submit").on("click", function(e) {
        function validateMyForm() {
            val_fullname = $('input[name="fullname"]').val();
            val_telp     = $('input[name="telp"]').val();
            
            if(val_fullname.length<3) {
                $('input[name="fullname"]').addClass('inp-invalid');
            } else {
                $('input[name="fullname"]').removeClass('inp-invalid');
            }
            
            if(val_telp.length<9) {
                $('input[name="telp"]').addClass('inp-invalid');
            } else {
                $('input[name="telp"]').removeClass('inp-invalid');
            }
            
            if(val_fullname.length<3 || val_telp.length<9) {
                return false;
            } else {
                return true;
            }
        }
        // });
    </script>
@endsection