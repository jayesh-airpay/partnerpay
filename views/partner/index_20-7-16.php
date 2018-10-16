<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PartnerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Partners';
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
    <h4>Partner Listing</h4>
    <div class="fieldstx">
        <?php  if(Yii::$app->user->identity->USER_TYPE == 'admin' || Yii::$app->user->identity->USER_TYPE == 'merchant') { ?>
            <?php
            $create_url = \yii\helpers\Url::to(['create']);
            echo Html::a('Create Partner', $create_url, ['class' => 'btn btn-default'])
            ?>
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
        'summary' => '<div class="summary">Displaying <b> {begin} - {end}</b> of <b>{totalCount}</b> items.</div>',
        'tableOptions' => ['class' => 'table table-striped table-bordered text-center'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'PARTNER_ID',
        	[
                'attribute' => 'PARTNER_ID',
                'value' => function($data){
                    return $data->PARTNER_ID;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
           // 'MERCHANT_ID',
            [
                'attribute' => 'MERCHANT_ID',
                'value' => function ($data) {
                    return !empty($data->merchant)?$data->merchant->MERCHANT_NAME:null;
                },
                'filter'=> \yii\helpers\ArrayHelper::map(\app\models\MerchantMaster::find()->all(), 'MERCHANT_ID', 'MERCHANT_NAME'),
            'visible' => (Yii::$app->getUser()->getIdentity()->USER_TYPE =='admin'),
            ], 

            //'PARTNER_NAME',
            [
                'attribute' => 'PARTNER_NAME ',
                'value' => function($data){
                    return $data->PARTNER_NAME;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            //'PARTNER_LOCATION',
            [
                'attribute' => 'PARTNER_LOCATION',
                'value' => function($data){
                    return $data->PARTNER_LOCATION;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            /*[
                'attribute' => 'AIRPAY_MERCHANT_ID',
                'value' => function($data){
                    return $data->AIRPAY_MERCHANT_ID;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],*/
        
            //'AIRPAY_MERCHANT_ID',
            //'AIRPAY_USERNAME',
            // 'AIRPAY_PASSWORD',
            // 'AIRPAY_SECRET_KEY',
            // 'EMAIL_FOOTER:ntext',
            // 'PARTNER_STATUS',
            // 'CREATED_ON',
            // 'UPDATED_ON',
            [
                'attribute' => 'PARTNER_STATUS',
                'value' => function ($data) {
                    return $data->PARTNER_STATUS=="E"?"Enable":"Disable";
                },
                'filter'=> [
                    ''=>'All',
                    'E'=>'Enable',
                    'D'=>'Disable',
                ],
            ],

            [    'class' => 'yii\grid\ActionColumn',
                'template' => (Yii::$app->user->identity->USER_TYPE=='partner')?'{view}':'{view}{update}',
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

