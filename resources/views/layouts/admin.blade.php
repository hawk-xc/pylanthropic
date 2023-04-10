<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="Content-Language" content="en" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{ (isset($second_title) && !empty($second_title) ? $second_title.' - ' : '').($title ?? env('APP_NAME')) }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
        <meta name="robots" content="noindex, nofollow">
        <meta name="googlebot" content="noindex, nofollow">
        <meta name="googlebot-news" content="noindex, nofollow">

        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('site.webmanifest') }}">

        <link href="{{ asset('admin/main.css') }}" rel="stylesheet" />
        
        @yield('css_plugins')
        @yield('css_inline')
    </head>

    <body>
        <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
            
            <!-- Main Sidebar Container -->
            @include('layouts.admin.navbar')

            <div class="app-main">

                <!-- Main Sidebar Container -->
                @include('layouts.admin.sidebar')

                <div class="app-main__outer">
                    <div class="app-main__inner">

                        @yield('content')
                        
                    </div>

                    <!-- Main Sidebar Container -->
                    @include('layouts.admin.footer')
                    
                </div>
            </div>
        </div>
        
        @yield('content_modal')

        <div class="app-drawer-overlay d-none animated fadeIn"></div>
        <script type="text/javascript" src="{{ asset('admin') }}/scripts/main.js"></script>

        @yield('js_plugins')
        @yield('js_inline')
    </body>

</html>
