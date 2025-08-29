@extends('layouts.public', [
    'second_title'    => 'Detail Program'
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
    fbq('init', '1278491429470122');
    fbq('track', 'ViewContent');
    window.loadedPixel = []
  </script>
  <!-- End Meta Pixel Code -->
@endsection


@section('css_inline')
    
@endsection

@section('content')
  <!-- header start -->
  <header class="section-t-space pt-0">
    <!-- <div class="custom-container"> -->
      <div class="header-panel bg-me header-title">
        <!-- <div class="header-title"> -->
          <a href="{{ url('/').'/'.$program->slug }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="#fff">
              <line x1="19" y1="12" x2="5" y2="12"></line>
              <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
          </a>
          <h2 class="fs-16">Kabar Terbaru</h2>
        <!-- </div> -->
      </div>
    <!-- </div> -->
  </header>
  <!-- header end -->

  @if(count($info)>0)
  <!-- Kabar Terbaru section start -->
  <section class="py-20">
    <div class="custom-container">
        <div class="title mb-3 pb-1">
          <div class="fw-bold fs-16" id="kabar-terbaru">{{ ucwords($program->title) }}</div>
        </div>
        <div>
          <ul class="info-timeline">
            @foreach($info as $vi)
              <li>
                <div class="content-preview">
                  <div class="info-head">
                    <div class="info-box justify-content-start">
                        <div class="img-wrap">
                            <img src="{{ asset('public/images/fundraiser'.'/'.$program->logo) }}" />
                        </div>
                        <div class="pt-1">
                            <h4>{{ ucwords($vi->title) }}</h4>
                            <p class="fs-12 mt-1 mb-3">{{ date('d-m-Y', strtotime($vi->created_at)) }}</p>
                        </div>
                    </div>
                  </div>
                  <div class="info-content">
                    {!! $vi->content !!}
                  </div>
                </div>
                <div class="text-center pt-3 pb-2">
                  <button class="btn-selengkapnya-about">Baca selengkapnya</button>
                </div>
              </li>
            @endforeach
          </ul>
        </div>
        <!-- else -->
        <!-- <div class="title mb-3 pb-1">
          <div class="fw-bold fs-16" id="kabar-terbaru">Kabar Terbaru</div>
        </div>
        <div class="text-center fs-14 lh-20 text-muted">Belum ada data kabar</div> -->
    </div>
  </section>
  <!-- Kabar Terbaru section end -->
  @else
    <section class="py-20">
      <div class="custom-container">
        <h5>Data tidak ditemukan</h5>
      </div>
    </section>
  @endif

@endsection


@section('content_modal')

@endsection


@section('js_plugins')
  <!-- JQuery -->
  <script src="{{ asset('public/js/jquery-3.6.4.min.js') }}"></script>
@endsection


@section('js_inline')
  <script type="text/javascript">
    // Baca selengkapnya Info
    $(".btn-selengkapnya-about").on("click", function(e) {
      const a = e.target.parentNode.parentNode.querySelector(".content-preview");
      console.log(a);
      a.classList.add('no-after');
      $(this).remove();
    });
  </script>
@endsection

@section('js_inline')
    <script type="text/javascript">
        $("img.lazyload").lazyload();

        $(".share-btn").on("click", function() {
            var myOffcanvas = document.getElementById("offcanvas");
            var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
            bsOffcanvas.show();
        });

        // action link share
        $(".btn-icon-share").on("click", function() {
            let name = $(this).attr('aria-label');
            let uri = "{{ url('/') . '/' . $program->slug }}";
            let txt =
                'Jangan%20biarakan%20mereka%20merasa%20sendirian!%0AYuk%20berinfaq%20untuk%20memuliakan%20dan%20membahagiakan%20adik-adik%20yatim%20yang%20membutuhkan%20bantuan..%20Bantu%20Donasi%20dengan%20klik';
            let txt2 =
                'Jangan biarkan mereka merasa sendirian! Yuk bantu bersama yang membutuhkan bantuan, dengan klik';
            let utm = 'utm_source=';
            let utm2 =
                'utm_source%3Dsocialsharing_donor_web_null%26utm_medium%3Dshare_campaign_whatsapp%26utm_campaign%3Dshare_detail_campaign';
            if (name == 'facebook') {
                var url = encodeURI('https://www.facebook.com/sharer/sharer.php?u=' + uri + '?' + utm +
                    'fb&quote%3D' + txt2);
                window.open(url, 'name', 'width=600,height=400');
            } else if (name == 'twitter') {
                let url = encodeURI('https://twitter.com/intent/tweet?url=' + uri + '?' + utm + 'tw&text=' + txt2);
                window.open(url, 'name', 'width=600,height=400');
            } else if (name == 'whatsapp') {
                let url = encodeURI('https://api.whatsapp.com/send?phone=&text=' + txt2 + ' ' + uri + '?' + utm +
                    'wa');
                window.open(url, 'name', 'width=600,height=400');
            } else if (name == 'telegram') {
                let url = encodeURI('https://telegram.me/share/url?url=' + uri + '&text={{ $program->title }}');
                window.open(url, 'name', 'width=600,height=400');
            } else if (name == 'line') {
                let url = encodeURI('https://social-plugins.line.me/lineit/share?url=' + uri + '?' + utm +
                    'line&text=' + txt2);
                window.open(url, 'name', 'width=600,height=400');
            } else if (name == 'linkedin') {
                let url = encodeURI('https://www.linkedin.com/shareArticle?url=' + uri +
                    '&mini=true&title={{ $program->title }}&summary={{ $program->short_desc }}&source={{ url('/') }}'
                    );
                window.open(url, 'name', 'width=600,height=400');
            } else if (name == 'email') {
                let url = encodeURI(
                    'mailto:Bantubersama.com<contact@bantubersama.com>?subject={{ $program->title }}&body=' +
                    txt2 + ' ' + uri);
                window.open(url);
            } else {
                let link_share = $(this).attr('data-clipboard-text');
                navigator.clipboard.writeText(link_share);
                $('#copyUrlToast').toast({
                    animation: false,
                    delay: 3000
                });
                $('#copyUrlToast').toast('show');

                // var myAlert = document.getElementById('copyUrlToast');//select id of toast
                // var bsAlert = new bootstrap.Toast(myAlert);//inizialize it
                // bsAlert.show();//show it

                // alert('ok');
            }
        });

        // Baca selengkapnya About
        $("#about-more").on("click", function() {
            $('#preview-about').addClass('no-after');
            $('#preview-about').css('height', '100%');
            $('#preview-about').css('max-height', '100%');
            $(this).remove();

            $.ajax({
                type: "POST",
                url: "{{ route('program.count.read_more', $program->slug) }}",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    console.log(data);
                    if (data == 'success') {
                        // toast success  
                    }
                }
            });
        });

        // Baca selengkapnya Info
        $("#info-more").on("click", function() {
            $('#preview-info').addClass('no-after');
            $('#preview-info').css('height', '100%');
            $('#preview-info').css('max-height', '100%');
            $(this).remove();
        });
    </script>
@endsection