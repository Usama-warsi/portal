
@php
    $favicon = isset($company_settings['favicon']) ? $company_settings['favicon'] : (isset($admin_settings['favicon']) ? $admin_settings['favicon'] : 'uploads/logo/favicon.png');
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('Modules/Rotas/Resources/assets/css/css.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="icon" href="{{ get_file( $favicon )}}{{'?'.time()}}" type="image/x-icon" />

    <style>
        [dir="rtl"] .dash-sidebar {
            left: auto !important;
        }

        [dir="rtl"] .dash-header {
            left: 0;
            right: 280px;
        }

        [dir="rtl"] .dash-header:not(.transprent-bg) .header-wrapper {
            padding: 0 0 0 30px;
        }

        [dir="rtl"] .dash-header:not(.transprent-bg):not(.dash-mob-header) ~ .dash-container {
            margin-left: 0px;
        }

        [dir="rtl"] .me-auto.dash-mob-drp {
            margin-right: 10px !important;
        }

        [dir="rtl"] .me-auto {
            margin-left: 10px !important;
        }
    </style>


    <style type="text/css">
        .text-white { color: #fff; }
        table { width: 97%; }
        table,
        th,
        td {
            border: 1px solid rgba(0, 0, 0, 0);
            border-collapse: collapse;
        }
        th,
        td {
            padding: 15px;
            text-align: left;
        }
        /* #t01 tr:nth-child(even) { background-color: #eee; }
        #t01 tr:nth-child(odd) { background-color: #fff; } */
        #t01 th {
            background-color: #051C4B;
            color: white;
            font-size: 13px;
        }
        .m0 { margin: 0px; }
        .mb5 { margin-bottom: 5px; }
        .mb10 { margin-bottom: 10px; }
        tr.dsads td{
            background-color: #000;
            padding: 0px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

    </style>
</head>
@php 
    $company_settings = getCompanyAllSetting(); 
@endphp
<body class="overflow-x-hidden">
    <div class="container" id="boxes">
        <div id="app" class="content">
            <div style="width:1000px;margin-left: auto;margin-right: auto; background-color: #dddddd26; height: 98vh;">
                <div class="bg-primary" style="padding: 20px 25px;">
                    <div class="bg-primary" style="padding: 20px 25px;display: inline-block;">
                        {{-- <img src="{{ asset('storage/uploads/logo/logo.png') }}" style="width: 100%; display: inline-block; float: left; max-width: 150px;"> --}}
                        <img src="{{ get_file(sidebar_logo()) }}{{'?'.time()}}" alt="" class="logo logo-sm" style="width: 100%; display: inline-block; float: left; max-width: 150px;"/>
                    </div>
                    <div style="display: inline-block; float: right; color: white; width: 250px; text-align: center;">
                        <h2 class="m0 mb5">{{ (isset($company_settings['company_name'])) ? $company_settings['company_name'] : '' }}</h2>
                        <div class="mb10"> {{ (isset($company_settings['company_address'])) ? $company_settings['company_address'] : '' }} </div>
                        {{-- <div> {{ __('week').' '.date("W Y", strtotime($week_date[0])) }} </div> --}}
                    </div>
                    <span style="clear: both; display: block;"></span>
                </div>
                <table id="t01" style="margin: 20px 15px;">
                    <thead>
                        <tr>
                            <th class="bg-primar">{{ __('Date') }}</th>
                            <th class="bg-primar">{{ __('Employee') }}</th>
                            <th class="bg-primar">{{ __('Time In') }}</th>
                            <th class="bg-primar">{{ __('Time Out') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if (!empty($users))
                            @foreach ($week_date as $date)
                                @foreach ($users as $item)
                                    {!! \Modules\Rotas\Entities\Rota::getdaterotasreport($date, $item['id']) !!}
                                @endforeach
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">
                                    <h2>
                                        <center> {{ __('No Data Found') }} </center>
                                    </h2>
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- <script src="{{asset('Modules/Rotas/Resources/js/print/jquery.min.js')}}"></script> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <script src="{{asset('Modules/Rotas/Resources/js/print/moment.min.js')}}"></script>
    <script src="{{asset('Modules/Rotas/Resources/js/print/pdfmake.min.js')}}"></script>
    <script src="{{asset('Modules/Rotas/Resources/js/print/vfs_fonts.js')}}"></script>
    <script src="{{asset('Modules/Rotas/Resources/js/print/tableExport.js')}}"></script>
    <script>
        $(document).ready(function() {
            var name = 'Rotas-' + moment().format("YYYYMMDDhhmmssA");
            $('#t01').tableExport({

                type: 'pdf',
                fileName: name,
                pdfmake: {
                    enabled: true,
                    docDefinition: {
                        pageOrientation: 'landscape'
                    }
                },
                onAfterSaveToFile: function(data, fileName) {
                    if (fileName != '') {
                        setTimeout(() => {
                            window.history.back();
                        }, 1000);
                    }
                }
            });
        });
    </script>
</body>
</html>
