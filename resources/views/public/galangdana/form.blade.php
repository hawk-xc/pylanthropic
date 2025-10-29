@extends('layouts.public', [
    'second_title' => 'Form Pengajuan Program - LAZISNU DIY x BANTUBERSAMA',
    'meta_desc'    => 'Bantusesama adalah platform penggalang dana untuk membantu bersama secara online',
    'image'        => 'pengajuan_bantubersama_lazisnu_diy.jpg',
])


@section('css_plugins')
  
@endsection


@section('css_inline')
    
@endsection


@section('content')
  <!-- header start -->
  <header class="section-t-space pt-0">
    <div class="header-panel bg-me header-title">
      <a href="{{ route('index') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="#fff">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
      </a>
      <h2 class="fs-16">Form Pengajuan Program</h2>
    </div>
  </header>
  <!-- header end -->

  <form method="post" action="{{ route('submit') }}">
    @csrf
    <!-- payment method section start -->
    <section class="payment method section-lg-b-space pt-0">
      <div class="custom-container">
        <h5 class="text-center fw-medium fs-16 mt-4">Kategori
          @if($_GET['c']=='kesehatan')
            Kesehatan
          @elseif($_GET['c']=='rumahibadah')
            Rumah Ibadah
          @elseif($_GET['c']=='pendidikan')
            Pendidikan
          @elseif($_GET['c']=='kemanusiaan')
            Kemanusiaan
          @elseif($_GET['c']=='bencanaalam')
            Bencana Alam
          @else
            Lain-Lain
          @endif
        </h5>
        <hr>
        <div class="form-input mt-4">
          <label class="fw-medium fs-15 mb-1 pb-1">Data PIC</label>
          
          <input type="text" name="fullname" class="form-control fs-14 form-payment" placeholder="Nama Lengkap" required />
          
          <input type="text" name="telp" class="form-control fs-14 form-payment mt-2" placeholder="Nomor WA : 08....." required />
        </div>
        <div class="form-input mt-4">
          <label class="fw-medium fs-15 mb-1 pb-1">Detail Program Bantuan</label>
          
          <input type="text" name="title" class="form-control fs-14 form-payment" placeholder="Judul Pengajuan Program" required />
          
          <div class="d-flex align-items-center mt-2">
            <span class="ph-rp fs-14">Rp</span>
            <input class="form-control form-nominal-other fs-14 bg-white" id="rupiah" style="border-style:solid;" name="amount" placeholder="0" type="text" value=""/ required >
          </div>
          
          <textarea name="address" rows="2" class="form-control fs-14 lh-20 form-payment mt-2" placeholder="Alamat penerima manfaat" required></textarea>

          <textarea name="doa" rows="5" class="form-control fs-14 lh-20 form-payment mt-2" placeholder="Detail keterangan pengajuan program" required></textarea>
        </div>
        <div class="form-input mt-4">
          <label class="fw-medium fs-15 mb-1 pb-1">Lampiran File <span class="fs-12">(bisa berupa .zip berisi beberapa foto)</span></label>
          <input type="file" name="file" id="files" class="form-control fs-14 form-payment" style="display:none;" required />
          <label for="files" class="form-control fs-14 form-payment">Pilih file max 5MB (1-5 foto)</label>
        </div>
        <button type="submit" class="btn w-100 donate-btn mb-3 mt-3">Ajukan Program</button>
      </div>
    </section>
    <!-- payment method section end -->
  </form>
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
  </script>
@endsection
