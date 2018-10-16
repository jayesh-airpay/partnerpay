<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserMaster */


$this->params['breadcrumbs'][] = ['label' => 'Category', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header">
    <h4>View Category</h4>
    <div class="fieldstx">
        <?= Html::a('Update', ['update', 'id' => $model->CAT_ID], ['class' => 'btn btn-default']) ?>
    </div>
</div>

<div class="table-responsive invoice-table">
    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table table-striped'],
        'template'=>'<tr><td><b>{label}</b></td><td>{value}</td></tr>',
        'attributes' => [
            'CAT_NAME',
            'CAT_DESC',
            [
                'attribute' => 'CAT_STATUS',
                'value' =>($model->CAT_STATUS=="E")?"Enable":'Disable'
            ],
            [
                'attribute' => 'CREATED_ON',
                'value' => date("d M Y", $model->CREATED_ON)
            ],
        ],
    ]) ?>
</div>


