<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\MerchantMaster */
/* @var $form yii\widgets\ActiveForm */
//var_dump($model->CREATE_QR);exit;
$checkpo = false;
if($model->CREATE_QR == 'Y'){
   $checkpo = true;
}
?>
<?php
$this->registerJs('
$("#merchantmaster-merchant_name").keyup(function(e) {
	var str = $("#merchantmaster-merchant_name").val();
	str = str.replace(/[^a-zA-Z0-9 \-]/g, "");
	str = str.replace(/\s+/g, "_");
	$("#merchantmaster-db_name").val(str.toLowerCase());
})
', \yii\web\View::POS_READY);
?>

<?php
$catArray = $catChkArray = [];
if(!empty(Yii::$app->session->getFlash('success'))) {
	?>
	<div><?= Yii::$app->session->getFlash('success'); ?></div>
	<?php
}
?>

<div class="page-header">
    <h4><?= ($model->isNewRecord)?'Create':'Update' ?> Merchants </h4>
    <div class="fieldstx">Fields with <span>*</span> are required.</div>
</div>

<?php $form = ActiveForm::begin([ 'options' => ['enctype'=>'multipart/form-data', 'class'=>'form']]); ?>

<div class="row">
	<div class="col-sm-6 col-md-4">
		<div class="form-group req">
			<?= $form->field($model, 'MERCHANT_NAME')->textInput(['maxlength' => 50, 'placeholder'=>'Merchant Name'])->label(false) ?>
			<!--<input type="text" class="form-control" placeholder="Merchant Name">-->
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6 col-md-4">
		<div class="form-group req">
			<?= $form->field($model, 'DOMAIN_NAME')->textInput(['maxlength' => 50, 'placeholder'=>'Domain Name'])->label(false) ?>
			<!--<input type="text" class="form-control" placeholder="Merchant Name">-->
			<p>(Eg. http://<strong>domain_name</strong>.partnerpay.co.in)</p>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6 col-md-4">
		<div class="form-group req">
		   <!-- <textarea class="form-control" rows="2" placeholder="Merchant Address"></textarea>-->
			<?= $form->field($model, 'MERCHANT_ADDRESS')->textArea(['rows' => '5','maxlength' => 200,'placeholder'=>'Domain Address'])->label(false) ?>
		</div>
	</div>
</div>

<?php  if(!empty($model->MERCHANT_LOGO)) { ?>
	<div class="row">
		<div class="col-sm-6 col-md-4">
			<?php if(!$model->isNewRecord) {
				echo Html::img(\yii\helpers\Url::to(['/uploads/logo/' . $model->MERCHANT_LOGO]), ['height' => 100, 'width' => 100]);
			} ?>
		</div>
	</div>
 <?php } ?>

<div class="row">
	<div class="col-sm-6 col-md-4">
		<div class="form-group <?= ($model->isNewRecord)?'req':'' ?>">
			<div class="form-control file">
				<?= $form->field($model,'LOGO')->fileInput(['title'=>'Select Logo File'])->label(false); ?><!--<input type="file" title="Select Logo File">-->

			</div>
		</div>
	</div>
</div>

<?php if(!empty($model->BANK_LOGO)) { ?>
	<div class="row">
		<div class="col-sm-6 col-md-4">
			<div class="loadimg">
			<?php if(!$model->isNewRecord) {
				echo Html::img(\yii\helpers\Url::to(['/uploads/bank_logo/' . $model->BANK_LOGO]), ['height' => 100, 'width' => 100]);
			} ?>
			<div class="close-loadimg">
				<?php if(!$model->isNewRecord) {
				//echo Html::submitButton('Delete Bank Logo', ['class' => 'btn btn-primary']);
				echo Html::a('Delete Bank Logo', ['remove','id' => $model->MERCHANT_ID], ['class'=>'btntx']);
				} ?>
			</div>
			</div>
		</div>
	</div>
<?php } ?>

<div class="row">
	<div class="col-sm-6 col-md-4">
		<div class="form-group <?= ($model->isNewRecord)?'req':'' ?>">
			<div class="form-control file">
				<?= $form->field($model,'B_LOGO')->fileInput(['title'=>'Select Bank Logo'])->label(false); ?><!--<input type="file" title="Select Logo File">-->

			</div>
		</div>
	</div>
</div>

<?php if(!$model->isNewRecord) { ?>
	<div class="row">
	<div class="col-sm-6 col-md-4">
		<div class="onoffswitch req">
			<?= $form->field($model, 'MERCHANT_STATUS')->dropDownList(['E' => 'Enable', 'D' => 'Disable'])->label(false) ?>
		</div>
	</div>
</div>
<?php } ?>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
        <input type="hidden" id="qr" value="<?php echo $model->CREATE_QR;?>">
            <?= $form->field($model, 'CREATE_QR')->checkbox()->label(false) ?>
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