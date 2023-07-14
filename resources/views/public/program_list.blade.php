@extends('layouts.public', [
    'second_title'    => 'List Program'
])


@section('css_plugins')
    
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
  </style>
@endsection

@section('content')
  <!-- List section starts -->
  <section class="search-section pt-3">
    <div class="custom-container">
      <form class="auth-form search-head" method="get" action="#" id="search">
        <div class="form-group">
          <a href="{{ url('/') }}" class="logo-navbar">
            <img class="" src="{{ asset('Logo Bantubersama.png') }}">
          </a>
          <div class="form-input">
            <input type="text" class="form-control search typewrite" id="inputkey" placeholder="" />
            <i class="ri-search-line search-icon color-me"></i>
          </div>

          <a href="#search-filter" class="btn filter-button mt-0" data-bs-toggle="modal">
            <i class="ri-equalizer-line color-me"></i>
          </a>
        </div>
      </form>
    </div>
  </section>
  <!-- search section end -->

  <!-- Explore Restaurants section start -->
  <section class="section-t-space pt-3 mt-1 pb-4">
    <div class="custom-container">
      <!-- <div>
        <span class="badge-search">Semua Kategori</span>
        <span class="badge-search">Terbaru</span>
      </div> -->
      <div class="row gy-2 pt-1">
        @foreach($program as $vn)
          <div class="col-12">
            <div class="vertical-product-box">
              <div class="vertical-box-img">
                <a href="{{ url('/').'/'.$vn->slug }}">
                  <img class="img-fluid img" src="{{ asset('public/images/program').'/'.$vn->thumbnail }}" alt="{{ ucwords($vn->title) }}" />
                </a>
              </div>
              <div class="vertical-box-details">
                <a href="{{ url('/').'/'.$vn->slug }}">
                  <div class="vertical-box-head">
                    <div class="restaurant">
                      <h5 class="two-line fs-13">{{ ucwords($vn->title) }}</h5>
                    </div>

                    <h6 class="rating-star mt-2 mb-3">
                      {{ ucwords($vn->name) }} 
                      @if($vn->status=='verified' || $vn->status=='verif_org')
                        <span class="star"><i class="ri-star-s-fill"></i></span>
                      @endif
                    </h6>

                    <div class="progress mt-1 mb-2" role="progressbar" aria-label="Basic example" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                      <div class="progress-bar" style="width: {{ ceil($vn->sum_amount/$vn->nominal_approved*100) }}%"></div>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                      <div class="fw-semibold fs-11 pe-0 lh-20">Rp {{ str_replace(',', '.', number_format($vn->sum_amount)) }}</div>
                      <div class="fw-semibold fs-11 text-end ps-1 lh-20">
                        {{ now()->diffInDays(substr($vn->end_date, 0,10)) }}
                      </div>
                    </div>
                    <div class="d-flex justify-content-between">
                      <div class="fw-light fs-10 pe-0">Donasi Terkumpul</div>
                      <div class="fw-light fs-10 text-end ps-1">Hari Lagi</div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
        @endforeach

        @if(count($program)<1)
          <div class="col-12 text-center mt-4">
            <h4 class="fs-16">Program Tidak Ditemukan</h4>
            <p class="fs-14">Coba atur kembali pencarian Anda atau reset filter</p>
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
    const texts = ['Gandakan sedekah disaat Ramadhan', 'Cari Program Kebaikan... ','Anak Yatim...','Beasiswa Santri...', 'Rumah Tahfidz...'];
    const input = document.querySelector('#inputkey');
    const animationWorker = function (input, texts) {
      this.input              = input;
      this.defaultPlaceholder = this.input.getAttribute('placeholder');
      this.texts              = texts;
      this.curTextNum         = 0;
      this.curPlaceholder     = '';
      this.blinkCounter       = 0;
      this.animationFrameId   = 0;
      this.animationActive    = false;
      this.input.setAttribute('placeholder',this.curPlaceholder);

      this.switch = (timeout) => {
        this.input.classList.add('imitatefocus');
        setTimeout(
          () => { 
            this.input.classList.remove('imitatefocus');
            if (this.curTextNum == 0) 
              this.input.setAttribute('placeholder',this.defaultPlaceholder);
            else
              this.input.setAttribute('placeholder',this.curPlaceholder);

            setTimeout(
              () => { 
                this.input.setAttribute('placeholder',this.curPlaceholder);
                if(this.animationActive) 
                  this.animationFrameId = window.requestAnimationFrame(this.animate)}, 
              timeout);
          }, 
          timeout);
      }

      this.animate = () => {
        if(!this.animationActive) return;
        let curPlaceholderFullText = this.texts[this.curTextNum];
        let timeout = 600; // lama kedip kursor akhir text setelah selesai text
        if (this.curPlaceholder == curPlaceholderFullText+'|' && this.blinkCounter==3) {
          this.blinkCounter = 0;
          this.curTextNum = (this.curTextNum >= this.texts.length-1)? 0 : this.curTextNum+1;
          this.curPlaceholder = '|';
          this.switch(400); // waktu setelah selesai mau lanjut ke text berikutnya
          return;
        }
        else if (this.curPlaceholder == curPlaceholderFullText+'|' && this.blinkCounter<3) {
          this.curPlaceholder = curPlaceholderFullText;
          this.blinkCounter++;
        }
        else if (this.curPlaceholder == curPlaceholderFullText && this.blinkCounter<3) {
          this.curPlaceholder = this.curPlaceholder+'|';
        }
        else {
          this.curPlaceholder = curPlaceholderFullText
            .split('')
            .slice(0,this.curPlaceholder.length+1)
            .join('') + '|';
          timeout = 180; // kecepatan mengetik
        }
        this.input.setAttribute('placeholder',this.curPlaceholder);
        setTimeout(
          () => { if(this.animationActive) this.animationFrameId = window.requestAnimationFrame(this.animate)}, 
          timeout);
      }

      this.stop = () => {
        this.animationActive = false;
        window.cancelAnimationFrame(this.animationFrameId);
      }

      this.start = () => {
        this.animationActive = true;
        this.animationFrameId = window.requestAnimationFrame(this.animate);
        return this;
      }
    }

    document.addEventListener("DOMContentLoaded", () => {
      let aw = new animationWorker(input, texts).start();
      input.addEventListener("focus", (e) => aw.stop());
      input.addEventListener("blur", (e) => {
        aw = new animationWorker(input, texts);
        if(e.target.value == '') setTimeout( aw.start, 400);
      });
    });

    // Apply Filter
    $("#apply").on("click", function() {
      let kategori = $('input[name=kategori]:checked').val();
      let sort     = $('input[name=sort]:checked').val();
      window.location.href = "{{ url('/') }}/programs/?kategori="+kategori+"&sort="+sort;
    });

    $('#search').on("submit", function(e){
      let keys = $('#inputkey').val();
      window.location.href = "{{ url('/') }}/programs/?key="+keys;
      return false;
    });
  </script>
@endsection
