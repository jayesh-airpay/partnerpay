<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Quotation */
/* @var $form yii\widgets\ActiveForm */
$cat = \app\models\CategoryMaster::find()->all();
$listData = ArrayHelper::map($cat, 'CAT_ID', 'CAT_NAME');
$merchant = \app\models\MerchantMaster::find()->where(['CREATE_QR' => 'Y'])->all();//echo '<pre>';var_dump($merchant);exit;
$listMerchantData = ArrayHelper::map($merchant, 'MERCHANT_ID', 'MERCHANT_NAME');
$query = new \yii\db\Query();
$query	->select(['tbl_partner_master.PARTNER_ID', 'tbl_partner_master.PARTNER_NAME'])
    ->from('tbl_partner_master')
    ->join(	'INNER JOIN',
        'tbl_partner_categories',
        'tbl_partner_categories.PARTNER_ID =tbl_partner_master.PARTNER_ID'
    )->where(['tbl_partner_master.MERCHANT_ID' => $model->MERCHANT_ID, 'tbl_partner_categories.CAT_ID'=>$model->CAT_ID]);
$command = $query->createCommand();
$partners = $command->queryAll();

$listPartnerData = ArrayHelper::map($partners, 'PARTNER_ID', 'PARTNER_NAME');
$partnerdata = app\models\TblQuotationPartners::find()->where(['QUOTATION_ID' => $model->ID])->all();

$poptions = [];
if(count($partnerdata)){
foreach ($partnerdata as $key => $value) {
   // $model->PARTNERS[] = $value->PARTNER_ID;
   $poptions[$value->PARTNER_ID] = $value->PARTNER_ID;
}
}
if(count($post)){
if(isset($post['Quotation']['PARTNERS'])){
    foreach ($post['Quotation']['PARTNERS'] as $key => $value) {
      $poptions[$value] = $value;
   }

}
}

