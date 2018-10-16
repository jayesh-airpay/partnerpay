<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel app\models\QuotationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quotations';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="quotation-index page-header">

    <h4><?= Html::encode($this->title).' Listing' ?></h4>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <input type="hidden" id="q_id" value="<?php echo $id;?>">
    </div>

    <div class="tablebox">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

             [
                'label' => 'Quotation Name',
                'attribute' => 'QUOTATION_ID',
                'value' => function($data) {
                    $d =  \app\models\Quotation::find()->where('ID = :pid', [':pid' => $data->QUOTATION_ID])->one();
                    return $d->NAME;                                                                                                                                                              
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
            [
                'label' => 'Quotation Document',
                'format' => 'raw',
                'contentOptions' => ['class' => ''],
                'value' => function($data) {
                    $d =  \app\models\Quotation::find()->where('ID = :pid', [':pid' => $data->QUOTATION_ID])->one();
                    //return Html::a('Download file', ['/uploads/quotation/'.$d->FILE],['download' => true]);  
                    return Html::a('Download File',["uploads/quotation/".$d->FILE],['download'=>'','class' => 'btn btn-primary']);
                },//return Html::a('LIST',['quotation/listofapplicants/'.$data->ID]);
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
           [
                  
                'label' => 'Fill Quotation',
                'format' => 'raw',
                'contentOptions' => ['class' => 'status'],
                'value' => function($data) {
                    $q = base64_encode($data->QUOTATION_ID);
                    $p = base64_encode($data->PARTNER_ID);
                    return Html::a('Click Here', ['/quotation/assignquotation?q='.$q.'&p='.$p],[]);                                                                                                                                                              
                },//return Html::a('LIST',['quotation/listofapplicants/'.$data->ID]);
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
           
           ],
    ]); ?>

</div>
</div>
