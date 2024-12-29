@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/booking.css') }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" type="text/javascript"></script>

    <style>.daterangepicker {z-index: 9999;}</style>


    <script>

        function bookListok(){
            var selected = $('.selected');
            var dates = [];
            var room_name;
            var room;
            var type_name;

            selected.each(function(){
                dates.push($(this).attr('data-day') + '.' + $(this).attr('data-month') + '.' + $(this).attr('data-year'));
                room_name = $(this).attr('data-room_name');
                room = $(this).attr('data-room');
                type_name = $(this).attr('data-type_name');
            });

            var minDate = dates.reduce(function(a, b){
                return new Date(a.split('.').reverse().join('-')) < new Date(b.split('.').reverse().join('-')) ? a : b;
            });

            var maxDate = dates.reduce(function(a, b){
                return new Date(a.split('.').reverse().join('-')) > new Date(b.split('.').reverse().join('-')) ? a : b;
            });

            $.confirm({
                title: 'Guest registration in room ' + room_name + ' (' + type_name + ') for the period from ' + minDate + ' to ' + maxDate,
                content:function (){
                    var url = 'url:booking/form';
                    var params = {
                        minDate: minDate,
                        maxDate: maxDate,
                        room: room,
                        room_name: room_name,
                        type_name: type_name
                    };
                    return url+ '?' + $.param(params);
                },
                type: 'blue',
                columnClass: 'col-lg-12',
                typeAnimated: true,
                buttons:{
                    save:{
                        text: 'Save',
                        btnClass: 'btn-green',
                        action: function(){
                            var form = this.$content.find('form');
                            var data = form.serialize();
                            data += '&_token=' + '{{ csrf_token() }}';
                            data += '&room=' + room_name;
                            console.log(data);
                            $.ajax({
                                url: '{{ route('booking.bookGuest') }}',
                                method: 'POST',
                                data: data,
                                success: function(response){
                                    if(response.status == 'success'){
                                        selected.removeClass('selected');
                                        selected.addClass('listok');
                                        location.reload();
                                    }else{
                                        $.alert('Error');
                                    }
                                },
                                error: function(xhr, status, error) {
                                    // Xatolik haqida ma'lumotni ko'rsatish
                                    $.alert('Ошибка регистрации: ' + xhr.status + ' - ' + xhr.statusText);

                                    // Konsolga batafsil xatolik tafsilotlarini chiqarish
                                    console.log('AJAX Error:', {
                                        status: xhr.status,
                                        statusText: xhr.statusText,
                                        responseText: xhr.responseText
                                    });

                                    // Xatolikni o‘qish uchun JSON formatda tahlil qilishga urinish
                                    try {
                                        const jsonResponse = JSON.parse(xhr.responseText);
                                        console.log('Detailed Error:', jsonResponse);
                                    } catch (e) {
                                        console.log('Response is not JSON:', xhr.responseText);
                                    }
                                }
                            });
                            return false;
                        },
                        isDisabled: true

                    },
                    close:{
                        text: 'Закрыть',
                        btnClass: 'btn-red',
                        action: function(){
                            location.reload();
                        }
                    }
                },
                onContentReady: function(){
                    var modal = this;
                    var requiredFields = ['#start-date', '#end-date', '#surname', '#contact-phone'];

                    modal.buttons.save.disable();

                    function parseDate(dateString) {
                        var parts = dateString.split('.');
                        if (parts.length !== 3) return null;
                        var day = parseInt(parts[0], 10);
                        var month = parseInt(parts[1], 10) - 1;
                        var year = parseInt(parts[2], 10);
                        return new Date(year, month, day);
                    }

                    function validateFields() {
                        var isValid = true;

                        // Majburiy maydonlarni tekshirish
                        requiredFields.forEach(function (selector) {
                            var field = modal.$content.find(selector);
                            if (!field.val().trim()) {
                                isValid = false;
                            }
                        });


                        // minDate va maxDateni tekshirish
                        var minDateVal = parseDate(modal.$content.find('#start-date').val());
                        var maxDateVal = parseDate(modal.$content.find('#end-date').val());

                        if (isValid && (!isNaN(minDateVal) && !isNaN(maxDateVal))) {
                            if (minDateVal >= maxDateVal) {
                                isValid = false;
                            }
                        }

                        // Tugmani yoqish yoki o‘chirish
                        if (isValid) {
                            modal.buttons.save.enable();
                        } else {
                            modal.buttons.save.disable();
                        }
                    }

                    // Majburiy maydonlarda o‘zgarishlarni kuzatish
                    requiredFields.forEach(function (selector) {
                        modal.$content.find(selector).on('input change', function () {
                            validateFields();
                        });
                    });

                    // Birinchi tekshirish
                    validateFields();

                }
            })

        }

        function regListok(listokData) {
            if (!listokData) {
                console.error("listokData mavjud emas.");
                return;
            }
            const dateStart = listokData.dateStart;
            const dateEnd = listokData.dateEnd;
            const listokId = listokData.listokId;
            console.log(dateStart, dateEnd, listokId);

            /*
            var selected = $('.selected');
            var dates = [];
            var room_name;
            var room;
            var type_name;

            selected.each(function() {
                dates.push($(this).attr('data-day') + '.' + $(this).attr('data-month') + '.' + $(this).attr('data-year'));
                room_name = $(this).attr('data-room_name');
                room = $(this).attr('data-room');
                type_name = $(this).attr('data-type_name');
            });

            var minDate = dates.reduce(function (a, b) {
                return new Date(a.split('.').reverse().join('-')) < new Date(b.split('.').reverse().join('-')) ? a : b;
            });

            var maxDate = dates.reduce(function (a, b) {
                return new Date(a.split('.').reverse().join('-')) > new Date(b.split('.').reverse().join('-')) ? a : b;
            });*/

            // Modal ochish
            $.confirm({
                title: 'Регистрация гостя в ид ' + listokId + ' на период с ' + dateStart + ' по ' + dateEnd,
                //content:dynamicContent,
                content: 'url:infobooking/form',
                type: 'blue',
                columnClass: 'col-lg-12',
                typeAnimated: true,
                buttons: {
                    formSubmit: {
                        text: 'Регистрация',
                        btnClass: 'btn-blue',
                        action: function () {
                            var form = this.$content.find('form');
                            var data = form.serialize();
                            data += '&_token=' + '{{ csrf_token() }}';
                            data += '&datevisiton=' + dateStart;
                            data += '&datevisitoff=' + dateEnd;
                            data += '&regnum=' + listokId;
                            $.ajax({
                                url: '{{ asset('listok/save') }}',
                                method: 'POST',
                                data: data,
                                success: function (response) {
                                    if (response.status == 'success') {
                                        $.alert('Гость успешно зарегистрирован');
                                        //selected.removeClass('selected');
                                        //selected.addClass('listok');
                                        location.reload();
                                    } else {
                                        $.alert('Ошибка регистрации');
                                    }
                                }
                            });
                        }
                    },
                    close: {
                        text: 'Закрыть',
                        btnClass: 'btn-blue',
                        action: function () {
                            location.reload();
                        }
                    }
                }
            });
        }

        function cb(start, end) {
            $('#rgsrange span').html(start.format('MMM DD, YYYY') + ' - ' + end.format('MMM DD, YYYY'));
            let url = '{{ route("book-table") }}?start=' + start.format('YYYY-MM-DD') + '&end=' + end.format('YYYY-MM-DD');
            let roomType = $('#roomType').val();
            if (roomType) {
                url += '&roomType=' + roomType;
            }
            $('#bookTable').load(url);
        }
        $('#rgsrange').daterangepicker({
            showDropdowns: true, // Additional options you may want to include
            minDate: moment().subtract(1, 'day'), // Example: Minimum selectable date
            maxDate: moment().add(2, 'month'), // Example: Maximum selectable date
            range: {
                'Сегодня': [moment(), moment()],
                'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'За последнюю неделю': [moment().subtract(6, 'days'), moment()],
                'Последние 30 дней': [moment().subtract(29, 'days'), moment()],
                'Последние 45 дней': [moment().subtract(44, 'days'), moment()],
                'Текущий месяц': [moment().startOf('month'), moment().endOf('month')],
                'Предыдущий месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Текущий год': [moment().startOf('year'), moment()],
                'Весь период': ['01/01/1970', moment()],
            }

        }, cb);
        cb(moment().subtract(1, 'day'), moment().add(1, 'month'));

        $('#prevMonth').click(function() {
            let daterangepicker = $('#rgsrange').data('daterangepicker');
            let start = daterangepicker.startDate.clone().subtract(1, 'month').startOf('month');
            let end = start.clone().endOf('month');
            cb(start, end);
            daterangepicker.setStartDate(start);
            daterangepicker.setEndDate(end);
        });

        $('#nextMonth').click(function() {
            let daterangepicker = $('#rgsrange').data('daterangepicker');
            let start = daterangepicker.startDate.clone().add(1, 'month').startOf('month');
            let end = start.clone().endOf('month');
            cb(start, end);
            daterangepicker.setStartDate(start);
            daterangepicker.setEndDate(end);
        });

    </script>

