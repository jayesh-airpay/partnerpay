<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CategoryMaster;
/* @var $this yii\web\View */
/* @var $model app\models\Partner */
/* @var $form yii\widgets\ActiveForm */

foreach ($model->categories as $key => $value) {
    $model->CATEGORIES[] = $value->CAT_ID;
}

?>
<?php
$merchantArray = [];
$approverarray = [];

if (!Yii::$app->user->isGuest) {
    if (Yii::$app->getUser()->identity->USER_TYPE == 'admin') {
        $merchant_detail = \app\models\MerchantMaster::find()->select('MERCHANT_ID, MERCHANT_NAME')->andWhere(['MERCHANT_STATUS' => 'E'])->all();
        if(!empty($merchant_detail)){
            foreach($merchant_detail as $merchant) {
                $merchantArray[$merchant["MERCHANT_ID"]] = $merchant["MERCHANT_NAME"];
            }
        }
    }

    if (Yii::$app->getUser()->identity->USER_TYPE == 'merchant') {
        //echo "asd"; exit;
        //$approver_detail = \app\models\UserMaster::find()->select('USER_ID, FIRST_NAME, LAST_NAME')->andWhere(['USER_STATUS' => 'E', 'USER_TYPE' => 'approver', 'MERCHANT_ID' =>Yii::$app->getUser()->identity->MERCHANT_ID])->all();
        $approver_detail = \app\models\UserMaster::find()->select('USER_ID, FIRST_NAME, LAST_NAME')->where(['USER_STATUS'=>'E','USER_TYPE'=>'approver', 'MERCHANT_ID' =>Yii::$app->getUser()->identity->MERCHANT_ID])->all();
        //echo "<pre>";
        //var_dump($approver_detail); exit;
        if(!empty($approver_detail)){
           // $approverarray['']= 'Select Approver';
            foreach($approver_detail as $approver) {
                $approverarray[$approver["USER_ID"]] = $approver["FIRST_NAME"]." ".$approver["LAST_NAME"];
            }
        }
    }

}

$this->registerJs('
function loadApproverDropdown(merchant_id) {
    var approver_selected = "'.$model->APPROVER_ID.'";
    $("#partner-approver_id").html("");
    if(merchant_id != "") {
        var url = "' . \yii\helpers\Url::to(['/partner/get-approver-list']) . '";
        var post_data  = "ajax=true&' . Yii::$app->request->csrfParam . '=' . Yii::$app->request->csrfToken . '&merchant_id="+merchant_id+"&selected="+approver_selected;
        $.post(url, post_data, function (data) {
                $("#partner-approver_id").show();
                $("#partner-approver_id").html(data);
        }).fail(function (data) {});
    }
}

', yii\web\View::POS_READY, 'merchant-function');

$this->registerJs('
$("#partner-merchant_id").change(function () {
    var merchant_id = $("#partner-merchant_id").val();
    loadApproverDropdown(merchant_id);

});
//$("#partner-approver_id").hide();
$("#partner-merchant_id").change();
', yii\web\View::POS_READY, 'merchant');

if (Yii::$app->getUser()->identity->USER_TYPE == 'admin') {
    $this->registerJs('
    $("#partner-approver_id").hide();
//$("#partner-merchant_id").change();
', \yii\web\View::POS_READY, 'hideapproverdropdown');
}
?>

<div class="page-header">
    <h4><?= ($model->isNewRecord)?'Create':'Update' ?> Partner</h4>
    <div class="fieldstx">Fields with <span>*</span> are required.</div>
</div>

<?php $form = ActiveForm::begin([ 'options' => ['enctype'=>'multipart/form-data', 'class'=>'form']]); ?>
<?php if (Yii::$app->getUser()->identity->USER_TYPE == 'admin') { ?>
<div class="row"><div class="col-sm-6"><h5>Airpay Config Details</h5></div></div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'AIRPAY_MERCHANT_ID')->textInput(['maxlength' => 50, 'placeholder'=>'Airpay Merchant ID'])->label(false) ?>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'AIRPAY_USERNAME')->textInput(['maxlength' => 50, 'placeholder'=>'Airpay Username'])->label(false) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'AIRPAY_PASSWORD')->textInput(['maxlength' => 50, 'placeholder'=>'Airpay Password'])->label(false) ?>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'AIRPAY_SECRET_KEY')->textInput(['maxlength' => 50, 'placeholder'=>'Airpay Secret Key'])->label(false) ?>
        </div>
    </div>
