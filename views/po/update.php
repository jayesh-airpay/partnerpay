<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PoMaster */

//$this->title = 'Update PO: ' . ' ' . $model->PO_ID;
$this->params['breadcrumbs'][] = ['label' => 'Po Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->PO_ID, 'url' => ['view', 'id' => $model->PO_ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="po-master-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'isPo'  => $isPo,
    ]) ?>

</div>