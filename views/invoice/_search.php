<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'INVOICE_ID') ?>

    <?= $form->field($model, 'HOTEL_ID') ?>

    <?= $form->field($model, 'REF_ID') ?>

    <?= $form->field($model, 'CREATED_BY') ?>

    <?= $form->field($model, 'ASSIGN_TO') ?>

    <?php // echo $form->field($model, 'COMPANY_NAME') ?>

    <?php // echo $form->field($model, 'CLIENT_EMAIL') ?>

    <?php // echo $form->field($model, 'CLIENT_MOBILE') ?>

    <?php // echo $form->field($model, 'MAIL_SENT') ?>

    <?php // echo $form->field($model, 'AMOUNT') ?>

    <?php // echo $form->field($model, 'PAID') ?>

    <?php // echo $form->field($model, 'BALANCE') ?>

    <?php // echo $form->field($model, 'INVOICE_STATUS') ?>

    <?php // echo $form->field($model, 'ATTACHMENT') ?>

    <?php // echo $form->field($model, 'EXPIRY_DATE') ?>

    <?php // echo $form->field($model, 'CREATED_ON') ?>

    <?php // echo $form->field($model, 'UPDATED_ON') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
