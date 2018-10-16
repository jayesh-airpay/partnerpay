<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Partner */

$path = Yii::$app->request->baseUrl;

$this->title = $model->PARTNER_NAME;
$this->params['breadcrumbs'][] = ['label' => 'Partners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//var_dump($model->approver); exit;

$cat = '';

foreach($model->categories as $key => $val){
    $cat .= $items[$val->CAT_ID].' ';
}
?>
<div class="page-header">
    <h4>View Partner</h4>
    <div class="fieldstx">
        <?php if((Yii::$app->user->identity->USER_TYPE != 'partner')) { ?>
        <?= Html::a('Update', ['update', 'id' => $model->PARTNER_ID], ['class' => 'btn btn-default']) ?>
        <?php } ?>
    </div>
</div>

<div class="row details-row">
    <div class="col-sm-6">
        <h5>Partner Details <?php ($model->PARTNER_STATUS == 'E')?" (Active)":"" ?></h5>
        <div class="table-responsive invoice-table">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-striped'],
                'template'=>'<tr><td><b>{label}</b></td><td>{value}</td></tr>',
                'attributes' => [
                    'PARTNER_NAME',
                    'PARTNER_LOCATION',
                    'MOBILE',
                    'EMAIL_ID',
                    [
                        'attribute' => 'VENDOR_LOGO',
                        'format' =>'raw',
                        'value' => Html::img(Url::to(['/uploads/vendor_logo/'.$model->VENDOR_LOGO]), ['style' => 'max-height:300px;max-width:300px']),
                    ],
                    [
                        'attribute' => 'SURCHARGES',
                        'value' => sprintf("%0.2f", $model->SURCHARGES),
                    ],
                    'VENDOR_REFERENCE_ID',
                    [
                        'attribute' => 'APPROVER_ID',
                        'value' => (!empty($model->approver))?$model->approver->FIRST_NAME." ".$model->approver->LAST_NAME:null,
                    ],
                    [
                        'attribute' => 'CATEGORIES',
                        'format' =>'raw',
                        'value' => $cat
                    ],
                    /*[
                        'attribute' => 'PARTNER_STATUS',
                        'value' => ($model->PARTNER_STATUS == 'E')?"Enable":"Disable",
                    ],*/

                    //'AIRPAY_MERCHANT_ID',
                    //'PARTNER_STATUS',


                ],
            ]) ?>

        </div>
    </div>

    <div class="col-sm-6"><h5>Bank Details</h5>
        <div class="table-responsive invoice-table">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-striped'],
                'template'=>'<tr><td><b>{label}</b></td><td>{value}</td></tr>',
                'attributes' => [
                    'ACCOUNT_HOLDER_NAME',
                    [
                        'attribute' => 'ACCOUNT_TYPE',
                        'value' => $model->ACCOUNT_TYPE,
                    ],
                    'ACCOUNT_NUMBER',
                    'IFSC_CODE',
                    'BANK_NAME',
                    'BRANCH',
                    'PHONE_NO',
                    'BANK_ADDRESS',
                    'CITY',
                    'STATE',

                ],
            ]) ?>

        </div>
    </div>
</div>
<div class="space"></div>

<div class="row details-row">
    <div class="col-sm-6">
        <h5>Compliance Details</h5>
        <div class="table-responsive invoice-table">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-striped'],
                'template'=>'<tr><td><b>{label}</b></td><td>{value}</td></tr>',
                'attributes' => [
                    [
                        'attribute' => 'VAT_TAX',
                        'value' => $model->VAT_TAX,
                    ],

                    [
                        'attribute' => 'SERVICE_TAX',
                        'value' => $model->SERVICE_TAX,
                    ],
                    [
                        'attribute' => 'PAN_CARD_LOGO',
                        'format' =>'raw',
                        'value' => Html::img(Url::to(['/uploads/vendor_logo/'.$model->PAN_CARD_LOGO]), ['style' => 'max-height:300px;max-width:300px']),
                    ],
                    'CORPORATE_PAN_CARD_NUMBER',
                    'GSTNUM'

                ],
            ]) ?>

        </div>
    </div>

    <div class="col-sm-6">
        <h5>Email/SMS Details</h5>
        <div class="table-responsive invoice-table">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-striped'],
                'template'=>'<tr><td><b>{label}</b></td><td>{value}</td></tr>',
                'attributes' => [

                    'INVOICE_EMAIL_TEMPLATE',
                    'INVOICE_SMS_TEMPLATE',
                    'REMINDER_INVOICE_EMAIL_TEMPLATE',
                    'REMINDER_INVOICE_SMS_TEMPLATE',

                ],
            ]) ?>

        </div>
    </div>
</div>

