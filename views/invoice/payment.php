<?php
/**
 * Created by PhpStorm.
 * User: nandana
 * Date: 19/2/16
 * Time: 10:03 AM
 */
?>
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
//print_r($model->getErrors());
/*if($client->EMAIL == 'gaurav.mehta369@gmail.com'){
   $client->EMAIL = '';
}

if($client->PHONE == '9821394146'){
   $client->PHONE = '';
}

if($client->FIRST_NAME == 'Gaurav'){
   $client->FIRST_NAME = '';
}

if($client->LAST_NAME == 'Mehta'){
   $client->LAST_NAME = '';
}*/

//$this->getView()->
$this->registerJsFile($theme->baseUrl. '/js/fancybox/jquery.fancybox.js', ['depends' => [\yii\web\JqueryAsset::className()], 'position' => \yii\web\View::POS_END]);
$this->registerCssFile($theme->baseUrl. '/js/fancybox/jquery.fancybox.css');

//echo "<pre>";
//var_dump($model->partner->merchant); exit;
//echo \Yii::$app->request->BaseUrl; exit;
?>

<?php
$this->registerJs('
 $(".fancybox").fancybox();
    $(".tnclink").fancybox();
', \yii\web\View::POS_READY, 'fancytandc');

?>
<?php if(!empty($model->partner->PARTNER_NAME)) { ?><h3><?php echo $model->partner->PARTNER_NAME; ?></h3><?php } ?>
<div class="row">
    <div class="col-md-12"> <div class="invoice-head"><b>Invoice No :</b> <?php echo  !empty($model->ATTACHMENT)?$model->REF_ID. '':$model->REF_ID; ?></div></div>


</div>

<div class="payformbox">
	<div class="row">
    <div class="download-wrap">
		<div class="col-sm-6 align-right">
        	<div class="download-img">
            	<?php echo  !empty($model->ATTACHMENT)?\yii\helpers\Html::a(' Click here to view invoice', ["uploads/attachment/$model->ATTACHMENT"], ['target'=>'_blank','title'=>'Click here to view invoice']):""; ?></div></div>
		
	</div>
	</div>
   <?php
   if(!empty( Yii::$app->session->getFlash('error'))) {
         echo  Yii::$app->session->getFlash('error');
     }
    ?>
    <?php $form = \yii\widgets\ActiveForm::begin([ 'options' => ['enctype'=>'multipart/form-data', 'class'=>'form','onsubmit'=>'return validateForm();']
    ]); ?>



    <div class="row">
        <div class="col-sm-6">
            <div class="form-group req">
                <?= $form->field($client, 'EMAIL')->textInput() ?>
            </div>
        </div>
		
        <div class="col-sm-6">
            <div class="form-group req">
                <?= $form->field($client, 'PHONE')->textInput(['maxlength'=>10]) ?>
            </div>
        </div>
		
		<div class="clearfix"></div>
		
        <div class="col-sm-6">
            <div class="form-group req">
                <?= $form->field($client, 'FIRST_NAME')->textInput() ?>
            </div>
        </div>
		
        <div class="col-sm-6">
            <div class="form-group req">
                <?= $form->field($client, 'LAST_NAME')->textInput() ?>
            </div>
        </div>
		
		<div class="clearfix"></div>
		
        <div class="col-sm-6">
            <div class="form-group req">
                <?= $form->field($model, 'pay_amount')->textInput() ?>
            </div>
        </div>
		
		<div class="clearfix"></div>
		<div class="col-sm-12 checkbox">
			<!--<input type="checkbox" name="tandc" id="tandc" class="css-checkbox"/>
			<label for="tandc" class="css-label">I accept the terms and conditions</label>-->
			<?php echo  $form->field($model, 'iagree', ['template' => '{input}{label}{error}'])->checkbox(['class' => 'css-checkbox', 'id'=>'tandc1','label'=>null])->label('I accept the <a href="#tandc" class="tnclink required" data-toggle="modal" data-target="#tandc">terms and conditions.</a>', ['class' => 'css-label']); ?>
        <div class="help-block" id="iagree_err" style="color:#d01d19;"></div>
		</div>

		<div class="col-sm-6 btngroup">
            <?= \yii\helpers\Html::submitButton('Pay', ['class' => 'btn btn-primary']) ?>
        </div>
		
		</div>
		
		<?php \yii\widgets\ActiveForm::end(); ?>

		<?php //echo "<pre>";var_dump($model->partner); //exit; ?>

		<?php if(!empty($model->partner)) {  ?>
			<div class="row partner_tax">
				<div class="col-md-12">
                	<?php if(!empty($model->partner->PARTNER_NAME) && (!empty($model->partner->SERVICE_TAX) || !empty($model->partner->VAT_TAX))) { ?><p><em class="pt">Partner</em> :  <?php echo $model->partner->PARTNER_NAME; ?></p><?php } ?>
					<?php if(!empty($model->partner->SERVICE_TAX)) { ?><p><em class="pt">Service Tax No</em> :  <?php echo $model->partner->SERVICE_TAX;?></p></p> <?php } ?>
					<?php if(!empty($model->partner->VAT_TAX)) { ?><p><em class="pt">VAT Tax No</em> :  <?php echo $model->partner->VAT_TAX;?></p> <?php } ?>
				</div>
			</div>
		<?php } ?>
		
		<div class="bott-logo">
            <div class="logoimg partnerpay"><img alt="Partnerpay" src="<?php echo $theme->baseUrl. '/images/partnerpay-logo.png';?>"/></div>
        	<?php if(!empty($model->partner->merchant->BANK_LOGO)) {
    			?>
        	<div class="logoimg banklogo"><img alt="Bank Logo" src="<?php echo Yii::$app->request->baseUrl.'/uploads/bank_logo/'.$model->partner->merchant->BANK_LOGO; ?>"/></div>
        		<?php
    		} ?>
        </div>

    </div>


</div><!--/close .login-form-->

	



<div id="tandc" style="display: none">
    <p class="MsoNormal"
       style="mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;background:white"><font
            color="black" face="Times New Roman" size="4"><span
                style="font-size:13.5pt">By visiting or accessing <?php echo Yii::$app->params['Name']; ?>
                payment Link (&#8220;Site&#8221;), the Customer agrees to be
              bound by the following Terms and Conditions and the
              Customers acceptance of the same is reaffirmed each time
              the Customer access the Payment Service (&#8220;Service&#8221;). The
              Site reserves the right, at its sole discretion, to
              change, modify, add, or delete portions of these Terms
              &amp; Conditions at any time without further notice. The
              Customer shall re-visit the&nbsp;<b><u><span
                            style="font-weight:bold">&#8220;Terms &amp; Conditions&#8221;</span></u></b>&nbsp;link
              from time to time to stay abreast of any changes that the
              Site may introduce.</span></font></p>
    <p class="MsoNormal"
       style="mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;background:white"><br>
        <font color="black" face="Times New Roman" size="4"><span
                style="font-size:13.5pt"><o:p></o:p></span></font></p>
    <p class="MsoNormal"
       style="mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;background:white"><font
            color="black" face="Times New Roman" size="4"><span
                style="font-size:13.5pt">We do not collect or store
              Customer Credit Card or bank account information. In case
              of Credit Cards, you will be transferred to a secure
              payment gateway where you can enter your Credit Card
              details. The payment gateway authenticates the transaction
              and we would then initiate the booking request. For
              Internet Banking/ Debit Cards, you would be transferred to
              the relevant bank website where you are required to enter
              your Internet Banking login and password or your Debit
              Card details. The booking request is initiated once
              authentication is complete. <br>
              <o:p></o:p></span></font><br>
    </p>
    <p class="MsoNormal"
       style="mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;background:white"><font
            color="black" face="Times New Roman" size="4"><span
                style="font-size:13.5pt">We shall not be liable for any
              failure of the payment gateway to authenticate the
              transaction or any failure of internet services during the
              transaction.<br>
              <br>
              The Customer agrees and undertakes not to sell, trade or
              resell or exploit for any commercial purposes, any portion
              of this Service. For the removal of doubt, it is clarified
              that this <br>
              Service is not for commercial use but is specifically
              meant for personal use only.<br>
              <o:p></o:p></span></font></p>
    <p class="MsoNormal"
       style="mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;background:white"><font
            color="black" face="Times New Roman" size="4"><span
                style="font-size:13.5pt"><br>
              The Customer agrees and undertakes to abide by the
              provisions of Information Technology Act and/or any other
              laws/rules/regulations of India. The Customer impliedly
              and expressly undertakes to submit to the jurisdiction of
              enforcing/statutory authorities for any violation of the
              IT act and other laws.<br>
              <br>
              The Customer agrees and undertakes not to use the Service
              or the Site in any unlawful manner or in any other manner
              that could damage, disable, overburden, impair or disrupt
              the Service, servers, system, site or networks connected
              to the Service.<o:p></o:p></span></font></p>
    <p class="MsoNormal"
       style="mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;background:white"><font
            color="black" face="Times New Roman" size="4"><span
                style="font-size:13.5pt"><br>
              As a condition of use of this Site, the Customer agrees to
              indemnify the Site and its affiliates from and against any
              and all actions, claims, losses, damages, liabilities and
              expenses (including reasonable attorneys' fees) arising
              out User's use of this Site.<o:p></o:p></span></font></p>
    <p class="MsoNormal"
       style="mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;background:white"><font
            color="black" face="Times New Roman" size="4"><span
                style="font-size:13.5pt"><br>
              The Site is not to be held liable for special,
              consequential, incidental, indirect or punitive loss,
              damage or expenses , data, loss of facilities, or
              equipment or the cost of recreating lost data regardless
              of whether arising out of a breach of contract, warranty,
              tort, strict liability or otherwise.<o:p></o:p></span></font></p>
    <p class="MsoNormal"
       style="mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;background:white"><font
            color="black" face="Times New Roman" size="4"><span
                style="font-size:13.5pt"><br>
              This Site contains links to third party sites. The linked
              sites are not under the control of the Site and the Site
              is not responsible for the contents of any linked site or
              any link contained in the linked site. The Site provides
              these links only as a convenience, and the inclusion of a
              link does not imply endorsement of the linked site by the
              Site.<o:p></o:p></span></font></p>
    <font color="black" face="Times New Roman" size="4"><span
            style="font-size:13.5pt"><br>
            This Site and the contents hereof are provided "as is" and
            without warranties of any kind either expressed or implied
            to the fullest extent permissible pursuant to applicable
            law. The Site disclaims all warranties, express or implied,
            and conditions including, but not limited to, implied
            warranties of merchantability, fitness for particular
            purpose, title and non - infringement. Nor do we warrant
            that the functions of this Site or the functions contained
            in the materials of this Site will be interrupted or be
            error free, that defects will be corrected, or that this
            site or the server(s) that make the sites available are free
            of viruses or other harmful components. In no event shall we
            be liable for any special, indirect or consequential damages
            or any damages whatsoever resulting from loss of use, data
            or profits, whether in an action of contract, negligence or
            other wrongful actions, arising out of or in connection with
            the use or performance of any products, materials or
            information available from this Site. This Site in whole or
            in part, could include technical inaccuracies or
            typographical errors. Changes are periodically added to the
            information herein. Customer&#8217;s continued use of this Site
            following the posting of any change or modification of the
            Terms and Conditions will mean the Customer has accepted
            those changes or modifications.</span></font><br>
    <br>
</div><!-- tandc -->
<script>
  function validateForm(){
    var isValidate = true;
    if($("#tandc1").is(":checked")){
      
    }else{
      $("#iagree_err").html('Please accept terms and conditions');
     // $("#tandc1").css('border-color','red');
      isValidate = false;
    }
    return isValidate;
  }
  </script>