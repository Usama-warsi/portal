@php
    $favicon = isset($company_settings['favicon']) ? $company_settings['favicon'] : (isset($admin_settings['favicon']) ? $admin_settings['favicon'] : 'uploads/logo/favicon.png');
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
      <!-- Primary Meta Tags -->
      <meta name="title" content="{{(!empty($store->meta_keywords)?$store->meta_keywords:'')}}">
      <meta name="description" content="{{(!empty($store->meta_description)?$store->meta_description:'')}}">

      <!-- Open Graph / Facebook -->
      <meta property="og:type" content="website">
      <meta property="og:url" content="{{env('APP_URL')}}">
      <meta property="og:title" content="{{(!empty($store->meta_keywords)?$store->meta_keywords:'')}}">
      <meta property="og:description" content="{{(!empty($store->meta_description)?$store->meta_description:'')}}">
      @if(!empty($store->meta_image))
      <meta property="og:image" content="{{get_file($store->meta_image)}}">
      @endif
      <!-- Twitter -->
      <meta property="twitter:card" content="summary_large_image">
      <meta property="twitter:url" content="{{env('APP_URL')}}">
      <meta property="twitter:title" content="{{(!empty($store->meta_keywords)?$store->meta_keywords:'')}}">
      <meta property="twitter:description" content="{{(!empty($store->meta_description)?$store->meta_description:'')}}">
      @if(!empty($store->meta_image))
          <meta property="twitter:image" content="{{get_file($store->meta_image)}}">
      @else
          <meta property="twitter:image" content="{{asset('Modules/LMS/Resources/assets/themes/theme3/images/bachelor-of-sci.jpg')}}">
      @endif
    @yield('meta-data')

    <title>@yield('page-title')</title>

    <!-- Preloader -->
    <style>

    </style>
    @if(env('SITE_RTL')=='on')
        <link rel="stylesheet" href="{{ asset('css/bootstrap-rtl.css') }}">
    @endif
    @stack('css-page')
<!-- Favicon -->
    <link rel="icon" href="{{ check_file($favicon) ? get_file($favicon) : get_file('uploads/logo/favicon.png')  }}{{'?'.time()}}" type="image/x-icon" />
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{asset('Modules/LMS/Resources/assets/css/jquery.fancybox.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('Modules/LMS/Resources/assets/css/all.min.css')}}">
    <!-- Quick CSS -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome 5 -->
    <link rel="stylesheet" href="{{asset('Modules/LMS/Resources/assets/css/all.min.css')}}"><!-- Page CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/plugins/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('js/swiper/dist/css/swiper.min.css')}}">
    <!-- site CSS -->
    <link rel="stylesheet" href="{{asset('Modules/LMS/Resources/assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('Modules/LMS/Resources/assets/css/bootstrap.min.3.3.5.css')}}">


    @if(!empty($store->store_theme))
        <link rel="stylesheet" href="{{asset('Modules/LMS/Resources/assets/themes/theme2/css/'.$store->store_theme)}}" id="stylesheet">
    @else
        <link rel="stylesheet" href="{{asset('Modules/LMS/Resources/assets/themes/theme2/css/dark-blue-color.css')}}" id="stylesheet">
    @endif

    <link rel="stylesheet" href="{{ asset('Modules/LMS/Resources/assets/themes/theme2/css/responsive.css') }}">

    @if ($store->enable_pwa == 'on')
        <link rel="manifest" href="{{ get_file('uploads/theme_app/lms_' . $store->id . '/manifest.json') }}" />
    @endif
    @if (!empty($store->pwa_store($store->slug)->theme_color))
        <meta name="theme-color" content="{{ $store->pwa_store($store->slug)->theme_color }}" />
    @endif
    @if (!empty($store->pwa_store($store->slug)->background_color))
        <meta name="apple-mobile-web-app-status-bar"
            content="{{ $store->pwa_store($store->slug)->background_color }}" />
    @endif

</head>