@endsection

@section('content')

    <div class="container-fluid">

    <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between",>
                    <h4 class="mb-0">Регистрация гостья</h4>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-12">
                <div class="d-flex align-items-center">
                    <i class="fa fa-chevron-left" id="prevMonth" style="cursor: pointer; margin-right: 10px;"></i>
                    <div id="rgsrange" class="form-control"
                         style="background: #fff; cursor: pointer; border: 1px solid #55acee; display: inline!important; width: auto;">
                        <i class="fa fa-calendar"></i>&nbsp;<span></span>
                    </div>
                    <i class="fa fa-chevron-right" id="nextMonth" style="cursor: pointer; margin-left: 10px;"></i>
                    <i class="fa fa-bed" style="margin-left: 10px;"></i>
                    <select class="form-control" id="roomType" style="margin-left: 10px; width: 200px;">
                        <option value="" disabled selected>Room Type</option>
                        <!-- Add your room type options here -->
                        <option value="standart">Standart</option>
                        <option value="Lux">Lux</option>
                        <option value="Delux">Delux</option>
                    </select>
                    <button class="btn btn-primary" id="ApplyButton" style="margin-left: 10px;">Apply</button>
                </div>
                <hr>
                <div id="bookTable" style="width: 100%; overflow-x: auto;position: relative">
                </div>
            </div>

        </div>

    </div>
@endsection
