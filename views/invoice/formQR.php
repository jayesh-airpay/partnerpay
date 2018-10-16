<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


//start
$mercid  = \Yii::$app->user->identity->MERCHANT_ID;
$partnerid  = \Yii::$app->user->identity->PARTNER_ID;
$po_id = '';
if(isset($_GET['po_id'])){
    $po_id = trim($_GET['po_id']);
}
$connection = Yii::$app->getDb();
if(\Yii::$app->user->identity == 'partner'){
$command = $connection->createCommand("SELECT tbl_po_master.PO_ID,tbl_po_master.PO_NUMBER,tbl_po_master.AMOUNT as poamt,sum(tbl_invoice.AMOUNT) as invamt from tbl_po_master left join tbl_invoice on tbl_po_master.PO_ID = tbl_invoice.PO_ID where tbl_po_master.MERCHANT_ID= '$mercid' and tbl_po_master.PARTNER_ID='$partnerid' and  tbl_po_master.STATUS='A' group by tbl_po_master.PO_ID", []);

}else{
$command = $connection->createCommand("SELECT tbl_po_master.PO_ID,tbl_po_master.PO_NUMBER,tbl_po_master.AMOUNT as poamt,sum(tbl_invoice.AMOUNT) as invamt from tbl_po_master left join tbl_invoice on tbl_po_master.PO_ID = tbl_invoice.PO_ID where tbl_po_master.MERCHANT_ID= '$mercid' and  tbl_po_master.STATUS='A' group by tbl_po_master.PO_ID", []);

}
$podata = $command->queryAll();
//end

/* @var $this yii\web\View */
/* @var $model app\models\Invoice */
/* @var $form yii\widgets\ActiveForm */
$cuser = \Yii::$app->user->identity;
$mercid = $cuser->MERCHANT_ID;
$podata_select = [];
//$podata = app\models\PoMaster::find()->select('PO_ID, PO_NUMBER')->where(['MERCHANT_ID' => $mercid])->asArray()->all();
foreach ($podata as $key => $value) {
	if($value['invamt'] < $value['poamt']){
	   $podata_select[$value['PO_ID']] = $value['PO_NUMBER'];
	}
}

$podata_select_flip = array_flip($podata_select);

$partnerdata = app\models\Partner::find()->select('PARTNER_ID, PARTNER_NAME')->all();
?>
<?php
$this->registerJsFile('@web/js/jquery-ui.min.js',['depends' => [\yii\web\JqueryAsset::className()], 'position' => \yii\web\View::POS_END]);
$this->registerCssFile('@web/css/jquery-ui.css');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/jquery-ui-timepicker-addon.js', ['depends' => [\yii\web\JqueryAsset::className()], 'position' => \yii\web\View::POS_END]);
//$this->registerJsFile(Yii::$app->request->baseUrl . '/js/jquery-ui-timepicker-addon.js', ['depends' => [\yii\web\JqueryAsset::className()], 'position' => \yii\web\View::POS_END]);
//$this->registerJsFile(Yii::$app->request->baseUrl . '/js/jquery.min.js', array('position' => $this::POS_HEAD), 'jquery');
$this->registerCss("
.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
.ui-timepicker-div dl { text-align: left; }
.ui-timepicker-div dl dt { float: left; clear:left; padding: 0 0 0 5px; }
.ui-timepicker-div dl dd { margin: 0 10px 10px 45%; }
.ui-timepicker-div td { font-size: 90%; }
.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }

.ui-timepicker-rtl{ direction: rtl; }
.ui-timepicker-rtl dl { text-align: right; padding: 0 5px 0 0; }
.ui-timepicker-rtl dl dt{ float: right; clear: right; }
.ui-timepicker-rtl dl dd { margin: 0 45% 10px 10px; }");

