<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserMerchantSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-merchant-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'USER_ID') ?>

    <?= $form->field($model, 'EMAIL') ?>

    <?= $form->field($model, 'PASSWORD') ?>

    <?= $form->field($model, 'USER_TYPE') ?>

    <?= $form->field($model, 'HOTEL_ID') ?>

    <?php // echo $form->field($model, 'FIRST_NAME') ?>

    <?php // echo $form->field($model, 'LAST_NAME') ?>

    <?php // echo $form->field($model, 'USER_STATUS') ?>

    <?php // echo $form->field($model, 'CREATED_ON') ?>

    <?php // echo $form->field($model, 'UPDATED_ON') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
