@extends('layouts.public', [
    'second_title' => ucwords($org->name),
    'meta_desc'    => strip_tags($org->about),
    'image'        => $org->logo,
    'image_type'   => $org->logo,
])


@section('css_plugins')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
@endsection


@section('css_inline')
  <style type="text/css">
    input:focus::-webkit-input-placeholder {
      -webkit-transform: translateY(-125%);
    /*   font-size: 75%; */
      opacity: 0.05
    }
    input.imitatefocus::-webkit-input-placeholder {
      -webkit-transform: translateY(-125%);
      opacity: 0.05
    }
    .about-desc {
      color: rgba(var(--dark-text), 1);
      text-align: justify;
      line-height: 22px;
    }
    .text-dark2 {
      color: rgba(var(--dark-text), 1);
    }
    .lh-22 {
      line-height: 22px !important;
    }
    .lh-26 {
      line-height: 26px !important;
    }
    .fw-500 {
      font-weight: 500 !important;
    }
    .header-title a {
      color: ;
    }
    .about-detail a {
        color: rgba(var(--bs-link-color-rgb),var(--bs-link-opacity,1));
    }
    .icon-verivied {
        width: auto;
        height: 16px;
    }
    .icon-verivied-sm {
        width: auto;
        height: 14px;
    }
    .icon-verivied-org {
        width: auto;
        height: 12px;
    }
    .star {
        width: 6px;
        height: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 6px;
        background-color: #21A9E1;
        border-radius: 100%;
        font-size: 10px;
        color: white;
    }
    .product-box .product-box-detail .bottom-panel {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }
    .product-box .product-box-detail .timing {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 3px;
    }
    .product-box .product-box-detail .timing li {
        position: relative;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 5px;
        color: rgba(var(--dark-text), 1);
    }
  </style>
@endsection

