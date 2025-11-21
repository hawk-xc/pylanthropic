<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0" />
    <title>
        {{ (isset($second_title) && !empty($second_title) ? $second_title . ' - ' : '') . ($title ?? env('APP_NAME')) . ' - Bantu Bersama Kebaikan' }}
    </title>
    <!-- <link rel="manifest" href="manifest.json" /> -->

    <!-- Meta SEO -->
    <meta name="description"
        content="{{ isset($meta_desc) && !empty($meta_desc) ? $meta_desc . ' - ' : 'Bantubersama adalah platform penggalangan dana untuk membantu bersama secara online' }}" />
    <meta name="keywords" content="{{ isset($meta_desc) && !empty($meta_desc) ? $meta_desc . ' - ' : 'bantubersama' }}" />
    <meta name="author" content="{{ isset($meta_desc) && !empty($meta_desc) ? $meta_desc . ' - ' : 'bantubersama' }}" />

    <meta name="og:site_name"
        content="{{ isset($second_title) && !empty($second_title) ? $second_title : ' Platform Penggalang Dana - Bantubersama.com' }}" />
    <meta property="og:title"
        content="{{ isset($second_title) && !empty($second_title) ? $second_title : ' Platform Penggalang Dana - Bantubersama.com' }}" />
    @if (isset($image) && isset($image_type))
        <meta property="og:image" content="{{ asset('public/images/fundraiser/' . $image) }}" />
    @else
        <meta property="og:image"
            content="{{ isset($image) ? asset('public/images/program/' . $image) : asset('public/images/logo/Bantubersama-preview.png') }}" />
    @endif
    <meta property="og:url" content="https://www.bantubersama.com" />
    <meta property="og:type" content="website" />
    <meta property="og:description"
        content="{{ isset($meta_desc) && !empty($meta_desc) ? $meta_desc . ' - ' : 'Bantubersama adalah platform penggalangan dana untuk membantu bersama secara online' }}" />

    <!-- Style -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-title" content="Bantubersama" />
    <meta name="application-name" content="Bantubersama" />
    <meta name="msapplication-TileImage" content="{{ asset('favicon-16x16.png') }}" />
    <meta name="msapplication-TileColor" content="#3BA8DD" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="preconnect" href="https://bantubersama.com" crossorigin>
    <link rel="dns-prefetch" href="https://bantubersama.com">
    <link rel="preconnect" href="https://graph.facebook.com" />

    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#3BA8DD">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#3BA8DD">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- favicons
    ================================================== -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <!-- font link -->
    <link rel="stylesheet" href="{{ asset('public') }}/css/vendors/metropolis.min.css" />
    <!-- <link rel="stylesheet" type="text/css" href="{-- asset('public') --}/css/vendors/remixicon.min.css" /> -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css" rel="stylesheet">
    <!-- <link rel="stylesheet" id="rtl-link" type="text/css" href="{-- asset('public') --}/css/vendors/bootstrap.min.css" /> -->
    <link rel="stylesheet" id="rtl-link" type="text/css"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" />
    <!-- Theme css -->
    <link rel="stylesheet" id="change-link" type="text/css" href="{{ asset('public') }}/css/style.css?v=1234567" />
    {{-- Captcha --}}
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.v3_site') }}"></script>
    {{-- lazyloads lazysizes --}}
    <script src="https://cdn.jsdelivr.net/npm/lazysizes/lazysizes.min.js" async></script>

    @if (true)
        <!-- Google Tag Manager -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', 'GTM-T6JPJ3J4');
        </script>
        <!-- End Google Tag Manager -->
    @endif

    <!-- Hotjar Tracking Code for https://bantubersama.com/ -->
    <script>
        (function(h, o, t, j, a, r) {
            h.hj = h.hj || function() {
                (h.hj.q = h.hj.q || []).push(arguments)
            };
            h._hjSettings = {
                hjid: 3507888,
                hjsv: 6
            };
            a = o.getElementsByTagName('head')[0];
            r = o.createElement('script');
            r.async = 1;
            r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
            a.appendChild(r);
        })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
    </script>

    @yield('css_plugins')
    @yield('css_inline')

