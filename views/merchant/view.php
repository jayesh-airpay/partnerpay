<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\MerchantMaster */

$this->title = $model->MERCHANT_NAME;
$this->params['breadcrumbs'][] = ['label' => 'Merchants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//var_dump(Yii::$app->request->baseUrl); exit;
$path = Yii::$app->request->baseUrl;

 ?>

<div class="page-header">
    <h4>View Merchant</h4>
    <div class="fieldstx">
        <?php
        if(Yii::$app->user->identity->USER_TYPE == 'admin') {
            echo Html::a('Update', ['update', 'id' => $model->MERCHANT_ID], ['class' => 'btn btn-default']);
        }
        ?>
    </div>
</div>


<div class="table-responsive invoice-table">
    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table table-striped'],
        'template'=>'<tr><td><b>{label}</b></td><td>{value}</td></tr>',
        'attributes' => [
            //'MERCHANT_ID',
            'MERCHANT_NAME',
//            'DOMAIN_NAME',
            //'DOMAIN_NAME',
            [
                'attribute' => 'DOMAIN_NAME',
                'format' =>'raw',
                'value' => Html::a('http://'.$model->DOMAIN_NAME.'.partnerpay.co.in', 'http://'.$model->DOMAIN_NAME.'.partnerpay.co.in', ['terget'=>'_blank']),
            ],
            //'DB_NAME',
            //'AIRPAY_MERCHANT_KEY',
            //'AIRPAY_MERCHANT_USERNAME',
            //'AIRPAY_MERCHANT_PASSWORD',
            //'AIRPAY_MERCHANT_SECRETE_KEY',
            'MERCHANT_ADDRESS',
            [
                'attribute' => 'MERCHANT_LOGO',
                'format' =>'raw',
                'value' => (!empty($model->MERCHANT_LOGO))?Html::img(Url::to(['/uploads/logo/'.$model->MERCHANT_LOGO], true),['class'=>'uploadlogo']):"",
            ],
        	[
                'attribute' => 'BANK_LOGO',
                'format' =>'raw',
                'value' => (!empty($model->BANK_LOGO))?Html::img(Url::to(['/uploads/bank_logo/'.$model->BANK_LOGO], true),['class'=>'uploadlogo']):"",
            ],

            //'CREATED_ON',
            //'UPDATED_ON',
            [
                //'attribute' => 'CREATED_ON',
                'label' => 'Date of Creation',
                'value' => date("d M Y", $model->CREATED_ON)
            ],
        ],
    ]) ?>

</div>


