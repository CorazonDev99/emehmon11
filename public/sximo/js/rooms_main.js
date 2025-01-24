
var jc = null;
var _rooms_data = null;
var _print_ids = null;
var sel_rooms = null;
var operated = 0;
var myMenu = [
    {
        icon: 'icon-info',
        label: 'Сведения о госте',
        action: function(option, contextMenuIndex, optionIndex) {showGuerstInfo();superCm.destroyMenu();},
        submenu: null
    },
    {
        icon: 'icon-airplane',
        label: 'CheckOut',
        action: function (option, contextMenuIndex, optionIndex) {preCheckOutGuest(); superCm.destroyMenu();}
    },
    {
        icon: 'fa fa-check-square-o',
        label: 'Уборка номера',
        action: function (option, contextMenuIndex, optionIndex) {preCleanRoom(); superCm.destroyMenu();}
    },
    {
        icon: 'icon-transmission2',label: 'Переместить в другой номер',
        action: function(option, contextMenuIndex, optionIndex) {MoveTo(event); superCm.destroyMenu();},
        submenu: null,disabled: false
    },
    {
        icon: 'icon-calculate2',label: 'Изменить статус оплаты',
        action: function(option, contextMenuIndex, optionIndex) {PayStatusRoom();superCm.destroyMenu();},
        submenu: null,disabled: false
    },
    {
        icon: 'icon-bubbles3',label: 'Добавить отзыв',
        action: function(option, contextMenuIndex, optionIndex) {AddFeedback(); superCm.destroyMenu();},
        submenu: null,disabled: false
    },
    {
        icon: 'icon-print',label: 'Печать листок прибытия',
        action: function(option, contextMenuIndex, optionIndex) {prePrint(); superCm.destroyMenu();},
        submenu: null,disabled: false
    },
    {
        icon: 'icon-tag',label: 'Присвоить тег',
        action: function(option, contextMenuIndex, optionIndex) {_setTag(); superCm.destroyMenu();},
        submenu: null,disabled: false
    },
    {
        icon: 'icon-remove5',label: 'Удалить тег',
        action: function(option, contextMenuIndex, optionIndex) { _unsetTag(); superCm.destroyMenu();},
        submenu: null, disabled: false
    },
    {
        icon: 'icon-vcard',label: 'Продлить визу',
        action: function(option, contextMenuIndex, optionIndex) {AddVisa(); superCm.destroyMenu();},
        submenu: null, disabled: false
    },
];
function clearFilter(e) {
    e.preventDefault();
    $('#srch-tag').val('');
    $('#srch-floor').val('');
    $('#srch-room-type').val('0');
    $('#srch-room-st').val('0');
    getMyRoomsdata();
}
function checkSelected(cls = ''){
    var selected = document.getElementsByClassName('item ' + cls + ' check');
    if (selected.length == 0) {
        $.alert({
            icon: 'fa fa-info',
            animation: 'rotate',
            closeAnimation: 'rotate',
            closeIcon: true,
            type: 'red',
            title: '&nbsp;Не выбран номер (комната)!',
            content: '<br>Сначало выберите номер щелкнув левой кнопкой мыши по номеру!',
            columnClass: 'small',
        });
        return false;
    }
    return true;
}
function getSelected(first_only = false, cls = '') {
    var selected = document.getElementsByClassName('item ' + cls + ' check');
    if (selected.length == 0) return null;
    if (first_only) return selected[0].dataset.room;
    var rooms = [];
    for (var i=0; i<selected.length; i++) {
        rooms.push({roomid:selected[i].dataset.roomid,roomnumb:selected[i].dataset.numb, lids:selected[i].dataset.lids, ctzs: selected[i].dataset.ctzs,guests: selected[i].dataset.guests});
    }
    return rooms;
}