$this->registerJs('
$("#invoice-expiry_date").datetimepicker({
    dateFormat: "dd-M-yy",
    showTimepicker: false
   
});
$(".datepicker").datetimepicker({
    dateFormat: "dd-M-yy",
    showTimepicker: false,
    minDate : 0
   
});

$("#issuedate").datetimepicker({
    dateFormat: "dd-mm-yy",
    showTimepicker: false,
    minDate : 0
   
});

$("#duedate").datetimepicker({
    dateFormat: "dd-mm-yy",
    showTimepicker: false,
    minDate : 0
   
});

    $("#advanced").change(function(){
	if(!$(\'#advanced\').is(":checked")){
       $(\'.file\').attr(\'class\',\'form-group file req\');
	}else{
       $(\'.file\').attr(\'class\',\'form-group file\');
	}
});

', yii\web\View::POS_READY, 'datetimepickerjs');

$this->registerJs('
var po_inv_id = "'.$po_id.'";

if(po_inv_id != ""){
  $("#po").val(po_inv_id);
  $("#po").change();
}

');
?>

		<div class="page-header">
			<h4>Create Invoices </h4>
			<div class="fieldstx">Fields with <span>*</span> are required.</div>
		</div>
		<form id="formPO" name ="formPO" action="/invoice/createpoinvoices" method="post" class="form" role="form" onsubmit = "return valFrm();" enctype="multipart/form-data">
		  <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>"
           value="<?=Yii::$app->request->csrfToken?>"/>
       <?php if($cuser->USER_TYPE != 'partner') {?>
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="form-group req">
					<select id="partner" class="form-control" name="partner">
						<option value="">Select Partner for invoice</option>
						<?php
                           if(count($partnerdata)){
                           	   foreach($partnerdata as $k => $v){
                                  echo '<option value="'.$v->PARTNER_ID.'">'.$v->PARTNER_NAME.'</option>';
                           	   }
                           }
						?>
					</select>
				</div>
			</div>
		</div>
		<?php } ?>
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="form-group req">
					<!-- <label>Select PO</label> -->
					<select id="po" name="po" class="form-control multiplebox" name="" multiple="multiple" data-placeholder="Select PO">
						<option value="">Select PO</option>
						<?php
                           if(count($podata_select)){
                           	   foreach($podata_select as $k => $v){
                                  echo '<option value="'.$k.'">'.$v.'</option>';
                           	   }
                           }
						?>
					</select>
					<div id="po_err" class="validationAlert help-block" style="color:#d01d19;"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="form-group req">
				<input type="text" class="form-control" placeholder="Issue Date" readonly="readonly" id="issuedate" name="issuedate">
				<div id="issuedate_err" class="validationAlert help-block" style="color:#d01d19;"></div>
				</div>
			</div>
		</div>
	    <div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="form-group req">
				<input type="text" class="form-control" placeholder="Due Date" readonly="readonly" id="duedate" name="duedate">
				<div id="duedate_err" class="validationAlert help-block" style="color:#d01d19;"></div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="form-group">
					<select id="surcharge" class="form-control" name="surcharge">
						<option value="">Apply Surcharge?</option>
						<option value="1">Yes</option>
						<option value="2">No</option>
					</select>
					<div id="surcharge_err" class="validationAlert hasError help-block" style="color:#d01d19;"></div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<label>Invoice Type</label>
				<div class="form-group">
					<div class="checkbox"><label><input type="checkbox" id="advanced" name="advanced">Advanced</label></div>
				</div>
				
			</div>
		</div>

		<div id="viewwrap">
			<div class="viewDetails">
				<!-- <div class="row">
					<div class="col-sm-6 col-md-4">
						<input type="hidden" name="po1" id="po1" value="">
						<div class="form-group req">
							<input type="text" class="form-control" placeholder="Reference Number" id="refnum1" name="refnum1">
							<div class="validationAlert" id="refnum1_err"></div>
						</div>
						<div class="form-group req">
							<input type="text" class="form-control" placeholder="Invoice Amount" id="invamt1" name="invamt1">
							<div class="validationAlert" id="invamt1_err"></div>
						</div>
						<div class="form-group req">
							<input type="text" class="form-control" placeholder="Tax Amount" id="txamt1" name="txamt1">
							<div class="validationAlert" id="txamt1_err"></div>
						</div>
						<div class="form-group"> 
							<div class="form-control file"><input type="file" class="updoc" title="Upload Chalan" id="updoc1" name="updoc1"></div>
							<div class="validationAlert" id="updoc1_err"></div>
						</div>
					</div>
				</div>  -->
			</div>
			<div class="row">
				<div class="col-sm-6 col-md-4 btnwrap">
					<div class="form-group"> 
					<!-- <a href="javascript:void(0);" class="btn btn-default addmore" id="addmore"><i>+</i> Add more</a> -->
				</div>
				</div> 
			</div> 
		</div>
		
		<div class="row">
			<input type="hidden" id="count" name="count">
			<div class="col-sm-6 col-md-4">
				<div class="formbtn-group">					
				<input type="submit" class="btn btn-primary" id="submitform" value="Submit">
				<input type="button" class="btn btn-secondary" id="cancelform" value="Cancel" onclick="javascript:window.location='/invoice';">
				</div>
			</div>
		</div>
		</form>


<script>
                        
                    
function valFrm(){
    $("input").css("border-color","");
    $(".select2-selection--multiple").css("border-color","");
	amtRegex = /^\d+(\.\d{1,2})?$/;
    refNumRegex = /^([0-9a-zA-Z]+)$/;
	$('.validationAlert').html('');
	isValidate = true;
    
    if($.trim($("#po").val()) == ''){
        $('#po_err').html('Please select PO.');
        $(".select2-selection--multiple").css("border-color","red");
	    isValidate = false;
    }

    if($.trim($("#issuedate").val()) == ''){
        $('#issuedate_err').html('Please select Issue Date.');
        $("#issuedate").css("border-color","red");
	    isValidate = false;
    }

    if($.trim($("#duedate").val()) == ''){
        $('#duedate_err').html('Please select Issue Date.');
        $("#duedate").css("border-color","red");
	    isValidate = false;
    }

    // if($.trim($("#surcharge").val()) == ''){
    //     $('#surcharge_err').html('Please Select Apply surcharge or not.');
    // isValidate = false;
    // }

    issuedate = $.trim($("#issuedate").val()).split("-");
    issuedate = issuedate[2]+'-'+issuedate[1]+'-'+issuedate[0];
    issuedate = new Date(issuedate);

    
    duedate = $.trim($("#duedate").val()).split("-");
    duedate = duedate[2]+'-'+duedate[1]+'-'+duedate[0];
    duedate = new Date(duedate);
  
    
    if(+issuedate > +duedate){
    	$('#duedate_err').html('Due date must not be lessar than due date.');
	    isValidate = false;
    }


	$('.form-control').each(function(index,element){
         if($('#refnum'+index).length){

         	if($.trim($('#refnum'+index).val()) == ''){
	              $('#refnum'+index+'_err').html('Please enter invoice number.');
                  $('#refnum'+index).css("border-color","red");
	              isValidate = false;
	        }
            if($.trim($('#refnum'+index).val()) != ''){
            if(!refNumRegex.test($.trim($('#refnum'+index).val()))){
              $('#refnum'+index+'_err').html('Only letters and numbers allowed.');
              $('#refnum'+index).css("border-color","red");
              isValidate = false;
            }
            }
         	
            if($.trim($('#invamt'+index).val()) == '' || $.trim($('#invamt'+index).val()) < 1){
              $('#invamt'+index+'_err').html('Please enter invoice amount.');
              $('#invamt'+index).css("border-color","red");
              isValidate = false;
            }

            if(!amtRegex.test($.trim($('#invamt'+index).val()))){
              $('#invamt'+index+'_err').html('Please enter valid invoice amount.');
              $('#invamt'+index).css("border-color","red");
              isValidate = false;
            }

            if($.trim($('#txamt'+index).val()) == ''){
              $('#txamt'+index+'_err').html('Please enter tax amount.');
              $('#txamt'+index).css("border-color","red");
              isValidate = false;
            }
            if($.trim($('#txamt'+index).val()) != ''){
            if(!amtRegex.test($.trim($('#txamt'+index).val()))){
              $('#txamt'+index+'_err').html('Please enter valid tax amount.');
              $('#txamt'+index).css("border-color","red");
              isValidate = false;
            }
            }
            
            if($.trim($('#updoc'+index).val()) != ''){
	            var ext = $('#updoc'+index).val().split('.').pop().toLowerCase();
				if($.inArray(ext, ['pdf','doc','docx','xls','xlsx']) == -1) {
				    $('#updoc'+index+'_err').html('invalid file only pdf,doc,xlsx files are allowed!');
                    $('#updoc'+index).css("border-color","red");
				    isValidate = false;
				}
			}

			if(!$('#advanced').is(":checked")){
	         	if($.trim($('#updoc'+index).val()) == ''){
	              $('#updoc'+index+'_err').html('Please upload document.');
                  $('#updoc'+index).css("border-color","red");
	              isValidate = false;
	            }
	        }
	     }

	});

    $('.amountcheck').each(function(index,element){
    	amountpaid = parseInt($("#"+element.id).attr("data-invamts"));
    	if(isNaN(amountpaid)) {
		   amountpaid = 0;
		}
    	amounttotal = parseInt($("#"+element.id).attr("data-amount"));

    	amountremianing = amounttotal - amountpaid;

    	elementid = $("#"+element.id).attr("data-elementid"); 
    	elementCounter = $("#"+element.id).attr("data-counter");
    	elemeentId = elementid + elementCounter;
      
    	if($("#"+elemeentId).val() > amountremianing){
	        $("#"+elemeentId+"_err").html('Invoice amount can not be more than '+amountremianing+' Rs.');
            $("#"+elemeentId).css("border-color","red");
	    	isValidate = false;
	    }
    
	});

	return isValidate;
}

 po_data = <?php echo json_encode($podata_select_flip); ?>;// don't use quotes
  po_data_rev = <?php echo json_encode($podata_select); ?>;

</script>
