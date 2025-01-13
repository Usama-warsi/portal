
@php
    $favicon = isset($company_settings['favicon']) ? $company_settings['favicon'] : (isset($admin_settings['favicon']) ? $admin_settings['favicon'] : 'uploads/logo/favicon.png');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Lato&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('Modules/Rotas/Resources/assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Modules/Rotas/Resources/assets/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="icon" href="{{ get_file($favicon)}}{{'?'.time()}}" type="image/x-icon" />

    <style type="text/css">
        .text-white {
            color: #fff;
        }

        table#t01 {
            width: 97%;
        }

        table#t01,
        #t01 th,
        #t01 td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        #t01 th,
        #t01 td {
            padding: 15px;
            text-align: left;
        }

        #t01 tr:nth-child(even) {
            background-color: #eee;
        }

        #t01 tr:nth-child(odd) {
            background-color: #fff;
        }

        #t01 th {
            background-color: #051C4B;
            color: white;
            font-size: 13px;
        }


        .form-control {
            display: block;
            width: 90%;
            height: 10px;

            padding: 0.75rem 1.25rem;
            font-size: 1rem;
            /* font-size: 13px; */
            font-weight: 400;
            line-height: 1.5;
            color: #8492A6;
            background-color: #FFF;
            background-clip: padding-box;
            border: 1px solid #E0E6ED;
            border-radius: 0.25rem;
            box-shadow: inset 0 1px 1px rgba(31, 45, 61, 0.075);
            transition: all 0.2s ease;
        }

        .form-control-label {
            color: #3C4858;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .form_box {
            background-color: #fff;
            width: 50%;
            box-shadow: 0 0 3px 2px #dddddd3d;
            padding: 20px;
            display: block;
            margin: 60px auto 0;
        }

        .btn {
            color: #FFF;
            background-color: #051C4B;
            border-color: #051C4B;
            box-shadow: inset 0 1px 0 rgb(255 255 255 / 15%);
            border-radius: 50rem !important;
            margin-right: auto !important;
            position: relative;
            transition: all 0.2s ease;
            border: 0;
            padding: 10px 30px;
            margin-top: 10px;
        }


        .round_print_btn {
            border: 1px solid #051c4b;
            padding: 6px 14px;
            border-radius: 5px;
            background-color: #051c4b;
            color: #fff;
            cursor: pointer;
        }

        .clearfix::after {
            display: block;
            clear: both;
            content: "";
        }

        .float-left {
            float: left;
        }

        .float-right {
            float: right;
        }

        .clearfix {
            clear: both;
            display: block;
        }

        .lr-btn {
            color: #fff;
            background-color: #051c4b;
            height: 20px;
            width: 20px;
            text-align: center;
            line-height: 20px;
            border-radius: 2px;
            cursor: pointer;
        }

    </style>
</head>

