<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PoMasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="po-master-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'PO_ID') ?>

    <?= $form->field($model, 'MERCHANT_ID') ?>

    <?= $form->field($model, 'PARTNER_NAME') ?>

    <?= $form->field($model, 'SAP_REFERENCE') ?>

    <?= $form->field($model, 'PO_NUMBER') ?>

    <?php // echo $form->field($model, 'DATE_OF_CREATION') ?>

    <?php // echo $form->field($model, 'PAYMENT_DUE_DATE') ?>

    <?php // echo $form->field($model, 'AMOUNT') ?>

    <?php // echo $form->field($model, 'PDF_ATTACHMENT') ?>

    <?php // echo $form->field($model, 'CREATED_ON') ?>

    <?php // echo $form->field($model, 'UPDATED_ON') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
