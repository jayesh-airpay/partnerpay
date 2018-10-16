<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserMerchant */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
if(Yii::$app->user->identity->USER_TYPE == 'merchant') {
    $hotelarray = [];
    $hotelres = \app\models\Partner::find()->where(['PARTNER_STATUS' => 'E'])->all();
    if (!empty($hotelres)) {
        foreach ($hotelres as $row) {
            $hotelarray[$row['PARTNER_ID']] = $row['PARTNER_NAME'];
        }
    }
} else {
    $hotelres = \app\models\Partner::find()->where(['PARTNER_STATUS' => 'E', 'PARTNER_ID'=>Yii::$app->user->identity->PARTNER_ID])->all();
    if (!empty($hotelres)) {
        foreach ($hotelres as $row) {
            $hotelarray[$row['PARTNER_ID']] = $row['PARTNER_NAME'];
        }
    }

}
//  echo "<pre>"; print_r($hotelarray); exit;


?>

<div class="user-merchant-form">
    <?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-6"><?= $form->field($model, 'FIRST_NAME')->textInput(['maxlength' => true]) ?> </div>

		<div class="col-md-6"><?= $form->field($model, 'LAST_NAME')->textInput(['maxlength' => true]) ?> </div>
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($model, 'EMAIL')->textInput(['maxlength' => true]) ?> </div>
		<div class="col-md-6"><?= $form->field($model, 'MOBILE')->textInput(['maxlength' => true]) ?> </div>
	</div>
	<div class="row">
		<div class="col-md-6"><?php echo  $form->field($model, 'PASSWORD')->passwordInput(['maxlength' => true]); ?></div>
		<div class="col-md-6"><?php echo  $form->field($model, 'REPEAT_PASSWORD')->passwordInput(['maxlength' => true]); ?> </div>
	</div>
    <div class="row">
		<div class="col-md-6"> <?= $form->field($model, 'USER_TYPE')->dropDownList(

        (Yii::$app->user->identity->USER_TYPE == 'merchant' || Yii::$app->user->identity->USER_TYPE == 'cro')?
            ['merchant' => 'Merchant', 'cro' => 'CRO', 'hotel'=>'Hotel', 'sale'=>'Sale']:['hotel'=>'Hotel', 'sale'=>'Sale']


        , ['prompt' => 'select']
       ) ?> </div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($model, 'PARTNER_ID')->dropDownList($hotelarray, ['prompt' => 'select Partner'] ) ?> </div>
		<div class="col-md-6"><?= $form->field($model, 'USER_STATUS')->dropDownList([ 'E' => 'Enable', 'D' => 'Disable' ]) ?> </div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			</div>
		</div>
	</div>
    <?php ActiveForm::end(); ?>
</div>
