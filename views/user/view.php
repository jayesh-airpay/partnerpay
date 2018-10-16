<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserMaster */

$this->title = $model->FIRST_NAME;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header">
    <h4>View User</h4>
    <div class="fieldstx">
        <?= Html::a('Update', ['update', 'id' => $model->USER_ID], ['class' => 'btn btn-default']) ?>
    </div>
</div>

<div class="table-responsive invoice-table">
    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table table-striped'],
        'template'=>'<tr><td><b>{label}</b></td><td>{value}</td></tr>',
        'attributes' => [
            //'USER_ID',
            'FIRST_NAME',
            'LAST_NAME',
            'MOBILE',
            'EMAIL:email',
            'USER_TYPE',
            [
                'attribute' => 'USER_STATUS',
                'value' =>($model->USER_STATUS=="E")?"Enable":'Disable'
            ],
            //'ACCESS_TOKEN',
            //'AUTH_KEY',
            [
                'attribute' => 'CREATED_ON',
                'value' => date("d M Y", $model->CREATED_ON)
            ],
        ],
    ]) ?>
</div>


