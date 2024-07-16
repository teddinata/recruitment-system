<!doctype html>
<html class="no-js" lang="zxx">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
         <title>BukaLowongan - Viscus</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="manifest" href="site.webmanifest">
		<link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/assets/img/favicon.ico') }}">

		<!-- CSS here -->
        @include('layouts.partials.styles')
            @stack('styles')
   </head>

   <body>
    <!-- Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="{{ asset('frontend-new/img/viscus.png') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader Start -->

    <main>
        @include('layouts.partials.navbar')
        @yield('content')

        @include('sweetalert::alert')

    </main>

    @include('layouts.partials.footer')


    <!-- JS here -->
    @include('layouts.partials.scripts')
    @stack('scripts')



    </body>
</html>
