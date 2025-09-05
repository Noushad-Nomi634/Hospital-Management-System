
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark-theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Body Experts</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ URL::asset('build/images/favicon-32x32.png') }}" type="image/png">

    {{-- Common CSS & page-specific CSS --}}
    @include('layouts.head-css')
    @stack('css') {{-- Page-specific CSS from @push('css') --}}
    <link rel="stylesheet" href="{{ URL::asset('build/plugins/notifications/css/lobibox.min.css') }}">

 <!-- 👇 Custom Dashboard CSS -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

    
</head>

<body>

    {{-- Top navigation bar --}}
    @include('layouts.topbar')

    {{-- Sidebar menu --}}
    @include('layouts.sidebar')

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            {{-- Page Content --}}
            @yield('content')
        </div>
    </main>

    <!--start overlay-->
    <div class="overlay btn-toggle"></div>
    <!--end overlay-->

    {{-- Extra layout elements --}}
    @include('layouts.extra')

    {{-- Common JS scripts --}}
    @include('layouts.common-scripts')

    {{-- Page-specific scripts from @push('scripts') --}}
    @stack('script')
</body>

</html>
