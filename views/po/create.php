<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PoMaster */

//$this->title = 'Create PO';
$this->params['breadcrumbs'][] = ['label' => 'Po', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-master-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'isPo' => $isPo,
        'qr_id' => $qr_id
    ]) ?>

</div>
