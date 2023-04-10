@extends('layouts.public', [
    'second_title'    => 'Detail Program'
])


@section('css_plugins')
    
@endsection


@section('css_inline')
    
@endsection

@section('content')
  <header>
    <div class="header-panel-lg" style="background-image: url({{ asset('public/images/program') }}/g.jpeg);">
      <div class="custom-container">
        <div class="panel">
          <a href="{{ url('/') }}"><i class="ri-arrow-left-s-line"></i></a>
          <!-- <a href="search.html"><i class="ri-search-2-line"></i></a> -->
        </div>
      </div>
    </div>
  </header>
  <!-- header end -->

  <!-- Detail section start  -->
  <section class="pt-3">
    <div class="custom-container">
      <h4 class="title-detail-program">Jumat Berkah: 5000 Mukena Layak untuk Lansia Dhuafa di Desa Terpencil</h4>
      <h6 class="short-desc mt-2 pt-1">Yuk bantu dan support nenek di Desa Kademangan (Kab. Jepara) untuk beribadah dengan layak</h6>
      <div class="mt-3 donate-collect"> Rp 2.459.560.000</div>
      <div class="row mt-1 pb-1 fs-15">
        <div class="col-8">
          dari target <span class="fw-semibold">Rp 4.000.000.000</span>
        </div>
        <div class="col-4 text-end">
          <span class="fw-semibold">34</span> hari
        </div>
      </div>
      <div class="progress mt-2" role="progressbar" aria-label="Basic example" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
        <div class="progress-bar" style="width: 65%"></div>
      </div>
      <div class="mt-3 row d-flex align-content-center text-center">
        <a href="#donasi" class="col-4 btn-donate-detail1">
          <div>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" role="img">
                <path d="M8.687 4.121c1.03.228 1.895.754 2.633 1.5.226.228.474.435.68.623.25-.232.53-.487.806-.747 1.088-1.025 2.364-1.571 3.877-1.483 1.045.06 1.97.442 2.71 1.18 1.438 1.435 1.918 3.15 1.411 5.122-.26 1.008-.742 1.914-1.322 2.77-.906 1.336-2.018 2.49-3.207 3.574-1.146 1.045-2.367 1.998-3.616 2.916a.993.993 0 0 1-1.168.004l-2.11-1.514c-1.375-1.084-2.659-2.266-3.8-3.596-.747-.87-1.41-1.799-1.901-2.84-.36-.76-.608-1.551-.667-2.394-.08-1.151.22-2.202.856-3.162C4.971 4.415 6.74 3.692 8.687 4.121Z"fill="#F7BC00"></path>
            </svg>
            <span class="inline-block fs-14 fw-semibold">22.238</span>
          </div>
          <div class="fs-13">Donasi</div>
        </a>
        <a href="#kabar-terbaru" class="col-4 btn-donate-detail2">
          <div>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" role="img">
                <path d="M17.354 21H18a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3h-.646A4.482 4.482 0 0 1 18.5 6v12a4.484 4.484 0 0 1-1.146 3Z" fill="#F7BC00"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.25 3a.75.75 0 0 1 .75-.75h3A3.75 3.75 0 0 1 17.75 6v12A3.75 3.75 0 0 1 14 21.75H6A3.75 3.75 0 0 1 2.25 18v-7a.75.75 0 0 1 1.5 0v7A2.25 2.25 0 0 0 6 20.25h8A2.25 2.25 0 0 0 16.25 18V6A2.25 2.25 0 0 0 14 3.75h-3a.75.75 0 0 1-.75-.75Z" fill="#6A6A6A"></path>
                <path d="M9.5 15.5h2.75a.75.75 0 0 0 0-1.5H9.5a.75.75 0 0 0 0 1.5ZM14.25 18.5H9.5a.75.75 0 0 1 0-1.5h4.75a.75.75 0 0 1 0 1.5ZM5.75 15.5h.5a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5ZM7 17.75a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5a.75.75 0 0 1 .75.75Z" fill="#6A6A6A" ></path>
                <path d="M6 9H3l6-6v3a3 3 0 0 1-3 3Z" fill="#F7BC00"></path>
            </svg>
            <span class="fw-semibold fs-14">23</span>
          </div>
          <div class="fs-13">Kabar Terbaru</div>
        </a>
        <a href="#penggunaan-dana" class="col-4 btn-donate-detail3">
          <div>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" class="mt-0.5">
                <path d="M21 13.65a1 1 0 1 0-2 0v2a2 2 0 0 1-2 2h-5.5l.6-.45a1 1 0 1 0-1.2-1.6l-3 2.25a1 1 0 0 0 0 1.6l3 2.25a1 1 0 0 0 1.2-1.6l-.6-.45H17a4 4 0 0 0 4-4v-2Z" fill="#F7BC00"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M5 3.75A2.25 2.25 0 0 0 2.75 6v6A2.25 2.25 0 0 0 5 14.25h2.5a.75.75 0 0 1 0 1.5H5A3.75 3.75 0 0 1 1.25 12V6A3.75 3.75 0 0 1 5 2.25h14A3.75 3.75 0 0 1 22.75 6v6a.75.75 0 0 1-1.5 0V6A2.25 2.25 0 0 0 19 3.75H5ZM13.75 15a.75.75 0 0 1 .75-.75H17a.75.75 0 0 1 0 1.5h-2.5a.75.75 0 0 1-.75-.75Z" fill="#6A6A6A"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" fill="#6A6A6A"></path>
            </svg>
            <span class="fw-semibold fs-14">7 kali</span>
          </div>
          <div class="fs-13">Penggunaan Dana</div>
        </a>
      </div>
    </div>
  </section>
  <!-- filter section end  -->

  <!-- Empty section start -->
  <section class="empty-section section-t-space section-b-space pb-0">
    <div class="custom-container space-empty pb-2">
    </div>
  </section>
  <!-- Empty section end -->

  <!-- Fundraiser section start -->
  <section class="">
    <div class="custom-container">
      <div class="fw-bold fs-15 mb-3 pb-1">Info Penggalang Dana</div>
      <a class="d-flex mt-2" href="#">
        <img class="img rounded-circle border img-fundraiser-detail me-2" src="{{ asset('public') }}/images/fundraiser/a.png">
        <div class="ms-2">
          <h6 class="fs-14 lh-24 fw-semibold">Yayasan Bantu Bersama Indonesia</h6>
          <div class="verified-fundraiser d-inline-block mt-1">
            <div class="d-flex align-items-center">
              <svg width="12" height="12" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                  <circle cx="8" cy="8" r="8" fill="currentColor"></circle>
                  <path d="m4 7.5 3 3L12.5 5" stroke="#fff" stroke-width="1.5"></path>
              </svg>
              <div class="fs-11 ms-1 ps-1">Akun Terverifikasi</div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </section>
  <!-- Fundraiser section end -->

  <!-- Empty section start -->
  <section class="empty-section section-t-space section-b-space pb-0">
    <div class="custom-container space-empty pb-2">
    </div>
  </section>
  <!-- Empty section end -->

  <!-- Program Detail section start -->
  <section class="py-20">
    <div class="custom-container">
      <div class="fw-bold fs-16 mb-3 pb-1">Tentang Program</div>
      <div class="content-preview">
        <div class="content-mini expanded">
          <h5 class="fs-14 fw-semibold lh-24 mb-2">Bantu tingkatkan semangat belajar anak-anak Maluku! Ayo Bangunkan mereka Ruang Kelas yang baru!</h5>

          <figure>
              <img
                  src="https://amalsholeh-s3.imgix.net/content/u8LKJy58yf97c1omOV1w0y0tQQnAX0FoAS3JDoAE.png?ar=16:9&amp;w=720&amp;auto=format,compress"
                  data-image="u8LKJy58yf97c1omOV1w0y0tQQnAX0FoAS3JDoAE.png"
                  width="405"
                  height="228"
                  style="width: 405px; max-width: 405px; height: 228px;"
              />
          </figure>
          <p>
              Jauh dari hiruk pikuk kota, suara ceria anak-anak sekolah belajar terdengar dari dalam sebuah bangunan se-petak kecil di Desa Kanafa,&nbsp;Teluk Waru,&nbsp;Seram Bagian Timur Maluku. Madrasah Tsanawiyah Mathlaul Anwar, itulah nama
              Sekolah yang berada di Desa Kanafa Kecamatan Teluk Waru&nbsp;ini. Siswa-Siswi disana berasal dari Desa-Desa yang ada disekitarnya yang berjarak bisa sampai 10 KM dan ditempuh Siswa-Siswi dengan menumpang pada kendaraan yang melewati
              jalan yang sama.
          </p>

          <figur>
              <img
                  src="https://amalsholeh-s3.imgix.net/content/c8YQEaCWFKwnsNhpbU5BhS8Ir5WyJkvcdBDR9hwK.png?ar=16:9&amp;w=720&amp;auto=format,compress"
                  data-image="c8YQEaCWFKwnsNhpbU5BhS8Ir5WyJkvcdBDR9hwK.png"
                  width="420"
                  height="236"
                  style="width: 420px; max-width: 420px; height: 236px;"
              />
          </figure>
          <p>
              <br />
              Sekolah ini menyelenggarakan pendidikan tanpa biaya atau gratis. Bahkan para Siswa-Siswi nya pun menggunakan baju seragam hasil dari sumbangan warga dari kota. Mereka belajar dalam ruangan yang berukuran hanya sekitar 4x4 meter,
              itupun dipinjamkan oleh Pemerintah Desa. Walaupun hanya sepetak, sekolah ini menyimpan banyak sekali mimpi-mimpi dan cita-cita mulia dari Siswa-Siswinya.
          </p>

          <figure>
              <img src="https://amalsholeh-s3.imgix.net/content/WYCd1DrICgJ1ASdHcyEMIOCtihBGQ5WP61akgBOH.png?ar=16:9&amp;w=720&amp;auto=format,compress" data-image="WYCd1DrICgJ1ASdHcyEMIOCtihBGQ5WP61akgBOH.png" width="444" height="250" style="width: 444px; max-width: 444px; height: 250px;" />
          </figure>
          <p>
              <br />
              Beberapa bulan lagi sekolah ini akan menerima pendaftaran Siswa baru, dan angkatan yang sudah ada akan naik kelas, tetapi di sekolah ini hanya tersedia satu ruangan belajar. Mari bersama kita bantu membangunkan Ruangan untuk Belajar
              yang lebih layak untuk para Siswa-Siswi di Desa Kanafa Kecamatan&nbsp;Teluk Waru&nbsp;Timur Maluku ini.
          </p>

          <figure>
              <img
                  src="https://amalsholeh-s3.imgix.net/content/3iY8pbRWxtn65ygDbgXo3GugWWKKRlxlSqedC5Oq.png?ar=16:9&amp;w=720&amp;auto=format,compress"
                  data-image="3iY8pbRWxtn65ygDbgXo3GugWWKKRlxlSqedC5Oq.png"
                  width="428"
                  height="241"
                  style="width: 428px; max-width: 428px; height: 241px;"
              />
          </figure>
          <p>
              <em>
                  Cahaya itu terlihat dari sana.<br />
                  Salam dari Timur Indonesia.
              </em>
          </p>
          <p>Semoga niat baik ini menjadi wasilah keberkahan untuk Kita semua. 🤲</p>
        </div>
      </div>
      <div class="text-center pt-3 pb-2">
        <button class="btn-selengkapnya-about">Baca selengkapnya</button>
      </div>
      
      <div class="alert alert-secondary disclaimer-detail mt-4">
        <strong class="">Disclaimer :</strong> Informasi, opini dan foto yang ada di halaman program ini adalah milik dan tanggung jawab penggalang dana dan tidak mewakili Bantubersama.com. Jika ada masalah/kecurigaan silahkan <a href="#">lapor kepada kami disini</a>
      </div>

    </div>
  </section>
  <!-- Program Detail section end -->

  <!-- Empty section start -->
  <section class="empty-section section-t-space section-b-space pb-0 pt-1">
    <div class="custom-container space-empty pb-2">
    </div>
  </section>
  <!-- Empty section end -->

  <!-- Kabar Terbaru section start -->
  <section class="py-20">
    <div class="custom-container">
      <div class="title mb-3 pb-1">
        <div class="fw-bold fs-16" id="kabar-terbaru">Kabar Terbaru</div>
        <a href="" class="d-flex fs-15 align-items-center">
          <span class="fs-14 color-me">Lihat Semua</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-1"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </a>
      </div>
      <div class="content-preview">
        <div class="">
          <h5 class="fs-14 fw-semibold lh-20">RAB Untuk Program Penyaluran Fidyah RAB Untuk Program Penyaluran Fidyah RAB Untuk Program Penyaluran Fidyah</h5>
          <div class="fs-14 mt-1">18 hari yang lalu</div>
          <div class="fs-14 mt-3">
            <p>
              Assalamu'alaikum Wa Rohmatullahi Wa Barokaatuh
              <br>Alhamdulillah fidyah bapak ibu sekalian melalui Lazismu telah di salurkan dan dirasakan manfaatnya oleh orang banyak.
              <br>Mulai dari para pekerja informal seperti, pemulung, pedagang kecil, penyapu jalan dll.
            </p>
            <figure>
              <img src="https://amalsholeh-s3.imgix.net/content/WYCd1DrICgJ1ASdHcyEMIOCtihBGQ5WP61akgBOH.png?ar=16:9&amp;w=720&amp;auto=format,compress" data-image="WYCd1DrICgJ1ASdHcyEMIOCtihBGQ5WP61akgBOH.png" width="444" height="250" style="width: 444px; max-width: 444px; height: 250px;" />
            </figure>
          </div>
        </div>
      </div>
      <div class="text-center pt-3 pb-2">
        <button class="btn-selengkapnya-about">Baca selengkapnya</button>
      </div>
    </div>
  </section>
  <!-- Kabar Terbaru section end -->

  <!-- Empty section start -->
  <section class="empty-section section-t-space section-b-space pb-0 pt-3">
    <div class="custom-container space-empty pb-2">
    </div>
  </section>
  <!-- Empty section end -->

  <!-- Donatur section start -->
  <section class="py-20">
    <div class="custom-container">
      <div class="title mb-3 pb-1">
        <div class="fw-bold fs-16" id="donasi">Donatur</div>
        <a href="" class="d-flex fs-15 align-items-center">
          <span class="fs-14 color-me">Lihat Semua</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-1"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </a>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="d-flex mb-3">
            <div class="mr-3 rounded-full bg-coal relative" style="width: 50px; height: 50px;">
              <img alt="Orang Baik" src="{{ asset('public') }}/images/icons/user-anonim.png" width="50" height="50" decoding="async" data-nimg="1" class="w-full h-full rounded-full object-cover object-center" loading="lazy" style="color: transparent;" />
            </div>
            <div class="content-donatur ms-3">
              <div class="">
                <div class="fs-14 fw-semibold">Orang Baik</div>
                <div class="fs-13">2 menit yang lalu</div>
              </div>
              <span class="fs-14 fw-semibold">Rp 50.000</span>
            </div>
          </div>
          <hr class="mt-0 mb-3 line-donatur">
        </div>
        <div class="col-12">
          <div class="d-flex mb-3">
            <div class="mr-3 rounded-full bg-coal relative" style="width: 50px; height: 50px;">
              <img alt="Orang Baik" src="{{ asset('public') }}/images/icons/user-anonim.png" width="50" height="50" decoding="async" data-nimg="1" class="w-full h-full rounded-full object-cover object-center" loading="lazy" style="color: transparent;" />
            </div>
            <div class="content-donatur ms-3">
              <div class="">
                <div class="fs-14 fw-semibold">Orang Baik</div>
                <div class="fs-13">8 menit yang lalu</div>
              </div>
              <span class="fs-14 fw-semibold">Rp 20.000</span>
            </div>
          </div>
          <hr class="mt-0 mb-3 line-donatur">
        </div>
        <div class="col-12">
          <div class="d-flex mb-3">
            <div class="mr-3 rounded-full bg-coal relative" style="width: 50px; height: 50px;">
              <img alt="Orang Baik" src="{{ asset('public') }}/images/icons/user-anonim.png" width="50" height="50" decoding="async" data-nimg="1" class="w-full h-full rounded-full object-cover object-center" loading="lazy" style="color: transparent;" />
            </div>
            <div class="content-donatur ms-3">
              <div class="">
                <div class="fs-14 fw-semibold">Orang Baik</div>
                <div class="fs-13">12 menit yang lalu</div>
              </div>
              <span class="fs-14 fw-semibold">Rp 30.000</span>
            </div>
          </div>
          <hr class="mt-0 mb-3 line-donatur">
        </div>
        <div class="col-12">
          <div class="d-flex mb-3">
            <div class="mr-3 rounded-full bg-coal relative" style="width: 50px; height: 50px;">
              <img alt="Orang Baik" src="{{ asset('public') }}/images/icons/user-anonim.png" width="50" height="50" decoding="async" data-nimg="1" class="w-full h-full rounded-full object-cover object-center" loading="lazy" style="color: transparent;" />
            </div>
            <div class="content-donatur ms-3">
              <div class="">
                <div class="fs-14 fw-semibold">Orang Baik</div>
                <div class="fs-13">17 menit yang lalu</div>
              </div>
              <span class="fs-14 fw-semibold">Rp 10.000</span>
            </div>
          </div>
          <hr class="mt-0 mb-3 line-donatur">
        </div>
        <div class="col-12">
          <div class="d-flex mb-3">
            <div class="mr-3 rounded-full bg-coal relative" style="width: 50px; height: 50px;">
              <img alt="Orang Baik" src="{{ asset('public') }}/images/icons/user-anonim.png" width="50" height="50" decoding="async" data-nimg="1" class="w-full h-full rounded-full object-cover object-center" loading="lazy" style="color: transparent;" />
            </div>
            <div class="content-donatur ms-3">
              <div class="">
                <div class="fs-14 fw-semibold">Orang Baik</div>
                <div class="fs-13">22 menit yang lalu</div>
              </div>
              <span class="fs-14 fw-semibold">Rp 200.000</span>
            </div>
          </div>
          <hr class="mt-0 mb-3 line-donatur">
        </div>
      </div>
    </div>
  </section>
  <!-- Donatur section end -->

  <!-- Empty section start -->
  <section class="empty-section section-t-space section-b-space pt-4 pb-4">
    <div class="custom-container space-empty pb-3">
    </div>
  </section>
  <!-- Empty section end -->
