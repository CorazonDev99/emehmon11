
<script src="/assets/libs/jquery/jquery.min.js"></script>
<script src="/assets/libs/select2/js/select2.min.js"></script>

<script>
    function UpdateSelectList(data,domid,selid){
        $(domid).append('<option value="" ' + (selid == -1 ? ' selected': '') + '> --- НЕ ВЫБРАНО ---</option>');
        $.each(data,function(key, value){$(domid).append('<option value="'+value[0]+'" '+(selid==value[0]?' selected':'')+'>'+value[1]+'</option>');});
        $(domid).trigger('change');
    }

    $.getJSON("{!! url('listok/comboselect?filter=citizens:id:SP_NAME03') !!}",function(json){
        UpdateSelectList(json,'#id_country',{{ !empty($row["id_country"]) ? $row["id_country"] : -1}});
        UpdateSelectList(json,'#id_citizen',{{ !empty($row["id_citizen"]) ? $row["id_citizen"] : -1}});
        UpdateSelectList(json,'#id_countryFrom',{{ !empty($row["id_countryFrom"]) ? $row["id_countryFrom"] : -1}});
    });

    $('.select2').select2({
        parent: $('#jconfirm-row')
    });
    // add select2 to select2 class
</script>
