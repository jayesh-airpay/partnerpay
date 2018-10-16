<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TblGuestUserDocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Tbl Guest User Docs';
$this->title = 'Upload User Docs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
	<h4><?= Html::encode($this->title).' Request' ?></h4>
</div>
<div class="tbl-guest-user-doc-index">

<!--     <h1>Guest User Document</h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Upload Document', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ID',
            'DOC_NAME',
            'FILE',
            //'USER_ID',
            //'CREATED',
            // 'UPDATED',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
