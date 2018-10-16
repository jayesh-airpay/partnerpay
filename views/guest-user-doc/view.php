<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TblGuestUserDoc */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Tbl Guest User Docs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-guest-user-doc-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'DOC_NAME',
            //'FILE',
            [
                'attribute' => 'FILE',
                'format' =>'raw',
                'value' => Html::a('Download file', ['/uploads/user-docs/'.$model->FILE], ['class' => 'btn btn-primary','download' => true])
                ,
            ],
            //'USER_ID',
            //'CREATED',
            //'UPDATED',
            [
                'label' => 'Created On',
                'value' => empty($model->CREATED)?'':date('d-M-Y h:i:s a',$model->CREATED)
            ],
            [
                'label' => 'Updated On',
                'value' => empty($model->UPDATED)?'':date('d-M-Y h:i:s a',$model->UPDATED)
            ],
        ],
    ]) ?>

</div>
