$(document).ready(function () {

//dashboard js

    $('.dashselect .total-countbox').on('click', function (event) {
        $('.dashselect .total-countbox.selected').removeClass('selected');
        $(event.currentTarget).addClass('selected');
        var boxDatatype = $(event.currentTarget).attr('data-tab');
        getbox(boxDatatype);
    });

    function getbox(boxDatatype) {
        if (boxDatatype == "tabs-1") {
            $('.main-box').hide();
            $('.main-box[data="' + boxDatatype + '"]').show();
        }
        if (boxDatatype == "tabs-2") {
            $('.main-box').hide();
            $('.main-box[data="' + boxDatatype + '"]').show();
        }
        if (boxDatatype == "tabs-3") {
            $('.main-box').hide();
            $('.main-box[data="' + boxDatatype + '"]').show();
        }
    }

//

    $("#quotation-merchant_id").change(function () {
        $("#quotation-cat_id").val("");
        $("#quotation-partners").html('');
        return false;
    });

    $('input[type=file]').bootstrapFileInput();
    $('.file-inputs').bootstrapFileInput();
    $('#utSelect').prop('selectedIndex', -1);
    $('#utSelect2').prop('selectedIndex', -1);
    $("#quotation-cat_id").change(function () {
        var catId = this.value;
        var merId = $("#quotation-merchant_id").val();
        //alert(catId+ ' ' + merId);return false;
        if (catId.length > 0 && merId.length > 0) {
            $.ajax('getpartners', {
                type: 'POST',  // http method
                data: {catId: catId, merId: merId},  // data to submit
                success: function (data, status, xhr) {
                    $("#quotation-partners").html(data);
                },
                error: function (jqXhr, textStatus, errorMessage) {

                }
            });
        } else {
            if(merId.length == 0) {
                alert("Please select merchant!");
                return false;
            }
        }
    });
}); // (jQuery)End of use strict
