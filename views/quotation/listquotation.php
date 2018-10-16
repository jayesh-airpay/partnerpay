<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel app\models\QuotationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$server = explode('.',$_SERVER['SERVER_NAME']);
$server = $server[0];
$this->title = 'Quotations';
$this->params['breadcrumbs'][] = $this->title;

$d =  \app\models\Quotation::find()->where('ID = :pid', [':pid' => $id])->one();

?>
<div class="quotation-index page-header" onload="checkStatus()">

    <h4><?= Html::encode($this->title).' Listing' ?></h4>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <input type="hidden" id="q_id" value="<?php echo $id;?>">
    <input type="hidden" id="q_status" value="<?php echo $d->STATUS;?>">
    </div>

    <div class="tablebox">
     <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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
                'attribute' => 'UPDATED',
                'format' =>'raw',
                'label' => 'Submitted Date',
                'value' => function($data){
                    if(!empty($data->UPDATED)){
                       return date('d-m-Y',$data->UPDATED);
                    }
                 },
           ],
//             ['class' => 'yii\grid\CheckboxColumn',
//              'header' => 'Approved',
//              'checkboxOptions' => function($data) {
//                          return  ['value' => $data->PARTNER_ID ,'label'=>'','onclick' => 'js:approveVendor(this)','class' => 'checkAssignee'];
//                  }
             
//             ],
             ['class' => 'yii\grid\RadioButtonColumn',
             'header' => 'Approved',
             'radioOptions' => function ($data) use ($assign_partner) {
                return [
                   'value' => $data->PARTNER_ID,
                   'checked' => $data->PARTNER_ID == $assign_partner,
                   'label' => '',
                   'onclick' => 'js:approveVendor(this)',
                   'class' => 'checkAssignee',
                ];
             }
             
            ],
           // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
</div>
    <div id="polink" style="margin-left: 7.5em;"></div>
<script>


function approveVendor(t){
    if(t.checked){
        $(".checkAssignee").not(t).attr('disabled',true);
        var quotationId = $("#q_id").val();
        var partnerId = t.value;
        var basePath = "<?php echo \Yii::$app->request->BaseUrl;?>";
  
       // var server = 'swapnil';
        if(confirm("Do you want to approve this quotation.")){
        $('.loader').show();
        $.ajax(basePath+'/quotation/updateapplicant', 
        {
            data:{q:quotationId,p:partnerId},
            dataType: 'json', // type of response data
            success: function (data,status,xhr) {   // success callback function
            $('.loader').hide();
               if(data == '1'){
                  alert('Partner assigned successfully');
                  $(".checkAssignee").not(t).attr('disabled',true);
                  $(".checkAssignee").attr('onclick','return false');
                  $("#polink").html('Click <a href="/po/create?qr='+quotationId+'"><u>here</u></a> to create PO');
               }else if(data == '2'){
                  if(confirm("Please make him Partner first Do you wish to proceed click ok will lead you to partner page.")){
                      var server = "<?php echo $server;?>";
                      window.location.href ='http://'+server+'.partnerpay.co.in/partner/update/'+partnerId;
                  }else{
                      t.checked = false;
                   $(".checkAssignee").not(t).attr('disabled',false);
                  }
                  //alert('Please make him Partner first');
                  
               }else{
                  alert('Unable to process your request please try later.');
               }
            },
            error: function (jqXhr, textStatus, errorMessage) { // error callback 
                   $('.loader').hide();
                   alert('Unable to process your request please try after sometime.');
            }
        });
       
        }else{
          t.checked = false;
            $(".checkAssignee").not(t).attr('disabled',false);
        }
        
    }else{
        var quotationId = $("#q_id").val();
        var partnerId = '0';
        var basePath = "<?php echo \Yii::$app->request->BaseUrl;?>";
        //var server = <?php //echo $server;?>
//         if(confirm("Do you want to approve this quotation.")){
//         $.ajax(basePath+'/quotation/updateapplicant', 
//         {
//             data:{q:quotationId,p:partnerId},
//             dataType: 'json', // type of response data
//             success: function (data,status,xhr) {   // success callback function
//                if(data == '1'){
//                   alert('No Partner currently assigned');
//                }else if(data == '2'){
//                   alert('Please make him Partner first');
//                }else if(data == '3'){
//                   alert('Please upload quotation first');
//                }else if(data == '4'){
//                   alert('Quotation already assigned');
//                }else{
//                   alert('Unable to process your request please try later.');
//                }
//             },
//             error: function (jqXhr, textStatus, errorMessage) { // error callback 

//             }
//         });
//         }
         t.checked = false;
        $(".checkAssignee").not(t).attr('disabled',false);
    }
}
</script>
