<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Invoice */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h4>View Invoice</h4>
    <div class="fieldstx">
        <?= Html::a('Update', ['update', 'id' => $model->INVOICE_ID], ['class' => 'btn btn-primary']) ?>
    </div>
</div>

    <p>
        <?php
        if($model->INVOICE_STATUS == 1) { ?>
             <p>Invoice could not be update</p>

        <?php } else { ?>

       <?php }

        ?>

    </p>

<div class="table-responsive invoice-table">
    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table table-striped'],
        'template'=>'<tr><td><b>{label}</b></td><td>{value}</td></tr>',
        'attributes' => [
            //'INVOICE_ID',
            [
                'label' => 'Invoice',
                'format' => 'raw',
                'value' => $model->INVOICE_ID,
            ],
            [
                'label' => 'Reference Number',
                'format' => 'raw',
                'value' => $model->REF_ID,
            ],
            [
                'label' => 'Created By',
                'format' => 'raw',
                'value' => Yii::$app->user->identity->EMAIL
            ],
            //'ASSIGN_TO',
            [
                'label' => 'Assign To',
                'value' => $model->ASSIGN_TO,
            ],
            'CLIENT_EMAIL:email',
            'CLIENT_MOBILE',
            'HOTEL_ID',
            [
                'label' => 'Corporate',
                'value' => ($model->IS_CORPORATE== 1)?'Yes':'No'
            ],
            [
                'label' => 'Company Name',
                'value' => $model->COMPANY_NAME,
                'visible' => ($model->IS_CORPORATE=='Y')
            ],
            'AMOUNT:currency',
            'SURCHARGE:currency',
            'PAID:currency',
            'BALANCE:currency',
            [
                'attribute' => 'EXPIRY_DATE',
                'value' => date("d M Y", $model->EXPIRY_DATE)
            ],
            [
                'label' => 'Invoice Status',
                'value' => empty($model->INVOICE_STATUS)?"Pending":"Paid",
            ],

            [
                'label' => 'Payment Url',
                'format' => 'raw',
                'value' => Html::a('http://'.$model->partner->merchant->DOMAIN_NAME.'.partnerpay.co.in/invoice/view/'.$model->INVOICE_ID,'http://'.$model->partner->merchant->DOMAIN_NAME.'.partnerpay.co.in/invoice/view/'.$model->INVOICE_ID, ['target'=>'_blank']),
            ],
            [
                'label' => 'Invoice PDF',
                'format' => 'raw',
                'value' => !empty($model->ATTACHMENT)?Html::a($model->ATTACHMENT, ["../uploads/attachment/$model->ATTACHMENT"], ['target'=>'_blank']):null,
            ],
        ],
    ]) ?>

</div>
