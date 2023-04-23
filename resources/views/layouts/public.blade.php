<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
    <title>{{ (isset($second_title) && !empty($second_title) ? $second_title.' - ' : '').($title ?? env('APP_NAME')).' - Bantu Bersama Kebaikan' }}</title>
    <!-- <link rel="manifest" href="manifest.json" /> -->

    <!-- Meta SEO -->
    <meta name="description" content="{{ isset($meta_desc) && !empty($meta_desc) ? $meta_desc.' - ' : 'Bantubersama adalah platform penggalangan dana untuk membantu bersama secara online' }}" />
    <meta name="keywords" content="{{ isset($meta_desc) && !empty($meta_desc) ? $meta_desc.' - ' : 'bantubersama' }}" />
    <meta name="author" content="{{ isset($meta_desc) && !empty($meta_desc) ? $meta_desc.' - ' : 'bantubersama' }}" />

    <meta name="og:site_name" content="{{ (isset($second_title) && !empty($second_title) ? $second_title.' - ' : ' Platform Penggalang Dana - ') }} Bantubersama.com"/>
    <meta property="og:title" content="{{ (isset($second_title) && !empty($second_title) ? $second_title.' - ' : ' Platform Penggalang Dana - ') }} Bantubersama.com"/>
    <meta name="og:image" content="{{ asset('public/images/logo/Bantubersama.png') }}"/>
    <meta property="og:url" content="https://www.bantubersama.com"/>
    <meta property="og:description" content="{{ isset($meta_desc) && !empty($meta_desc) ? $meta_desc.' - ' : 'Bantubersama adalah platform penggalangan dana untuk membantu bersama secara online' }}"/>

    <!-- Style -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-title" content="Bantubersama" />
    <meta name="application-name" content="Bantubersama"/>
    <meta name="msapplication-TileImage" content="{{ asset('favicon-16x16.png') }}" />
    <meta name="msapplication-TileColor" content="#3BA8DD" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="preconnect" href="https://dev.bantubersama.com" crossorigin>
    <link rel="dns-prefetch" href="https://dev.bantubersama.com">
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
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/css/vendors/remixicon.css" />

    <!-- bootstrap css -->
    <link rel="stylesheet" id="rtl-link" type="text/css" href="{{ asset('public') }}/css/vendors/bootstrap.min.css" />

    <!-- Theme css -->
    <link rel="stylesheet" id="change-link" type="text/css" href="{{ asset('public') }}/css/style.css" />

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JSYTWE48K4"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-JSYTWE48K4');
    </script>

    @yield('css_plugins')
    @yield('css_inline')

</head>

  <body>
      @yield('content')


      @yield('content_modal')
      

      @yield('js_plugins')

      @yield('js_inline')
  </body>

</html>
