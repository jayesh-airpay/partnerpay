<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MerchantMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Merchant';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$this->registerJs('
$("#merchants").change(function(e) {
  if(this.value == ""){
     alert("please select merchants");
     return false;
    }
    $( "#invoice_form" ).submit();

});
', \yii\web\View::POS_READY);
?>
<div class="page-header">
    <h4>Merchant Listing</h4>
    <div class="fieldstx">
        <?php  if(Yii::$app->user->identity->USER_TYPE == 'admin') { ?>
            <?= Html::a('Create Merchant', ['create'], ['class' => 'btn btn-default']); ?>
        <?php } ?>
    </div>
</div>


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

            //'MERCHANT_ID',
            //'MERCHANT_NAME',
            [
                'attribute' => 'MERCHANT_NAME',
                'value' => function($data){
                    return $data->MERCHANT_NAME;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
           // 'DOMAIN_NAME',
            [
                'attribute' => 'DOMAIN_NAME',
                'format' =>'raw',
                'value' => function($data){
                    return Html::a('http://'.$data->DOMAIN_NAME.'.partnerpay.co.in', 'http://'.$data->DOMAIN_NAME.'.partnerpay.co.in', ['terget'=>'_blank']);
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            //'DB_NAME',
            //-'AIRPAY_MERCHANT_KEY',
             //'AIRPAY_MERCHANT_USERNAME',
            // 'AIRPAY_MERCHANT_PASSWORD',
            // 'AIRPAY_MERCHANT_SECRETE_KEY',
            // 'CREATED_ON',
            // 'UPDATED_ON',
             'MERCHANT_ADDRESS',

            [  'class' => 'yii\grid\ActionColumn',

                'template' =>  (Yii::$app->user->identity->USER_TYPE == 'admin')?'{view}{update}':'{view}',
                'contentOptions' => ['class' => 'action'],
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
            ],
        ],
    ]); ?>

    </div>

</div>


