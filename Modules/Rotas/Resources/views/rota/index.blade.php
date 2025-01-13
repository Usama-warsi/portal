@extends('layouts.main')
@php
    $company_settings = getCompanyAllSetting();
@endphp
@section('page-title')
    {{ __('Manage Rota') }}
@endsection
@section('page-breadcrumb')
    {{ __('Rota') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Rotas/Resources/assets/css/custom.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body">
                    <div class="row ">
                        <div class="form-group col-md-2">
                            {{ Form::label('branch_id', isset($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch'), ['class' => 'form-label']) }}<span
                                class="text-danger pl-1">*</span>
                            {{ Form::select('branch_id', $branch, null, ['class' => 'form-control hrm_branch_name_id', 'required' => 'required', 'placeholder' => 'Select Branch']) }}
                        </div>
                        <div class="form-group col-md-2">
                            {{ Form::label('department_id', isset($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department'), ['class' => 'form-label rotas_location_change']) }}<span
                                class="text-danger pl-1">*</span>
                            {{ Form::select('department_id', $department, null, ['class' => 'form-control hrm_department_name_id', 'required' => 'required', 'placeholder' => 'Select Department']) }}
                        </div>
                        <div class="form-group col-md-2">
                            {{ Form::label('designation_id', isset($company_settings['hrm_designation_name']) ? $company_settings['hrm_designation_name'] : __('Designation'), ['class' => 'form-label']) }}<span
                                class="text-danger pl-1">*</span>
                            {{ Form::select('designation_id', [], null, ['class' => 'form-control rotas_location_change', 'id' => 'designation_id', 'required' => 'required', 'placeholder' => 'Select Designation']) }}
                        </div>
                        <div class="form-group col-md-3 my-auto">
                            <div class="col-sm-12 col-md-auto  mx-2">
                                <i class="fa fa-caret-left weak-prev-left weak-prev weak_go bg-primary text-white"></i>
                                &nbsp;<span
                                    class="weak_go_html weak_go_html text-primary"><b>{{ $week_date[0] . ' - ' . $week_date[6] }}</b></span>&nbsp;
                                <i class="fa fa-caret-right weak-prev-left weak-left weak_go bg-primary text-white"></i>
                                <input type="hidden" data-start="{{ $week_date['week_start'] }}"
                                    data-end="{{ $week_date['week_end'] }}" class="week_last_daye">
                                <input type="hidden" value="{{ $temp_week }}" data-created-by="{{ $created_by }}"
                                    class="week_add_sub">
                                <input type="hidden" value="{{ Auth::user()->mode }}" class="mode">
                            </div>
                        </div>
                        <div class="form-group col-md-3 my-auto ">
                            <div class="rotas_filter_main_div">

                                <div class="btn-group card-option">
                                    @if (Laratrust::hasPermission('rota publish-week') || Laratrust::hasPermission('rota unpublish-week'))
                                        <button type="button" class=" btn btn-sm btn-primary btn-icon m-1 publish_shifs"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="feather icon-check" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Publish Rotas') }}"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                            @permission('rota publish-week')
                                                <a href="#" class="dropdown-item" id="publish_week"
                                                    onclick='publish_week()'>
                                                    <span>{{ __(' Publish Week ') }}</span>
                                                </a>
                                            @endpermission
                                            @permission('rota unpublish-week')
                                                <a href="#" class="dropdown-item hide_rss" id="un_publish_week"
                                                    onclick="un_publish_week()">
                                                    <span>{{ __(' Un-publish Week ') }}</span>
                                                </a>
                                            @endpermission
                                        </div>
                                    @endif
                                </div>

                                <div class="btn-group card-option">
                                    @permission('rota copy-week-shift')
                                        <button type="button" class="btn btn-sm btn-primary btn-icon m-1 publish_shifs"
                                            onclick="alert('{{ __('Drag and drop shift to other designation') }}')">
                                            <i class="feather icon-copy" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __(' Copy Shift ') }}"></i>
                                        </button>
                                    @endpermission
                                </div>

                                <div class="btn-group card-option">
                                    @permission('rota shift-copy')
                                        <button type="button" class="btn btn-sm btn-primary btn-icon m-1 Copy_Week_Shift">
                                            <i class="feather icon-move" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __(' Copy rotas next week ') }}"></i>
                                        </button>
                                    @endpermission
                                </div>

                                <div class="btn-group card-option rotas_filter">
                                    @if (Laratrust::hasPermission('rota hide/show day off') ||
                                            Laratrust::hasPermission('rota hide/show leave') ||
                                            Laratrust::hasPermission('rota hide/show availability') ||
                                            Laratrust::hasPermission('rota clear week') ||
                                            Laratrust::hasPermission('rota day off'))
                                        <button type="button" class="btn btn-sm btn-primary btn-icon m-1"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="feather icon-filter" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Additional Settings') }}"></i>
                                        </button>
                                    @endif
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        @permission('rota hide/show day off')
                                            <a href="#" class="dropdown-item {{ $day_off == 'hide' ? 'hide_rss' : '' }}"
                                                id="hidedayoff">
                                                <span class="span_hide"
                                                    style="{{ $day_off == 'show' ? 'display: none;' : '' }}">{{ __('Show') }}</span>
                                                <span class="span_show"
                                                    style="{{ $day_off == 'hide' ? 'display: none;' : '' }}">{{ __('Hide') }}</span>
                                                {{ __(' Day Off') }}
                                            </a>
                                        @endpermission
                                        @permission('rota hide/show leave')
                                            <a href="#"
                                                class="dropdown-item {{ $leave_display == 'hide' ? 'hide_rss' : '' }}"
                                                id="hideleave">
                                                <span class="span_hide"
                                                    style="{{ $leave_display == 'show' ? 'display: none;' : '' }}">{{ __('Show') }}</span>
                                                <span class="span_show"
                                                    style="{{ $leave_display == 'hide' ? 'display: none;' : '' }}">{{ __('Hide') }}</span>
                                                {{ __(' Leave') }}
                                            </a>
                                        @endpermission
                                        @permission('rota hide/show availability')
                                            <a href="#"
                                                class="dropdown-item {{ $availability_display == 'hide' ? 'hide_rss' : '' }}"
                                                id="hideavailability">
                                                <span class="span_hide"
                                                    style="{{ $availability_display == 'show' ? 'display: none;' : '' }}">{{ __('Show') }}</span>
                                                <span class="span_show"
                                                    style="{{ $availability_display == 'hide' ? 'display: none;' : '' }}">{{ __('Hide') }}</span>
                                                {{ __(' Availability') }}
                                            </a>
                                        @endpermission
                                        @permission('rota clear week')
                                            <a href="#" class="dropdown-item" id="clear_week">
                                                {{ __('Clear Week') }} </a>
                                        @endpermission
                                        @permission('rota day off')
                                            <a href="#" class="dropdown-item hide_rss" id="add_remove_dayeoff">
                                                {{ __('Add / Remove Day Off') }}
                                            </a>
                                        @endpermission
                                    </div>
                                </div>

                                <div class="btn-group card-option">
                                    @permission('rota send mail')
                                        <button type="button" class="btn btn-sm btn-primary btn-icon m-1"
                                            id='send_email_rotas'>
                                            <i class="feather icon-mail" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Send rotas via email') }}"></i>
                                        </button>
                                    @endpermission
                                </div>

                                <div class="btn-group card-option">
                                    <button type="button" class="print_rotas_cls d-none" data-size="lg"
                                        data-ajax-rota="true" data-title="{{ __('Print rotas') }}"
                                        data-url="{{ route('rotas.print_rotas_popup') }}">
                                    </button>
                                    @permission('rota print')
                                        <button type="button" class="btn btn-sm btn-primary btn-icon m-1" id="print_rotas"
                                            data-urls="{{ route('rotas.print_rotas_popup') }}">
                                            <i class="feather icon-printer"
                                                data-urls="{{ route('rotas.print_rotas_popup') }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="{{ __('Print rotas') }}"></i>
                                        </button>
                                    @endpermission

                                </div>

                                <div class="btn-group card-option">
                                    @permission('rota share')
                                        <button type="button" data-url2="{{ route('rotas.share_popup') }}"
                                            class="btn btn-sm btn-primary btn-icon m-1 share_rotas_cls" data-size="md"
                                            data-ajax-rota="false" data-title="{{ __('Share rotas') }}">
                                            <i class="feather icon-share-2" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Share rotas') }}"></i>
                                        </button>
                                    @endpermission

                                </div>
                            </div>

                            <div class="rotas_filter_main_div_responce">
                                <div class="add_remove_dayeoff" style="display: none;">
                                    <span>{{ __('Click a day to set employees day off') }}</span>
                                    &nbsp;
                                    <button type="button"
                                        class="dropdown-toggle btn btn-sm btn-primary btn-icon m-1 dayoff_close"
                                        data-toggel="tooltip" title="{{ __('Back to Rota Builder') }}"><span
                                            class="btn-inner--icon"><i class="fas fa-times"></i></span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Listing -->
        <div class="col-xl-12">
            <div class="mt-4">
                <div class="card">
                    <div class="card-wrapper rotas-timesheet overflow-auto" id="rotas-timesheet">
                        <table class="table work_sheet_table1 mx-2">
                            <thead>
                                <tr class="text-center work_sheet_table">
                                    <th><span>{{ __(date('D', strtotime($week_date[0]))) }}</span><br><span>{{ $week_date[0] }}</span>
                                    </th>
                                    <th><span>{{ __(date('D', strtotime($week_date[1]))) }}</span><br><span>{{ $week_date[1] }}</span>
                                    </th>
                                    <th><span>{{ __(date('D', strtotime($week_date[2]))) }}</span><br><span>{{ $week_date[2] }}</span>
                                    </th>
                                    <th><span>{{ __(date('D', strtotime($week_date[3]))) }}</span><br><span>{{ $week_date[3] }}</span>
                                    </th>
                                    <th><span>{{ __(date('D', strtotime($week_date[4]))) }}</span><br><span>{{ $week_date[4] }}</span>
                                    </th>
                                    <th><span>{{ __(date('D', strtotime($week_date[5]))) }}</span><br><span>{{ $week_date[5] }}</span>
                                    </th>
                                    <th><span>{{ __(date('D', strtotime($week_date[6]))) }}</span><br><span>{{ $week_date[6] }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($employees))
                                    @foreach ($employees as $emp)
                                        <td> {!! \Modules\Rotas\Entities\Rota::getWorkSchedule($emp->id, $temp_week, $emp->designation_id) !!}</td>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8">
                                            <div class="text-center">
                                                <i class="fas fa-map-marker-alt text-primary fs-40"></i>
                                                <h2>{{ __('Opps...') }}</h2>
                                                <h6> {!! __('You must add a designation to your account and assign user <br> before you can start building rotas.') !!} </h6>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>

                            @if (Auth::user()->type == 'company' && !empty($emp))
                                <tfoot class="bt2">
                                    {!! \Modules\Rotas\Entities\Rota::getCompanyWeeklyUserSalary(0, $created_by, $emp->designation_id, 0) !!}
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ 'Modules/Rotas/Resources/js/html2pdf.bundle.min.js' }}"></script>
    <script src="{{ asset('Modules/Rotas/Resources/js/custom.js') }}"></script>
    <script src="{{ asset('Modules/Rotas/Resources/js/moment.min.js') }}"></script>
    <script src="{{ asset('Modules/Rotas/Resources/js/jquery-ui.min.js') }}"></script>
    <script src="https://app.thestaffweb.com/js/jquery-ui.min.js"></script>


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            cache: false,
            complete: function() {
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
    </script>


    <script>
        $(document).ready(function() {
            $(document).on('click', '.weak_go', function() {

                var weak_prev = $(this).hasClass('weak-prev');
                var weak_left = $(this).hasClass('weak-left');
                var week_no = $(this).parent().find('.week_add_sub').val();
                var week_startdate = $(this).parent().find('.week_last_daye').attr('data-start');
                var week_enddate = $(this).parent().find('.week_last_daye').attr('data-end');
                var total_week = $(this).parent().find('.week_add_sub').val();

                if (weak_prev) {
                    week_no = parseInt(week_no) - 1;
                    $(this).parent().find('.week_add_sub').attr('value', week_no);
                    var next_week_startdate = moment(week_startdate);
                    var next_week_startdate_b = next_week_startdate.subtract(week_no, 'week');
                    var next_week_startdate1 = next_week_startdate.format('D MMM YYYY');
                    var next_week_startdate2 = next_week_startdate.format('YYYY/MM/D');

                    var next_week_enddate = moment(week_enddate);
                    var next_week_enddate_b = next_week_enddate.subtract(week_no, 'week');
                    var next_week_enddate1 = next_week_enddate.format('D MMM YYYY');
                    var next_week_enddate2 = next_week_enddate.format('YYYY/MM/D');
                }
                if (weak_left) {
                    week_no = parseInt(week_no) + 1;
                    $(this).parent().find('.week_add_sub').attr('value', week_no);

                    var next_week_startdate = moment(week_startdate);
                    var next_week_startdate_b = next_week_startdate.add(week_no, 'week');
                    var next_week_startdate1 = next_week_startdate.format('D MMM YYYY');
                    var next_week_startdate2 = next_week_startdate.format('YYYY/MM/D');

                    var next_week_enddate = moment(week_enddate);
                    var next_week_enddate_b = next_week_enddate.add(week_no, 'week');
                    var next_week_enddate1 = next_week_enddate.format('D MMM YYYY');
                    var next_week_enddate2 = next_week_enddate.format('YYYY/MM/D');
                }
                //$(this).parent().find('.weak_go_html').html(next_week_startdate1 +' - '+ next_week_enddate1);

                var between = betweenDate(next_week_startdate2, next_week_enddate2);

                var record_hours = '<th></th>';
                var record_hours1 = '';
                $.each(between, function(key, val) {
                    var date = moment(val).format('D');
                    var mon = moment(val).format('MMM');
                    var days = moment(val).format('ddd');

                    var today = [];
                    today.push(date + '/' + mon + '/' + days);

                    record_hours1 += '<th><span>' + days + '</span><br><span>' + date + ' ' + mon +
                        '</span></th>';
                });
                record_hours += record_hours1;

                $(".week_go_table").html(record_hours);
                // $('.work_sheet_table_last thead tr').html(data.thead);
                // $('.work_sheet_table_last tbody').html(data.week_exp);
            });
        })

        function betweenDate(startDt, endDt) {
            var error = ((isDate(endDt)) && (isDate(startDt)) && isValidRange(startDt, endDt)) ? false : true;
            var between = [];
            if (error) console.log('error occured!!!... Please Enter Valid Dates');
            else {
                var currentDate = new Date(startDt),
                    end = new Date(endDt);
                while (currentDate <= end) {
                    between.push(new Date(currentDate));
                    currentDate.setDate(currentDate.getDate() + 1);
                }
            }
            return between;

            function isDate(dateArg) {
                var t = (dateArg instanceof Date) ? dateArg : (new Date(dateArg));
                return !isNaN(t.valueOf());
            }

            function isValidRange(minDate, maxDate) {
                return (new Date(minDate) <= new Date(maxDate));
            }

            function loadConfirm() {
                $('[data-confirm]').each(function() {
                    var me = $(this),
                        me_data = me.data('confirm');

                    me_data = me_data.split("|");
                    me.fireModal({
                        title: me_data[0],
                        body: me_data[1],
                        buttons: [{
                                text: me.data('confirm-text-yes') || 'Yes',
                                class: 'btn btn-sm btn-danger rounded-pill',
                                handler: function() {
                                    eval(me.data('confirm-yes'));
                                }
                            },
                            {
                                text: me.data('confirm-text-cancel') || 'Cancel',
                                class: 'btn btn-sm btn-secondary rounded-pill',
                                handler: function(modal) {
                                    $.destroyModal(modal);
                                    eval(me.data('confirm-no'));
                                }
                            }
                        ]
                    })
                });
            }
        }


        $(document).ready(function() {
            dragdrop();
            leave_show();
            seturl();
            // loadConfirm();

            $(document).on('change', '.test', function() {

                var start_date = $('.week_last_daye').attr('data-start');
                var end_date = $('.week_last_daye').attr('data-end');
                var week = $('.week_add_sub').val();
                // var location_id = $(this).val();
                var designation_id = $('.hrm_designation_name_id').val();

                var department_id = $('.hrm_department_name_id').val();
                var branch_id = $('.hrm_branch_name_id').val();
                // var role_id = $('.rotas_role_change').val();
                var created_by = $('.week_add_sub').attr('data-created-by');
                // if (location_id == null) {
                //     var location_id = 0;
                // }
                var data = {

                    start_date: start_date,
                    end_date: end_date,
                    week: week,
                    created_by: created_by,
                    designation_id: designation_id,
                    department_id: department_id,
                    branch_id: branch_id,
                }


                $.ajax({
                    url: '{{ route('rotas.week_sheet') }}',
                    method: 'post',
                    data: data,
                    success: function(data) {
                        $('.work_sheet_table1').html(data.table);
                        $('.work_sheet_table1 tfoot').html(data.week_exp);
                        $('.weak_go_html').html(data.title);
                        $('.work_sheet_table_last thead tr').html(data.thead);
                        $('.work_sheet_table_last tbody').html(data.week_exp);
                        // loadConfirm();
                        dragdrop();
                        seturl();
                    }
                });
            });


            $(document).on('click', '.share_rotas_cls', function() {
                var user_array = [];
                $(".work_sheet_table1 tbody tr").each(function(propName, index) {
                    var user_id = $(this).attr('data-user-id');
                    user_array[propName] = user_id;
                });

                if ($(".work_sheet_table1 tbody tr").attr('data-user-id') == undefined) {
                    toastrs('Error', '{{ __('Employee not found.') }}', 'error');
                    return;
                }
            });

            $(document).on('click', '#share_rotas', function() {
                var week = $('.week_add_sub').val();
                var designation_id = $('#designation_id').val();
                var created_by = $('.week_add_sub').attr('data-created-by');
                var user_array = [];

                $(".work_sheet_table1 tbody tr").each(function(propName, index) {
                    var user_id = $(this).attr('data-user-id');
                    user_array[propName] = user_id;
                });

                if ($(".work_sheet_table1 tbody tr").attr('data-user-id') == undefined) {
                    toastrs('Error', '{{ __('User not found.') }}', 'error');
                    return;
                }

                var url = $(this).attr('data-urls');
                $(this).parent().find('.share_rotas_cls').attr('data-url', url + '?designation=' +
                    designation_id + '&create_by=' + created_by + '&week=' + week +
                    '&user=' + user_array);
                $('.share_rotas_cls').trigger('click');
            });

            $(document).on('click', '#click_to_copy', function() {
                /* Get the text field */
                var copyText = document.getElementById("click_link");

                $('#click_link').addClass('clickanimation');

                setTimeout(function() {
                    $('#click_link').removeClass('clickanimation');
                }, 1000);

                /* Select the text field */
                copyText.select();
                copyText.setSelectionRange(0, 99999); /* For mobile devices */

                /* Copy the text inside the text field */
                document.execCommand("copy");

                // clickanimation
                var msg = '{{ __('Link copied successfully.') }}';
                var msg123 = '{{ __('Copied the text: ') }}' + copyText.value;

                /* Alert the copied text */
                toastrs('Success', msg, 'success');
            });

            $(document).on('click', '.create_link', function() {
                var designation = $('input[name="designation_id"]').val();
                var role_id = $('input[name="role_id"]').val();
                var create_by = $('input[name="create_by"]').val();
                var week = $('input[name="week"]').val();
                var user_array = $('input[name="user_array"]').val();
                var share_password = $('input[name="share_password"]').val();
                var expiry_date = $('input[name="expiry_date"]').val();

                var data = {
                    designation: designation,
                    week: week,
                    create_by: create_by,
                    share_password: share_password,
                    expiry_date: expiry_date,
                    user_array: user_array,

                }

                $.ajax({
                    url: '{{ route('rotas.share_rotas_link') }}',
                    method: 'post',
                    data: data,
                    success: function(data) {
                        if (data.status == 'success') {
                            $("#click_link").attr('value', data.message);
                            toastrs('Success', "{{ __('Link created successfully.') }}",
                                'success');
                        } else {
                            toastrs('Error', '{{ __('Something went wrong.') }}',
                                'error');
                        }

                        $('#copy_box').show();
                    }
                });

            });

            $(document).on('click', '.weak_go', function() {
                var start_date = $('.week_last_daye').attr('data-start');
                var end_date = $('.week_last_daye').attr('data-end');
                var week = $('.week_add_sub').val();
                var designation_id = $('#designation_id').val();;
                var created_by = $('.week_add_sub').attr('data-created-by');
                if (designation_id == null) {
                    var designation_id = 0;
                }
                var data = {
                    start_date: start_date,
                    end_date: end_date,
                    week: week,
                    designation_id: designation_id,
                    created_by: created_by
                }

                $.ajax({
                    url: '{{ route('rotas.week_sheet') }}',
                    method: 'post',
                    data: data,
                    success: function(data) {
                        $('.work_sheet_table1').html(data.table);
                        $('.work_sheet_table1 tfoot').html(data.week_exp);
                        $('.weak_go_html').html(data.title);
                        $('.work_sheet_table_last thead tr').html(data.thead);
                        $('.work_sheet_table_last tbody').html(data.week_exp);
                        $('[data-toggle="tooltip"]').tooltip();
                        // loadConfirm();
                        dragdrop();
                        leave_show();
                        seturl();
                    }
                });
            });

            $(document).on('click', '#print_rotas', function() {
                var week = $('.week_add_sub').val();
                var designation_id = $('#designation_id').val();
                var created_by = $('.week_add_sub').attr('data-created-by');
                var user_array = [];
                $(".work_sheet_table1 tbody tr").each(function(propName, index) {
                    var employee_id = $(this).attr('data-user-id');
                    user_array[propName] = employee_id;
                });

                if ($(".work_sheet_table1 tbody tr").attr('data-user-id') == undefined) {
                    toastrs('Error', '{{ __('User not found.') }}', 'error');
                    return;
                }

                var url = $(this).attr('data-urls');
                $(this).parent().find('.print_rotas_cls').attr('data-url', url + '? designation=' +
                    designation_id + '&create_by=' + created_by + '&week=' +
                    week +
                    '')
                $('.print_rotas_cls').trigger('click');
            });



            $(document).on('change', '.rotas_location_change', function() {

                var start_date = $('.week_last_daye').attr('data-start');
                var end_date = $('.week_last_daye').attr('data-end');
                var week = $('.week_add_sub').val();
                var designation_id = $(this).val();

                var created_by = $('.week_add_sub').attr('data-created-by');
                if (designation_id == null) {
                    var designation_id = 0;
                }

                var data = {
                    start_date: start_date,
                    end_date: end_date,
                    week: week,
                    created_by: created_by,
                    designation_id: designation_id,
                }

                $.ajax({
                    url: '{{ route('rotas.week_sheet') }}',
                    method: 'post',
                    data: data,
                    success: function(data) {
                        $('.work_sheet_table1').html(data.table);
                        $('.work_sheet_table1 tfoot').html(data.week_exp);
                        $('.weak_go_html').html(data.title);
                        $('.work_sheet_table_last thead tr').html(data.thead);
                        $('.work_sheet_table_last tbody').html(data.week_exp);
                        // loadConfirm();
                        dragdrop();
                        seturl();
                    }
                });
            });
            $(document).on('change', '.rotas_time', function() {
                var start_time = $('.start_time').val();
                var end_time = $('.end_time').val();
                if (start_time != '' && end_time == '') {
                    $('.end_time').attr('value', start_time);
                }
                if (end_time != '' && start_time == '') {
                    $('.start_time').attr('value', end_time);
                }
                return;
            });

            $('.rotas_filter_main_div_responce').on('click', '.rotas_raeponce_btn_filter', function(e) {
                $('.add_remove_dayeoff').toggle();
                $('.rotas_filter_main_div').toggle();
            });

            $('.rotas_filter').on('click', '.dropdown-item', function(e) {
                var val = $(this).attr('id');
                var start_date = $('.week_last_daye').attr('data-start');
                var end_date = $('.week_last_daye').attr('data-end');
                var week = $('.week_add_sub').val();
                var created_by = $('.week_add_sub').attr('data-created-by');
                var designation_id = $('#designation_id').val();

                var data = {
                    start_date: start_date,
                    end_date: end_date,
                    week: week,
                    created_by: created_by,
                    designation_id: designation_id
                }
                $(this).find('.span_hide').toggle();
                $(this).find('.span_show').toggle();

                if (val == 'hideavailability') {
                    $('.availability_table_box').toggle();
                    $('#hideavailability').toggleClass('hide_rss');

                    var availability_display = 'show';
                    if ($('#hideavailability').hasClass('hide_rss')) {
                        var availability_display = 'hide';
                    }
                    var data = {
                        availability_display: availability_display,
                    }

                    $.ajax({
                        url: '{{ route('hideavailability') }}',
                        method: 'POST',
                        data: data,
                        success: function(data) {

                        }
                    });
                }
                if (val == 'hidedayoff') {
                    $('.ws_day_off_leave').toggle();
                    $('.cus_day_off_leave').toggle();
                    $('.day_off_leave123').toggleClass('badge-secondary');
                    $('#hidedayoff').toggleClass('hide_rss');

                    var hide_day_off = 'show';
                    if ($('#hidedayoff').hasClass('hide_rss')) {
                        var hide_day_off = 'hide';
                    }
                    var data = {
                        hide_day_off: hide_day_off,
                    }

                    $.ajax({
                        url: '{{ route('hidedayoff') }}',
                        method: 'POST',
                        data: data,
                        success: function(data) {

                        }
                    });

                }
                if (val == 'hideleave') {
                    $('#hideleave').toggleClass('hide_rss');
                    $('.other_leave123').toggleClass('badge-soft-success');
                    $('.holiday_leave123').toggleClass('badge-soft-info');

                    var leave_display = 'show';
                    if ($('#hideleave').hasClass('hide_rss')) {
                        var leave_display = 'hide';
                    }
                    var data = {
                        leave_display: leave_display,
                    }

                    $.ajax({
                        url: '{{ route('hideleave') }}',
                        method: 'POST',
                        data: data,
                        success: function(data) {

                        }
                    });
                }
                if (val == 'clear_week') {
                    $.ajax({
                        url: '{{ route('rotas.clear_week') }}',
                        method: 'POST',
                        data: data,
                        success: function(data) {

                            if (data["status"] == "success") {
                                toastrs('Success', data["msg"], 'success');
                            } else {
                                toastrs('Error', data["msg"], 'error');
                            }

                            if (data['status'] != 'error') {
                                $('.rotas_time1').remove();
                            }
                            // loadConfirm();
                        }
                    });
                }
                if (val == 'add_remove_dayeoff') {
                    $('.rotas_filter_main_div').toggle();
                    $('.add_remove_dayeoff').toggle();
                }
                if (val == 'add_remove_employee') {
                    $('.add_remove_employee').trigger('click');
                }
                leave_show();
            });

            $('.rotas_filter_main_div_responce').on('click', '.add_remove_dayeoff .dayoff_close', function() {
                $(this).parent().hide();
                $('.rotas_filter_main_div').show();
                location.reload();
            });

            $(document).on('click', '.work_sheet_table1>tbody>tr>td.droppable-class', function() {
                var date = $(this).attr('data-date');
                var user_id = $(this).attr('data-id');
                var data = {
                    date: date,
                    user_id: user_id,
                }
                var has_dayoff = $(this).find('.ws_day_off_leave').hasClass('day_off_leave');
                var has_dayoff_hide = $("#hidedayoff").hasClass('hide_rss');

                if ($('.add_remove_dayeoff').css('display') != 'none' && $('.add_remove_dayeoff').length >
                    0) {
                    if (has_dayoff != true) {
                        $.ajax({
                            url: '{{ route('rotas.add_dayoff') }}',
                            method: 'POST',
                            data: data,
                            context: this,
                            success: function(data) {
                                if (data.status == 'success') {
                                    if (data.date_status != '') {
                                        $(this).prepend(data.date_status);
                                    } else {
                                        $('.work_sheet_table1 tbody td.droppable-class')
                                            .children('.cus_day_off_leave').remove();
                                    }

                                    if (has_dayoff_hide) {
                                        $('.work_sheet_table1 tbody td.droppable-class')
                                            .children('.day_off_leave').hide();
                                    } else {
                                        $('.work_sheet_table1 tbody td.droppable-class')
                                            .children('.day_off_leave').show();
                                    }
                                    $('.rotas_location_change').trigger('change');
                                    toastrs('Success', data["msg"], 'success');
                                } else {
                                    toastrs('Error', data["msg"], 'error');
                                }

                                // loadConfirm();
                            }
                        });

                    } else {
                        toastrs('{{ __('This day already day off') }}', '{!! session('error') !!}',
                            'error');
                    }
                }
            });

            $(document).on('click', '#send_email_rotas', function() {
                var week = $('.week_add_sub').val();
                var designation_id = $('.rotas_location_change').val();

                var data = {
                    week: week,
                    designation_id: designation_id
                }

                if (designation_id == undefined) {
                    toastrs('Error', '{{ __('Location not found.') }}', 'error');
                    return;
                }

                $.ajax({
                    url: '{{ route('rotas.send_email_rotas') }}',
                    method: 'POST',
                    data: data,
                    success: function(data) {
                        if (data.status == 'success') {
                            toastrs('Success', data.message, 'success');
                        } else {
                            toastrs('Error', data.message, 'error');
                        }

                        // toastrs(data.status, data.message, data.status);
                    },
                    error: function(data) {
                        data = data.responseJSON;
                        toastrs('Error', data.message, 'error');
                    },
                });
            });

            $(document).on('click', '.rotas_cteate', function() {

                var form = $(this).parents('.rotas_cteate_frm');
                var user_id = form.find('input[name="user_id"]').val();
                var rotas_date = form.find('input[name="rotas_date"]').val();
                var designation_id = form.find('input[name="designation_id"]').val();
                var start_time = form.find('input[name="start_time"]').val();
                var end_time = form.find('input[name="end_time"]').val();
                var break_time = form.find('input[name="break_time"]').val();
                var synchronize_type = ($("input[name=synchronize_type]").is(':checked')) ? $(
                    "input[name=synchronize_type]").val() : "";
                var note = form.find('textarea[name="note"]').val();
                var token = $('meta[name="csrf-token"]').attr('content');

                var data = {
                    "_token": token,
                    "user_id": user_id,
                    "rotas_date": rotas_date,
                    "designation_id": designation_id,
                    "start_time": start_time,
                    "end_time": end_time,
                    "break_time": break_time,
                    "synchronize_type": synchronize_type,

                    "note": note,
                }

                $.ajax({
                    url: '{{ route('rota.store') }}',
                    method: 'POST',
                    data: data,
                    success: function(data) {
                        if (data["status"] == 'success') {
                            toastrs('Success', data["msg"], 'success');
                        } else {
                            toastrs('Error', data["msg"], 'error');
                        }
                        $('.rotas_location_change').trigger('change');
                        $('#designation_id').trigger('change');
                    }
                });
                $('#commonModal').modal('toggle');
                return;
            });



            $(document).on('click', '.rotas_edit_btn', function() {
                var form = $(this).parents('.rotas_edit_frm');
                var user_id = form.find('input[name="user_id"]').val();
                var rotas_date = form.find('input[name="rotas_date"]').val();
                var designation_id = form.find('input[name="designation_id"]').val();
                var start_time = form.find('input[name="start_time"]').val();
                var end_time = form.find('input[name="end_time"]').val();
                var break_time = form.find('input[name="break_time"]').val();
                var note = form.find('textarea[name="note"]').val();
                var rotas_id = form.find('input[name="rotas_id"]').val();
                var u_url = form.find('input[name="u_url"]').val();
                var token = $('input[name="_token"]').val();

                var data = {
                    "_token": token,
                    "rotas_id": rotas_id,
                    "user_id": user_id,
                    "rotas_date": rotas_date,
                    "designation_id": designation_id,
                    "start_time": start_time,
                    "end_time": end_time,
                    "break_time": break_time,

                    "note": note,
                }

                $.ajax({
                    url: u_url,
                    method: 'PUT',
                    data: data,
                    success: function(data) {
                        if (data["status"] == 'success') {
                            toastrs('Success', data["msg"], 'success');
                        } else {
                            toastrs('Error', data["msg"], 'error');
                        }
                        $('#commonModal').modal('toggle');
                        $('.rotas_location_change').trigger('change');
                    }
                });
                return;
            });

            $(document).on('click', '.delete_rotas_action', function() {
                var id = $(this).attr('data-id');
                var token = $('meta[name="csrf-token"]').attr('content');
                var url = $(this).attr('action_url');
                var data = {
                    "id": id,
                    "token": token,
                }

                $.ajax({
                    url: url,
                    method: 'DELETE',
                    data: data,
                    success: function(data) {
                        if (data["status"] == 'success') {
                            toastrs('Success', data["msg"], 'success');
                        } else {
                            toastrs('Error', data["msg"], 'error');
                        }
                        $('.rotas_location_change').trigger('change');
                    }
                });
                return;
            });

        });

        $(document).on('click', '.Copy_Week_Shift', function() {
            var rotas_id_array = [];
            $(".work_sheet_table1 tbody .rotas_time").each(function(propName, index) {
                var rotas_id = $(this).attr('data-rotas-id');
                rotas_id_array[propName] = rotas_id;
            });

            var data = {
                rotas_id_array: rotas_id_array,
            }
            $.ajax({
                url: '{{ route('copy.week.sheet') }}',
                method: 'POST',
                data: data,
                context: this,
                success: function(data) {
                    if (data.status == 'success') {
                        toastrs('Success', data["msg"], 'success');
                    } else {
                        toastrs('Error', data["msg"], 'error');
                    }
                }
            });
        });

        function publish_week() {
            var week = $('.week_add_sub').val();
            var created_by = $('.week_add_sub').attr('data-created-by');
            var designation_id = $('#designation_id').val();
            if (designation_id.length == 0) {
                designation_id = 0;
            }

            var data = {
                week: week,
                created_by: created_by,
                designation_id: designation_id
            }

            $.ajax({
                url: '{{ route('rotas.publish_week') }}',
                method: 'POST',
                data: data,
                success: function(data) {
                    if (data["status"] == 'success') {

                        toastrs('Success', data["msg"], 'success');
                    } else {
                        toastrs('Error', data["msg"], 'error');
                    }
                    $('.work_sheet_table1 tbody .rotas_time1').removeClass('opacity-50');
                    $('[data-toggle="tooltip"]').tooltip();
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            });
        }

        function un_publish_week() {
            // $('.publish_shifs').on('click', '#un_publish_week', function() {
            var week = $('.week_add_sub').val();
            var created_by = $('.week_add_sub').attr('data-created-by');
            var designation_id = $('#designation_id').val();
            var data = {
                week: week,
                created_by: created_by,
                designation_id: designation_id
            }

            $.ajax({
                url: '{{ route('rotas.un_publish_week') }}',
                method: 'POST',
                data: data,
                success: function(data) {
                    if (data["status"] == 'success') {
                        toastrs('Success', data["msg"], 'success');
                    } else {
                        toastrs('Error', data["msg"], 'error');
                    }
                    $('.work_sheet_table1 tbody .rotas_time1').addClass('opacity-50');
                    $('[data-toggle="tooltip"]').tooltip();
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            });
            // });
        }
        @if (Laratrust::hasPermission('rota copy-week-shift'))
            function dragdrop() {
                $(".droppable-class").sortable({
                    revert: true,
                    cancel: '.add_rotas,.availability_table_box'
                });

                $(".draggable-class").draggable({
                    tolerance: "pointer",
                    connectToSortable: ".droppable-class",
                    helper: "clone",
                    start: function(event, ui) {
                        $('.work_sheet_table1 tbody tr td.droppable-class').addClass('tr-drop-zone');
                        $('.work_sheet_table1 tbody tr td.draggable-class').addClass('tr-drag-item-zindex');
                    },
                    drag: function(event, ui) {

                    },
                    stop: function(event, ui) {
                        $('.work_sheet_table1 tbody tr td.droppable-class').removeClass('tr-drop-zone');
                        $('.work_sheet_table1 tbody tr td .draggable-class').removeClass('tr-drag-item-zindex');

                        var designation_id = $('.rotas_location_change').val();
                        var created_by = $('.week_add_sub').attr('data-created-by');
                        var rotas_id = $(this).attr('data-rotas-id');

                        setTimeout(function() {
                            var drop_user_id = ui.helper.parents('.droppable-class').attr('data-id');
                            var drop_date = ui.helper.parents('.droppable-class').attr('data-date');

                            var data = {
                                drop_date: drop_date,
                                drop_user_id: drop_user_id,
                                rotas_id: rotas_id,
                                designation_id: designation_id,
                                created_by: created_by
                            }

                            if (drop_date != undefined) {
                                $.ajax({
                                    url: '{{ route('rotas.shift_copy') }}',
                                    method: 'post',
                                    data: data,
                                    success: function(data) {
                                        if (data['status'] == 'success') {
                                            ui.helper.html(data['shift']);
                                            ui.helper.attr('data-rotas-id', data[
                                                'insert_id']);
                                            toastrs('Success', data['msg'], 'success');
                                        } else {
                                            ui.helper.remove();
                                            toastrs('Error', data['msg'], 'error');
                                        }
                                        $('[data-toggle="tooltip"]').tooltip();
                                        // loadConfirm();
                                        $('.rotas_location_change').trigger('change');

                                        $('[data-toggle="tooltip"]').tooltip();

                                        if ($('.add_remove_dayeoff').css('display') !=
                                            'none') {
                                            $('.day_off_leave').show();
                                        } else {
                                            $('.day_off_leave').hide();
                                        }

                                    }
                                });
                            }
                        }, 1000);
                    }
                });
            }
        @endif
        function seturl() {
            var week = $('.week_add_sub').val();
            var designation_id = $('#designation_id').val();
            var created_by = $('.week_add_sub').attr('data-created-by');

            var user_array = [];
            $(".work_sheet_table1 tbody tr").each(function(propName, index) {
                var user_id = $(this).attr('data-user-id');
                user_array[propName] = user_id;
            });

            var url = $('.share_rotas_cls').attr('data-url2');
            var new_url = url + '?designation=' + designation_id + '&create_by=' + created_by + '&week=' +
                week + '&user=' + user_array;

            $('.share_rotas_cls').attr('data-url', new_url);
            $('.share_rotas_cls').attr('data-ajax-rota', true);

            if ($(".work_sheet_table1 tbody tr").attr('data-user-id') == undefined) {
                $('.share_rotas_cls').attr('data-ajax-rota', false);
            }
        }

        function leave_show() {
            $('.day_off_leave').hide();
            if (!$("#hidedayoff").hasClass('hide_rss')) {
                $('.day_off_leave').show();
            }


            $('.other_leave').hide();
            $('.holiday_leave').hide();
            if (!$("#hideleave").hasClass('hide_rss')) {
                $('.other_leave').show();
                $('.holiday_leave').show();
            }
            $('[data-toggle="tooltip"]').tooltip();
        }

        $(document).ready(function() {
            comman_function();
        });

        function comman_function(param) {
            var start_date = $('.week_last_daye').attr('data-start');
            var end_date = $('.week_last_daye').attr('data-end');
            var week = $('.week_add_sub').val();
            var designation_id = $('.rotas_location_change').val();;
            var created_by = $('.week_add_sub').attr('data-created-by');
            if (designation_id == null) {
                var designation_id = 0;
            }
            var data = {
                start_date: start_date,
                end_date: end_date,
                week: week,
                designation_id: designation_id,
                created_by: created_by
            }

            $.ajax({
                url: '{{ route('rotas.week_sheet') }}',
                method: 'post',
                data: data,
                success: function(data) {
                    $('.work_sheet_table1').html(data.table);
                    $('.work_sheet_table1 tfoot').html(data.week_exp);
                    $('.weak_go_html').html(data.title);
                    $('.work_sheet_table_last thead tr').html(data.thead);
                    $('.work_sheet_table_last tbody').html(data.week_exp);
                    $('[data-toggle="tooltip"]').tooltip();
                    // loadConfirm();
                    dragdrop();
                    leave_show();
                    seturl();
                }
            });
        }
    </script>

    <script type="text/javascript">
        $(document).on('change', '#branch_id', function() {
            var branch_id = $(this).val();
            getDepartment(branch_id);
        });

        function getDepartment(branch_id) {
            var data = {
                "branch_id": branch_id,
                "_token": "{{ csrf_token() }}",
            }

            $.ajax({
                url: '{{ route('employee.getdepartment') }}',
                method: 'POST',
                data: data,
                success: function(data) {
                    $('#department_id').empty();
                    $('#department_id').append(
                        '<option value="" disabled>{{ __('Select Department') }}</option>');

                    $.each(data, function(key, value) {
                        $('#department_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    $('#department_id').val('');
                }
            });
        }

        $(document).on('change', 'select[name=department_id]', function() {
            var department_id = $(this).val();
            getDesignation(department_id);
        });

        function getDesignation(did) {
            $.ajax({
                url: '{{ route('employee.getdesignation') }}',
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#designation_id').empty();
                    $('#designation_id').append(
                        '<option value="">{{ __('Select Designation') }}</option>');
                    $.each(data, function(key, value) {
                        $('#designation_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                }
            });
        }
    </script>
    <script>
        $(document).on('click',
            'a[data-ajax-rota="true"], div[data-ajax-rota="true"], td[data-ajax-rota="true"], button[data-ajax-rota="true"]',
            function(e) {
                e.preventDefault();

                var data = {};
                var title = $(this).data('title');
                var size = (($(this).data('size') == '') && (typeof $(this).data('size') === "undefined")) ? 'md' : $(
                    this).data('size');
                var url = $(this).attr('data-url');
                var align = $(this).data('align');
                var rotas_location = $('.rotas_location_change').val();
                var data_availability = $(this).parent().parent().attr('data-availability-json');
                $("#commonModal .modal-title").html(title);
                $("#commonModal .modal-dialog").addClass('modal-' + size + ' modal-dialog-' + align);

                $.ajax({
                    url: url,
                    data: data,
                    cache: false,
                    success: function(data) {
                        $('#commonModal .body').html(data);
                        // $("#commonModal").modal('show');
                        if ($('.js-single-select').length > 0) {
                            $('.js-single-select').select2();
                        }
                        if ($('.js-multiple-select').length > 0) {
                            $('.js-multiple-select').select2();
                        }

                        $('#rotas_ctrate_location').attr('value', rotas_location);
                        $('.autogrow').height("auto").height($(this)[0].scrollHeight - 24);
                        $("#rule_select").trigger("change");
                        $("#date_between").trigger("change");
                        $(".total_daily_hour").trigger("change");
                        $(".manager_manag_emp").trigger("change");

                        availabilitytablejs();
                        ddatetime_range();
                        if (data_availability != undefined) {
                            var data = JSON.parse(data_availability);
                            editavailabilitytablejs(data);
                        }

                        //loadConfirm();
                        comman_fuction();

                        $('#commonModal').modal('toggle');
                        $('#commonModal').modal({
                            keyboard: false
                        });
                    },
                    error: function(data) {
                        data = data.responseJSON;
                        toastrs('Error', data.error, 'error')
                    }
                });
            });
    </script>
@endpush
