@php

    if(!empty(session()->get('lang')))
    {
        $currantLang = session()->get('lang');
    }else{
        $currantLang = $store->lang;
    }
    \App::setLocale($currantLang);
    $languages=languages();
    $lang = \App::getLocale('lang');
    $company_settings = getCompanyAllSetting($store->created_by,$store->workspace_id);
    $favicon = isset($company_settings['favicon']) ? $company_settings['favicon'] : (isset($admin_settings['favicon']) ? $admin_settings['favicon'] : 'uploads/logo/favicon.png');
@endphp
<!DOCTYPE html>
<html class="">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Dashboard Template Description" />
    <meta name="keywords" content="Dashboard Template" />
    <meta name="author" content="Rajodiya Infotech" />

    <!-- Favicon icon -->
    <title>{{__('User Order')}} - {{($store->tagline) ?  $store->tagline : env('APP_NAME', ucfirst($store->name))}}</title>
    <link rel="icon" href="{{ check_file($favicon) ? get_file($favicon) : get_file('uploads/logo/favicon.png')  }}{{'?'.time()}}" type="image/x-icon" />


    <link rel="stylesheet" href="{{asset('Modules/LMS/Resources/assets/css/jquery.fancybox.min.css')}}">

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css')}}">
    <link rel="stylesheet" href="{{ asset('Modules/LMS/Resources/assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Modules/LMS/Resources/assets/css/select2.min.css') }}">
    <!-- vendor css -->

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css')}}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/landing.css')}}"> --}}

    <link rel="stylesheet" href="{{asset('assets/css/plugins/bootstrap-switch-button.min.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css')}}">
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('Modules/LMS/Resources/assets/css/custom.css') }}">


    @if (env('SITE_RTL') == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
    @else
        @if( isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
            <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
        @else
            <link rel="stylesheet" href="{{ asset('Modules/LMS/Resources/assets/css/style.css') }}" id="main-style-link">
        @endif
    @endif
  </head>

  <body class="">
    <!-- [ auth-signup ] start -->
    <div class="auth-wrapper auth-v3">

      <div class="auth-content">
        <nav class="navbar navbar-expand-md navbar-light default">
          <div class="container-fluid pe-2">
            <a class="navbar-brand mr-lg-4 pt-0" href="{{route('store.slug',$store->slug)}}">
                @if(!empty($store->logo))
                    <img alt="Image placeholder" src="{{get_file($store->logo.'?'. time())}}" id="navbar-logo" style="height: 40px;">
                @else
                    <img alt="Image placeholder" src="{{asset('Modules/LMS/Resources/assets/image/logo.png')}}" id="navbar-logo" style="height: 40px;">
                @endif
            </a>
            <div class="d-lg-inline-block">
                <span class="navbar-text mr-3 pt-3 text-lg align-middle">{{Str::upper($lang)}}</span>
            </div>

            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
              <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <li class="nav-item">
                    <select name="language" id="language" class="btn btn-sm btn-primary custom_btn ms-2 me-2" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                        @foreach($languages as $code => $language)
                            <option @if($currantLang == $code) selected @endif value="{{route('change.languagestore',[$store->slug,$code])}}">{{ucfirst($language)}}</option>
                        @endforeach
                    </select>
                </li>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <div class="">
            <div class="mt-4">
                <header class="mb-4">
                    <div class=" d-flex justify-content-between">
                        <div class="row float-left">
                            <div class=" col-auto">
                                <div class="row align-items-center ">
                                    <h4 class="">{{__('Your Order Details')}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="#" onclick="saveAsPDF();" data-toggle="tooltip" data-title="{{__('Download')}}" id="download-buttons" class="p-0 btn btn-sm btn-white btn-icon rounded-pill btn-primary">
                                <span class="btn-inner--icon text-white"><i class="fa fa-print"></i></span>
                                <span class="btn-inner--text text-white">{{__('Print')}}</span>
                            </a>
                        </div>
                    </div>
                </header>
                <div id="printableArea">

                    <div class="row">
                        <div class="col-6 pb-2 invoice_logo"></div>
                        <div class="col-lg-8">
                            <div class="card card-fluid">
                                <div class="card-header ">
                                    <h6 class="mb-0">{{__('Items from Order')}} {{$order->order_id}}</h6>
                                </div>
                                <div class="card-body">
                                    <h6 class="mb-4">{{__('Shipping Information')}}</h6>
                                    <address class="mb-0 text-sm">
                                        <dl class="row mt-4 align-items-center">
                                            <dt class="col-sm-3 h6 text-sm">{{__('Name')}}</dt>
                                            <dd class="col-sm-9 text-sm"> {{$student_data->name}}</dd>
                                            <dt class="col-sm-3 h6 text-sm">{{__('E-mail')}}</dt>
                                            <dd class="col-sm-9 text-sm">{{$student_data->email}}</dd>
                                        </dl>
                                    </address>
                                </div>
                                <div class="card-footer table-border-style">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead class="thead-light">
                                                <tr class="border-top-0">
                                                    <th>{{__('Item')}}</th>
                                                    <th>{{__('Price')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $sub_tax = 0;
                                                    $total = 0;
                                                @endphp
                                                @foreach($order_products as $key=>$product)
                                                    <tr>
                                                        <td class="total">
                                                        <span class="h6 text-sm">
                                                            @if(isset($product->product_name))
                                                                {{$product->product_name}}
                                                            @else
                                                                {{$product->name}}
                                                            @endif
                                                        </span>
                                                            @php
                                                                $total_tax = 0
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            {{ currency_format_with_sym($product->price,$store->created_by,$store->workspace_id)}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card card-fluid">
                                <div class="card-header border-0">
                                    <h6 class="mb-0">{{__('Items from Order '). $order->order_id}}</h6>
                                </div>
                                <div class="card-body table-border-style">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>{{__('Description')}}</th>
                                                    <th>{{__('Price')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{__('Grand Total')}} :</td>
                                                    <td>{{ currency_format_with_sym($sub_total,$store->created_by,$store->workspace_id)}}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Apply Coupon')}} :</th>
                                                    <th>{{ (!empty($order->discount_price))?$order->discount_price: currency_format_with_sym(0,$store->created_by,$store->workspace_id)}}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Total')}} :</th>
                                                    <th>{{  currency_format_with_sym($grand_total,$store->created_by,$store->workspace_id) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Payment Type')}} :</th>
                                                    <th>{{ $order['payment_type'] }}</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


      </div>
    </div>
    <div id="invoice_logo_img" class="d-none">
        <div class="row align-items-center py-2 px-3">
            @if(!empty($store->invoice_logo))
                <img alt="Image placeholder" src="{{get_file($store->invoice_logo)}}" id="navbar-logo" style="width: 300px;">
            @else
                <img alt="Image placeholder" src="{{asset('Modules/LMS/Resources/assets/image/invoice_logo.png')}}" id="navbar-logo" style="width: 300px;">
            @endif
        </div>
    </div>
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/custom.js')}}"></script>
    <script src="{{ asset('Modules/LMS/Resources/assets/js/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
    <script src="{{asset('js/swiper/dist/js/swiper.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>


@php
    $store_settings = \Modules\LMS\Entities\Store::getStore($store->slug);
@endphp

<script async src="https://www.googletagmanager.com/gtag/js?id={{$store_settings->google_analytic}}"></script>

{!! $store_settings->storejs !!}

<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', '{{ $store_settings->google_analytic }}');
</script>

<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '{{$store_settings->fbpixel_code}}');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=0000&ev=PageView&noscript={{$store_settings->fbpixel_code}}"
/></noscript>
<script>
    var filename = $('#filesname').val();

    function saveAsPDF() {
        var element = document.getElementById('printableArea');
        var logo_html = $('#invoice_logo_img').html();
        $('.invoice_logo').empty();
        $('.invoice_logo').html(logo_html);

        var opt = {
            margin: 0.3,
            filename: filename,
            image: {type: 'jpeg', quality: 1},
            html2canvas: {scale: 4, dpi: 72, letterRendering: true},
            jsPDF: {unit: 'in', format: 'A2'}
        };

        html2pdf().set(opt).from(element).save();
        setTimeout(function () {
            $('.invoice_logo').empty();
        }, 0);
    }
</script>
  </body>
</html>
