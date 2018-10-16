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
?>
<div class="page-header">
    <h4>View Partner</h4>
    <div class="fieldstx">
        <?php if((Yii::$app->user->identity->USER_TYPE != 'partner')) { ?>
        <?= Html::a('Update', ['update', 'id' => $model->PARTNER_ID], ['class' => 'btn btn-default']) ?>
        <?php } ?>
    </div>
</div>


<div class="table-responsive invoice-table">
    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table table-striped'],
        'template'=>'<tr><td><b>{label}</b></td><td>{value}</td></tr>',
        'attributes' => [
            'PARTNER_NAME',
            'MOBILE',
            'PARTNER_LOCATION',
            'AIRPAY_MERCHANT_ID',
            //'PARTNER_STATUS',
            [
                'attribute' => 'PARTNER_STATUS',
                'value' => ($model->PARTNER_STATUS == 'E')?"Enable":"Disable",
            ],
            [
                'attribute' => 'SERVICE_TAX',
                'value' => $model->SERVICE_TAX,
            ],
            [
                'attribute' => 'VAT_TAX',
                'value' => $model->VAT_TAX,
            ],
            [
                'attribute' => 'SURCHARGES',
                'value' => sprintf("%0.2f", $model->SURCHARGES),
            ],
           /* 'CREATED_ON',
            'UPDATED_ON',*/
            [
                'attribute' => 'VENDOR_LOGO',
                'format' =>'raw',
                'value' => Html::img(Url::to(['/uploads/vendor_logo/'.$model->VENDOR_LOGO]), ['class' => 'uploadlogo']),
            ],
            'INVOICE_EMAIL_TEMPLATE',
            'INVOICE_SMS_TEMPLATE',
            'REMINDER_INVOICE_EMAIL_TEMPLATE',
            'REMINDER_INVOICE_SMS_TEMPLATE',
        ],
    ]) ?>

</div>
