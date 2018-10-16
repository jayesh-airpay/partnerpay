<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\QuotationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="quotation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'NAME') ?>

    <?= $form->field($model, 'DESCRIPTION') ?>

    <?= $form->field($model, 'CAT_ID') ?>

    <?= $form->field($model, 'PARENT_ID') ?>

    <?php // echo $form->field($model, 'VERSION_ID') ?>

    <?php // echo $form->field($model, 'DUE_DATE') ?>

    <?php // echo $form->field($model, 'STATUS') ?>

    <?php // echo $form->field($model, 'ASSIGN_PARTNER') ?>

    <?php // echo $form->field($model, 'ASSIGN_DATE') ?>

    <?php // echo $form->field($model, 'CREATED') ?>

    <?php // echo $form->field($model, 'MODIFIED') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
