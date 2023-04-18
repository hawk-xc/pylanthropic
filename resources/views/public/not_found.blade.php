@extends('layouts.public', [
    'second_title'    => 'Home'
])


@section('css_plugins')
    <!-- swiper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/css/vendors/swiper-bundle.min.css" />
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
    <!-- search section starts -->
    <section class="search-section pt-3">
      <div class="custom-container">
        <form class="auth-form search-head" target="_blank">
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

    <!-- payment method section start -->
    <section class="payment method section-lg-b-space pt-0">
      <div class="custom-container text-center">
        <img class="mt-4" src="{{ asset('public/images/icons/error.png') }}">
        <h3 class="fw-medium fs-16 mt-4 mb-2">Maaf, halaman tidak ditemukan</h3>
        <p class="fs-14 lh-20">Halaman yang kamu cari tidak ada di sistem kami atau sedang mengalami gangguan</p>
        <a href="{{ url('/') }}" class="btn  share-btn mt-3 w-100">
          Kembali ke halaman depan
        </a>
      </div>
    </section>
    <!-- payment method section end -->

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
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="fixed1" checked />
                      </div>
                    </div>
                    <div class="auth-form search-form">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed2">Kemanusiaan</label>
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="fixed2" />
                      </div>
                    </div>
                  </div>
                  <div class="order-type">
                    <div class="auth-form search-form">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed1">Pendidikan</label>
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="fixed1" />
                      </div>
                    </div>
                    <div class="auth-form search-form">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed2">Kesehatan</label>
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="fixed2" />
                      </div>
                    </div>
                  </div>
                  <div class="order-type">
                    <div class="auth-form search-form">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed1">Rumad Ibadah</label>
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="fixed1" />
                      </div>
                    </div>
                    <div class="auth-form search-form">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed2">Difabel</label>
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="fixed2" />
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
                        <input class="form-check-input" type="radio" name="RadioDefault" id="fixed3" checked />
                      </div>
                    </div>
                    <div class="auth-form search-form section-b-space">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed4">Segera Berakhir</label>
                        <input class="form-check-input" type="radio" name="RadioDefault" id="fixed4" />
                      </div>
                    </div>
                  </div>
                  <div class="order-type">
                    <div class="auth-form search-form">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed3">Donasi Terbanyak</label>
                        <input class="form-check-input" type="radio" name="RadioDefault" id="fixed3" />
                      </div>
                    </div>
                    <div class="auth-form search-form section-b-space">
                      <div class="form-check">
                        <label class="form-check-label" for="fixed4">Donasi Sedikit</label>
                        <input class="form-check-input" type="radio" name="RadioDefault" id="fixed4" />
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </section>

          <div class="footer-modal d-flex">
            <a href="index.html" class="btn btn-link btn-inline mt-0 w-50">Reset Filter</a>
            <a href="{{ route('program.list') }}" class="theme-btn btn btn-inline mt-0 w-50">Terapkan</a>
          </div>
        </div>
      </div>
    </div>
    <!-- filter offcanvas end -->
@endsection


@section('js_plugins')
    <!-- bootstrap js -->
    <script src="{{ asset('public') }}/js/bootstrap.bundle.min.js"></script>

    <!-- swiper js -->
    <script src="{{ asset('public') }}/js/swiper-bundle.min.js"></script>
    <script src="{{ asset('public') }}/js/custom-swiper.js"></script>

    <!-- homescreen popup js -->
    <!-- <script src="{{ asset('public') }}/js/homescreen-popup.js"></script> -->
    
    <!-- PWA offcanvas popup js -->
    <script src="{{ asset('public') }}/js/offcanvas-popup.js"></script>
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
    </script>
@endsection
