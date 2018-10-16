<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Manage Invoices';
$this->params['breadcrumbs'][] = $this->title;
//var_dump(\yii\helpers\Url::base()); exit;
$this->registerCssFile(\yii\helpers\Url::base().'/css/daterangepicker.css');
$this->registerJsFile(\yii\helpers\Url::base().'/js/moment.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJsFile(\yii\helpers\Url::base().'/js/jquery.daterangepicker.js',['depends'=>\yii\web\JqueryAsset::className()]);

?>

<?php

$this->registerJs('
function closedtpicker()    {
    $("#invoicesearch-created_on").dateRangePicker({shortcuts: {"prev-days":[1,3,5,7], "prev":["week","month","year"], "next-days":false, "next":false, apply : "Apply" } }).bind("datepicker-apply",function()
    {
        var e = jQuery.Event("keydown");
        e.keyCode = 13;
        $("#invoicesearch-created_on").trigger(e);
        return true;
    });
}

', \yii\web\View::POS_HEAD);

$this->registerJs('
closedtpicker();
', \yii\web\View::POS_READY,'rangepicker');



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
    <h4>Invoices</h4>
    <div class="fieldstx">
        <?php
        if(Yii::$app->getUser()->getIdentity()->USER_TYPE !='merchant') {
        	$create_url = \yii\helpers\Url::to(['create']);
        	echo Html::a('Create Invoice', $create_url, ['class' => 'btn btn-default']);
        }
        ?>
    </div>
</div>
<?php
if(!empty( Yii::$app->session->getFlash('error'))) {
    echo  Yii::$app->session->getFlash('error');
}

if(!empty( Yii::$app->session->getFlash('success'))) {
    echo  Yii::$app->session->getFlash('success');
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
        //'filterInputOptions' => ['class' => 'searchid'],
        'summary' => '<div class="summary">Displaying <b> {begin} - {end}</b> of <b>{totalCount}</b> items.</div>',
        'tableOptions' => ['class' => 'table table-striped table-bordered text-center'],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'INVOICE_ID',
            [
                'attribute' => 'INVOICE_ID',
                'value' => function($data){
                    return $data->INVOICE_ID;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            //'REF_ID',
            [
                'attribute' => 'REF_ID',
                'value' => function($data){
                    return $data->REF_ID;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            
            [
                'attribute' => 'MERCHANT_ID',
                'header' => 'Merchant Name',
                'value' => function ($data) {
                    return !empty($data->partner->merchant)?$data->partner->merchant->MERCHANT_NAME:null;
                },
                'filter'=> \yii\helpers\ArrayHelper::map(\app\models\MerchantMaster::find()->all(), 'MERCHANT_ID', 'MERCHANT_NAME'),
            'visible' => (Yii::$app->getUser()->getIdentity()->USER_TYPE =='admin'),
            ],
           // 'partner.merchant.MERCHANT_NAME',
            [
                'attribute' => 'PARTNER_ID',
                //'header' => 'Merchant Name',
                'format' => 'raw',
                'value' => function ($data) {
                    return !empty($data->partner)?$data->partner->PARTNER_NAME:null;
                },
                'filter'=> \yii\helpers\ArrayHelper::map(\app\models\Partner::find()->all(), 'PARTNER_ID', 'PARTNER_NAME'),
            	'visible' => (!Yii::$app->getUser()->getIsGuest() && Yii::$app->getUser()->getIdentity()->USER_TYPE !='partner'),
            ],
            //'partner.PARTNER_NAME',
            //'CREATED_BY',
            //'ASSIGN_TO',
            /*[
                'attribute' => 'ASSIGN_TO',
                'value' => function ($data) {
                    //var_dump($data->ASSIGN_TO);
                    //$user = \app\models\UserMaster::find()->where(['USER_TYPE' => 'partner', 'USER_ID'=>$data->ASSIGN_TO])->one();
                    return \app\helpers\generalHelper::getAssigneeName($data->ASSIGN_TO);
                    //return !empty($user) ? $user["FIRST_NAME"] . " " . $user["LAST_NAME"] : null;
                },
                'filter'=> \yii\helpers\ArrayHelper::map(\app\models\UserMaster::find()->all(), 'USER_ID', 'EMAIL')
            ],*/
            //'ASSIGN_TO',
            'TOTAL_AMOUNT:currency',
            'PAID:currency',
            'BALANCE:currency',
            //'PAID:currency',
            [
                'header' => 'Reminder',
                'format' => 'raw',
                'value' => function ($data) {
                    return (empty($data->CLIENT_EMAIL) || $data->BELONGS_TO_GROUP == 'Y') ? "" : Html::a(($data->MAIL_SENT == "Y") ? "Resend" : "Send", ['/invoice/send-reminder', 'id' => $data->INVOICE_ID], ['class' => '']);
                },
                'visible' => (!Yii::$app->getUser()->getIsGuest() && Yii::$app->getUser()->getIdentity()->USER_TYPE !='merchant'),
                //'contentOptions' => ['class' => 'status'],               
            ],
            /*[
                'header' => 'Send Mail',
                'format' => 'raw',
                'value' => function ($data) {
                    return (empty($data->CLIENT_EMAIL) || $data->BELONGS_TO_GROUP == 'Y') ? "" : Html::a(($data->MAIL_SENT == "Y") ? "Resend" : "Send", ['/invoice/send-mail', 'id' => $data->INVOICE_ID], ['class' => '']);
                },
                'visible' => (!Yii::$app->getUser()->getIsGuest() && Yii::$app->getUser()->getIdentity()->USER_TYPE !='merchant'),
                //'contentOptions' => ['class' => 'status'],
            ],
            [
                'header' => 'Send SMS',
                'format' => 'raw',
                'value' => function ($data) {
                    return (empty($data->CLIENT_MOBILE) || $data->BELONGS_TO_GROUP == 'Y') ? "" : Html::a("Resend", ['/invoice/send-sms', 'id' => $data->INVOICE_ID], ['class' => '']);
                },
                'visible' => (!Yii::$app->getUser()->getIsGuest() && Yii::$app->getUser()->getIdentity()->USER_TYPE !='merchant'),
               // 'contentOptions' => ['class' => 'status'],
            ],*/
            [
                'header' => 'Payment Url',
                'format' => 'raw',
                'value' => function ($data) {
                    return (!empty($data->INVOICE_STATUS) || $data->BELONGS_TO_GROUP == 'Y') ? "" : Html::a((Yii::$app->user->identity->USER_TYPE == 'merchant')?'Pay Now':'URL','http://'.$data->partner->merchant->DOMAIN_NAME.'.partnerpay.co.in/invoice/view/'.$data->INVOICE_ID, ['target'=>'_blank']);
                    //return ($data->MAIL_SENT
                },
                'contentOptions' => ['class' => 'status'],

            ],
            [
                'attribute' => 'INVOICE_STATUS',
                'format' => 'raw',
                'value' => function ($data) {
                    return empty($data->INVOICE_STATUS) ? 'Unpaid' : "Paid";
                    //return ($data->MAIL_SENT status
                },
                'filter' => ['0' => 'Unpaid', '1' => 'Paid'],
                'contentOptions' => ['class' => 'status'],

            ],
            [
                'header' => 'Check Status',
                'format' => 'raw',
                'value' => function ($data) {
                	//if(Yii::$app->user->identity->USER_TYPE == 'admin')
                    return ($data->BELONGS_TO_GROUP == 'Y') ? '' : Html::a("Update", ['/invoice/get-airpay-status', 'id' => $data->INVOICE_ID]);
                },
            	'visible' => (!Yii::$app->getUser()->getIsGuest() && Yii::$app->getUser()->getIdentity()->USER_TYPE =='admin'),
            ],
            /*[
                'header' => 'Active Status',
                'attribute' => 'active_status',
                'format' => 'raw',
                'value' => function ($data) {
                    return ($data->EXPIRY_DATE >= time()) ? "Yes" : "No";
                },
                'filter' => [
                    '' => 'All',
                    'Y' => 'Yes',
                    'N' => 'No'
                ],

            ],*/
            [
                'header' => 'Created On',
                'attribute' => 'CREATED_ON',
                'format' => 'raw',
                'value' => function ($data) {
                    return !empty($data->CREATED_ON) ?date("Y, M d h:i a", $data->CREATED_ON):null;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            //'CREATED_ON:datetime',
            // 'COMPANY_NAME',
            // 'CLIENT_EMAIL:email',
            // 'CLIENT_MOBILE',
            // 'MAIL_SENT',
            // 'AMOUNT',
            // 'PAID',
            // 'BALANCE',
            // 'INVOICE_STATUS',
            // 'ATTACHMENT',
            // 'EXPIRY_DATE',
            // 'CREATED_ON',
            // 'UPDATED_ON',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => (Yii::$app->user->identity->USER_TYPE == 'partner')?'{view}{update}':'{view}',
                'contentOptions' => ['class' => 'action'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        $url = \yii\helpers\Url::to(["viewdetails", 'id' => $model->INVOICE_ID]);
                        return $model->BELONGS_TO_GROUP == 'Y'? '': "<div class='bbox'>". Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>',
                            $url);
                    },
                    'update' => function ($url, $model, $key) {
                        $url = \yii\helpers\Url::to(["update", 'id' => $model->INVOICE_ID]);
                        return $model->BELONGS_TO_GROUP == 'Y'? '': Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            $url) ."</div>";
                    },
                ],
            ],
        ],
    ]); ?>

    </div>

</div>