@endsection


@section('content_modal')
  <!-- cart popup start -->
  <div class="cart-popup">
    <button class="btn btn-outline-warning share-btn me-2">
      <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" class="mr-2 w-4">
        <path d="M17.5 10A3.5 3.5 0 1 0 14 6.5c0 .43-.203.86-.595 1.037L10.034 9.07c-.427.194-.924.052-1.283-.25a3.5 3.5 0 1 0-.2 5.517c.38-.275.885-.381 1.297-.156l3.585 1.955c.412.225.597.707.572 1.176a3.5 3.5 0 1 0 1.445-2.649c-.38.275-.886.381-1.298.156l-3.585-1.955c-.412-.225-.597-.707-.572-1.176.003-.062.005-.125.005-.188 0-.43.203-.86.595-1.037l3.371-1.533c.428-.194.924-.052 1.283.25.609.512 1.394.82 2.251.82Z" fill="#F7BC00"></path>
      </svg>
      Bagikan
    </button>
    <!-- <a href="cart.html" class="btn theme-btn cart-btn mt-0">View Cart</a> -->
    <a href="{{ route('donate.amount', $slug) }}" class="btn btn-warning donate-btn">Donasi Sekarang</a>
  </div>
  <!-- cart popup end -->
@endsection


@section('js_plugins')

@endsection


@section('js_inline')
    
@endsection
