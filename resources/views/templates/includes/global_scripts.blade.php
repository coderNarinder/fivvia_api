 <script type="text/javascript">

  var change_primary_data_url = "{{ route('changePrimaryData') }}";
  $('.customerLang').click(function() {
        var changLang = $(this).attr('langId');
        settingData('language', changLang);
    });

    $('.customerCurr').click(function() {
        var changcurrId = $(this).attr('currId');
        var changSymbol = $(this).attr('currSymbol');
        settingData('currency', changcurrId, changSymbol);
    });

function settingData(type = '', v1 = '', v2 = '') {
        $.ajax({
            type: "post",
            dataType: "json",
            url: change_primary_data_url,
            data: {
                "type": type,
                "value1": v1,
                "value2": v2
            },
            success: function(response) {
                window.location.reload();
            },
            error: function(data) {
                window.location.reload();
            },
        });
}
</script>