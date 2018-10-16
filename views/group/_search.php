<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GroupInvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="group-invoice-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'GROUP_INVOICE_ID') ?>

    <?= $form->field($model, 'GROUP_ID') ?>

    <?= $form->field($model, 'PARTNER_ID') ?>

    <?= $form->field($model, 'PARTNER_NAME') ?>

    <?= $form->field($model, 'AMOUNT') ?>

    <?php // echo $form->field($model, 'PAN_NO') ?>

    <?php // echo $form->field($model, 'CREATED_ON') ?>

    <?php // echo $form->field($model, 'UPDATED_ON') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
