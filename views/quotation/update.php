<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Quotation */

//$this->title = 'Quotation Request: ' . ' ' . $model->NAME;
$this->title = 'Quotation Request '  ;
$this->params['breadcrumbs'][] = ['label' => 'Quotations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->NAME, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="quotation-update">

<!--     <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
