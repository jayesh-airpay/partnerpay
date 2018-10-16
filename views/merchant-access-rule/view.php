<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserMerchant */

$this->title = $model->USER_ID;
$this->params['breadcrumbs'][] = ['label' => 'User Merchants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//var_dump("asd"); exit;
?>
<div class="user-merchant-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if(Yii::$app->user->identity->USER_TYPE != 'sale' && Yii::$app->user->identity->USER_TYPE != 'cro') {?>
        <?= Html::a('Update', ['update', 'id' => $model->USER_ID], ['class' => 'btn btn-primary']) ?>
        <?php } ?>
        <?php if((Yii::$app->user->identity->USER_TYPE == 'merchant')) { ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->USER_ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php } ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'EMAIL:email',
           // 'PASSWORD',
            //'USER_TYPE',
            [
                'attribute' => 'USER_TYPE',
                'value' =>($model->USER_TYPE=="hotel")?"hotel":$model->USER_TYPE
            ],
            //'HOTEL_ID',
            'hotel.HOTEL_NAME',
            'FIRST_NAME',
            'LAST_NAME',
            //'USER_STATUS',
            [
                'attribute' => 'USER_STATUS',
                'value' =>($model->USER_STATUS=="E")?"Enable":'Disable'
            ],
            [
                'attribute' => 'CREATED_ON',
                'value' => date("d M Y", $model->CREATED_ON)
            ],
            //'CREATED_ON',
            //'UPDATED_ON',
        ],
    ]) ?>

</div>
