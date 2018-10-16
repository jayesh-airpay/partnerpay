<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PartnerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hotel-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'PARTNER_ID') ?>

    <?= $form->field($model, 'PARTNER_NAME') ?>

    <?= $form->field($model, 'PARTNER_LOCATION') ?>

    <?= $form->field($model, 'AIRPAY_MERCHANT_ID') ?>

    <?= $form->field($model, 'AIRPAY_USERNAME') ?>

    <?php // echo $form->field($model, 'AIRPAY_PASSWORD') ?>

    <?php // echo $form->field($model, 'AIRPAY_SECRET_KEY') ?>

    <?php // echo $form->field($model, 'EMAIL_FOOTER') ?>

    <?php // echo $form->field($model, 'PARTNER_STATUS') ?>

    <?php // echo $form->field($model, 'CREATED_ON') ?>

    <?php // echo $form->field($model, 'UPDATED_ON') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
