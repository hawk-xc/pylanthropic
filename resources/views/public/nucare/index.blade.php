@extends('layouts.public', [
    'second_title' => 'Pengajuan Program - LAZISNU DIY x BANTUBERSAMA',
    'meta_desc'    => 'Bantubersama adalah platform penggalang dana untuk membantu bersama secara online',
    'image'        => asset('public/images/program/Infaq_Bantu_Bersama.jpg'),
])


@section('css_plugins')
  

@endsection


@section('css_inline')
    
@endsection


@section('content')
  <div class="">
    <img alt="Pengajuan Program Bantubersama.com" class="h-auto w-100" src="{{ asset('public/images/program/Infaq_Bantu_Bersama.jpg') }}">
  </div>
  <!-- header end -->

  <!-- Detail section start  -->
  <section class="pt-3 pb-2">
    <div class="custom-container text-center">
      <h4 class="donate-collect">Pengajuan Program Warga NU D.I.Yogyakarta</h4>
      <div class="short-desc mt-1 pt-1">Beritahu kami jika ada tetangga / masyarakat umum yang membutuhkan bantuan penggalang dana</div>
    </div>
  </section>
  <!-- filter section end  -->

  <!-- Empty section start -->
  <section class="empty-section section-t-space section-b-space pb-0 pt-1">
    <div class="custom-container space-empty pb-2">
    </div>
  </section>
  <!-- Empty section end -->

  <!-- Program Detail section start -->
  <section class="py-20">
    <div class="custom-container">
      <div class="text-center fw-bold fs-16 mb-2 pb-1">Pilih Kategori Program</div>
      <div class="row gy-3">
        <div class="col-6">
          <div class="text-center" style="">
            <a href="{{ route('form') }}?c=kesehatan" class="">
              <img class="img-fluid categories-img" src="https://bantubersama.com/public/images/categories/scholarship.png" alt="Pendidikan">
            </a>
            <h6 class="fs-14 mt-1">Kesehatan</h6>
          </div>
        </div>
        <div class="col-6">
          <div class="text-center" style="">
            <a href="{{ route('form') }}?c=rumahibadah" class="">
              <img class="img-fluid" src="https://bantubersama.com/public/images/categories/scholarship.png" alt="Pendidikan">
            </a>
            <h6 class="fs-14 mt-1">Rumah Ibadan</h6>
          </div>
        </div>
        <div class="col-6">
          <div class="text-center" style="">
            <a href="{{ route('form') }}?c=pendidikan" class="">
              <img class="img-fluid" src="https://bantubersama.com/public/images/categories/scholarship.png" alt="Pendidikan">
            </a>
            <h6 class="fs-14 mt-1">Pendidikan</h6>
          </div>
        </div>
        <div class="col-6">
          <div class="text-center" style="">
            <a href="{{ route('form') }}?c=kemanusiaan" class="">
              <img class="img-fluid" src="https://bantubersama.com/public/images/categories/scholarship.png" alt="Pendidikan">
            </a>
            <h6 class="fs-14 mt-1">Kemanusiaan</h6>
          </div>
        </div>
        <div class="col-6">
          <div class="text-center" style="">
            <a href="{{ route('form') }}?c=bencanaalam" class="">
              <img class="img-fluid" src="https://bantubersama.com/public/images/categories/scholarship.png" alt="Pendidikan">
            </a>
            <h6 class="fs-14 mt-1">Bencana Alam</h6>
          </div>
        </div>
        <div class="col-6">
          <div class="text-center" style="">
            <a href="{{ route('form') }}?c=lainnya" class="">
              <img class="img-fluid" src="https://bantubersama.com/public/images/categories/scholarship.png" alt="Pendidikan">
            </a>
            <h6 class="fs-14 mt-1">Lainnya</h6>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Program Detail section end -->


  <!-- Footer section start -->
  <section class="empty-section section-t-space section-b-space pb-0 pt-3">
    <div class="custom-container footer pb-3 pt-3">
      <div class="fw-medium text-grey pt-2 fs-14">
        <a class="text-grey" href="">Tentang Kami</a> | 
        <a class="text-grey" href="">Syarat & Ketentuan</a> | 
        <a class="text-grey" href="">Pusat Bantuan</a>
      </div>
      <div class="mt-3 text-grey fs-14">
        Temukan kami di <br>
        <div class="socmed mb-3 mt-1">
          <a rel="noreferrer" href="https://www.facebook.com/profile.php?id=100091563649667" target="_blank" class="me-2 socmed-item rounded-circle">
              <svg class="mx-auto" width="12" height="12" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M9 .002L7.443 0C5.695 0 4.565 1.16 4.565 2.953v1.362H3.001a.245.245 0 00-.245.245v1.973c0 .135.11.244.245.244h1.564v4.978c0 .135.11.245.245.245h2.041c.136 0 .245-.11.245-.245V6.777h1.83c.135 0 .244-.11.244-.244V4.56a.245.245 0 00-.244-.245h-1.83V3.16c0-.555.132-.837.855-.837h1.048c.135 0 .245-.11.245-.245V.247A.245.245 0 009 .002z" fill="currentColor"></path>
              </svg>
              <span class="screen-reader-text">Facebook</span>
          </a>
          <a rel="noreferrer" href="https://www.instagram.com/bantubersamacom/" target="_blank" class="me-2 socmed-item rounded-circle">
              <svg class="mx-auto" width="12" height="12" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                      d="M8.688 0H3.311A3.315 3.315 0 000 3.312v5.376A3.315 3.315 0 003.311 12h5.377A3.315 3.315 0 0012 8.688V3.312A3.315 3.315 0 008.688 0zm2.247 8.688a2.25 2.25 0 01-2.247 2.247H3.311a2.25 2.25 0 01-2.246-2.247V3.312A2.25 2.25 0 013.31 1.065h5.377a2.25 2.25 0 012.247 2.247v5.376z"
                      fill="currentColor"
                  ></path>
                  <path
                      d="M6 2.906a3.096 3.096 0 00-3.092 3.092A3.095 3.095 0 006 9.09a3.095 3.095 0 003.092-3.092A3.096 3.096 0 006 2.906zm0 5.12a2.03 2.03 0 01-2.028-2.028A2.03 2.03 0 016 3.971a2.03 2.03 0 012.027 2.027A2.03 2.03 0 016 8.025zM9.222 2.004a.784.784 0 00-.781.78.787.787 0 00.78.78.788.788 0 00.553-.227.784.784 0 00-.552-1.333z"
                      fill="currentColor"
                  ></path>
              </svg>
              <span class="screen-reader-text">Instagram</span>
          </a>
          <a rel="noreferrer" href="https://twitter.com/bantubersamacom" target="_blank" class="me-2 socmed-item rounded-circle">
              <svg class="mx-auto" width="12" height="12" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                  <g>
                      <path
                          d="M14.356 4.742A6.547 6.547 0 0016 3.039a6.85 6.85 0 01-1.89.518 3.263 3.263 0 001.443-1.813 6.563 6.563 0 01-2.08.794A3.28 3.28 0 007.8 4.781c0 .26.022.51.076.748a9.287 9.287 0 01-6.761-3.432 3.308 3.308 0 00-.45 1.658c0 1.136.585 2.143 1.458 2.726A3.242 3.242 0 01.64 6.077v.036a3.296 3.296 0 002.628 3.224 3.262 3.262 0 01-.86.108c-.21 0-.422-.012-.62-.056a3.312 3.312 0 003.064 2.285 6.593 6.593 0 01-4.067 1.399c-.269 0-.527-.012-.785-.045A9.237 9.237 0 005.032 14.5c5.789 0 9.561-4.83 9.324-9.758z"
                          fill="currentColor"
                      ></path>
                  </g>
              </svg>
              <span class="screen-reader-text">Twitter</span>
          </a>
          <a rel="noreferrer" href="https://www.youtube.com/@Bantubersama-de2vi" target="_blank" class="socmed-item rounded-circle">
              <svg class="mx-auto" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24">
                  <path
                      d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"
                      fill="currentColor"
                  ></path>
              </svg>
              <span class="screen-reader-text">Youtube</span>
          </a>
        </div>
      </div>
      <div class="fs-14 fw-normal text-grey mt-3">
        Copyright © 2023 Yayasan Bantu Bersama Sejahtera
      </div>
    </div>
  </section>
  <!-- footer section end -->
@endsection


@section('content_modal')


@endsection


@section('js_plugins')
  
@endsection


@section('js_inline')
  <script type="text/javascript">
  
  </script>
@endsection
