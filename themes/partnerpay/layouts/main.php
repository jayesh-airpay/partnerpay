<?php
/**
 * Created by PhpStorm.
 * User: nandana
 * Date: 21/3/16
 * Time: 11:08 AM
 */
\app\assets\AppAsset::register($this);
$this->beginPage();
$theme = $this->theme;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= \yii\helpers\Html::csrfMetaTags() ?>
    <title>Partnerpay</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $theme->baseUrl. '/images/favicon.ico';?>">
    <?php $this->head(); ?>
    <link rel="stylesheet" href="<?php echo $theme->baseUrl. '/css/font-awesome.css';?>" type="text/css" />
    <link rel="stylesheet" href="/bbps/js/jquery-ui/jquery-ui.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $theme->baseUrl. '/css/custom.css'?>" type="text/css" />
      <link rel="stylesheet" href="<?php echo $theme->baseUrl. '/css/select2.css'?>" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<?php
$this->beginBody();
?>
<script>var po_data = '';var invoicecounter='';</script>
<body>
    <!--------------- Start Menu --------------->
    <?= $this->render('menu.php', ['theme' => $theme]) ?>
    <!--------------- End Menu --------------->
    <div class="wrapper">
        <div class="container">
            <?php
                echo $content;
            ?>
         </div>
     </div>


    <div class="footer">
        <div class="container copyright"><p>Copyright &copy; <?php echo date("Y"); ?> by airpay.</p></div>
    </div>
    <?php
    //$this->registerJsFile($theme->baseUrl.'/js/bootstrap.min.js', ['depends' => [\yii\web\JqueryAsset::className()], 'position' => \yii\web\View::POS_END]);
    $this->registerJsFile($theme->baseUrl.'/js/bootstrap.file-input.js', ['depends' => [\yii\web\JqueryAsset::className()], 'position' => \yii\web\View::POS_END]);
    $this->registerJsFile($theme->baseUrl.'/js/custom.js', ['depends' => [\yii\web\JqueryAsset::className()], 'position' => \yii\web\View::POS_END]);
    $this->registerJsFile($theme->baseUrl.'/js/select2.js', ['depends' => [\yii\web\JqueryAsset::className()], 'position' => \yii\web\View::POS_END]);
    $this->endBody();
    ?>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.js"></script>
        <script type="text/javascript" src="/bbps/js/validate.js"></script>
        <script src="/bbps/js/jquery.dataTables.min.js"></script>
	
</body>
<script>$('.multiplebox').select2();</script>
<script>
$(document).ready(function(){
    var status = $("#q_status").val();
   if(status == 'Executed'){
     
      $("input[type=radio]").attr('disabled', true);
   }else{
      $("input[type=radio]").attr('enabled', true);
   }
});
$("#quotation-merchant_id").change(function () {
        $("#quotation-cat_id").val("");
        $("#quotation-partners").html('');
        return false;
});
</script>

<script>
  function chargeval(){
        chargeRegex = /^[a-zA-Z ]*$/;
        chargeValRegex = /[+-]?([0-9]*[.])?[0-9]+/;
        $('.error-msg').html('');
        var validate = true;
        
        var pomaster_amount = parseInt($("#pomaster-amount").val());
        var a_pomaster_amount = parseInt($("#pomaster-amount").attr('data-amount'));
       
        if(pomaster_amount > a_pomaster_amount){
           $("#pomaster-amount").next('.help-block').text('Amount can not be greater than '+$("#pomaster-amount").attr('data-amount')).css('color','#d01d19');
           validate = false;
        }
  
        $('input[name^="charge_name"]').each(function() {

            if($(this).val() == ''){
               $(this).next().html('Please enter charge name');
               validate = false;
            }

            if(!chargeRegex.test($(this).val())){
               $(this).next().html('Please enter valid charge name');
               validate = false;
            }
        });

        $('input[name^="charge_value"]').each(function() {
            if($(this).val() == ''){
                $(this).next().html('Please enter charge value');
                validate = false;
            }

            if(!chargeValRegex.test($(this).val())){
               $(this).next().html('Please enter valid charge value');
               validate = false;
            }
        });

        return validate;
    }


