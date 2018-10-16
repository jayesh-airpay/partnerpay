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
                'label' => 'Partner Name',
                'attribute' => 'PARTNER_ID',
                'value' => function($data) {
                    $d =  \app\models\Partner::find()->where('PARTNER_ID = :pid', [':pid' => $data->PARTNER_ID])->one();
                    return $d->PARTNER_NAME;                                                                                                                                                              
                },
                'filterInputOptions' => ['class' => 'form-control searchid'],
            ],
          //  'PARTNER_UPLOADED_DOC',
            [
                'attribute' => 'PARTNER_UPLOADED_DOC',
                'format' =>'raw',
                'label' => 'Uploaded Doc',
                'value' => function($data){
                 if($data->PARTNER_UPLOADED_DOC){
                     return Html::a('Download File',["uploads/quotation/".$data->PARTNER_UPLOADED_DOC],['download'=>'','class' => 'btn btn-primary']);
                 }else{
                     return '';
                 }
                 },
           ],

            'AMOUNT',
            [
                 'label' => 'Status',   
                 'format' =>'raw',
                  'value' => function($data){
                     $d =  \app\models\Quotation::find()->where('ID = :pid', [':pid' => $data->QUOTATION_ID])->one();
                     if($d->STATUS == 'Executed'){
                       return !empty($d->ASSIGN_PARTNER == $data->PARTNER_ID)?'Approved':'Rejected';   
                     }else{
                       return 'Submitted';
                     }
                 }
            ],
//             ['class' => 'yii\grid\CheckboxColumn',
//              'header' => 'Approve',
//              'checkboxOptions' => function($data) {
//                          return  ['value' => $data->PARTNER_ID ,'label'=>'Approved','onclick' => 'js:approveVendor(this)','class' => 'checkAssignee'];
//                  }
             
//             ],
           // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
</div>
<script>
function approveVendor(t){
    if(t.checked){
        $(".checkAssignee").not(t).attr('disabled',true);
        var quotationId = $("#q_id").val();
        var partnerId = t.value;
        var basePath = "<?php echo \Yii::$app->request->BaseUrl;?>";
       
        $.ajax(basePath+'/quotation/updateapplicant', 
        {
            data:{q:quotationId,p:partnerId},
            dataType: 'json', // type of response data
            success: function (data,status,xhr) {   // success callback function
               if(data == '1'){
                  alert('Partner assigned successfully');
               }else if(data == '2'){
                  
               //    if(confirm('Please make him Partner first If you click "ok" you would be redirected . Cancel will load this website ')) 
               // {
               //       window.location.href='https://www.google.com/chrome/browser/index.html';
               //  };
                   alert('Please make him Partner first ');
               }else{
                  alert('Unable to process your request please try later.');
               }
            },
            error: function (jqXhr, textStatus, errorMessage) { // error callback 

            }
        });
    }else{
        var quotationId = $("#q_id").val();
        var partnerId = '0';
        var basePath = "<?php echo \Yii::$app->request->BaseUrl;?>";
       
        $.ajax(basePath+'/quotation/updateapplicant', 
        {
            data:{q:quotationId,p:partnerId},
            dataType: 'json', // type of response data
            success: function (data,status,xhr) {   // success callback function
               if(data == '1'){
                  alert('No Partner currently assigned');
               }else if(data == '2'){
               
               // if(confirm('Please make him Partner first If you click "ok" you would be redirected . Cancel will load this website ')) 
               // {
               //       window.location.href='https://www.google.com/chrome/browser/index.html';
               //  };
                  alert('Please make him Partner first ');
               }else if(data == 3){
                  alert('Please upload quotation first');
               }else{
                  alert('Unable to process your request please try later.');
               }
            },
            error: function (jqXhr, textStatus, errorMessage) { // error callback 

            }
        });
        $(".checkAssignee").not(t).attr('disabled',false);
    }
}
</script>