@section('content')
  <!-- header start -->
  <header class="section-t-space pt-0">
    <div class="header-panel bg-me header-title">
      <a href="/" class="text-dark">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="#fff">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
      </a>
      <h2 class="fs-16 text-dark">Program Mitra</h2>
    </div>
  </header>
  <!-- header end -->

  <!-- Fundraiser Top section start -->
  <section class="py-20 pb-3">
    <div class="custom-container">
      <div class="d-flex mt-2 justify-content-center">
        <img class="img img-fundraiser-detail me-2" src="{{ asset('public/images/fundraiser/'.$org->logo) }}" alt="aa">
        <div class="ms-2 flex-column align-items-center">
            <div class="d-flex align-items-center">
                <h6 class="fs-18 lh-22 fw-semibold">{{ ucwords($org->name) }}</h6>
                @if($org->status=='verified' || $org->status=='verif_org')
                    <img class="img icon-verivied ms-1" src="{{ asset('public/images/icons/verified.png') }}" alt="verified campaigner bantubersama">
                @endif
                @if($org->status=='verif_org')
                    <img class="img icon-verivied-org ms-1" src="{{ asset('public/images/icons/verified_org.png') }}" alt="aa">
                @endif
            </div>
            @if($org->status=='verified' || $org->status=='verif_org')
              <div class="fs-10 text-dark2">
                  Akun Terverifikasi
              </div>
            @endif
        </div>
      </div>
      <div class="row d-flex align-content-center text-center mt-4 fw-500">
        <a href="#donasi" class="col-4 btn-donate-detail1">
          <div>
            <span class="fs-14 lh-22">{{ number_format($jml_program) }}</span>
          </div>
          <div class="fs-14 lh-22">Program</div>
        </a>
        <a href="#kabar-terbaru" class="col-4 btn-donate-detail2">
          <div>
            <span class="fs-14 lh-22">{{ number_format($jml_donatur) }}</span>
          </div>
          <div class="fs-14 lh-22">Donatur</div>
        </a>
        <a href="#penggunaan-dana" class="col-4 btn-donate-detail3">
          <div>
            <span class="fs-14 lh-22">{{ number_format($jml_salur) }}</span>
          </div>
          <div class="fs-14 lh-22">Salur Dana</div>
        </a>
      </div>
    </div>
  </section>
  <!-- Fundraiser section end -->

  <!-- Empty section start -->
  <section class="empty-section section-t-space section-b-space pb-0 pt-0">
    <div class="custom-container space-empty pb-2">
    </div>
  </section>
  <!-- Empty section end -->

  <!-- Fundraiser section start -->
  <section class="py-20">
    <div class="custom-container text-dark2 fs-14">
        <div class="fw-bold fs-16 mb-2 pb-1">Tentang</div>
        <p class="fs-14 about-desc">
            {!! $org->about !!}
        </p>
        <div class="about-detail">
            <div class="d-flex mb-2">
              <div style="min-width: 24px;">
                <i class="fa fa-location-dot fa-sm text-dark2 lh-26"></i>
              </div>
              <div class=""> {{ $org->address }}</div>
            </div>
            <div class="d-flex mb-2">
              <div style="min-width: 24px;">
                <i class="fa fa-location-dot fa-sm text-dark2 lh-26"></i>
              </div>
              <div class=""> Bergabung sejak {{ date('d-m-Y', strtotime($org->created_at)) }}</div>
            </div>
            <!-- <div class="d-flex">
              <div style="min-width: 24px;">
                <i class="fa fa-link fa-sm text-dark2 lh-26"></i>
              </div>
              <a href="https://link.tree/lazisnusleman" target="_blank" class="">link.tree/lazisnusleman</a>
            </div> -->
        </div>
    </div>
  </section>
  <!-- Fundraiser section end -->

  <!-- Empty section start -->
  <section class="empty-section section-t-space section-b-space pb-0 pt-0">
    <div class="custom-container space-empty pb-2">
    </div>
  </section>
  <!-- Empty section end -->

  <!-- Explore Restaurants section start -->
  <section class="section-t-space pt-3 mt-1 pb-4">
    <div class="custom-container">
        <div class="fw-bold fs-16 mb-2 pb-1">Program Galang Dana</div>
        <div class="row gy-4 gx-3 pt-1">

        @foreach($program as $vn)
            <div class="col-6">
                <a href="{{ url('/').'/'.$vn->slug }}" class="">
                  <div class="product-box">
                    <img class="img-fluid rounded-top lazyload" src="{{ asset('public/images/program').'/'.$vn->thumbnail }}" alt="{{ ucwords($vn->title) }}" />
                    <div class="product-box-detail product-box-bg mt-2">
                      <h5 class="two-line mt-1 mb-1 fs-11 lh-14 fw-semibold">{{ ucwords($vn->title) }}</h5>
                      <ul class="timing mt-2 mb-2">
                        <li class="fs-12 lh-14">
                          {{ ucwords($vn->name) }} 
                          @if($vn->status=='verified' || $vn->status=='verif_org')
                            <!-- <span class="star"><i class="ri-star-s-fill"></i></span> -->
                            <img class="img icon-verivied-sm" src="{{ asset('public/images/icons/verified.png') }}" alt="verified campaigner bantubersama" sty>
                          @endif
                        </li>
                      </ul>
                      <div class="progress mt-1" role="progressbar" aria-label="Basic example" aria-valuenow="86" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                        <div class="progress-bar" style="width: {{ ceil($vn->sum_amount/$vn->nominal_approved*100) }}%"></div>
                      </div>
                      <div class="bottom-panel">
                        <div class="pe-0 fw-semibold fs-11 lh-16">Rp {{ str_replace(',', '.', number_format($vn->sum_amount)) }}</div>
                        <div class="fw-semibold fs-11 lh-16 text-end">
                          {{ now()->diffInDays(substr($vn->end_date, 0,10)) }}
                        </div>
                      </div>
                      <div class="bottom-panel mt-0">
                        <div class="fw-light fs-10 lh-14 pe-0">Donasi Terkumpul</div>
                        <div class="fw-light fs-10 lh-14 text-end">Hari Lagi</div>
                      </div>
                    </div>
                  </div>
                </a>
            </div>
        @endforeach

        @if(count($program)<1)
          <div class="col-12 text-center mt-4">
            <h4 class="fs-16">Program Tidak Ditemukan</h4>
            <p class="fs-14">Lembaga ini belum memiliki programgalang dana</p>
          </div>
        @endif
      </div>
    </div>
  </section>
  <!-- List section end -->

@endsection


