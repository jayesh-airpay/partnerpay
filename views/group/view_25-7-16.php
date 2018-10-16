<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\GroupInvoice */

$this->title = $model->GROUP_INVOICE_ID;
$this->params['breadcrumbs'][] = ['label' => 'Group Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-invoice-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <!--<?= Html::a('Update', ['update', 'id' => $model->GROUP_INVOICE_ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->GROUP_INVOICE_ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>-->
        <?= Html::a('Download Invoices', ['download-invoice', 'id' => $model->GROUP_INVOICE_ID], [
            'class' => 'btn btn-danger',
        ]) ?>
        <?= Html::a('Upload UTR', ['utr-update'], [
            'class' => 'btn btn-primary',
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'GROUP_INVOICE_ID',
            'AMOUNT',
            'SERVICE_CHARGE',
            'TOTAL_AMOUNT',
            [
                'attribute' => 'CREATED_ON',
                'value' => date("d M Y", $model->CREATED_ON)
            ],
        ],
    ]) ?>

    <h3>Invoices</h3>
    <div id="w0" class="grid-view"><div class="summary">Showing <b>1-20</b> of <b>43</b> items.</div>
        <table class="table table-striped table-bordered text-center"><thead>
            <tr>
                <th>#</th>
                <th>Invoice Id</th>
                <th>Amount</th>
                <th>UTR Number</th>
            	<th>Partner Name</th>
            	<th>Brand Name</th>
                <th>Branch Name</th>
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
                    <td><?=$map_model->invoice->TOTAL_AMOUNT; ?></td>
                    <td><?=$map_model->UTR_NO; ?></td>
                    <td><?=$map_model->invoice->partner->PARTNER_NAME; ?></td>
                	<td><?=$map_model->BRAND; ?></td>
                    <td><?=$map_model->BRANCH; ?></td>
                    <td><?=!empty($map_model->PAYMENT_DATE)?date('d M Y', $map_model->PAYMENT_DATE):'&nbsp;'; ?></td>
                </tr>

            <?php
                $i++;
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
