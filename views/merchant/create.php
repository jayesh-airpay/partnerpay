<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MerchantMaster */

$this->title = 'Create Merchant';
$this->params['breadcrumbs'][] = ['label' => 'Merchant', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<div class="merchant-master-create">-->



    <?= $this->render('_form', [
        'model' => $model,
        'usermodel' => $usermodel,
    ]) ?>

<!--</div>-->
