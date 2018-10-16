<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserMerchant */

$this->title = 'Update User Merchant: ' . ' ' . $model->USER_ID;
$this->params['breadcrumbs'][] = ['label' => 'User Merchants', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->USER_ID, 'url' => ['view', 'id' => $model->USER_ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-merchant-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