$(document).ready(function(){

  $('#invoice-po_id').change(function(){
 
     var po_number = $("#invoice-po_id").val();
     host = window.location.hostname;
     
     $.ajax({
        url: "invoice/partnerdata", 
        method : 'post',
        data : {"po_number":po_number},
        success: function(result){
           if(result){
               $("#invoice-assign_to").val(result).attr('selected',true);
           }else{
               alert('Unable to get partner details plz try after some time.');
           }
            
        }
    });
});
   
  $("#pomaster-partner_id").change(function(){
     var partner_id = $("#pomaster-partner_id").val();
     $.ajax({
        url: "/po/quotationlist", 
        method : 'post',
        data : {"id":partner_id},
        success: function(result){
               $("#pomaster-quotation_id").html(result);
        }
    });
  });

    var status = $("#q_status").val();
   if(status == 'Executed'){
     
      $("input[type=radio]").attr('disabled', true);
   }else{
      $("input[type=radio]").attr('enabled', true);
   }
});
$("#quotation-merchant_id").change(function () {
        $("#quotation-cat_id").val("");
        $("#quotation-partners").html('');
        return false;
});

  jQuery(".remove").click(function(){
    $(this).parent().parent().parent().remove();
  });


jQuery("#addmore").click(function () {
  jQuery("#addmorewrap").append("<div class='row'><div class='col-xs-5'><div class='form-group req'><input type='text' class='form-control' placeholder='Charge Name' name='charge_name[]'> <div class='error-msg help-block' style='color:#d01d19;'></div></div></div><div class='col-xs-5'><div class='form-group req'><input type='text' class='form-control' placeholder='Charge Value' name='charge_value[]'> <div class='error-msg help-block' style='color:#d01d19;'></div></div></div><div class='col-xs-2'><div class='form-group text-right'><button type='button' class='btn btn-default remove' name='removerangebtn1' value='remove' >-</button></div></div></div>");

  jQuery(".remove").click(function(){
    $(this).parent().parent().parent().remove();
  });
});

$('.po_status').change(function(){
     var status  = $('input[name=status]:checked').val();
     var po_id = $("#PO_ID").val();
     host = window.location.hostname;
 
     $.ajax({
        url: "/po/changestatus", 
        method : 'post',
        data : {"id":po_id,"status":status},
        success: function(result){
            if(result){
              if(status == 'A'){
                alert('PO Approved successfully');
                $("#invoice_link").html('Click <a href="http://'+host+'/invoice/create?po_id='+po_id+'"><u>here</u></a> to create invoice.').show();
              }else{
                alert('PO Rejected successfully');
              }
            }else{
               alert('Unable to process your request please try after sometime.');
            }
        }
    });
});

$("#invoice-po_id").change(function(){
      var po_id = $(this).val(); 
      $.ajax({
        url: "/po/invoiceamt", 
        method : 'post',
        data : {"po_id":po_id},
        dataType: "json",
        success: function(result){
            if(result){
               $("#invoice-amount").val(result.AMOUNT);
               $("#invoice-amount").attr("readonly",true);
               $("#invoice-tax_amount").val(result.TAX);
               $("#invoice-tax_amount").attr("readonly",true);
            }else{
               alert('Unable to get PO Amount now please try again later.');
            }
        }
    });
});

</script>

<!-- Added on 02-07-2018-->
<script>
//$( "#w0" ).addClass( "table-responsive" );
var a = $(".summary").text();
$(".summary").html('<div class="pull-right">'+a+'</div>');

function removeelement(t){
  console.log(t);
}

