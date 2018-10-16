<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h4>Users</h4>
    <div class="fieldstx">
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-default']) ?>
    </div>
</div>


    <?php
    $filter = '';
    if(Yii::$app->user->identity->USER_TYPE != 'partner'){
        if(Yii::$app->user->identity->USER_TYPE == 'admin'){
            $filter =  [
                '' => 'All',
                'admin' => 'Admin',
                'merchant' => 'Merchant',
                'partner' => 'Partner',
                'approver' => 'Approver',
                'Payment' => 'Payment',
            ];
        } else {
            $filter =  [
                '' => 'All',
                'merchant' => 'Merchant',
                'partner' => 'Partner',
                'approver' => 'Approver',
                'Payment' => 'Payment',
            ];

        }

    }
    $merchantArray = [];
    $merchant_details = \app\models\MerchantMaster::find()->where(['MERCHANT_STATUS'=>'E'])->all();
    foreach($merchant_details as $merchant_row){
        $merchantArray[$merchant_row['MERCHANT_ID']] = $merchant_row['MERCHANT_NAME'];
    }
    //echo "<pre>";
    //var_dump($merchantArray); exit;
     ?>
<div class="tablebox">
    <div class="table-responsive">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'headerRowOptions' =>['class' => 'text-center'],
        'filterRowOptions' =>['class' => 'searchrow'],
        'options' => ['class' => 'grid-view'],
        //'filterInputOptions' => ['class' => 'searchid'],
        'summary' => '<div class="summary"><div class="pull-right">Displaying <b> {begin} - {end}</b> of <b>{totalCount}</b> items.</div></div>',
        'tableOptions' => ['class' => 'table table-striped table-bordered text-center'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'idnum']
            ],

            //'USER_ID',
            //'EMAIL:email',
            [
                'attribute' => 'EMAIL',
                'value' => function($data){
                    return $data->EMAIL;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            'MOBILE',
            //'USER_TYPE',
            [
                'attribute' => 'USER_TYPE',
                'value' => function ($data) {
                    return $data->USER_TYPE;
                },

                'filter'=> $filter,

            ],
            [
                'attribute' => 'MERCHANT_ID',
                'value' => function ($data) {
                    //echo "<pre>"; var_dump($data); exit;
                    if($data->MERCHANT_ID !=0 && !empty($data->MERCHANT_ID)){
                        $merchant = \app\models\MerchantMaster::find()->where(['MERCHANT_ID' => $data->MERCHANT_ID])->one();
                    }
                    return !empty($merchant) ? $merchant->MERCHANT_NAME : null;
                },
                'filter'=> \yii\helpers\ArrayHelper::map(\app\models\MerchantMaster::find()->all(), 'MERCHANT_ID','MERCHANT_NAME'),
                'visible' => (!Yii::$app->getUser()->getIsGuest() && Yii::$app->getUser()->getIdentity()->USER_TYPE =='admin'),
            ],
            [
                'attribute' => 'PARTNER_ID',
                'value' => function ($data) {
                    //echo "<pre>"; var_dump($data); exit;
                    if($data->PARTNER_ID !=0 && !empty($data->PARTNER_ID)){
                        $partner = \app\models\Partner::find()->where(['PARTNER_ID' => $data->PARTNER_ID])->one();
                    }
                    return !empty($partner) ? $partner->PARTNER_NAME : null;
                },
                'filter'=> \yii\helpers\ArrayHelper::map(\app\models\Partner::find()->all(),'PARTNER_ID', 'PARTNER_NAME'),
                'visible' => (!Yii::$app->getUser()->getIsGuest() && Yii::$app->getUser()->getIdentity()->USER_TYPE =='merchant'),
            ],
            //'HOTEL_ID',
            //'FIRST_NAME',
            [
                'attribute' => 'FIRST_NAME',
                'value' => function($data){
                    return $data->FIRST_NAME;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            //'LAST_NAME',
            [
                'attribute' => 'LAST_NAME',
                'value' => function($data){
                    return $data->LAST_NAME;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            //'USER_STATUS',
            [
                'attribute' => 'USER_STATUS',
                'value' => function ($data) {
                    return $data->USER_STATUS=="E"?"Enable":"Disable";
                },
                'filter'=> [
                    ''=>'All',
                    'E'=>'Enable',
                    'D'=>'Disable',
                ],
            ],

            // 'ACCESS_TOKEN',
            // 'AUTH_KEY',
            // 'CREATED_ON',
            // 'UPDATED_ON',

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view}{update}',
             'buttons' => [
                'view' => function ($url, $model) {
                    //$url = \yii\helpers\Url::to(["viewdetails", 'id' => $model->INVOICE_ID]);
                    return "<div class='bbox'>". Html::a(
                        '<span class="glyphicon glyphicon-eye-open"></span>',
                        $url);
                },
                'update' => function ($url, $model, $key) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil"></span>',
                        $url) ."</div>";
                },

             ],
             'contentOptions' => ['class' => 'action']
            ],
        ],
    ]); ?>

    </div>

</div>