</head>

<body>
    @if (true)
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T6JPJ3J4" height="0" width="0"
                style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    @yield('content')

    @yield('content_modal')

    @yield('js_plugins')

    @yield('js_inline')

    {{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}

    @if (false)
    <script>
        (function() {
            const ua = navigator.userAgent || navigator.vendor || window.opera;

            // --- DETEKSI IN-APP COMMON ---
            const isFacebook = /\bFBAN|FBAV|FB_IAB|FB4A|FBIOS|Messenger\b/i.test(ua);
            const isInstagram = /\bInstagram\b/i.test(ua);
            const isTikTok = /\bTikTok\b/i.test(ua);
            const isTwitter = /\bTwitter\b/i.test(ua);
            const isLine = /\bLine\b/i.test(ua);
            const isSnapchat = /\bSnapchat\b/i.test(ua);
            const isInApp = isFacebook || isInstagram || isTikTok || isTwitter || isLine || isSnapchat;

            const isAndroid = /android/i.test(ua);
            const isIOS = /iphone|ipad|ipod/i.test(ua);

            // --- Helper Function ---
            function currentHttpsUrl() {
                return window.location.href.replace(/^http:\/\//i, 'https://');
            }

            function showFallback(message) {
                if (document.getElementById('open-in-browser')) return; // prevent duplicate

                const el = document.createElement('div');
                el.id = 'open-in-browser';
                el.style.cssText = `
      position:fixed;
      inset:0;
      display:flex;
      flex-direction:column;
      gap:12px;
      align-items:center;
      justify-content:center;
      padding:24px;
      background:#fff;
      z-index:99999;
      text-align:center;
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;
    `;
                el.innerHTML = `
      <h3 style="margin:0 0 4px;font-size:20px;">Buka di Browser Bawaan</h3>
      <p style="margin:0 0 12px;color:#444">${message || 'Untuk pengalaman terbaik, buka di Chrome atau Safari.'}</p>
      <a id="open-native-btn" href="${currentHttpsUrl()}" target="_blank" rel="noopener"
         style="display:inline-block;padding:12px 18px;border-radius:10px;background:#0d6efd;color:#fff;text-decoration:none;font-weight:600">
         Buka di Chrome / Safari
      </a>
      <p style="margin-top:8px;font-size:12px;color:#666">Jika belum terbuka, tekan tombol di atas.</p>
    `;
                document.body.appendChild(el);
            }

            // --- STRATEGI REDIRECT ---
            if (isInApp) {
                const url = currentHttpsUrl();

                if (isAndroid) {
                    // 1️⃣ Coba paksa buka Chrome via intent
                    try {
                        const intentUrl = 'intent://' + url.replace(/^https?:\/\//i, '') +
                            '#Intent;scheme=https;package=com.android.chrome;end';
                        window.location.href = intentUrl;

                        // 2️⃣ Fallback jika diblok oleh IAB
                        setTimeout(() => {
                            showFallback('Jika tidak otomatis, klik tombol untuk membuka di Chrome.');
                        }, 800);
                    } catch (e) {
                        showFallback('Klik tombol untuk membuka di Chrome.');
                    }
                } else if (isIOS) {
                    // 3️⃣ iOS biasanya blok auto-redirect, jadi tampilkan tombol manual
                    showFallback('Safari memerlukan izin Anda untuk membuka halaman ini di luar aplikasi.');
                    // Bonus nudge (kadang bisa berhasil)
                    setTimeout(() => {
                        try {
                            window.open(url, '_blank');
                        } catch (e) {}
                    }, 600);
                } else {
                    // 4️⃣ Fallback untuk platform lain
                    showFallback();
                }
            }
        })();
    </script>
    @endif

</body>

</html>