<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PoMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Po Masters';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header">
    <h4>PO Listing</h4>
    <?php
    if (!Yii::$app->user->isGuest) {
        if (Yii::$app->getUser()->identity->USER_TYPE != 'partner') { ?>
            <div class="fieldstx">
                <?= Html::a('Create PO', ['create'], ['class' => 'btn btn-default']) ?>
            </div>
            <?php
        }
    }
    ?>
</div>

    <div class="tablebox">
        <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'headerRowOptions' =>['class' => 'text-center'],
        'filterRowOptions' =>['class' => 'searchrow'],
        'options' => ['class' => 'grid-view'],
        'summary' => '<div class="summary"><div class="pull-right">Displaying <b> {begin} - {end}</b> of <b>{totalCount}</b> items.</div></div>',
        'tableOptions' => ['class' => 'table table-striped table-bordered text-center'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'idnum']
            ],
            [
                'attribute' => 'PO_NUMBER',
                'value' => function($data) {
                    return $data->PO_NUMBER;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            [
                'attribute' => 'MERCHANT_ID',
                'value' => function($data) {
                    return $data->merchant->MERCHANT_NAME;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
                'visible' => (!Yii::$app->getUser()->getIsGuest() && Yii::$app->getUser()->getIdentity()->USER_TYPE =='admin'),
            ],
            [
                'attribute' => 'PARTNER_ID',
                'value' => function($data) {
                    return $data->partner->PARTNER_NAME;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
                'visible' => (!Yii::$app->getUser()->getIsGuest() && Yii::$app->getUser()->getIdentity()->USER_TYPE !='partner'),
            ],
            [
                'attribute' => 'SAP_REFERENCE',
                'value' => function($data) {
                    return $data->SAP_REFERENCE;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            [
                'attribute' => 'AMOUNT',
                'value' => function($data) {
                    return $data->AMOUNT;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            [
                'attribute' => 'STATUS',
                'value' => function($data) {
                    $st=['A' => 'Accepted','R' => 'Rejected','' => '-'];
                    return $st[$data->STATUS];
                    //return ($data->STATUS == 'A')?'Accepted':'Rejected';
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
                 'filter'=> [
                    ''=>'All',
                    'A'=>'Accepted',
                    'R'=>'Rejected',
                ],
            ],
            [
                'attribute' => 'IS_PAID',
                'value' => function ($data) {
                    //return $data->IS_PAID=="Y"?"Paid":"Unpaid";
                    $connection = Yii::$app->getDb();
                    $command = $connection->createCommand("select tbl_po_master.PO_ID, tbl_po_master.AMOUNT ,tbl_invoice.INVOICE_STATUS, sum(tbl_invoice.AMOUNT) as INV_AMT FROM tbl_po_master join tbl_invoice on tbl_po_master.PO_ID = tbl_invoice.PO_ID where tbl_po_master.PO_ID=".$data->PO_ID." AND tbl_invoice.INVOICE_STATUS=1", []);
                    $qrdata = $command->queryAll();
                    $status = 0; 
                    foreach($qrdata as $k){
                      if($k['INVOICE_STATUS']){
                        if($k['AMOUNT'] < $k['INV_AMT']){
                           $status = 1; 
                        }
                      }else{
                         $status = 0;
                      }
                    }
                
                    return $status=="0"?"Unpaid":"Paid";
                },
                'filter'=> [
                    ''=>'All',
                    'Y'=>'Paid',
                    'N'=>'Unpaid',
                ],
            ],
            [   'class' => 'yii\grid\ActionColumn',
                'template' =>  (Yii::$app->user->identity->USER_TYPE != 'partner')?'{view}{update}':'{view}',
                'contentOptions' => ['class' => 'action'],
                'buttons' => [
                    'view' => function ($url) {
                        return "<div class='bbox'>". Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>',
                            $url);
                    },
                    'update' => function ($url) {
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

