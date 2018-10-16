<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GroupInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Group Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Group Invoice', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'GROUP_INVOICE_ID',
            'AMOUNT',
            'SERVICE_CHARGE',
            'TOTAL_AMOUNT',            
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
                'attribute' => 'INVOICE_STATUS',
                'format' => 'raw',
                'value' => function ($data) {
                    return empty($data->INVOICE_STATUS) ? 'Pending' : "Paid";
                    //return ($data->MAIL_SENT status
                },
                'filter' => ['0' => 'Pending', '1' => 'Paid'],
                'contentOptions' => ['class' => 'status'],

            ],
            // 'PAN_NO',
            // 'CREATED_ON',
            // 'UPDATED_ON',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>

</div>
