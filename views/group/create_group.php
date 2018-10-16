<?php
/**
 *
 * @var \app\models\Group $model
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Create Group';
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="page-header">
    <h4>Create Group</h4>
    <div class="fieldstx">Fields with <span>*</span> are required.</div>
</div>

<?php $form = \yii\widgets\ActiveForm::begin([ 'options' => ['enctype'=>'multipart/form-data', 'class'=>'form']
]); ?>

<div class="row">
    <div class="col-sm-6 col-md-5 col-lg-4">
        <div class="form-group req">
            <?= $form->field($model, 'EMAIL')->textInput(['size'=>20,'maxlength'=>250, 'placeholder'=>'Email'])->label(false) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-5 col-lg-4">
        <div class="form-group req">
            <?= $form->field($model, 'MOBILE')->textInput(['placeholder'=>'Mobile'])->label(false) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-5 col-lg-4">
        <div class="form-group req">
            <?= $form->field($model, 'AIRPAY_MERCHANT_KEY')->textInput(['placeholder'=>'Airpay Merchant Id'])->label(false) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-5 col-lg-4">
        <div class="form-group req">
            <?= $form->field($model, 'AIRPAY_MERCHANT_USERNAME')->textInput(['placeholder'=>'Airpay Merchant Username'])->label(false) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-5 col-lg-4">
        <div class="form-group req">
            <?= $form->field($model, 'AIRPAY_MERCHANT_PASSWORD')->textInput(['placeholder'=>'Airpay Merchant Password'])->label(false) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-5 col-lg-4">
        <div class="form-group req">
            <?= $form->field($model, 'AIRPAY_MERCHANT_SECRETE_KEY')->textInput(['placeholder'=>'Airpay Merchant Secret Key'])->label(false) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-5 col-lg-4">
        <div class="onoffswitch2 req">
            <?php  echo $form->field($model, 'APPLY_SC')->dropDownList(
                ['1'=>'Yes','0'=>'No'], ['prompt' => 'Apply Service Charge?'])->label(false);
            ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-5 col-lg-4">
        <div class="form-group">
            <?php echo Html::submitButton('Submit', ['class' => 'btn btn-primary lg-btn']); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

