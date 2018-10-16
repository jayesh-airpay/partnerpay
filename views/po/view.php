<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PoMaster */

$this->title = $model->PO_ID;
$this->params['breadcrumbs'][] = ['label' => 'Po', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$connection = Yii::$app->getDb();
$command = $connection->createCommand("SELECT CHARGE_NAME,CHARGE_VALUE from tbl_po_tax where PO_ID=".$model->PO_ID, []);
$potaxdata = $command->queryAll();

?>

<div class="page-header">
    <h4>View PO</h4>
    <div class="fieldstx">
        <?php if((Yii::$app->user->identity->USER_TYPE != 'partner')) { ?>
            <?= Html::a('Update', ['update', 'id' => $model->PO_ID], ['class' => 'btn btn-default']) ?>           
        <?php } ?>
    </div>
</div>

<div class="table-responsive invoice-table">

    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table table-striped'],
        'template'=>'<tr><td><b>{label}</b></td><td>{value}</td></tr>',
        'attributes' => [
            'PO_ID',
            [
                'attribute' => 'MERCHANT_NAME',
                'value' => !empty($model->merchant)?$model->merchant->MERCHANT_NAME:'',
                'visible' => (!Yii::$app->getUser()->getIsGuest() && Yii::$app->getUser()->getIdentity()->USER_TYPE !='partner'),
            ],
            [
                'attribute' => 'PARTNER_NAME',
                'value' => !empty($model->partner->PARTNER_NAME)?$model->partner->PARTNER_NAME:'',
                'visible' => (!Yii::$app->getUser()->getIsGuest() && Yii::$app->getUser()->getIdentity()->USER_TYPE !='partner'),
            ],
            [
                'attribute' => 'SAP_REFERENCE',
                'value' => !empty($model->SAP_REFERENCE)?$model->SAP_REFERENCE:'',
                'visible' => (!Yii::$app->getUser()->getIsGuest() && Yii::$app->getUser()->getIdentity()->USER_TYPE !='merchant'),
            ],
            'PO_NUMBER',
            [
                'attribute' => 'DATE_OF_CREATION',
                'value' => $model->DATE_OF_CREATION,
            ],
            'AMOUNT:currency',
            [
                'label' => 'PO PDF',
                'format' => 'raw',
                'value' => !empty($model->PDF_ATTACHMENT)?Html::a($model->PDF_ATTACHMENT, ["/uploads/pdf/$model->PDF_ATTACHMENT"], ['target'=>'_blank']):null,
            ],
            [
                'attribute' => 'CREATED_ON',
                'value' => date("d M Y", $model->CREATED_ON)
            ],
        ],
    ]) ?>

</div>
<div>
<?php if(count($potaxdata)){ ?>
<table class="table table-bordered" style="width:100%">
  <tr>
    <th>CHARGE NAME</th>
    <th>CHARGE VALUE</th> 
</tr>
<?php 
 
     foreach($potaxdata as $k => $v){
        echo  '<tr>
                 <td>'.$v['CHARGE_NAME'].'</td>
                 <td>'.$v['CHARGE_VALUE'].'</td> 
              </tr>';
     }


?>
 
</table>
<?php } ?>
</div>
<?php if(Yii::$app->user->identity->USER_TYPE == 'partner'){ ?>
<?php if($model->STATUS == 'A'){?>
<label class="radio-inline"><input type="radio" name="status" class="po_status" value="A" checked disabled>Approve PO</label>
<label class="radio-inline"><input type="radio" name="status" class="po_status" value="R" disabled>Reject PO</label>
<?php } else if($model->STATUS == 'R'){?>
<label class="radio-inline"><input type="radio" name="status" class="po_status" value="A" disabled>Approve PO</label>
<label class="radio-inline"><input type="radio" name="status" class="po_status" value="R" checked disabled>Reject PO</label>
<?php }else{?>
<label class="radio-inline"><input type="radio" name="status" class="po_status" value="A" >Approve PO</label>
<label class="radio-inline"><input type="radio" name="status" class="po_status" value="R" >Reject PO</label>
<?php }?>
<input type="hidden" id="PO_ID" value="<?php echo $model->PO_ID;?>">
<?php } ?>
<?php if($model->STATUS == 'A'){?>

<?php } ?>
<div id="invoice_link" style="display:none;"></div>