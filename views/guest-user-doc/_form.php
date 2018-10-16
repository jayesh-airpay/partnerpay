<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblGuestUserDoc */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-guest-user-doc-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col-sm-6 col-md-4">
    <?= $form->field($model, 'DOC_NAME')->textInput(['maxlength' => true, 'placeholder' => 'Document Name'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-4">
    <?= $form->field($model, 'fileinput')->fileInput()->label(false) ?>
        </div>
    </div>
    <div class="row">
            <div class="col-sm-6 col-md-4">
                <div class="notetx">
                    (Upload only doc,docx,jpg,png,jpeg,pdf format file.)
                </div>
            </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
