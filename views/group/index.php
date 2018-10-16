<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GroupInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Group Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h4><?= Html::encode($this->title) ?></h4>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="fieldstx">
        <?= Html::a('Create Group Invoice', ['create'], ['class' => 'btn btn-default']) ?>
    </div>
</div>
<?php
setlocale(LC_MONETARY, 'en_IN');
$amount = 0;
if (!empty($dataProvider->getModels())) {
    foreach ($dataProvider->getModels() as $key => $val) {
        $amount += $val->TOTAL_AMOUNT;
    }
}

?>

    <div class="tablebox">
        <div class="table-responsive">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'headerRowOptions' =>['class' => 'text-center'],
        'filterRowOptions' =>['class' => 'searchrow'],
        'options' => ['class' => 'grid-view'],
        'showFooter' => true,
        'summary' => '<div class="summary"><div class="pull-right">Displaying <b> {begin} - {end}</b> of <b>{totalCount}</b> items.</div></div>',
        'tableOptions' => ['class' => 'table table-striped table-bordered text-center'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'GROUP_INVOICE_ID',
            [
                'attribute' => 'GROUP_INVOICE_ID',
                'value' => function($data){
                    return $data->GROUP_INVOICE_ID;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            //'AMOUNT:currency',
            [
                'attribute' => 'AMOUNT',
                'format' => 'currency',
                'value' => function($data){
                    return $data->AMOUNT;
                },                 
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            [
                'attribute' => 'SERVICE_CHARGE',
                'format' => 'currency',
                'value' => function($data){
                    return $data->SERVICE_CHARGE;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
           // 'TOTAL_AMOUNT',
            [
                'attribute' => 'TOTAL_AMOUNT',
                'format' => 'currency',
                'value' => function($data){
                    return $data->TOTAL_AMOUNT;
                },
                'footer'=> "<b>Total Rs.".money_format('%!i', $amount)."</b>",
                'contentOptions'=> ['class'=>'text-right'],
                'footerOptions'=> ['class'=>'text-right'],
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],        
            [
                'header' => 'Payment Url',
                'format' => 'raw',
                'value' => function ($data) {
                    return (!empty($data->INVOICE_STATUS)) ? "" : Html::a('Pay Now','http://'.$data->group->merchant->DOMAIN_NAME.'.partnerpay.co.in/group/payment/'.$data->GROUP_INVOICE_ID, ['target'=>'_blank']);
                    //return ($data->MAIL_SENT
                },
                'contentOptions' => ['class' => 'status'],

            ],
        	[
                'attribute' => 'CREATED_ON',
                'value' => function($data){
                    return date("d-m-Y",$data->CREATED_ON);
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            [
                'attribute' => 'INVOICE_STATUS',
                'format' => 'raw',
                'value' => function ($data) {
                    return empty($data->INVOICE_STATUS) ? 'Pending' : "Paid";
                    //return ($data->MAIL_SENT status
                },
                'filter' => ['0' => 'Pending', '1' => 'Paid'],
                'contentOptions' => ['class' => 'status'],

            ],
            [
                'attribute' => 'PAYMENT_DATE',
                'format' => 'raw',
                'value' => function($data){
                    return !empty($data->PAYMENT_DATE)?date('d-m-Y',$data->PAYMENT_DATE):"";
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            // 'PAN_NO',
            // 'UPDATED_ON',

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['class' => 'action'],
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {

                        return "<div class='bbox'>". Html::a(
                                '<span class="glyphicon glyphicon-eye-open"></span>',
                                $url);
                    },

                ],
            ],
        ],
    ]); ?>

    </div>
</div>

