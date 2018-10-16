<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PoMaster */
/* @var $form yii\widgets\ActiveForm */
$po_tax = '';
$qr_partner = '';

if(!empty($model->PO_ID)){
    $connection = Yii::$app->getDb();
    $command = $connection->createCommand("select CHARGE_NAME,CHARGE_VALUE from tbl_po_tax where PO_ID =".$model->PO_ID);
    $po_tax = $command->queryAll(); 
}

if(!empty($qr_id)){
    $connection = Yii::$app->getDb();
    $command = $connection->createCommand("select ASSIGN_PARTNER from tbl_quotation_master where ID =".$qr_id);
    $qr = $command->queryAll();  
    $qr_partner = $qr['0']['ASSIGN_PARTNER'];
}
?>

<?php
$this->registerJs('
    function loadPartnerDropdown(mid) {
        var partner_selected = "'.$model->MERCHANT_ID.'";
        if(mid != "" && mid != 0 && mid != null) {
            var url = "' . \yii\helpers\Url::to(['/user/get-partner-list']) . '";
            var post_data  = "ajax=true&' . Yii::$app->request->csrfParam . '=' . Yii::$app->request->csrfToken . '&mid="+mid+"&selected="+partner_selected;
            console.log(url);
            $.post(url, post_data, function (data) {
                $("#importpo-import_partner").html(data);
            }).fail(function (data) {});
        }
    }
', yii\web\View::POS_READY, 'merchant-function');

$this->registerJs('
$("#importpo-import_merchant").change(function () {
    var merchant_id = $("#importpo-import_merchant").val();
    loadPartnerDropdown(merchant_id);
});

', yii\web\View::POS_READY, 'merchant');

$this->registerJs('
     var qr_id ="'.$qr_id.'";
     var qr_partner = "'.$qr_partner.'";
     
     if(qr_id != ""){
        $("#pomaster-quotation_id").val(qr_id);
        setTimeout(function(){ 
        $("#pomaster-quotation_id").change();
        }, 500);

     }
     
     if(qr_partner != ""){
       $("#pomaster-partner_id").val(qr_partner);
     }
');
?>

<div class="page-header">
    <h4><?= ($model->isNewRecord)?'Create':'Update' ?> PO</h4>
    <div class="fieldstx">Fields with <span>*</span> are required.</div>
</div>

<div class="po-master-form">
   <?php if($isPo == 'Y') { ?>
    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'class'=>'form','onsubmit' => 'return chargeval()']]); ?>
   <?php } else { ?>
    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'class'=>'form']]); ?>
  <?php } ?>

    <?php
    if (!Yii::$app->user->isGuest) {
        if (Yii::$app->getUser()->identity->USER_TYPE == 'admin') { ?>
            <div class="row">
                <div class="col-sm-6 col-md-5 col-lg-4">
                    <div class="form-group req">
                        <?= $form->field($model, 'MERCHANT_ID')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\MerchantMaster::find()->all(), 'MERCHANT_ID', 'MERCHANT_NAME'), ['prompt' => 'Select Merchant'])->label(false) ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>

    <?php
    if (!Yii::$app->user->isGuest) {
        if (Yii::$app->getUser()->identity->USER_TYPE != 'partner') { ?>
            <div class="row">
                <div class="col-sm-6 col-md-5 col-lg-4">
                    <div class="form-group req">
                        <?= $form->field($model, 'PARTNER_ID')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Partner::find()->all(), 'PARTNER_ID', 'PARTNER_NAME'), ['prompt' => 'Select Partner'])->label(false) ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>

    <?php
    if (!Yii::$app->user->isGuest) {
        if (Yii::$app->getUser()->identity->USER_TYPE != 'partner' && $isPo == 'Y') { ?>
            <div class="row">
                <div class="col-sm-6 col-md-5 col-lg-4">
                    <div class="form-group req">
                        <?= $form->field($model, 'QUOTATION_ID')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Quotation::find()->all(), 'ID', 'NAME'), ['prompt' => 'Select Quotation'])->label(false) ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>

    <div class="row">
        <div class="col-sm-6 col-md-5 col-lg-4">
            <div class="form-group">
                <?= $form->field($model, 'SAP_REFERENCE')->textInput(['maxlength' => true, 'placeholder'=>'SAP Reference'])->label(false) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-5 col-lg-4">
            <div class="form-group req">
                <?= $form->field($model, 'PO_NUMBER')->textInput(['maxlength' => true, 'placeholder'=>'PO Number'])->label(false) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-5 col-lg-4">
            <div class="form-group">

                <?= $form->field($model, 'DATE_OF_CREATION')->widget(\yii\jui\DatePicker::className(), [
                    //'language' => 'ru',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => ['class' => 'form-control', 'readonly' => 'readonly','placeholder'=>'Date Of Creation'],
                    'clientOptions' => ['class' => 'form-control'],
                    'containerOptions' => ['class' => 'form-control'],

                ])->label(false) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-5 col-lg-4">
            <div class="form-group req">
                <?= $form->field($model, 'AMOUNT')->textInput(['placeholder'=>'Amount'])->label(false) ?>
            </div>
        </div>
    </div>
    <?php if($isPo == 'Y') {  ?>
    <?php if(empty($po_tax)){ ?>
            <div class="row">
            <div class="col-sm-6 col-md-4" id="addmorewrap">
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group req">
                        <input type="text" class="form-control" placeholder="Charge Name" name="charge_name[]">
                        <div class="error-msg help-block" style="color:#d01d19;"></div>
                      </div>
                      
                    </div>
                    <div class="col-xs-5">
                        <div class="form-group req">
                        <input type="text" class="form-control" placeholder="Charge Value" name="charge_value[]">
                        <div class="error-msg help-block" style="color:#d01d19;"></div>
                        </div>
                        
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group text-right">
                            <button type="button" class="btn btn-default addmore" name="addrangebtn1" value="Add" id="addmore">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    <?php }else {

       $cnt =1;
       foreach($po_tax as $key => $val){
         
        ?>
        <div class="row">
            <div class="col-sm-6 col-md-4" id="addmorewrap">
                <div class="row">
                    <div class="col-xs-5">
                        <div class="form-group">
                        <input type="text" class="form-control" placeholder="Charge Name" name="charge_name[]" value="<?php echo $val['CHARGE_NAME']?>">
                        </div>
                    </div>
                    <div class="col-xs-5">
                        <div class="form-group">
                        <input type="text" class="form-control" placeholder="Charge Value" name="charge_value[]" value="<?php echo $val['CHARGE_VALUE'];?>">
                        </div>
                    </div>
                    <?php if($cnt == 1){ ?>
                     <div class="col-xs-2">
                     
                        <div class="form-group text-right">
                            <button type="button" class="btn btn-default addmore" name="addrangebtn1" value="Add" id="addmore">+</button>
                        </div>
                     </div>
                     <?php } else {?>
                        <div class='col-xs-2'>
                           <div class='form-group text-right'><button type='button' class='btn btn-default remove' name='removerangebtn1' value='remove' >-</button>
                           </div>
                        </div>
                     <?php } ?>
                    
                </div>
            </div>
        </div>

    <?php $cnt++; } ?>
        

    <?php } ?>
    <?php }?>
    <div class="row">
        <div class="col-sm-6 col-md-5 col-lg-4">
            <div class="form-group">
                <?= $form->field($model,'PDF_ATTACHMENT')->fileInput(['title'=>'Select PDF'])->label(false); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary lg-btn' : 'btn btn-primary lg-btn']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

