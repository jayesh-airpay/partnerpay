<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MerchantMasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="merchant-master-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'MERCHANT_ID') ?>

    <?= $form->field($model, 'MERCHANT_NAME') ?>

    <?= $form->field($model, 'DOMAIN_NAME') ?>

    <?= $form->field($model, 'DB_NAME') ?>

    <?= $form->field($model, 'AIRPAY_MERCHANT_KEY') ?>

    <?php // echo $form->field($model, 'AIRPAY_MERCHANT_USERNAME') ?>

    <?php // echo $form->field($model, 'AIRPAY_MERCHANT_PASSWORD') ?>

    <?php // echo $form->field($model, 'AIRPAY_MERCHANT_SECRETE_KEY') ?>

    <?php // echo $form->field($model, 'CREATED_ON') ?>

    <?php // echo $form->field($model, 'UPDATED_ON') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
