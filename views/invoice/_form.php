<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Invoice */
/* @var $form yii\widgets\ActiveForm */

$isNewRecord = 1;

if(!$model->isNewRecord && $isQR == 'Y'){
    $isNewRecord = 0;
}
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
', yii\web\View::POS_READY, 'datetimepickerjs');

$this->registerJs('
   var isNewRecord = '.$isNewRecord.';
   if(!isNewRecord){
       $("#w0 :input").prop("disabled", true);
   }');
?>
<?php
$invoiceArray = [];

if (!Yii::$app->user->isGuest) {
    $invoice_detail = \app\models\PoMaster::find()->select('PO_NUMBER, SAP_REFERENCE')->andWhere(['IS_PAID' => 'N'])->all();
    if(!empty($invoice_detail)){
        foreach($invoice_detail as $invoice) {
            $invoiceArray[$invoice["PO_NUMBER"]] = $invoice["PO_NUMBER"].' - '.$invoice['SAP_REFERENCE'];
        }
    }
}
//echo "<pre>";
//var_dump($model); exit;
if($model->INVOICE_STATUS == 1) {
    echo '<div class="alert alert-warning">Invoice can not updated</div>';
}
?>
<div class="page-header">
    <h4><?= ($model->isNewRecord)?'Generate':'Update' ?> invoices</h4>
    <div class="fieldstx">Fields with <span>*</span> are required.</div>
</div>
	
    <?php $form = ActiveForm::begin([ 'options' => ['enctype'=>'multipart/form-data', 'class'=>'form']
    ]); ?>

        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <div class="form-group req">
                    <?= $form->field($model, 'REF_ID')->textInput(['size'=>20,'maxlength'=>20, 'placeholder'=>'Reference Number'])->label(false) ?>
                </div>
            </div>
        </div>

		 <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <div class="form-group">
                    <?= $form->field($model, 'PO_ID')->dropDownList($invoiceArray, ['prompt' => 'Select PO'],[])->label(false) ?>
                </div>
            </div>
        </div>

		<div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <div class="form-group">

                    <?= $form->field($model, 'ISSUE_DATE')->widget(\yii\jui\DatePicker::className(), [
                        //'language' => 'ru',
                        'dateFormat' => 'dd-MM-yyyy',
                        'options' => ['class' => 'form-control', 'readonly' => 'readonly','placeholder'=>'Issue Date'],
                        'clientOptions' => ['class' => 'form-control'],
                        'containerOptions' => ['class' => 'form-control'],

                    ])->label(false) ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <div class="form-group">

                    <?= $form->field($model, 'DUE_DATE')->widget(\yii\jui\DatePicker::className(), [
                        //'language' => 'ru',
                        'dateFormat' => 'dd-MM-yyyy',
                        'options' => ['class' => 'form-control', 'readonly' => 'readonly','placeholder'=>'Due Date'],
                        'clientOptions' => ['class' => 'form-control'],
                        'containerOptions' => ['class' => 'form-control'],

                    ])->label(false) ?>
                </div>
            </div>
        </div>

      

        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <div class="form-group req">
                    <?= $form->field($model, 'AMOUNT')->textInput(['placeholder'=>'Invoice Amount'])->label(false) ?>
                </div>
            </div>
        </div>

    <?php if((!(Yii::$app->user->getIsGuest()) && Yii::$app->user->identity->USER_TYPE != 'partner'))  { ?>
        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <div class="form-group req">
                    <?php
                    $users = \app\models\UserMaster::find()
                        ->andWhere(['USER_TYPE' => 'partner', 'USER_STATUS' => 'E'])
                        ->all();
                    $user_array = [];
                   // echo '<pre>';print_r($users);exit;
                    foreach($users as $usr) {
                    	$partnerData = \app\models\Partner::find()->andWhere(['PARTNER_ID'=>$usr->PARTNER_ID])->andWhere(['PARTNER_STATUS'=>'E'])->one();
                        //$user_array[$usr->USER_ID] = $usr->FIRST_NAME . ' '. $usr->LAST_NAME;
                    	  if(!empty($partnerData['PARTNER_NAME'])) {
                            $user_array[$usr->USER_ID] = $partnerData['PARTNER_NAME'];
                        }
                    }

                    echo $form->field($model, 'ASSIGN_TO')->dropDownList(
                         array_unique($user_array), ['prompt' => 'Select Partner for invoice'])->label(false);
                    ?>
                </div>
            </div>
        </div>
    <?php  } ?>


        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <div class="onoffswitch2">
                    <!--<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox2" id="myonoffswitch6">
                    <label class="onoffswitch-label2" for="myonoffswitch6">
                        <span class="onoffswitch-inner2"></span>
                        <span class="onoffswitch-switch2"></span>
                    </label>-->
                <?php  echo $form->field($model, 'APPLY_SURCHARGE')->dropDownList(
                        ['1'=>'Yes','0'=>'No'], ['prompt' => 'Apply Surcharge?'])->label(false);
                    ?>
                </div>
            </div>
        </div>

        <?php if(!$model->isNewRecord){ ?>
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <?php
                        echo !empty($model->ATTACHMENT)?Html::a($model->ATTACHMENT, ["../uploads/attachment/$model->ATTACHMENT"], ['target'=>'_blank']):null;
                    ?>
                </div>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <div class="form-group">
                    <div class="form-control file">
                        <?= $form->field($model,'ATTACHMENTPDF')->fileInput(['title'=>'Upload a PDF'])->label(false); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 col-md-5 col-lg-4">
                <div class="form-group">
                    <?php if(!$model->isNewRecord) {
                        if ($model->INVOICE_STATUS == 0) {
                            echo Html::submitButton('Update',['class' => 'btn btn-primary lg-btn']);
                        }
                    } else {
                        echo Html::submitButton('Submit', ['class' => 'btn btn-primary lg-btn']);
                    } ?>
                </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

