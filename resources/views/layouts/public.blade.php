<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0" />
    <title>{{ (isset($second_title) && !empty($second_title) ? $second_title.' - ' : '').($title ?? env('APP_NAME')).' - Bantu Bersama Kebaikan' }}</title>
    <!-- <link rel="manifest" href="manifest.json" /> -->

    <!-- Meta SEO -->
    <meta name="description" content="{{ isset($meta_desc) && !empty($meta_desc) ? $meta_desc.' - ' : 'Bantubersama adalah platform penggalangan dana untuk membantu bersama secara online' }}" />
    <meta name="keywords" content="{{ isset($meta_desc) && !empty($meta_desc) ? $meta_desc.' - ' : 'bantubersama' }}" />
    <meta name="author" content="{{ isset($meta_desc) && !empty($meta_desc) ? $meta_desc.' - ' : 'bantubersama' }}" />

    <meta name="og:site_name" content="{{ (isset($second_title) && !empty($second_title) ? $second_title : ' Platform Penggalang Dana - Bantubersama.com') }}"/>
    <meta property="og:title" content="{{ (isset($second_title) && !empty($second_title) ? $second_title : ' Platform Penggalang Dana - Bantubersama.com') }}"/>
    @if(isset($image) && isset($image_type))
      <meta name="og:image" content="{{ asset('public/images/fundraiser/'.$image) }}"/>
    @else
      <meta name="og:image" content="{{ isset($image) ? asset('public/images/program/'.$image) : asset('public/images/logo/Bantubersama.png') }}"/>
    @endif
    <meta property="og:url" content="https://www.bantubersama.com"/>
    <meta property="og:description" content="{{ isset($meta_desc) && !empty($meta_desc) ? $meta_desc.' - ' : 'Bantubersama adalah platform penggalangan dana untuk membantu bersama secara online' }}"/>

    <!-- Style -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-title" content="Bantubersama" />
    <meta name="application-name" content="Bantubersama"/>
    <meta name="msapplication-TileImage" content="{{ asset('favicon-16x16.png') }}" />
    <meta name="msapplication-TileColor" content="#3BA8DD" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="preconnect" href="https://bantubersama.com" crossorigin>
    <link rel="dns-prefetch" href="https://bantubersama.com">
    <link rel="preconnect" href="https://graph.facebook.com"/>
    
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

    <!-- remixicon css -->
    <!-- <link rel="stylesheet" type="text/css" href="{-- asset('public') --}/css/vendors/remixicon.min.css" /> -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css" rel="stylesheet">

    <!-- bootstrap css -->
    <!-- <link rel="stylesheet" id="rtl-link" type="text/css" href="{-- asset('public') --}/css/vendors/bootstrap.min.css" /> -->
    <link rel="stylesheet" id="rtl-link" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" />

    <!-- Theme css -->
    <link rel="stylesheet" id="change-link" type="text/css" href="{{ asset('public') }}/css/style.css?v=1234567" />

    @if(true)
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T6JPJ3J4');</script>
    <!-- End Google Tag Manager -->
    @endif

    <!-- Hotjar Tracking Code for https://bantubersama.com/ -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:3507888,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>

    @yield('css_plugins')
    @yield('css_inline')

</head>

  <body>
    @if(true)
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T6JPJ3J4"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif

      @yield('content')

      @yield('content_modal')

      @yield('js_plugins')

      @yield('js_inline')
  </body>

</html>
