<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Quotation */

$this->title = $model->NAME;
$this->params['breadcrumbs'][] = ['label' => 'Quotations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$cat = \app\models\CategoryMaster::find()->all();
$listData=ArrayHelper::map($cat,'CAT_ID','CAT_NAME');
$result = Yii::$app->db->createCommand("select tbl_partner_master.PARTNER_NAME  from tbl_quotation_partners  join tbl_partner_master on tbl_quotation_partners.PARTNER_ID = tbl_partner_master.PARTNER_ID where QUOTATION_ID='$model->ID'")
    ->queryAll();

$partnr = '';
foreach ($result as $key => $value) {
    if(!empty($partnr)){
        $partnr .= ',';
    }
    $partnr .= $value['PARTNER_NAME'];
}

$part = '';
?>
<div class="quotation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
<!--         <?= Html::a('Update', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?> -->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'NAME',
            'DESCRIPTION',
            [
                'attribute' => 'CAT_ID',
                'format' =>'raw',
                'value' => $listData[$model->CAT_ID]
                ,
            ],
            // 'PARENT_ID',
            // 'VERSION_ID',
            //'DUE_DATE',
            [
                'label' => 'Due Date',
                'value' => date('d-M-Y ',$model->DUE_DATE)
            ],
            'STATUS',
            [
                'label' => 'Partner',
                'attribute' => 'ASSIGN_PARTNER',
                'format' =>'raw',
                'value' => $partnr,
            ],
            'NEW_PARTNER_EMAIL',
            [
                'label' => 'Uploaded File',
                'attribute' => 'FILE',
                'format' =>'raw',
                'value' => Html::a('Download file', ['/uploads/quotation/'.$model->FILE], ['class' => 'btn btn-primary','download' => true])
                ,
            ],
        ],
    ]) ?>

</div>