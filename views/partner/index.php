<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PartnerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Partners';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
.container .colornotify{
	list-style-type:none;
}
.container .colornotify li{
	margin-right:10px;
}
.container .colornotify li span{
	width: 20px;
    height: 20px;
    margin-right: 10px;
}
.danger{
	background-color: #F2DEDE;
}
.info{
	background-color: #D9EDF7;
}
.warning    {
    background-color: #FCF8E3;
');


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
        <?php  if(Yii::$app->user->identity->USER_TYPE == 'merchant' && Yii::$app->user->identity->MERCHANT_ID ==30) { ?>
            <?php
            $create_url = \yii\helpers\Url::to(['intex-import']);
            echo Html::a('Import Partner Invoices', $create_url, ['class' => 'btn btn-default'])
            ?>
        <?php } ?>
        <?php  if(Yii::$app->user->identity->USER_TYPE == 'merchant' && Yii::$app->user->identity->MERCHANT_ID ==29) { ?>
            <?php
            $create_url = \yii\helpers\Url::to(['intex-import']);
            echo Html::a('Import Partner Invoices', $create_url, ['class' => 'btn btn-default'])
            ?>
        <?php } ?>
    </div>
</div>
<?php
$airpaydetails = '';
if(Yii::$app->user->identity->USER_TYPE == 'admin') {
$airpaydetails = '<ul class="colornotify pull-left">
    <li class="pull-left">
        <span class="pull-left danger"></span>Airpay Details not available
    </li>
</ul>';
 } ?>
<div class="clearfix"></div>

<div class="tablebox">
    <div class="table-responsive">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'headerRowOptions' =>['class' => 'text-center'],
        'filterRowOptions' =>['class' => 'searchrow'],
        'options' => ['class' => 'grid-view'],
        'summary' => '<div class="summary">'.$airpaydetails.'<div class="pull-right">Displaying <b> {begin} - {end}</b> of <b>{totalCount}</b> items.</div></div>',
        'tableOptions' => ['class' => 'table table-striped table-bordered text-center'],
        'rowOptions' => function ($model, $index, $widget, $grid){
            if(Yii::$app->user->identity->USER_TYPE=='admin') {
                if (empty($model->AIRPAY_MERCHANT_ID) || empty($model->AIRPAY_USERNAME) || empty($model->AIRPAY_PASSWORD) || empty($model->AIRPAY_SECRET_KEY)) {
                    return ['class' => 'danger'];
                }
            }

            return [];
        },
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
            'VENDOR_REFERENCE_ID',
            [
                'attribute' => 'PARTNER_NAME',
                'value' => function($data){
                    return $data->PARTNER_NAME;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            //'PARTNER_LOCATION',

            'MOBILE',
            'EMAIL_ID',
            [
                'attribute' => 'PARTNER_LOCATION',
                'value' => function($data){
                    return $data->PARTNER_LOCATION;
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
               'visible' => (Yii::$app->user->identity->USER_TYPE=='admin'),
            ],

            //'PARTNER_NAME',

            /*[
                'attribute' => 'AIRPAY_MERCHANT_ID',
                'value' => function($data){
                    return $data->AIRPAY_MERCHANT_ID;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],*/
            [
                'attribute' => 'APPROVER_ID',
                'value' => function($data) {
                    return (!empty($data->approver)) ? $data->approver->FIRST_NAME . " " . $data->approver->LAST_NAME : null;
                }
            ],
           // 'BANK_NAME',
            /*'ACCOUNT_HOLDER_NAME',
            [
                'attribute' => 'ACCOUNT_TYPE',
                'value' => function($data) {
                    return $data->ACCOUNT_TYPE;
                },
            ],*/
           // 'ACCOUNT_NUMBER',
           // 'IFSC_CODE',
            //'BRANCH',
            //'PHONE_NO',
           // 'BANK_ADDRESS',
           // 'CITY',
           // 'STATE',
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
        
            [
                'label' => 'KYC Files',
                'format' =>'raw',
                'value' => function($data){
                 $usr = \app\models\UserMaster::find()->where('PARTNER_ID = :Pid', [':Pid' => $data->PARTNER_ID])->one();
        
                 $files = \app\models\TblGuestUserDoc::find()->where('USER_ID = :uid', [':uid' => $usr->USER_ID])->all();
                 
                 if(count($files)){
                   $h = '';
                    foreach ($files as $file => $f){
                       $h .= Html::a('Download File',["uploads/user_docs/".$f->FILE],['download'=>'','class' => '']).'<br/>';
                    }
                    return $h;
                    // return Html::a('Download File',["uploads/quotation/".$data->PARTNER_UPLOADED_DOC],['download'=>'','class' => 'btn btn-primary']);
                 }else{
                     return '';
                 }
                 },
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