function _listokPrint() {
    var printing_css='';
    $.get('/print.css', function (css) {printing_css = css;});
    var html_to_print = '';
    $.ajax(
        {type: "GET", url: "/roomlists/printlistok", data:{ids: btoa(_print_ids)},
            success:function(result) {
                html_to_print = printing_css + result;
                var iframe = $('<iframe id="print_frame">');
                $('body').append(iframe);
                var doc = $('#print_frame')[0].contentDocument || $('#print_frame')[0].contentWindow.document;
                var win = $('#print_frame')[0].contentWindow || $('#print_frame')[0];
                doc.getElementsByTagName('body')[0].outerHTML+=html_to_print;
                win.print();
                $('iframe').remove();
                getMyRoomsdata();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert('Ошибка! ...  :-(');}
        });
}
function _checkOut(id, prn, paid, amount, paytp) {
    if (id)
    {
        $.ajax({
            type: "POST", url: "/roomlists/checkout",
            data: {id: btoa(id), printData: prn, payed: paid, amount: amount,paytp:paytp},
            success: function (data) {
                if (data != null && data != undefined) {
                    //if (prn != 0 && data.updated > 0)
                    showNotificationEx(data.status, data.message);
                }
            }
        });
    }
}
function _moveTo(id, room_id) {
    if (id && room_id){
        $.ajax({
            type: "POST", url: "/roomlists/domove",
            data: {id: btoa(id), room_id: btoa(room_id)},
            success: function (data) {
                if (data != null && data != undefined) {
                    showNotificationEx(data.status, data.message);
                }
            }
        });
    }
}
function _payFor(id, payst, amt) {
    //console.log(id + '; ' + payst);
    if (id && payst){
        $.ajax({
            type: "POST", url: "/roomlists/dopay",
            data: {id: btoa(id), pay_status: btoa(payst), amount:btoa(amt)},
            success: function (data) {
                if (data != null && data != undefined) {
                    showNotificationEx(data.status, data.message);
                }
            }
        });
    }
}
function _addNote(eid,guest,ctz) {
    if (!(eid && guest && ctz)) return false;
    var url = _URL_FEEDBACKS.concat('/',eid);
    var urlImg  = _URL_FLAGS + '/' + ctz +'.png';
    $.confirm({
        title: 'ОТЗЫВ',
        size:'medium',
        content: "<form id='comments'"+eid +" action='" + url + "' class='formName'><div class='form-group'><img src='" + urlImg + "' width='36px'/>&nbsp;<span class='font-fold text-info'>" + guest + "</span><p>&nbsp;<br/></p>" +
        "<textarea name='text' placeholder='введите отзыв' maxlength='400' class='form-control' rows='5' required /></textarea></div>" +
        "<div class='form-group'><label class='form-inline'>Черный список: <input type='radio' name='inBlack' value='1'> ДА </label>" +
        "&nbsp;<label class='form-inline'><input type='radio' name='inBlack' value='0' checked='true'> НЕТ </label></div></form>",
        buttons: {
            formSubmit: {
                text: 'Добавить',
                btnClass: 'btn-blue',
                action: function () {
                    var feedback = this.$content.find('textarea[name=text]').val();
                    var inBlack = this.$content.find('input[name=inBlack]:checked').val();
                    if (!feedback) {
                        $.alert(
                            {
                                title: 'Не верно заполнено поле',
                                content: 'Добавьте отзыв и нажмите на кнопку ОК!',
                                type: 'red'
                            });
                        return false;
                    }
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            'text': feedback,
                            'inBlack': inBlack,
                            '_token': __token
                        },
                        success: function(data){
                            showNotificationEx(data.status, data.message);
                        }
                    });
                }
            },
            cancel:{
                text: 'Отмена',
            },
        },
        onContentReady: function () {
            this.$content.find('textarea[name=text]').focus();
        }
    });
}
function _setTag(){
    if (!checkSelected('room_busy')) return false;
    sel_rooms = getSelected(false, 'room_busy');
    if (!sel_rooms) return false;
    _print_ids = '';
    var url = 'roomlists/settagsroom';
    for (var i=0; i < sel_rooms.length; i++) {
        _print_ids += atob(sel_rooms[i].lids) + ',';
    }
    if (_print_ids == '') return false;

    $.confirm({
        title: 'Присваевание тега для гостей.',
        size:'medium',
        content: "<form id='tag-form' action='" + url + "' class='formName'><div class='form-group'>&nbsp;<span class='font-fold text-info'><i class='fa fa-tag fa-2x'></i> Выбрано " + sel_rooms.length + " номеров.</span><p>&nbsp;<br/></p>" +
        "<input type='text' name='tagname' placeholder='введите название тега' class='form-control' maxlength='40' required/></div>" +
        "<input type='hidden' name='ids' value='" + _print_ids + "'/></form>",
        buttons: {
            formSubmit: {
                text: 'ОК',
                btnClass: 'btn-blue',
                action: function () {
                    var tagname = this.$content.find('input[name=tagname]').val();
                    if (!tagname) {
                        $.alert(
                            {
                                title: 'Не верно указан тег! ' + tagname,
                                content: 'Введите название тега и нажмите на кнопку (ОК)!',
                                type: 'red'
                            });
                        return false;
                    }
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            'tag': btoa(tagname),
                            'ids': btoa(_print_ids),
                            '_token': __token
                        },
                        success: function(data){
                            showNotificationEx(data.status, data.message);
                        }
                    });
                }
            },
            cancel:{
                text: 'Отмена',
            },
        },
        onContentReady: function () {
            this.$content.find('input[name=tagname]').focus();
            var jc = this;
            this.$content.find('form').on('submit', function (e) {
                e.preventDefault();
                jc.$$formSubmit.trigger('click'); // reference the button and click it
            });
        }
    });
}
function _unsetTag(){
    if (!checkSelected('room_busy')) return false;
    sel_rooms = getSelected(false, 'room_busy');
    if (!sel_rooms) return false;
    _print_ids = '';
    var url = 'roomlists/unsettagsroom';
    for (var i=0; i < sel_rooms.length; i++) {
        _print_ids += atob(sel_rooms[i].lids) + ',';
    }
    if (_print_ids == '') return false;

    $.confirm({
        title: 'Удаление тега для гостей.',
        size:'medium',
        content: "<form id='tag-form' action='" + url + "' class='formName'><div class='form-group'>&nbsp;<span class='font-fold text-info'><i class='fa fa-tag fa-2x'></i> Выбрано " + sel_rooms.length + " номеров.</span><p>&nbsp;<br/></p><input type='hidden' name='ids' value='" + btoa(_print_ids) + "'/></form>",
        buttons: {
            formSubmit: {
                text: 'ОК',
                btnClass: 'btn-red',
                action: function () {
                    var idss = this.$content.find('input[name=ids]').val();
                    if (!idss) {
                        $.alert(
                            {
                                title: 'Нет данных! ',
                                content: 'Выделите номера для начало!',
                                type: 'red'
                            });
                        return false;
                    }
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            'ids': btoa(_print_ids),
                            '_token': __token
                        },
                        success: function(data){
                            showNotificationEx(data.status, data.message);
                        }
                    });
                }
            },
            cancel:{
                text: 'Отмена',
            },
        },
        onContentReady: function () {
            this.$content.find('input[name=tagname]').focus();
            var jc = this;
            this.$content.find('form').on('submit', function (e) {
                e.preventDefault();
                jc.$$formSubmit.trigger('click'); // reference the button and click it
            });
        }
    });
}
function _visaExtension(eid,guest,ctz){
    var url = 'roomlists/extensionvisaroom';
    var urlImg  = _URL_FLAGS + '/' + ctz +'.png';
    $.confirm({
        title: 'Продление визы.',
        size:'medium',
        content: "<form id='comments'"+eid +" action='" + url + "' class='formName'><div class='form-group'><img src='" + urlImg + "' width='36px'/>&nbsp;<span class='font-fold text-info'>" + guest + "</span><p>&nbsp;<br/></p>" +
        "<div class='col-md-6'><label>Срок визы с: </label><input type='date' name='visaFrom' placeholder='ДД/ММ/ГГГГ' class='form-control date' required/></div><div  class='col-md-6'><label>Срок визы до: </label><input type='date' name='visaTo' placeholder='ДД/ММ/ГГГГ' class='form-control date' required/></div><input type='hidden' name='id' value='" + btoa(eid) + "'/></form><hr/>",
        buttons: {
            formSubmit: {
                text: 'Изменить',
                btnClass: 'btn-green',
                action: function () {
                    var from = this.$content.find('input[name=visaFrom]').val();
                    var to = this.$content.find('input[name=visaTo]').val();
                    if (!from || !to) {
                        $.alert(
                            {
                                title: 'Не верно указана дата! ',
                                content: 'Введите срок визы корректно!',
                                type: 'red'
                            });
                        return false;
                    }
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            'id': btoa(eid),
                            'from': btoa(from),
                            'to': btoa(to),
                            '_token': __token
                        },
                        success: function(data){
                            showNotificationEx(data.status, data.message);
                        }
                    });
                }
            },
            cancel:{
                text: 'Отмена',
            },
        },
        onContentReady: function () {
            this.$content.find('input[name=from]').focus();
        }
    });
}

