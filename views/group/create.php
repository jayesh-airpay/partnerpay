<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GroupInvoice */

$this->title = 'Create Group Invoice';
$this->params['breadcrumbs'][] = ['label' => 'Group Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::a('Download Sample', \yii\helpers\Url::to(['/download/group_invoice_sample.csv']), ['class' => 'btn btn-primary']) ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