?>
<?php
$this->registerJsFile('@web/js/jquery-ui.min.js', ['depends' => [\yii\web\JqueryAsset::className()], 'position' => \yii\web\View::POS_END]);
$this->registerCssFile('@web/css/jquery-ui.css');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/jquery-ui-timepicker-addon.js', ['depends' => [\yii\web\JqueryAsset::className()], 'position' => \yii\web\View::POS_END]);
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
$("#quotation-due_date").datetimepicker({
    dateFormat: "dd-mm-yy",
    showTimepicker: false,
    minDate : 0
   
});
', yii\web\View::POS_READY, 'datetimepickerjs');
?>
<div class="quotation-form">
    <div class="page-header">
    <h4>Quotation Request</h4>
    <div class="fieldstx">Fields with <span>*</span> are required.</div>
    </div>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','onsubmit' => 'return qrvalidate();']]); ?>


    <div class="row">
        <div class="col-sm-6 col-md-4 req">
        <?php if($model->isNewRecord){ ?>
            <?= $form->field($model, 'NAME')->textInput(['maxlength' => true, 'placeholder' => 'QR Name'])->label(false) ?>
        <?php }else { ?>
            <?= $form->field($model, 'NAME')->textInput(['maxlength' => true, 'placeholder' => 'QR Name','readonly'=>'readonly'])->label(false) ?>
        <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-4 req  group_box">
         <?php if($model->isNewRecord){ ?>
            <?= $form->field($model, 'DESCRIPTION')->textArea(['maxlength' => true, 'placeholder' => 'Description'])->label(false) ?>
         <?php }else { ?>
           <?= $form->field($model, 'DESCRIPTION')->textArea(['maxlength' => true, 'placeholder' => 'Description','readonly'=>'readonly'])->label(false) ?>
         <?php } ?>
        <small>(max 255 characters)</small>
        </div>
       
    </div>
    <?php if(Yii::$app->user->identity->USER_TYPE == 'merchant') { ?>
    <div class="row" style="display:none;">
        <div class="col-sm-6 col-md-4 req">
            <?= $form->field($model, 'MERCHANT_ID')->dropDownList($listMerchantData, ['prompt' => 'Select Merchant','disabled'=>true])->label(false) ?>
        <?= $form->field($model, 'MERCHANT_ID')->hiddenInput(['hidden' => true])->label(false) ?>
        </div>
    </div>
    <?php } else { ?>
     
<div class="row">
        <div class="col-sm-6 col-md-4 req">
            <?= $form->field($model, 'MERCHANT_ID')->dropDownList($listMerchantData, ['prompt' => 'Select Merchant'])->label(false) ?>
        </div>
    </div>

   <?php } ?>

    <div class="row">
        <div class="col-sm-6 col-md-4 req">
         <?php if(empty($model->CAT_ID)) { ?>
            <?= $form->field($model, 'CAT_ID')->dropDownList($listData, ['prompt' => 'Select Category'])->label(false) ?>
        <?php } else { ?>
            <?= $form->field($model, 'CAT_ID')->dropDownList($listData, ['prompt' => 'Select Category','disabled'=> true])->label(false) ?>
            <?= $form->field($model, 'CAT_ID')->hiddenInput(['hidden' => true])->label(false) ?>
        <?php } ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-4 ">
<!--             <?= $form->field($model, 'PARTNERS')->dropDownList($listPartnerData, ['class'=>'multiplebox form-control','multiple'=>'multiple','data-placeholder'=>'Select Partner','options'=>array(1095=>array('selected'=>'selected'))])->label(false); ?> -->
        <div class="form-group field-quotation-partners required">

       <input type="hidden" name="Quotation[PARTNERS]" value=""><select id="quotation-partners" class="multiplebox form-control" name="Quotation[PARTNERS][]" multiple="multiple" size="4" data-placeholder="Select Partner">
      <?php foreach($listPartnerData as $key => $value){
        $sel = ($key == $poptions[$key])?'selected':'';
        echo '<option value="'.$key.'" '.$sel.'>'.$value.'</option>';
       } ?>
       </select>

<div class="help-block"></div>
</div>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-6 col-md-4">
            <?= $form->field($model, 'NEW_PARTNER_EMAIL')->textInput(['maxlength' => true, 'placeholder' => 'New Partner Email'])->label(false) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-4 req">
            <?= $form->field($model, 'DUE_DATE')->widget(\yii\jui\DatePicker::className(), [
                //'language' => 'ru',
                'dateFormat' => 'yyyy-MM-dd',
                'options' => ['class' => 'form-control', 'readonly' => 'readonly','placeholder'=>'Due Date'],
                'clientOptions' => ['class' => 'form-control'],
                'containerOptions' => ['class' => 'form-control'],

            ])->label(false) ?>
        </div>
    </div>



    <div class="row" style="display:none;">
        <div class="col-sm-6 col-md-4">
<!--             <?= $form->field($model, 'STATUS')->dropDownList(['Submitted' => 'Submitted'], [])->label(false) ?> -->
        <?= $form->field($model, 'STATUS')->textInput(['maxlength' => true, 'placeholder' => 'New Partner Email','readonly' => 'readonly','value' => empty($model->STATUS)?'Submitted':'Processing'])->label(false) ?>
        </div>
    </div>
    <?php if (!empty($model->FILE)) { ?>
        <!--<div class="row">
            <div class="col-sm-6 col-md-4">
                <?php /*if (!$model->isNewRecord) {
                    echo Html::img(\yii\helpers\Url::to(['/uploads/quotation/' . $model->fileinput]), ['class' => "uploadlogo"]);
                } */?>
            </div>
        </div>-->
    <?php } ?>

    <div class="row">
        <div class="col-sm-6 col-md-4 req group_box">
            <?= $form->field($model, 'fileinput')->fileInput()->label(false) ?>
        	<small>(only jpg,jpeg,png,doc,docx,pdf,xls,xlsx files are allowed)</small>
        	<div class="form-group">
           	<?php if(!empty($model->FILE)){
             	echo Html::a('Download file', ['/uploads/quotation/'.$model->FILE], ['class' => 'btn btn-primary','download' => true]);
           	} ?>
        	</div>
        
    </div>
	</div>
	<div class="row">
        <div class="col-sm-6 col-md-4">
    		<div class="form-group">
       		 <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary lg-btn']) ?>
    		</div>
    		<input type="hidden" name="guestpartnes" value="<?php echo $model->NEW_PARTNER_EMAIL;?>">
    <?php 
      $i = 1;
      $cnt = count($poptions);
     foreach($poptions as $k => $v){ 
       if($i<$cnt){
          echo '<input type="hidden" name="qpartnes[]" value="'.$k.'">';
       }
      $i++;
     } ?>
    <?php ActiveForm::end(); ?>
       </div>
	</div>
</div>
<script>
function qrvalidate(){
  validate = true;
  if($.trim($("#quotation-partners").val()) == '' && $.trim($("#quotation-new_partner_email").val()) == ''){
     alert("Kindly select partner or new partner email");
     validate = false;
  }
  return validate; 
}
</script>
