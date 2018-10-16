<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Partner */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
  $merchantArray = [];

if (!Yii::$app->user->isGuest) {
    if (Yii::$app->getUser()->identity->USER_TYPE == 'admin') {
        $merchant_detail = \app\models\MerchantMaster::find()->select('MERCHANT_ID, MERCHANT_NAME')->andWhere(['MERCHANT_STATUS' => 'E'])->all();
        if(!empty($merchant_detail)){
            foreach($merchant_detail as $merchant) {
                $merchantArray[$merchant["MERCHANT_ID"]] = $merchant["MERCHANT_NAME"];
            }
        }
    }
}
?>
<div class="page-header">
    <h4><?= ($model->isNewRecord)?'Create':'Update' ?> Partner</h4>
    <div class="fieldstx">Fields with <span>*</span> are required.</div>
</div>

    <?php $form = ActiveForm::begin([ 'options' => ['enctype'=>'multipart/form-data', 'class'=>'form']]); ?>
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

	<div class="row"><div class="col-sm-6"><h5>Vendor Details</h5></div></div>
    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="form-group req">
                <?= $form->field($model, 'PARTNER_NAME')->textInput(['maxlength' => 50, 'placeholder'=>'Partner Name'])->label(false) ?>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="form-group req">
                <?= $form->field($model, 'PARTNER_LOCATION')->textInput(['maxlength' => 30, 'placeholder'=>'Partner Location'])->label(false) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="form-group req">
                <?= $form->field($model, 'MOBILE')->textInput(['maxlength' => 10, 'placeholder'=>'Mobile'])->label(false) ?>
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
           }
        ?>
     </div>
     <?php  if(!empty($model->VENDOR_LOGO)) { ?>
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <?php if(!$model->isNewRecord) {
                        echo Html::img(\yii\helpers\Url::to(['/uploads/vendor_logo/' . $model->VENDOR_LOGO]), ['height' => 100, 'width' => 100]);
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
                <?= $form->field($model, 'SERVICE_TAX')->textInput(['maxlength' => 50, 'placeholder'=>'Service Tax No.'])->label(false) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="form-group">
                <?= $form->field($model, 'VAT_TAX')->textInput(['maxlength' => 50, 'placeholder'=>'VAT No.'])->label(false) ?>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="form-group">
                <?= $form->field($model, 'SURCHARGES')->textInput(['maxlength' => true, 'placeholder'=>'Surcharge ( % )'])->label(false) ?>
            </div>
        </div>
    </div>

	<div class="row"><div class="col-sm-6"><h5>Email/SMS Details</h5></div></div>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="form-group">
                <?= $form->field($model, 'INVOICE_EMAIL_TEMPLATE')->textArea(['rows' => '5', 'placeholder'=>'Invoice Email Template'])->label(false) ?>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="form-group">
                <?= $form->field($model, 'INVOICE_SMS_TEMPLATE')->textArea(['rows' => '3', 'placeholder'=>'Invoice SMS Template'])->label(false) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="form-group">
                <?= $form->field($model, 'REMINDER_INVOICE_EMAIL_TEMPLATE')->textArea(['rows' => '5', 'placeholder'=>'Reminder Invoice Email Template'])->label(false) ?>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="form-group">
                <?= $form->field($model, 'REMINDER_INVOICE_SMS_TEMPLATE')->textArea(['rows' => '3', 'placeholder'=>'Reminder Invoice SMS Template'])->label(false) ?>
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