</script>
<script type="text/javascript">
var counter = 1;
$("#po").change(function () {
/*old code
  po_val =  $("#po").val();

  
  if(po_val !== null){
    po_val  = po_val.toString();
    po_val  = po_val.slice(-2);
    po_val  = po_val.replace(',','');
  }

  if($("#"+po_val).length){
    return false;
  }
*/

  po_val =  $("#po").val();
  po_val_new = Object.values(po_val);
  po_val_new.sort(function(a, b){return a-b});

  if(po_val !== null){
    po_val  = po_val.toString();
    po_val  = po_val.slice(-2);
    po_val  = po_val.replace(',','');
  }

  if($("#"+po_val).length){
    for(i=0;i<po_val_new.length;i++){
      if(!$("#"+po_val_new[i]).length){
         po_val = po_val_new[i];
      }
    }
  }

  if($("#"+po_val).length){
      return false;
  }


  if (counter <= 50){
 
    if($("#invoice_"+counter).length){
        counter++;
    }
    
    var k = parseInt(counter);

    if(counter != invoicecounter){
      $(".viewDetails").append("<div class='row viewrow' id='invoice_"+counter+"'><div class='col-sm-6 col-md-4'>PO Number : "+po_data_rev[po_val]+"<input type='hidden' name='po"+counter+"' value='"+po_val+"' id='"+po_val+"' class='amountcheck' data-counter='"+counter+"'><div class='form-group req'><input type='text' class='form-control' placeholder='Invoice Number' id='refnum"+counter+"' name='refnum"+counter+"'><div class='validationAlert hasError help-block' style='color:#d01d19;' id='refnum"+counter+"_err'></div></div><div class='form-group req'><input type='text' class='form-control' placeholder='Invoice Amount' id='invamt"+counter+"' name='invamt"+counter+"'><div class='validationAlert hasError help-block' style='color:#d01d19;' id='invamt"+counter+"_err'></div></div><div class='form-group req'><input type='text' class='form-control' placeholder='Tax Amount' id='txamt"+counter+"' name='txamt"+counter+"'><div class='validationAlert hasError help-block' style='color:#d01d19;' id='txamt"+counter+"_err'></div></div><div class='form-group'><div class='form-control req file'><input type='file' class='updoc"+ k +"' title='Upload Chalan' id='updoc"+counter+"' name='updoc"+counter+"'><span>(Only pdf,doc,docx,xls and xlsx files are allowed)</span><div class='validationAlert hasError help-block' style='color:#d01d19;' id='updoc"+counter+"_err'></div></div></div><div class='form-group'><!--<a href='javascript:void(0);' class='btn btn-default remove'  data-num='"+counter+"'><i></i> Delete </a>--></div>");
        $('.updoc'+ k +'').bootstrapFileInput();
        $('.remove').click(function () { 
            datanum = $(this).attr('data-num');
            counter = counter -1;
            $(this).parents('.viewrow').fadeOut();
            $("#invoice_"+datanum).remove();
           //$(this).parents('.viewrow').delete();
        });

        counter++;
    }
    invoicecounter = '';
  }

  $.ajax('/invoice/getinvoiceamt', {
      type: 'POST',  // http method
      dataType:'json',
      data: { PO_ID: $("#po").val() },  // data to submit
      success: function (data, status, xhr) {

          for(i=0;i<data.length;i++){
             console.log(data[i]);
             $("#"+data[i].PO_ID).attr("data-invamts",data[i].INV_AMT);
             $("#"+data[i].PO_ID).attr("data-amount",data[i].AMOUNT);
             $("#"+data[i].PO_ID).attr("data-elementid",'invamt');
          }
      },
      error: function (jqXhr, textStatus, errorMessage) {
        alert('Unable to process your request plz try after some time.');
      }
   });

  $("#count").val(counter);
});
</script>
<script>
  $(".select2-selection__choice__remove").click(function(){
      //alert('hi');
  });
$(document).ready(function(){
    
    if($("#qr").val() == 'Y'){
      $("#merchantmaster-create_qr").attr('checked',true);
    }

    $("#partner").change(function(){
     $.ajax({url: "/invoice/polisting",method:'post',data:{partnerid:$("#partner").val()}, success: function(result){
        $("#po").html(result);
    }});
 });

$("#pomaster-quotation_id").change(function(){
     $.ajax('/quotation/qramt', {
      type: 'POST',  // http method
      dataType:'json',
      data: { QR_ID: this.value },  // data to submit
      success: function (data, status, xhr) {
         if(data.success){
           $("#pomaster-amount").attr('data-amount',data.amount);
           $("#pomaster-amount").val(data.amount);
         }else{
           alert('Unable to find quotation amount please try after sometime.');
           $("#pomaster-amount").attr('disabled',true);
         }
      },
      error: function (jqXhr, textStatus, errorMessage) {
        alert('Unable to process your request plz try after some time.');
      }
   });
});

 });

</script>
<!-- end -->
</html>
<?php
$this->endPage();
?>
