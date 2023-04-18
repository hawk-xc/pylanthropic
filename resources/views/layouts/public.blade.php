<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ (isset($second_title) && !empty($second_title) ? $second_title.' - ' : '').($title ?? env('APP_NAME')) }}</title>
    <!-- <link rel="manifest" href="manifest.json" /> -->

    <!-- Meta SEO -->
    <meta name="description" content="{{ isset($meta_desc) && !empty($meta_desc) ? $meta_desc.' - ' : 'bantubersama' }}" />
    <meta name="keywords" content="{{ isset($meta_desc) && !empty($meta_desc) ? $meta_desc.' - ' : 'bantubersama' }}" />
    <meta name="author" content="{{ isset($meta_desc) && !empty($meta_desc) ? $meta_desc.' - ' : 'bantubersama' }}" />

    <!-- Style -->
    <meta name="theme-color" content="#ff8d2f" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-title" content="bantubersama" />
    <meta name="msapplication-TileImage" content="{{ asset('favicon-16x16.png') }}" />
    <meta name="msapplication-TileColor" content="#3BA8DD" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- favicons
    ================================================== -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#3BA8DD">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#3BA8DD">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#3BA8DD">

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