@section('content_modal')
  <!-- filter offcanvas start -->
  <div class="modal search-filter" id="search-filter" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-title">
          <h3 class="fw-semibold">Filter</h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <section class="section-b-space pt-0">
          <div class="custom-container">
            <section class="pt-0">
              <div class="order-options">
                <h3 class="mt-3 mb-3 dark-text fw-semibold">Kategori</h3>
                <div class="order-type">
                  <div class="auth-form search-form">
                    <div class="form-check">
                      <label class="form-check-label" for="fixed1">Semua</label>
                      <input class="form-check-input" type="radio" name="kategori" value="semua" checked />
                    </div>
                  </div>
                  <div class="auth-form search-form">
                    <div class="form-check">
                      <label class="form-check-label" for="fixed2">Kemanusiaan</label>
                      <input class="form-check-input" type="radio" name="kategori" value="kemanusiaan" />
                    </div>
                  </div>
                </div>
                <div class="order-type">
                  <div class="auth-form search-form">
                    <div class="form-check">
                      <label class="form-check-label" for="fixed1">Pendidikan</label>
                      <input class="form-check-input" type="radio" name="kategori" value="pendidikan" />
                    </div>
                  </div>
                  <div class="auth-form search-form">
                    <div class="form-check">
                      <label class="form-check-label" for="fixed2">Kesehatan</label>
                      <input class="form-check-input" type="radio" name="kategori" value="kesehatan" />
                    </div>
                  </div>
                </div>
                <div class="order-type">
                  <div class="auth-form search-form">
                    <div class="form-check">
                      <label class="form-check-label" for="fixed1">Rumah Ibadah</label>
                      <input class="form-check-input" type="radio" name="kategori" value="rumah_ibadah" />
                    </div>
                  </div>
                  <div class="auth-form search-form">
                    <div class="form-check">
                      <label class="form-check-label" for="fixed2">Difabel</label>
                      <input class="form-check-input" type="radio" name="kategori" value="difabel" />
                    </div>
                  </div>
                </div>
                <div class="order-type">
                  <!-- <div class="auth-form search-form">
                    <div class="form-check">
                      <label class="form-check-label" for="fixed1">Sosial</label>
                      <input class="form-check-input" type="radio" name="kategori" value="sosial" />
                    </div>
                  </div> -->
                  <div class="auth-form search-form">
                    <div class="form-check">
                      <label class="form-check-label" for="fixed1">Bencana Alam</label>
                      <input class="form-check-input" type="radio" name="kategori" value="bencana_alam" />
                    </div>
                  </div>
                  <div class="auth-form search-form">
                    <div class="form-check">
                      <label class="form-check-label" for="fixed2">Kemanusiaan</label>
                      <input class="form-check-input" type="radio" name="kategori" value="kemanusiaan" />
                    </div>
                  </div>
                </div>
                <div class="order-type">
                  <div class="auth-form search-form">
                    <div class="form-check">
                      <label class="form-check-label" for="fixed2">Infrastruktur</label>
                      <input class="form-check-input" type="radio" name="kategori" value="infrastruktur" />
                    </div>
                  </div>
                  <div class="auth-form search-form">
                    <div class="form-check">
                      <label class="form-check-label" for="fixed2">Lainnya</label>
                      <input class="form-check-input" type="radio" name="kategori" value="lainnya" />
                    </div>
                  </div>
                </div>
              </div>
            </section>

            <section class="pt-0 section-lg-b-space">
              <div class="order-options">
                <h3 class="mb-3 dark-text fw-semibold">Urut Berdasarkan</h3>
                <div class="order-type">
                  <div class="auth-form search-form">
                    <div class="form-check">
                      <label class="form-check-label" for="fixed3">Tanggal Terbaru</label>
                      <input class="form-check-input" type="radio" name="sort" value="terbaru" checked />
                    </div>
                  </div>
                  <div class="auth-form search-form section-b-space">
                    <div class="form-check">
                      <label class="form-check-label" for="fixed4">Segera Berakhir</label>
                      <input class="form-check-input" type="radio" name="sort" value="segera_berakhir" />
                    </div>
                  </div>
                </div>
                <div class="order-type">
                  <div class="auth-form search-form">
                    <div class="form-check">
                      <label class="form-check-label" for="fixed3">Donasi Terbanyak</label>
                      <input class="form-check-input" type="radio" name="sort" value="terbanyak" />
                    </div>
                  </div>
                  <div class="auth-form search-form section-b-space">
                    <div class="form-check">
                      <label class="form-check-label" for="fixed4">Donasi Sedikit</label>
                      <input class="form-check-input" type="radio" name="sort" value="sedikit" />
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </section>

        <div class="footer-modal d-flex">
          <a href="#" class="btn btn-link btn-inline mt-0 w-50">Reset Filter</a>
          <a href="#" class="theme-btn btn btn-inline mt-0 w-50" id="apply">Apply</a>
        </div>
      </div>
    </div>
  </div>
  <!-- filter offcanvas end -->
@endsection


@section('js_plugins')
  <!-- JQuery -->
  <script src="{{ asset('public/js/jquery-3.6.4.min.js') }}"></script>
  <!-- bootstrap js -->
  <script src="{{ asset('public') }}/js/bootstrap.bundle.min.js"></script>
@endsection


@section('js_inline')
  <script type="text/javascript">
    
  </script>
@endsection
