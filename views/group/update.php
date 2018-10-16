<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GroupInvoice */

$this->title = 'Update Group Invoice: ' . ' ' . $model->GROUP_INVOICE_ID;
$this->params['breadcrumbs'][] = ['label' => 'Group Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->GROUP_INVOICE_ID, 'url' => ['view', 'id' => $model->GROUP_INVOICE_ID]];
$this->params['breadcrumbs'][] = 'Update';
?>


    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