function preCleanRoom() {
    if (!checkSelected()) return false;
    sel_rooms = getSelected(false);
    var url = 'roomlists/cleantheroom';
    for (var i=0; i < sel_rooms.length; i++) {
        $.confirm({
            title: 'Уборка номера: ' + sel_rooms[i].roomnumb,
            columnClass:'col-md-6 col-md-offset-3',
            content: "<form id='room_id"+atob(sel_rooms[i].roomid) +"' action='" + url + "' class='formName'><div class='form-group'>" + $('#cleaners_list').html() + "<input type='hidden' name='id_room' value='" +sel_rooms[i].roomid + "'/><br><textarea name='text' placeholder='Примечание ...' maxlength='300' style='width:90%;margin:15px;' class='form-control' rows='5' required /></textarea></div></form>",
            buttons: {
                formSubmit: {
                    text: 'Уборка',
                    btnClass: 'btn-green',
                    action: function () {
                        var descr = this.$content.find('textarea[name=text]').val();
                        var id_room = this.$content.find('input[name=id_room]').val();
                        var cleaner = this.$content.find('#id_cleaner').val();
                        if (!cleaner) {
                            $.alert(
                                {
                                    title: 'Не верно указан имя горничной!',
                                    content: 'Укажите имя сотрудника который отвечает за уборку номера!',
                                    type: 'red'
                                });
                            return false;
                        }
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                'text': descr,
                                'id_room': id_room,
                                'cleaner': btoa(cleaner),
                                '_token': __token
                            },
                            success: function(data){
                                showNotificationEx(data.status, data.message);
                            }
                        });
                    }
                },
                cancel:{
                    text: 'Отмена',
                },
            },
            onContentReady: function () {
                this.$content.find('textarea[name=text]').focus();
            },
            onDestroy: function() {
                var el =  document.getElementsByClassName('jconfirm');
                if (el.length == 0 &&  _print_ids != '') {getMyRoomsdata();}
            },
        });
    }
}
function preCheckOutGuest() {
    if (!checkSelected('room_busy')) return false;
    sel_rooms = getSelected(false, 'room_busy');
    if (!sel_rooms) return false;
    _print_ids = '';
    operated = 0;
    for (var i=0; i < sel_rooms.length; i++) {
        var ids = atob(sel_rooms[i].lids).split(',');
        /*if (i>0)
            _print_ids += ',' + atob(sel_rooms[i].lids);
        else
            _print_ids += atob(sel_rooms[i].lids);*/

        for(var j=0;j<ids.length;j++) {
            var __id_current = ids[j];
            $.confirm({
                icon: 'fa fa-user fa-2x',
                animation: 'rotate',
                closeAnimation: 'zoom',
                closeIcon: true,
                offsetTop: 25 + (j*15),
                dragWindowGap: 25,
                type: 'blue',
                columnClass: 'xlarge',
                backgroundDismiss: false,
                escapeKey: true,
                animation: 'rotatey',
                title: '&nbsp;Номер (комната): ' + atob(sel_rooms[i].roomnumb) + '; Гость №:'+(j+1),
                content: 'url:/roomlists/guestscheckout?id_room=' + sel_rooms[i].roomid+'&id=' + btoa(__id_current),
                buttons: {
                    checkout: {
                        text: 'CheckOut',
                        btnClass: 'btn-red',
                        action: function(){

                            //var $print = this.$content.find('#enablePrint');
                            var $id = this.$content.find('#listok_id');
                            var $payed = this.$content.find('#payed :selected');
                            var $amount = this.$content.find('#amount');
                            var $paytp = this.$content.find('#paytp');

                            if (!$payed.val()) {
                                showNotificationEx('info', 'Pay status field required!');
                                return false;
                            }
                            if (!($amount.val()>0)) {
                                showNotificationEx('info', 'Amount field required!');
                                return false;
                            }
                            if (!$paytp.val()) {
                                showNotificationEx('info', 'Payment type field required!');
                                return false;
                            }
                            _print_ids += $id.val() + ',';
                            this.buttons.checkout.disable();
                            _checkOut($id.val(), 1, $payed.val(), $amount.val(), $paytp.val()); /*$print.prop('checked') ? 1:0*/
                        },
                    },
                    cancel: {
                        text: 'Cancel',
                        btnClass: 'btn-gray',
                        action: function(){return true;},
                    },
                },
                onDestroy: function() {
                    var el =  document.getElementsByClassName('jconfirm');
                    if (el.length == 0 &&  _print_ids != '') {_listokPrint();}
                },
            });
        }
    }
    return Promise.resolve();
}
function prePrint() {
    if (!checkSelected('room_busy')) return false;
    sel_rooms = getSelected(false, 'room_busy');
    if (!sel_rooms) return false;
    _print_ids = '';
    operated = 0;
    for (var i=0; i < sel_rooms.length; i++) {
        var ids = atob(sel_rooms[i].lids).split(',');
        for(var j=0;j<ids.length;j++) {
            var __id_current = ids[j];
            $.confirm({
                icon: 'fa fa-user fa-2x',
                animation: 'rotate',
                closeAnimation: 'zoom',
                closeIcon: true,
                offsetTop: 25 + (j*15),
                dragWindowGap: 25,
                type: 'blue',
                columnClass: 'xlarge',
                backgroundDismiss: false,
                escapeKey: true,
                animation: 'rotatey',
                title: '&nbsp;Номер (комната): ' + sel_rooms[i].roomnumb + '; Гость №:'+(j+1),
                content: 'url:/roomlists/guestscheckout?id_room=' + sel_rooms[i].roomid+'&id=' + btoa(__id_current),
                buttons: {
                    print_btn: {
                        text: 'Печать',
                        btnClass: 'btn-blue',
                        action: function(){
                            this.buttons.print_btn.disable();
                            var $id = this.$content.find('#listok_id');
                            _print_ids += $id.val() + ',';
                        },
                    },
                    cancel: {
                        text: 'Cancel',
                        btnClass: 'btn-gray',
                        action: function(){return true;},
                    },
                },
                onDestroy: function() {
                    var el =  document.getElementsByClassName('jconfirm');
                    if (el.length == 0 &&  _print_ids != '') {_listokPrint();}
                },
            });
        }
    }
    return Promise.resolve();
}
function showGuerstInfo(){
    if (!checkSelected()) return false;
    var sel_rooms = getSelected();
    if (!sel_rooms) return false;
    for (var i=0; i<sel_rooms.length; i++) {
        var ids = atob(sel_rooms[i].lids).split(',');
        for(var j=0;j<ids.length;j++) {
            var __id_current = ids[j];
            $.alert({
                icon: 'fa fa-user fa-2x',
                animation: 'rotate',
                closeAnimation: 'rotate',
                closeIcon: true,
                offsetTop: 20 + (j*12),
                dragWindowGap: 20,
                type: 'blue',
                columnClass: 'xlarge',
                backgroundDismiss: true,
                escapeKey: true,
                animation: 'rotatey',
                title: '&nbsp;Номер (комната): ' + atob(sel_rooms[i].roomnumb) + '; Гость №:'+(j+1),
                content: 'url:/roomlists/guestrequestrooms?id_room=' + sel_rooms[i].roomid+'&id=' + btoa(__id_current)
            });
        }
    }
}
function MoveTo() {
    if (!checkSelected('room_busy')) return false;
    sel_rooms = getSelected(false, 'room_busy');
    if (!sel_rooms) return false;
    operated = 0;
    for (var i=0; i < sel_rooms.length; i++) {
        var ids = atob(sel_rooms[i].lids).split(',');
        for(var j=0;j<ids.length;j++) {
            var __id_current = ids[j];
            $.confirm({
                icon: 'fa fa-user fa-2x',
                animation: 'scale',
                closeAnimation: 'rotate',
                closeIcon: true,
                offsetTop: 25 + (j*15),
                dragWindowGap: 25,
                type: 'blue',
                columnClass: 'xlarge',
                backgroundDismiss: false,
                escapeKey: true,
                animation: 'rotatey',
                title: '&nbsp;Номер (комната): ' + sel_rooms[i].roomnumb + '; Гость №:'+(j+1),
                content: 'url:/roomlists/movetootherroom?id_room=' + sel_rooms[i].roomid+'&id=' + btoa(__id_current),
                buttons: {
                    moveto: {
                        text: 'Перевести',
                        btnClass: 'btn-blue',
                        action: function(){
                            this.buttons.moveto.disable();
                            var $room = this.$content.find('select[name="id_roomm"]');
                            var $id_reg = this.$content.find('input[name="id_reg"]');
                            _moveTo($id_reg.val(), $room.val());
                        },
                    },
                    cancel: {
                        text: 'Отмена',
                        btnClass: 'btn-gray',
                        action: function(){return true;},
                    },
                },
                onDestroy: function() {
                    var el =  document.getElementsByClassName('jconfirm');
                    if (el.length == 0) getMyRoomsdata();
                },
            });
        }
    }
    return Promise.resolve();
}
function PayStatusRoom() {
    if (!checkSelected('room_busy')) return false;
    sel_rooms = getSelected(false, 'room_busy');
    if (!sel_rooms) return false;
    var url = 'roomlists/setpaymentinfo';
    operated = 0;
    for (var i=0; i < sel_rooms.length; i++) {
        var ids = atob(sel_rooms[i].lids).split(',');
        for(var j=0;j<ids.length;j++) {
            var __id_current = ids[j];
            $.confirm({
                icon: 'fa fa-user fa-money',
                animation: 'scale',
                closeAnimation: 'rotate',
                closeIcon: true,
                offsetTop: 25 + (j*16),
                dragWindowGap: 29,
                type: 'green',
                columnClass: 'large',
                backgroundDismiss: false,
                escapeKey: true,
                animation: 'rotatey',
                title: 'Статус оплаты: Номер (комната): ' + sel_rooms[i].roomnumb + '; Гость №:'+(j+1),
                content: 'url:/roomlists/setpaymentinforoom?id_room=' + sel_rooms[i].roomid+'&id=' + btoa(__id_current),
                buttons: {
                    paybtn: {
                        text: 'Сохранить',
                        btnClass: 'btn-green',
                        action: function(){
                            this.buttons.paybtn.disable();
                            var $id_reg = this.$content.find('input[name="id_reg"]');
                            var $payst = this.$content.find('select[name="paystatus"]');
                            var $amount = this.$content.find('input[name="amount"]');
                            _payFor($id_reg.val(), $payst.val(),$amount.val());
                        },
                    },
                    cancel: {
                        text: 'Отмена',
                        btnClass: 'btn-gray',
                        action: function(){return true;},
                    },
                },
                onDestroy: function() {
                    var el =  document.getElementsByClassName('jconfirm');
                    if (el.length == 0) getMyRoomsdata();
                },
            });
        }
    }
    return Promise.resolve();
}
function AddFeedback() {
    if (!checkSelected('room_busy')) return false;
    sel_rooms = getSelected(false, 'room_busy');
    if (!sel_rooms) return false;
    for (var i=0; i < sel_rooms.length; i++) {
        var ids = atob(sel_rooms[i].lids).split(',');
        var ctzs = atob(sel_rooms[i].ctzs).split(',');
        var guests = atob(sel_rooms[i].guests).split(',');
        for(var j=0;j<ids.length;j++) {
            _addNote(ids[j], guests[j],ctzs[j]);
        }
    }
}
function AddVisa() {
    if (!checkSelected('room_busy')) return false;
    sel_rooms = getSelected(false, 'room_busy');
    if (!sel_rooms) return false;
    for (var i=0; i < sel_rooms.length; i++) {
        var ids = atob(sel_rooms[i].lids).split(',');
        var ctzs = atob(sel_rooms[i].ctzs).split(',');
        var guests = atob(sel_rooms[i].guests).split(',');
        for(var j=0;j<ids.length;j++) {
            _visaExtension(ids[j], guests[j],ctzs[j]);
        }
    }
}
function getMyRoomsdata() {
    $('#rooms_draw_point').empty();
    $.get(_URL_ROOMS, {
        token: __token,
        srch_tag: $('#srch-tag').val(),
        srch_floor: $('#srch-floor').val(),
        srch_room_type: $('#srch-room-type option:selected').val(),
        srch_room_st: $('#srch-room-st option:selected').val()
    }, function (result) {
        if (result.status == 'success') {
            drawRooms(result.data);
            return false;
        }
        else
            showNotificationEx('info', 'Sorry, rooms not found!');
    });
}

