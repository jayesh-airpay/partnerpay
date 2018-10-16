$.validator.addMethod('filesize', function () {
    console.log($('input[name="bulk_upload"]').val());
    if($('input[name="bulk_upload"]').val()!=""){
        return ($('input[name="bulk_upload"]')[0].files[0].size<= 10000)
    } else {
        return true;
    }
}, 'File size must be less than {0}');
$.validator.addMethod("regex",function(value, element, regexp) {
    // console.log('asdasd');
        var check = false;
        return this.optional(element) || regexp.test(value);
    },
    "Please check your input."
);
$(document).ready(function () {
    $("#bill_details").validate({
        rules: {
            email: {
                required: function(element){
                    return ($("#bulk_upload").val().length == 0);
                },
                email: true,
            },
            fname:{
                required: function(element){
                    return ($("#bulk_upload").val().length == 0);
                }, 
                lettersonly: true,   
            },
            lname:{
                required: function(element){
                    return ($("#bulk_upload").val().length == 0);
                },
                lettersonly: true,
            },
            bulk_upload: {
                required:function(element){
                    var valid=false;
                    // return ($("#email").val().length == 0 && $("#fname").val().length == 0 && $("#lname").val().length == 0 && $("#mobile_no").val().length == 0);
                    $('.dynamic_field').each(function() {
                        if ($(this).val() == "") {
                           valid=true;
                           return false;
                        }
                    });
                   return valid; 
                },
                extension: "csv",
                filesize: true
                
            },
            
        },
        messages: {
            bulk_upload: {
                required: "Bulk Upload File is Required",
                extension: "Invalid File Format",
                filesize:"Bulk Upload Size Not Valid"
            },
            email: {
                required: "Email is Required",
                email: "Email format not proper"
            },
            fname :{
                required: "First Name is required",
                lettersonly: "Invalid First Name",
            },
            lname :{
                required: "Last Name is required",
                lettersonly: "Invalid Last Name",
            },
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "bulk_upload"){
                    error.insertAfter("a.file-input-wrapper");
            } else if (element.attr("name") == "providers"){
                error.insertAfter(".page-header")
            } else {
                    error.insertAfter(element);
            }
            },
        submitHandler: function (form, event) {
                form.submit();
        }
    });

    $("#remove_filter").validate({
        rules: {
            utility: {
                required: true,
            },
            providers: {
                required: true,
            },
            
        },
        messages: {
            utility: {
                required: "This field is Required",
            },
            providers: {
                required: "This field is Required",
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

	$("#wallet_topup").validate({
        rules: {
            invoice_amount: {
                required : true,
                number : true,
                range : [1,500],
            }
        },
        messages: {
            invoice_amount: {
                required : "Topup amount is Required",
                number : "Invalid Topup amount",
                range : "Topup Amount out of range",

            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("#payment").validate({
        rules: {
            merchant: {
                required: true,
            },
            invoice_amount: {
                required: true,
            },
            agree: {
                required: true,
            },
            payment_mode: {
                required: true,
            },
            bill_amount: {
                required: true,
            },
        },
        messages: {
            merchant: {
                required: "Merchant Name field is Required",
            },
            invoice_amount: {
                required: "This field is Required",
            },
            agree: {
                required: "Please select terms and condition",
            },
            payment_mode: {
                required: "This field is Required",
            },
            bill_amount: {
                required: "This field is Required",
            },
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "agree"){
                    error.insertBefore(".field-invoice-iagree");
            } else {
                    error.insertAfter(element);
            }
            },
        submitHandler: function (form) {
            var id= $('#invoice_no').val();
            $.ajax({
                url: "/partnerpay/web/bbps/default/payment_amount_check",  
                data: {invoice_id: id},
               type: "POST",
                dataType: "json",
                success: function(data) {
                    var charges = JSON.parse(data.charges);
                    var charge_mode =  $('#payment_mode').val();
                    calculatedAmount = (charges[charge_mode] * data.sum) / 100;
                    b_chgs = calculatedAmount * taxRate;
                    tot_amt = parseFloat(data.sum) + parseFloat(calculatedAmount) + parseFloat(b_chgs);
                    $('#invoice_amount').val(parseFloat(tot_amt).toFixed(2));
                    $('#total_amount').val(parseFloat(tot_amt).toFixed(2))
                    $('#bill_amount').val(data.sum);
                    form.submit();
                }
             });
            
        }
    });
});