<body class="overflow-x-hidden">
    <div class="container" id="boxes">
        <div id="app" class="content">
            <div style="width:1000px;margin-left: auto;margin-right: auto; background-color: #dddddd26; height: 98vh;">
                <div class="bg-primary" style="padding: 20px 0;">
                    <center><img src="{{ get_file(sidebar_logo()) }}{{'?'.time()}}" alt="" style="max-width: 150px"/></center>
                </div>

                @if ($date_sts == 0)
                    <h2 style="text-align: center; padding-top: 20px; ">{{ $msg }}</h2>
                @else
                    @if ($password_sts == 1)
                        <div class="form_box_div">
                            <div class="form_box">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Password') }}</label>
                                    <input type="password" name="confirm_password"
                                        placeholder="{{ __('Enter Password') }}" required class='form-control mt-10'>
                                    <input type="hidden" value="{{ $slug }}" name="slug">
                                </div>
                                <button class="btn login-pass bg-primary"> {{ __('Submit') }} </button>
                            </div>
                        </div>
                        <div class="ajax_search" style="display: none;">
                            <div>
                                <div class="clearfix mt-10">
                                    <div class="float-left">
                                        <input type="text" id="date_duration" value="" placeholder="{{ __('Select Date') }}"/>
                                        <input type="hidden" value="{{ $week_date[0] }}" class="start_date">
                                        <input type="hidden" value="{{ $week_date[6] }}" class="end_date">
                                        {{-- <input type="hidden" value="{{ $location->id }}" class="location_id"> --}}
                                        <input type="hidden" value="{{ $user_array }}" class="user_array">
                                    </div>
                                    <div class="float-right">
                                        <button class="round_print_btn bg-primary   "> {{ __('Print') }} </button>
                                    </div>
                                </div>
                            </div>
                            <span class="clearfix"></span>

                            <table id="t01" style="margin: 20px 15px;">
                            </table>
                        </div>
                    @endif
                    @if ($password_sts == 0)
                        <div class="ajax_search">
                            <div>
                                <div class="clearfix mt-10">
                                    <div class="float-left">
                                        {{-- <span style="padding: 0 20px 0 0; font-size: 20px;">{{ $location->name }}</span> --}}
                                        <input type="hidden" value="0" class="week_no">

                                        <input type="text" id="date_duration" value="" placeholder="{{ __('Select Date') }}"/>
                                        <input type="hidden" value="{{ $week_date[0] }}" class="start_date">
                                        <input type="hidden" value="{{ $week_date[6] }}" class="end_date">
                                        {{-- <input type="hidden" value="{{ $designation->id }}" class="designation_id"> --}}
                                        <input type="hidden" value="{{ $user_array }}" class="user_array">
                                    </div>
                                    <div class="float-right">
                                        <button class="round_print_btn bg-primary"> {{ __('Print') }} </button>
                                    </div>
                                </div>
                            </div>
                            <span class="clearfix"></span>

                            <table id="t01" style="margin: 20px 15px;">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        @foreach ($week_date as $date)
                                            <th>{{ $date }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($users_name))
                                        @foreach ($users_name as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td>{!! \Modules\Rotas\Entities\Rota::getdaterotas($week_date[0], $item->id, 0) !!}</td>
                                                <td>{!! \Modules\Rotas\Entities\Rota::getdaterotas($week_date[1], $item->id, 0) !!}</td>
                                                <td>{!! \Modules\Rotas\Entities\Rota::getdaterotas($week_date[2], $item->id, 0) !!}</td>
                                                <td>{!! \Modules\Rotas\Entities\Rota::getdaterotas($week_date[3], $item->id, 0) !!}</td>
                                                <td>{!! \Modules\Rotas\Entities\Rota::getdaterotas($week_date[4], $item->id, 0) !!}</td>
                                                <td>{!! \Modules\Rotas\Entities\Rota::getdaterotas($week_date[5], $item->id, 0) !!}</td>
                                                <td>{!! \Modules\Rotas\Entities\Rota::getdaterotas($week_date[6], $item->id, 0) !!}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8">
                                                <h2>
                                                    <center> {{ __('No Data Found') }} </center>
                                                </h2>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                    @endif
                @endif
            </div>

        </div>
    </div>
    </div>
    <script src="{{ asset('Modules/Rotas/Resources/js/moment.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('Modules/Rotas/Resources/js/html2pdf.bundle.min.js') }}"></script>
    <script src="{{ asset('Modules/Rotas/Resources/js/daterangepicker.js') }}"></script>

    <script>
        $(function() {
            function cb(start, end) {
                $('#date_duration').val(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
                $('.start_date').val(start.format('YYYY-MM-DD'));
                $('.end_date').val(end.format('YYYY-MM-DD'));
                daterange();
            }

            $('#date_duration').daterangepicker({
                autoApply: true,
                autoclose: true,
                autoUpdateInput: false,
                locale: {
                    format: 'MMM D, YY hh:mm A',
                    applyLabel: "Apply",
                    cancelLabel: "Cancel",
                    fromLabel: "From",
                    toLabel: "To",
                    daysOfWeek: [
                        'Sun',
                        'Mon',
                        'Tue',
                        'Wed',
                        'Thu',
                        'Fri',
                        'Sat',
                    ],
                    monthNames: [
                        'January',
                        'February',
                        'March',
                        'April',
                        'May',
                        'June',
                        'July',
                        'August',
                        'September',
                        'October',
                        'November',
                        'December'
                    ],
                }
            }, cb);
        });
    </script>

    <script>
        $(document).on('click', '.login-pass', function() {
            var confirm_password = $('input[name="confirm_password"]').val();
            var slug = $('input[name="slug"]').val();
            var data = {
                "_token": "{{ csrf_token() }}",
                "confirm_password": confirm_password,
                "slug": slug,
            }

            $.ajax({
                url: '{{ route('slug.match') }}',
                method: 'post',
                data: data,
                success: function(data) {
                    if (data.status == 'error') {
                        alert('Password does not match');
                    }
                    if (data.status == 'success') {
                        // $('.lr-btn').trigger('click');
                        $('.ajax_search').show();
                        $('.form_box_div').remove();
                        daterange();
                    }
                }
            });
        });

        $(document).on('click', '.round_print_btn', function() {
            var element = document.getElementById('boxes');
            $('.round_print_btn').hide();

            setTimeout(function() {
                $('.round_print_btn').show();
            }, 1000);

            var opt = {
                filename: '#Rotas00010',
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A3'
                }
            };
            html2pdf().set(opt).from(element).save().then();
        });

        function daterange() {
            var s_date = $('.start_date').val();
            var e_date = $('.end_date').val();
            var week = $('.week_no').val();
            var location_id = $('.location_id').val();
            var role_id = $('.role_id').val();
            var user_array = $('.user_array').val();
            if ($(this).hasClass('l-btn')) {
                $('.week_no').attr('value', parseInt(week) - 1);
            }
            if ($(this).hasClass('r-btn')) {
                $('.week_no').attr('value', parseInt(week) + 1);
            }

            var week = $('.week_no').val();

            var data = {
                "_token": "{{ csrf_token() }}",
                "week": week,
                "location_id": location_id,
                "role_id": role_id,
                "user_array": user_array,
                "s_date" : s_date,
                "e_date" : e_date,
            }

            $.ajax({
                url: '{{ route('rota.date.change') }}',
                method: 'post',
                data: data,
                success: function(data) {
                    if (data.status == 1) {
                        $('#t01').html(data.msg);
                        $('.date_title').html(data.date_title);
                    }
                    if (data.status == 0) {

                        if(data.msg != '') {
                            alert(data.msg);
                        } else {
                            alert('{{ __('Something went wrong. please try again later.') }}');
                        }
                    }
                }
            });
        }
    </script>
</body>

</html>