</div>
<?php } ?>

<div class="row"><div class="col-sm-6"><h5>Partner Details</h5></div></div>
<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'PARTNER_NAME')->textInput(['maxlength' => 50, 'placeholder'=>'Name'])->label(false) ?>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'PARTNER_LOCATION')->textInput(['maxlength' => 30, 'placeholder'=>'Location'])->label(false) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'MOBILE')->textInput(['maxlength' => 10, 'placeholder'=>'Mobile'])->label(false) ?>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'EMAIL_ID')->textInput(['maxlength' => 70, 'placeholder'=>'Email'])->label(false) ?>
        </div>
    </div>

</div>

<?php  if(!empty($model->VENDOR_LOGO)) { ?>
    <div class="row">
        <div class="col-sm-6 col-md-4">
            <?php if(!$model->isNewRecord) {
                echo Html::img(\yii\helpers\Url::to(['/uploads/vendor_logo/' . $model->VENDOR_LOGO]), ['class'=>"uploadlogo"]);
            } ?>
        </div>
    </div>
<?php } ?>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group <?= ($model->isNewRecord)?'req':'' ?>">
            <div class="form-control file">
                <?= $form->field($model,'LOGO')->fileInput(['title'=>'Select Logo'])->label(false); ?>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'SURCHARGES')->textInput(['maxlength' => true, 'placeholder'=>'Surcharge ( % )'])->label(false) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'VENDOR_REFERENCE_ID')->textInput(['maxlength' => 50, 'placeholder'=>'Partner Reference Id'])->label(false) ?>
        </div>
    </div>
    <?php
    if (!Yii::$app->user->isGuest) {
        if (Yii::$app->getUser()->identity->USER_TYPE == 'admin') {      ?>
            <div class="col-sm-6 col-md-4">
                <div class="form-group req">
                    <?= $form->field($model, 'MERCHANT_ID')->dropDownList($merchantArray, ['prompt' => 'Select Merchant'])->label(false) ?>
                </div>
            </div>

        <?php
        }
        if (Yii::$app->getUser()->identity->USER_TYPE == 'merchant') {      ?>

            <div class="col-sm-6 col-md-4">
                <div class="form-group">
                    <?= $form->field($model, 'APPROVER_ID')->dropDownList($approverarray, ['prompt' => 'Select Approver'])->label(false) ?>
                </div>
            </div>

    <?php }
    }
    ?>

</div>
<?php if (Yii::$app->getUser()->identity->USER_TYPE == 'admin') {      ?>
<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'APPROVER_ID')->dropDownList($approverarray, ['prompt' => 'Select Approver'])->label(false) ?>
        </div>
    </div>
</div>
<?php } ?>


<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?php $listData= ArrayHelper::map(CategoryMaster::find()->all(), 'CAT_ID', 'CAT_NAME');?>
            <?= $form->field($model, 'CATEGORIES')->dropDownList($listData, ['class'=>'multiplebox form-control','multiple'=>'multiple','data-placeholder'=>'Select Category'])->label(false); ?>
       
        </div>
    </div>
</div>

