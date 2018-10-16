<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Invoice */

$this->title = 'Update Invoice: ';
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->INVOICE_ID, 'url' => ['view', 'id' => $model->INVOICE_ID]];
$this->params['breadcrumbs'][] = 'Update';
?>


    <?= $this->render('_form', [
        'model' => $model,
        'isQR' => $isQR
    ]) ?>


