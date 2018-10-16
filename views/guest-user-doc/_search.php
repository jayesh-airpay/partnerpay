<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblGuestUserDocSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-guest-user-doc-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'DOC_NAME') ?>

    <?= $form->field($model, 'FILE') ?>

    <?= $form->field($model, 'USER_ID') ?>

    <?= $form->field($model, 'CREATED') ?>

    <?php // echo $form->field($model, 'UPDATED') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
