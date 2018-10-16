<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel app\models\QuotationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quotations';
$this->params['breadcrumbs'][] = $this->title;

$cat = \app\models\CategoryMaster::find()->all(); 
$listData=ArrayHelper::map($cat,'CAT_ID','CAT_NAME');
$status = ['Submitted' => 'Submitted', 'Processing' => 'Processing', 'Executed'=>'Executed', 'Expired' => 'Expired', 'Failed'=>'Failed'];


//echo '<pre>';print_r(Yii::$app->user->identity);exit;

?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
  <div class="alert alert-danger alert-dismissable">

  <?= Yii::$app->session->getFlash('success') ?>
  </div>
<?php endif; ?>
<div class="quotation-index page-header">

    <h4><?= Html::encode($this->title).' Request' ?></h4>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="fieldstx">
        <?= Html::a('Create QR', ['create'], ['class' => 'btn btn-default']) ?>
    </div>

    </div>

    <div class="tablebox">
    <div class="table-responsive">
   
    <?php if(Yii::$app->user->identity->USER_TYPE != 'merchant') { ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
'summary' => '<div class="summary"><div class="pull-right">Displaying <b> {begin} - {end}</b> of <b>{totalCount}</b> items.</div></div>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          //  'ID',
           // 'NAME',
            [
                'attribute' => 'NAME',
                'value' => function($data){
                    return $data->NAME;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
           // 'DESCRIPTION',
             [
                'attribute' => 'CAT_ID',
                'value' => function($data) use ($listData) {
                    return $listData[$data->CAT_ID];
                },
               // 'filterInputOptions' => ['class' => 'form-control searchid'],
              'filterInputOptions' => false,
              'filter' => false
            ],
           // 'PARENT_ID',
            // 'VERSION_ID',
           [
                'attribute' => 'CREATED',
                'value' => function($data) {
                    return date('d-m-Y',$data->CREATED);
                },
                //'filterInputOptions' => ['class' => 'form-control searchid hasDatepicker'],
                //'filterInputOptions' => false,
                 'filter' => \yii\jui\DatePicker::widget([
        'model'=>$searchModel,
        'attribute'=>'CREATED',
        'dateFormat' => 'dd-MM-yyyy',
    ]),
    'format' => 'html',
            ],
            [
                'attribute' => 'DUE_DATE',
                'value' => function($data) {
                    return date('d-m-Y',$data->DUE_DATE);
                },
                 'filter' => \yii\jui\DatePicker::widget([
        'model'=>$searchModel,
        'attribute'=>'DUE_DATE',
        'dateFormat' => 'dd-MM-yyyy',
    ]),
    'format' => 'html',
            ],
            [
                'attribute' => 'MERCHANT_ID',
                'label' => 'Merchant',
                'value' => function($data) {
                    $d =  \app\models\MerchantMaster::find()->where('MERCHANT_ID = :mercntid', [':mercntid' => $data->MERCHANT_ID])->one();
                    return $d->MERCHANT_NAME;                                                                                                                                                               
                    // if(count($d)){
                    //    return @$d->MERCHANT_NAME;
                    // }else{
                    //    return '';
                    // }
                },
                //'filterInputOptions' => ['class' => 'form-control searchid'],
             'filterInputOptions' => false,
            ],
            [
                'attribute' => 'ASSIGN_PARTNER',
                'label' => 'Assigned Partner',
                'value' => function($data) {
                    $d =  \app\models\Partner::findOne($data->ASSIGN_PARTNER);
                    return $d->PARTNER_NAME;                                                                                     
                },
               // 'filterInputOptions' => ['class' => 'form-control searchid'],
             'filterInputOptions' => false,
             'filter' => false
            ],
          
            [
                'attribute' => 'STATUS',
                'value' => function($data) use ($status){
                    //echo '<pre>';print_r($data);exit;
                      return $data->STATUS;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
    
            [
                   'label'=>'List of Applicants',
                   'format' => 'raw',
                   'contentOptions' => ['class' => 'status'],
                   'value'=>function ($data) {
                      return Html::a('LIST',['quotation/listofapplicants/'.$data->ID]);
                },
            ],
            // 'ASSIGN_DATE',
          
            // 'MODIFIED',

            ['class' => 'yii\grid\ActionColumn',
                'template' =>  '{view}{update}',
                'contentOptions' => ['class' => 'action'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        //$url = \yii\helpers\Url::to(["viewdetails", 'id' => $model->INVOICE_ID]);
                        return "<div class=''>". Html::a(
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
    
    <?php } else { ?>
      <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
'summary' => '<div class="summary"><div class="pull-right">Displaying <b> {begin} - {end}</b> of <b>{totalCount}</b> items.</div></div>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          //  'ID',
           // 'NAME',
            [
                'attribute' => 'NAME',
                'value' => function($data){
                    return $data->NAME;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
           // 'DESCRIPTION',
             [
                'attribute' => 'CAT_ID',
                'value' => function($data) use ($listData) {
                    return $listData[$data->CAT_ID];
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
              'filter' => false
            ],
           // 'PARENT_ID',
            // 'VERSION_ID',
           [
                'attribute' => 'CREATED',
                'value' => function($data) {
                    return date('d-m-Y',$data->CREATED);
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            'filter' => \yii\jui\DatePicker::widget([
        'model'=>$searchModel,
        'attribute'=>'CREATED',
        'dateFormat' => 'dd-MM-yyyy',
    ]),
            ],
            [
                'attribute' => 'DUE_DATE',
                'value' => function($data) {
                    return date('d-m-Y',$data->DUE_DATE);
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
             'filter' => \yii\jui\DatePicker::widget([
        'model'=>$searchModel,
        'attribute'=>'DUE_DATE',
        'dateFormat' => 'dd-MM-yyyy',
    ]),
            ],
           
            [
                'attribute' => 'ASSIGN_PARTNER',
                'label' => 'Assigned Partner',
                'value' => function($data) {
                    $d =  \app\models\Partner::findOne($data->ASSIGN_PARTNER);
                    return $d->PARTNER_NAME;                                                                                     
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
             'filter' => false
            ],
          
            [
                'attribute' => 'STATUS',
                'value' => function($data) use ($status){
                    //echo '<pre>';print_r($data);exit;
                      return $data->STATUS;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
                [
                'attribute' => 'ID',
                'label' => 'QR Link',
                'format' => 'raw',
                 'contentOptions' => ['class' => 'status'],
                'value' => function($data){
                    
                      $connection = Yii::$app->getDb();
                    $command = $connection->createCommand("SELECT tbl_quotation_master.ID FROM tbl_quotation_master join tbl_po_master on tbl_quotation_master.ID = tbl_po_master.QUOTATION_ID join tbl_invoice on tbl_invoice.PO_ID = tbl_po_master.PO_ID where tbl_quotation_master.ID =".$data->ID, []);
                    $qrdata = $command->queryAll();
                    if(count($qrdata)){
                       //return "<a href='".Yii::$app->homeUrl."po/create?qr=".$data->ID."'><u>Create PO</u></a>";
                       return Html::a('Create PO',['po/create?qr='.$data->ID]);
                    }else{
                       return '';
                    }
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            [
                   'label'=>'List of Applicants',
                   'format' => 'raw',
                   'contentOptions' => ['class' => 'status'],
                   'value'=>function ($data) {
                      return Html::a('LIST',['quotation/listofapplicants/'.$data->ID]);
                },
            ],
            // 'ASSIGN_DATE',
          
            // 'MODIFIED',

            ['class' => 'yii\grid\ActionColumn',
                'template' =>  '{view}{update}',
                'contentOptions' => ['class' => 'action'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        //$url = \yii\helpers\Url::to(["viewdetails", 'id' => $model->INVOICE_ID]);
                        return "<div class=''>". Html::a(
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
    <?php } ?>
    </div>
</div>
</div>
