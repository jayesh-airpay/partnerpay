<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\GroupInvoice */

$this->title = "View Group Invoice";
$this->params['breadcrumbs'][] = ['label' => 'Group Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
setlocale(LC_MONETARY, 'en_IN');
?>
<div class="page-header">

    <h4><?= Html::encode($this->title) ?></h4>
    <div class="fieldstx">
        <?= Html::a('Download Invoices', ['download-invoice', 'id' => $model->GROUP_INVOICE_ID], [
            'class' => 'btn btn-danger',
        ]) ?>
    <?php if(Yii::$app->user->identity->USER_TYPE == 'admin') { ?>
        <?= Html::a('Upload UTR', ['utr-update'], [
            'class' => 'btn btn-primary',
        ]) ?>
    <?php } ?>
    </div>
</div>

        <!--<?= Html::a('Update', ['update', 'id' => $model->GROUP_INVOICE_ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->GROUP_INVOICE_ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>-->


<div class="table-responsive invoice-table">
    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table table-striped'],
        'template'=>'<tr><td><b>{label}</b></td><td>{value}</td></tr>',
        'attributes' => [
            'GROUP_INVOICE_ID',
            [
                'attribute' => 'AMOUNT',
                'value' => $model->AMOUNT,
                'format' => 'currency',
            ],
            [
                'attribute' => 'SERVICE_CHARGE',
                'value' => $model->SERVICE_CHARGE,
                'format' => 'currency',
            ],
            //'SERVICE_CHARGE',
            //'TOTAL_AMOUNT',
        	/*[
                'attribute' => 'GI_REF_ID',
                'value' => $model->GI_REF_ID,
            ],*/
            [
                'attribute' => 'TOTAL_AMOUNT',
                'value' => $model->TOTAL_AMOUNT,
                'format' => 'currency',
            ],
            [
                'attribute' => 'PAYMENT_DATE',
                'value' => (!empty($model->PAYMENT_DATE))?date("d M Y", $model->PAYMENT_DATE):null,
                'visible' => ($model->INVOICE_STATUS == 1),
            ],
            [
                'attribute' => 'CREATED_ON',
                'value' => date("d M Y", $model->CREATED_ON)
            ],
        ],
    ]) ?>

</div>

    <h3>Invoices</h3>
    <div id="w0" class="grid-view">
        <table class="table table-striped table-bordered text-center"><thead>
            <tr>
                <th>#</th>
                <th>Invoice Id</th>                
                <th>UTR Number</th>
            	<th>Partner Name</th>
                <th>Account Number</th>
                <th>IFSC Code</th>
            	<th>Brand Name</th>
                <th>Branch Name</th>

                <th>Amount</th>
                <th>Payment Date</th>
            </thead>
            <tbody>
            <?php
            $i = 1;
            foreach ($model->groupInvoiceMaps as $map_model)    {
                $invoice_id_arr[] = $map_model->INVOICE_ID;
                ?>
                <tr>
                    <td><?= $i; ?></td>
                    <td><?=$map_model->INVOICE_ID; ?></td>                    
                    <td><?=$map_model->UTR_NO; ?></td>
                    <td><?=$map_model->invoice->partner->PARTNER_NAME; ?></td>
                    <td><?=$map_model->invoice->partner->ACCOUNT_NUMBER; ?></td>
                    <td><?=$map_model->invoice->partner->IFSC_CODE; ?></td>
                	<td><?=$map_model->BRAND; ?></td>
                    <td><?=$map_model->BRANCH; ?></td>

                    <td class="text-right"><?= "Rs ".money_format('%!i', $map_model->invoice->TOTAL_AMOUNT); ?></td>
                    <td><?=!empty($map_model->PAYMENT_DATE)?date('d M Y', $map_model->PAYMENT_DATE):'&nbsp;'; ?></td>
                </tr>

            <?php
                $i++;
            }
            ?>
             <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="text-right"><?php echo "<b>Amount Rs ".money_format('%!i', $model->AMOUNT)."</b>"; ?></br><?php echo "<b>Service Charge Rs ".money_format('%!i', $model->SERVICE_CHARGE)."</b>"; ?></br><?php echo "<b>Total Amount Rs ".money_format('%!i', $model->TOTAL_AMOUNT)."</b>"; ?></td>
                <td>&nbsp;</td>
            </tr>
            </tbody>
        </table>
    </div>

