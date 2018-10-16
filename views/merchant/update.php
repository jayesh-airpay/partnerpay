<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MerchantMaster */

$this->title = 'Update Merchant: ' . ' ' . $model->MERCHANT_NAME;
$this->params['breadcrumbs'][] = ['label' => 'Merchant', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->MERCHANT_NAME, 'url' => ['view', 'id' => $model->MERCHANT_ID]];
$this->params['breadcrumbs'][] = 'Update';
?>




    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


