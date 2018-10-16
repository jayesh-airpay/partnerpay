<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CategoryMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-header">
    <h4><?= ($model->isNewRecord)?'Create':'Update' ?> Category</h4>
    <div class="fieldstx">Fields with <span>*</span> are required.</div>
</div>

<?php $form = ActiveForm::begin(['class'=>'form']); ?>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'CAT_NAME')->textInput(['maxlength' => 50, 'placeholder'=>'Category Name'])->label(false) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'CAT_DESC')->textArea(['rows' => '5','maxlength' => 200,'placeholder'=>'Category Summary'])->label(false) ?>
        </div>
    </div>
</div>

<?php //if(!$model->isNewRecord) { ?>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="onoffswitch req">
                <?= $form->field($model, 'CAT_STATUS')->dropDownList(['E' => 'Enable','D' => 'Disable'])->label(false) ?>
            </div>
        </div>
    </div>

<?php //} ?>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary lg-btn' : 'btn btn-primary lg-btn']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>