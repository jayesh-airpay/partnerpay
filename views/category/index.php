<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategoryMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Category';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h4>Category</h4>
    <div class="fieldstx">
        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-default']) ?>
    </div>
</div>

<div class="tablebox">
    <div class="table-responsive">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'headerRowOptions' =>['class' => 'text-center'],
        'filterRowOptions' =>['class' => 'searchrow'],
        'options' => ['class' => 'grid-view'],
        'summary' => '<div class="summary"><div class="pull-right">Displaying <b> {begin} - {end}</b> of <b>{totalCount}</b> items.</div></div>',
        'tableOptions' => ['class' => 'table table-striped table-bordered text-center'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'idnum']
            ],
            [
                'attribute' => 'CAT_NAME',
                'value' => function($data){
                    return $data->CAT_NAME;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            /*[
                'attribute' => 'CAT_DESC',
                'value' => function($data){
                    return $data->CAT_DESC;
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],*/
            [
                'attribute' => 'CAT_STATUS',
                'value' => function ($data) {
                    return $data->CAT_STATUS=="E"?"Enable":"Disable";
                },
                'filter'=> [
                    ''=>'All',
                    'E'=>'Enable',
                    'D'=>'Disable',
                ],
            ],
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view}{update}',
             'buttons' => [
                'view' => function ($url, $model) {
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