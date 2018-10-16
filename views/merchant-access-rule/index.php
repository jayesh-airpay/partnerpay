<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserMerchantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Merchants';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-merchant-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);
     ?>


    <p>
        <?php //if(Yii::$app->user->identity->USER_TYPE != 'sale')    { ?>
            <?= Html::a('Create User Merchant', ['create'], ['class' => 'btn btn-success']);
        //} ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'USER_ID',
             'FIRST_NAME',
             'LAST_NAME',
            'EMAIL:email',
             //'PASSWORD',
            //'USER_TYPE',
            [
                'attribute' => 'USER_TYPE',
                'value' => function ($data) {
                    return ($data->USER_TYPE=="hotel")?"hotel":$data->USER_TYPE;
                },
                'filter'=> [
                    '' => 'All',
                    'merchant' => 'merchant',
                    'hotel' => 'hotel',
                    'sale' => 'sale',
                ],

            ],
            [
                'attribute' => 'HOTEL_ID',
                'value' => function ($data) {
                    return !empty($data->hotel)?$data->hotel->HOTEL_NAME:"NA";
                },
                'filter'=> \yii\helpers\ArrayHelper::map(\app\models\Hotel::find()->all(), 'HOTEL_ID', 'HOTEL_NAME')

            ],
            //'HOTEL_ID',
            // 'FIRST_NAME',
            // 'LAST_NAME',
            // 'USER_STATUS',
            // 'CREATED_ON',
            // 'UPDATED_ON',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