$('#srch-floor,#srch-tag').on("keypress", function(e) {
    if (e.keyCode == 13) {
        getMyRoomsdata();
        return false; // prevent the button click from happening
    }
});
$("#srch-room-type,#srch-room-st").change(function() {
    getMyRoomsdata();
    return false;
});

function myFunction(id) {
    //console.log('#'+id);
    $("#" + id).classList.toggle("show");
}

function redrawRooms() {
    $('.inner .item i.fa-info-circle').hover(function () {
        var color = "black",
            hint = $(this).parent().find('.morehover');
        if (($(hint).text()).trim() == "") return 0;
        $(hint).parent().css('z-index', 999993000);
        $(hint).fadeIn();
    }, function () {
        var hint = $(this).parent().find('.morehover').first();
        $(hint).fadeOut();
    });
    $('.inner .item').click(function () {
        $(this).toggleClass('check');
    });
    $('.inner .item').on('contextmenu', function(e) {
        e.preventDefault();
        if($(this).hasClass('check')) superCm.createMenu(myMenu, e);
    });
    $('#actions-btn').on('contextmenu', function(e) {
        e.preventDefault();superCm.createMenu(myMenu, e);
    });
    $('#actions-btn').on('click', function(e) {
        e.preventDefault();superCm.createMenu(myMenu, e);
    });
}
$(document).ready(function () {;});
window.onload = function(){getMyRoomsdata();}