<div class="row"><div class="col-sm-6"><h5>Bank Details</h5></div></div>
<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'ACCOUNT_HOLDER_NAME')->textInput(['maxlength' => 100, 'placeholder'=>'Account Holder Name'])->label(false) ?>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
                <?= $form->field($model, 'ACCOUNT_TYPE')->dropDownList(['saving' => 'Saving', 'current' => 'Current'],['prompt' => 'Select Account Type'])->label(false) ?>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'ACCOUNT_NUMBER')->textInput(['maxlength' => 18, 'placeholder'=>'Account Number'])->label(false) ?>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'IFSC_CODE')->textInput(['maxlength' => 20, 'placeholder'=>'IFSC Code'])->label(false) ?>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'BANK_NAME')->textInput(['maxlength' => 100, 'placeholder'=>'Bank Name'])->label(false) ?>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'BRANCH')->textInput(['maxlength' => 50, 'placeholder'=>'Branch'])->label(false) ?>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'PHONE_NO')->textInput(['maxlength' => 12, 'placeholder'=>'Phone Number'])->label(false) ?>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'BANK_ADDRESS')->textArea(['rows' => '5','maxlength' => 200, 'placeholder'=>'Bank Address'])->label(false) ?>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'CITY')->textInput(['maxlength' => 50, 'placeholder'=>'City'])->label(false) ?>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'STATE')->textInput(['maxlength' => 50, 'placeholder'=>'State'])->label(false) ?>

        </div>
    </div>
</div>

<div class="row"><div class="col-sm-6"><h5>Compliance Details</h5></div></div>
<!--<div class="row">

    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?//= $form->field($model, 'VAT_TAX')->textInput(['maxlength' => 50, 'placeholder'=>'VAT No.'])->label(false) ?>
        </div>
    </div>

    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?//= $form->field($model, 'SERVICE_TAX')->textInput(['maxlength' => 50, 'placeholder'=>'Service Tax No.'])->label(false) ?>
        </div>
    </div>

</div>-->
<?php  if(!empty($model->PAN_CARD_LOGO)) { ?>
    <div class="row">
        <div class="col-sm-6 col-md-4">
            <?php if(!$model->isNewRecord) {
                echo Html::img(\yii\helpers\Url::to(['/uploads/vendor_logo/' . $model->PAN_CARD_LOGO]), ['class'=>"uploadlogo"]);
            } ?>
        </div>
    </div>
<?php } ?>
<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <div class="form-control file">
                <?= $form->field($model,'PAN_LOGO')->fileInput(['title'=>'Upload Pan Card'])->label(false); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'CORPORATE_PAN_CARD_NUMBER')->textInput(['maxlength' => 16, 'placeholder'=>'Pan Card Number'])->label(false) ?>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'GSTNUM')->textInput(['maxlength' => 100, 'placeholder'=>'GSTIN Number'])->label(false) ?>
        </div>
    </div>

</div>


<div class="row"><div class="col-sm-6"><h5>Email/SMS Details</h5></div></div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'INVOICE_EMAIL_TEMPLATE')->textArea(['rows' => '8', 'placeholder'=>'Invoice Email Template'])->label(false) ?>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'INVOICE_SMS_TEMPLATE')->textArea(['rows' => '8', 'placeholder'=>'Invoice SMS Template'])->label(false) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'REMINDER_INVOICE_EMAIL_TEMPLATE')->textArea(['rows' => '8', 'placeholder'=>'Reminder Invoice Email Template'])->label(false) ?>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'REMINDER_INVOICE_SMS_TEMPLATE')->textArea(['rows' => '8', 'placeholder'=>'Reminder Invoice SMS Template'])->label(false) ?>
        </div>
    </div>
</div>



<div class="row">
    <?php if(!$model->isNewRecord) { ?>
        <div class="col-sm-6 col-md-4">
            <div class="form-group req">
                <div class="onoffswitch req">
                    <?= $form->field($model, 'PARTNER_STATUS')->dropDownList(['E' => 'Enable', 'D' => 'Disable'])->label(false) ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary lg-btn' : 'btn btn-primary lg-btn']) ?>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>

