<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

?>

<?php if($disable == 'disable') { ?>

<h3 style="color:red;">Quotation already approved we cannot process your request.</h3>

<?php } else {?>

<div class="wrapper">
	<div class="container">
		<div class="page-header">
			<h4>Quotation Response </h4>
			<div class="fieldstx">Fields with <span>*</span> are required.</div>
		</div>
		<!-- <form  action="assignquotation" method="post" class="form" enctype="multipart/form-data"> -->
		 <?php $form = ActiveForm::begin(['action' =>['quotation/assignquotation'],'options' => ['method' => 'post','enctype' => 'multipart/form-data','onsubmit'=>'return formvalidate()','id'=>'myForm']]); ?>
		<input type="hidden" value="<?php echo $qid;?>" name="qid">
		<input type="hidden" value="<?php echo $pid;?>" name="pid">
      
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="form-group req">
					<input type="text" class="form-control" readonly="readonly" placeholder="Name" value="<?php echo $model->NAME;?>">

				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<label>Details of Quote</label>
				<div class="form-group">
				    <a href="<?php echo \yii\helpers\Url::to(['/uploads/quotation/' . $model->FILE]);?>" download class="btn btn-primary"><span class="glyphicon glyphicon-download-alt "></span> Download</a>
					
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-md-4 req">
				<div class="form-group">				
					<input type="text" class="form-control"  placeholder="Amount" name="amount" id="amount">
                 
					<div id="amountError" class="validationAlert" style="color:red;"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-md-4 req">
				<div class="form-group upchal">					
					<div class="form-control file"><input type="file" title="Upload File" name="Quotation[FILE]" id="file"><div id="fileError" class="validationAlert" style="color:red"></div></div>
                <small>(only jpg,png,doc,docx,pdf,xls,xlsx files are allowed)</small>
				</div>
				<div class="viewrow" style="display:none;">
					<div class="viewbox">
						
					</div>
				</div>
			</div>
		</div>
		<div class="row">			
			<div class="col-sm-6 col-md-4">
				<div class="formbtn-group">					
				<?php if(!empty($count->PARTNER_UPLOADED_DOC)){?>
				<input type="submit" class="btn btn-primary" id="submitform" value="Submit" disabled="disabled">
				<?php } else{ ?>
				<input type="submit" class="btn btn-primary" id="submitform" value="Submit" >
				<?php } ?>
				<input type="button" class="btn btn-secondary" id="cancelform" value="Cancel" onclick="resetForm();">
				</div>
			</div>
		</div>
	<!-- 	</form> -->
	   <?php ActiveForm::end(); ?>
	</div>
</div>

<script>

function resetForm(){
   document.getElementById('myForm').reset();
   $(".file-input-name").html('');
}

function formvalidate(){
    var amountRegex  = /^[1-9]\d*(\.\d+)?$/;
 
   
    $(".validationAlert").html('');

	if($.trim($("#amount").val()) == ""){
		$("#amountError").html('Please enter amount.');
		return false;
	}

    if($.trim($("#amount").val()) == "0.00" || $.trim($("#amount").val()) == "0"){
		$("#amountError").html('Please enter valid amount.');
		return false;
	}
	
	if($.trim($("#file").val()) == ""){
		$("#fileError").html('Please select file.');
		return false;
	}


    var ext = $('#file').val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['pdf','png','jpg','jpeg','docx','xls','xlsx','doc']) == -1) {
        $("#fileError").html('Please upload jpg,png,docx,pdf,xls,xlsx files only.');
        return false;
    }

     if(!amountRegex.test($.trim($("#amount").val()))){
        $("#amountError").html('Please enter amount in decimal format eg 100.00.');
        return false;
     }
	return true;
}
</script>
<?php } ?>