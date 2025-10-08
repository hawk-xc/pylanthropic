@extends('layouts.public', [
    'second_title' => '',
])


@section('css_plugins')
    <!-- swiper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/css/vendors/swiper-bundle.min.css" />

    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq.disablePushState = true;
        fbq('init', '1278491429470122');
        fbq('track', 'PageView');
        window.loadedPixel = []
    </script>
    <!-- End Meta Pixel Code -->
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

        /* Judul fleksibel: tidak dipotong, tapi ada tinggi minimum */
        .two-line {
            --lh: 12px;
            --lines: 2;
            line-height: var(--lh);
            min-height: calc(var(--lh) * var(--lines));
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        /* Overlay gelap */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            /* hitam transparan */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        /* Konten popup */
        .popup-content {
            background: transparent;
            /* tidak ada background */
            border: none;
            display: flex;
            flex-direction: column;
            outline: none;
        }

        .popup-image {
            max-width: 90%;
            height: auto;
        }

        #close-popup {
            background: #fff;
            border: none;
            width: 30%;
            min-width: 80px;
            margin-inline: auto;
            padding: 6px 18px;
            border-radius: 6px;
            font-weight: bold;
        }

        h3 {
            font-size: 1.5rem;
        }
    </style>
@endsection

@section('content')
    <!-- Popup Overlay -->
    <div class="col-12 text-center h-100 p-5 d-flex flex-column justify-content-center align-items-center gap-2 error-page">
        <img src="{{ asset('server-error.png') }}" alt="Error" class="img-fluid w-25 opacity-50">
        <h3>{{ $code ?? 'Error' }} {{ 'Terjadi kesalahan.' }}</h3>
        <p>
            @switch($code)
                @case(403)
                    Kamu tidak memiliki izin untuk mengakses halaman ini.
                    @break
                @case(404)
                    Halaman yang kamu cari tidak ditemukan.
                    @break
                @case(500)
                    Server sedang bermasalah. Silakan coba lagi nanti.
                    @break
                @default
                    Terjadi kesalahan yang tidak diketahui.
            @endswitch
        </p>
        <a href="{{ route('index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
@endsection


@section('js_plugins')
    <!-- JQuery -->
    <script src="{{ asset('public/js/jquery-3.6.4.min.js') }}"></script>
    <!-- bootstrap js -->
    <script src="{{ asset('public') }}/js/bootstrap.bundle.min.js"></script>

    <!-- swiper js -->
    <script src="{{ asset('public') }}/js/swiper-bundle.min.js"></script>
    <script src="{{ asset('public') }}/js/custom-swiper.js"></script>
    <!-- lazy load -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-lazyload@1.9.7/jquery.lazyload.min.js"></script>
@endsection


@section('js_inline')
    
@endsection
