<?php
/**
 * Created by PhpStorm.
 * User: nandana
 * Date: 18/2/16
 * Time: 12:31 PM
 */
?>
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
\app\assets\AppAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Invoice */
//echo "asdfg"; exit;
$this->title = 'Invoices';
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//var_dump($model->ATTACHMENT); exit;
?>
<div class="page-header">
    <h4>View Invoice</h4>
    <div class="fieldstx">
        <?php
        if(Yii::$app->user->identity->USER_TYPE == 'partner'){
            if($model->INVOICE_STATUS != 1) {
                echo Html::a('Update', ['update', 'id' => $model->INVOICE_ID], ['class' => 'btn btn-primary']);
            } else {
                echo '<div class="alert alert-warning">Invoice can not updated</div>';
            }
        }

        ?>
    </div>

</div>
    <?= Yii::$app->session->getFlash('error'); ?>


<div class="table-responsive invoice-table">

    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table table-striped'],
        'template'=>'<tr><td><b>{label}</b></td><td>{value}</td></tr>',
        'attributes' => [
            //'INVOICE_ID',
            [
                'label' => 'Invoice',
                'format' => 'raw',
                'value' => $model->INVOICE_ID,
            ],
            [
                'label' => 'Reference Number',
                'format' => 'raw',
                'value' => $model->REF_ID,
            ],
            [
                'label' => 'Created By',
                'format' => 'raw',
                'value' => Yii::$app->user->identity->EMAIL
            ],
            [
                'attribute' => 'ASSIGN_TO',
                //'label' => 'Created By',
                'format' => 'raw',
                'value' => \app\helpers\generalHelper::getAssigneeName($model->ASSIGN_TO)
            ],
            //'ASSIGN_TO',
           /* [
                'attribute' => 'ASSIGN_TO',
                //'label' => 'Assign To',
                'value' => function ($data) {
                    var_dump($data); exit;
                //$user = \app\models\UserMaster::find()->where(['P' => 'partner'])->one();

                //return !empty($user) ? $user["FIRST_NAME"] . " " . $user["LAST_NAME"] : null;
            },
            ],*/
            'CLIENT_EMAIL:email',
            'CLIENT_MOBILE',
            [
                'label' => 'Partner',
                'format' => 'raw',
                'value' => $model->partner->PARTNER_NAME
            ],

            [
                'attribute' => 'APPLY_SURCHARGE',
                'value' => ($model->APPLY_SURCHARGE== 1)?'Yes':'No'
            ],
            [
                'label' => 'Company Name',
                'value' => $model->COMPANY_NAME,
                'visible' => ($model->IS_CORPORATE=='Y')
            ],
            [
                'attribute' => 'AMOUNT',
                'value' => sprintf("%0.2f", $model->AMOUNT),
            ],
            [
                'attribute' => 'PAID',
                'value' => sprintf("%0.2f", $model->PAID),
            ],
            [
                'attribute' => 'BALANCE',
                'value' => sprintf("%0.2f", $model->BALANCE),
            ],
            'EXPIRY_DATE',
            [
                'label' => 'Invoice Status',
                'value' => empty($model->INVOICE_STATUS)?"Unpaid":"Paid",
            ],

            [
                'label' => 'Payment Url',
                'format' => 'raw',
                'value' => Html::a('http://'.$model->partner->merchant->DOMAIN_NAME.'.partnerpay.co.in/invoice/view/'.$model->INVOICE_ID,'http://'.$model->partner->merchant->DOMAIN_NAME.'.partnerpay.co.in/invoice/view/'.$model->INVOICE_ID, ['target'=>'_blank']),

            ],
            [
                'label' => 'Invoice PDF',
                'format' => 'raw',
                'value' => !empty($model->ATTACHMENT)?Html::a($model->ATTACHMENT, ["../uploads/attachment/$model->ATTACHMENT"], ['target'=>'_blank']):null,
            ],

        ],
    ]) ?>

</div>
    <?php if(Yii::$app->user->identity->USER_TYPE != 'merchant') {
        if($model->INVOICE_STATUS==0) { ?>
    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            Comment : <br>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?php echo \yii\helpers\Url::to(['mark-paid']); ?>" id="comment">
                       <?php  echo Html::textArea('comment','',['rows' => '7','class'=>'form-control']);
                       echo Html::hiddenInput('invoice_id',$model->INVOICE_ID);
                        echo "<br><br>";
                       // echo Html::a('Mark as Paid', ['mark-paid', 'id' => $model->INVOICE_ID, 'mid' => Yii::$app->request->get('mid')], ['class' => 'btn btn-primary']);

                       ?>
                </div>
                </form>

                <div class="modal-footer">
                    <?php echo Html::submitButton('Submit',['class'=>'btn btn-primary', 'id'=>'comment_form']); ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
            <br>
            <?php echo Html::button('Mark as Paid',['class'=>'btn btn-primary', 'id'=>'paid_form', 'data-toggle'=>"modal", 'data-target'=>"#myModal"]); ?>

        <?php }
    } ?>




<?php
$this->registerJs('
$("#comment_form").click(function (){
             $("#comment").submit();
    });


',\yii\web\View::POS_READY, 'comment_message');
?>
