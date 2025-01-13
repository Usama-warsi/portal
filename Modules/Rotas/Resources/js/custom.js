
$(document).on('click', 'a[data-ajax-avalibility="true"], button[data-ajax-avalibility="true"], div[data-ajax-avalibility="true"]', function () {
    var title = $(this).data('title');
    var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var url = $(this).data('url');

    var align = $(this).data('align');
    var rotas_location = $('.rotas_location_change').val();
    var data_availability = $(this).parent().parent().attr('data-availability-json');


    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);

    $.ajax({
        url: url,
        success: function (data) {
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            taskCheckbox();
            common_bind("#commonModal");

            // Rota module
            setTimeout(() => {
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
                comman_fuction();
            }, 100);
        },
        error: function (data) {
            data = data.responseJSON;
            toastrs('Error', data.error, 'error')
        }
    });
});

function availabilitytablejs() { }
function editavailabilitytablejs(data = []) { }

    $(document).on('click','.set_expiry_date', function () {
        if($(this).prop("checked") == true)
        {
            $('.expiry_date_box').show();
        }
        else if($(this).prop("checked") == false)
        {
            $('.expiry_date_box').hide();
            $('.expiry_date_box input[name="expiry_date"]').val('');
        }
    });

    $(document).on('click','.set_password', function () {
        if($(this).prop("checked") == true)
        {
            $('.password_box').show();
        }
        else if($(this).prop("checked") == false)
        {
            $('.password_box').hide();
            $('.password_box input[name="share_password"]').val('');
        }
    });

    function ddatetime_range() {
        if($('.datetime_class').length > 0) {
            $('.datetime_class').daterangepicker({
                "singleDatePicker": true,
                "timePicker": true,
                "autoApply": true,
                "locale": {
                    "format": 'YYYY-MM-DD H:mm'
                },
                "timePicker24Hour": true,
            }, function (start, end, label) {
                $('.start_date').val(start.format('YYYY-MM-DD H:mm'));
            });
        }
    }


    function comman_fuction() {

        /* chosen js => select */
        if ($(".multi-select").length > 0) {
            $($(".multi-select")).each(function (index, element) {
                var id = $(element).attr('id');
                var multipleCancelButton = new Choices(
                    '#' + id, {
                    removeItemButton: true,
                }
                );
            });
        }

    /* Tooltip */
    // if ($('[data-bs-toggle="tooltip"]').length > 0) {
    //     var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    //     var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    //     return new bootstrap.Tooltip(tooltipTriggerEl)
    //     })
    // }

    /* Date Piker */
    if ($('.pc-datepicker').length > 0) {
        $($(".pc-datepicker")).each(function (index, element) {
            var id = $(element).attr('id');
            (function () {
                const d_week = new Datepicker(document.querySelector('#'+id), {
                    buttonClass: 'btn'
                });
            })();
        });
    }

    /* Time Piker */
    if ($('.pc-timepicker-1-modal').length > 0) {
        $($(".pc-timepicker-1-modal")).each(function (index, element) {
            var id = '#' + $(element).attr('id');
            document.querySelector(id).flatpickr({
                enableTime: true,
                noCalendar: true,
                minuteIncrement:1,
            });
        });
    }

    /* Date Range Piker */
    if ($('.pc-daterangepicker-1').length > 0) {
        $($(".pc-daterangepicker-1")).each(function (index, element) {
            var id = '#' + $(element).attr('id');
            document.querySelector(id).flatpickr({
                mode: "range"
            });
        });
    }

    $(document).ready(function () {
        $(document).on('click', '.weak_go', function () {

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
            $.each(between, function (key, val) {
                var date = moment(val).format('D');
                var mon = moment(val).format('MMM');
                var days = moment(val).format('ddd');

                var today = [];
                today.push(date + '/' + mon + '/' + days);

                record_hours1 += '<th><span>' + days + '</span><br><span>' + date + ' ' + mon + '</span></th>';
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


}


    $(document).ready(function () {
        $(document).on('click', '.weak_go', function () {
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
            $.each(between, function (key, val) {
                var date = moment(val).format('D');
                var mon = moment(val).format('MMM');
                var days = moment(val).format('ddd');


                var today = [];
                today.push(date + '/' + mon + '/' + days);

                record_hours1 += '<th><span>' + days + '</span><br><span>' + date + ' ' + mon + '</span></th>';
            });
            record_hours += record_hours1;
            $(".week_go_table").html(record_hours);
        });

    })
     /* Tooltip */
    // if ($('[data-bs-toggle="tooltip"]').length > 0) {
    //     var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    //     var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    //     return new bootstrap.Tooltip(tooltipTriggerEl)
    //     })
    // }
}


