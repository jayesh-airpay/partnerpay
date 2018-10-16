<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserMerchant */

$this->title = 'Create User Merchant';
$this->params['breadcrumbs'][] = ['label' => 'User Merchants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-merchant-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
