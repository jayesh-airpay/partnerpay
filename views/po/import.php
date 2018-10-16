<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 27-06-2016
 * Time: 12:06
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use yii\widgets\DetailView;
//use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Partner */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$filename = "po.csv";
$path = Yii::$app->homeUrl . 'uploads/sample_files/' . $filename;

$merchantArray = $partnerArray = [];

if (!Yii::$app->user->isGuest) {
    if (Yii::$app->getUser()->identity->USER_TYPE == 'admin') {
        $merchant_detail = \app\models\MerchantMaster::find()->select('MERCHANT_ID, MERCHANT_NAME')->andWhere(['MERCHANT_STATUS' => 'E'])->all();
        if(!empty($merchant_detail)){
            foreach($merchant_detail as $merchant) {
                $merchantArray[$merchant["MERCHANT_ID"]] = $merchant["MERCHANT_NAME"];
            }
        }
    }
    if (Yii::$app->getUser()->identity->USER_TYPE == 'merchant') {
        $partner_detail = \app\models\Partner::find()->select('PARTNER_ID, PARTNER_NAME')->andWhere(['PARTNER_STATUS' => 'E','MERCHANT_ID' => Yii::$app->getUser()->identity->MERCHANT_ID])->all();
        if(!empty($partner_detail)){
            foreach($partner_detail as $partner) {
                $partnerArray[$partner["PARTNER_ID"]] = $partner["PARTNER_NAME"];
            }
        }
    }
}

$this->registerJs('
    function loadPartnerDropdown(mid) {
        var partner_selected = "'.$model->IMPORT_MERCHANT.'";
        if(mid != "" && mid != 0 && mid != null) {
            var url = "' . \yii\helpers\Url::to(['/user/get-partner-list']) . '";
            var post_data  = "ajax=true&' . Yii::$app->request->csrfParam . '=' . Yii::$app->request->csrfToken . '&mid="+mid+"&selected="+partner_selected;
            console.log(url);
            $.post(url, post_data, function (data) {
                $("#importpo-import_partner").html(data);
            }).fail(function (data) {});
        }
    }
', yii\web\View::POS_READY, 'merchant-function');

$this->registerJs('
$("#importpo-import_merchant").change(function () {
    var merchant_id = $("#importpo-import_merchant").val();
    loadPartnerDropdown(merchant_id);
});

', yii\web\View::POS_READY, 'merchant');
?>

<div class="page-header">
    <h4>Import Bulk PO</h4>
    <div class="fieldstx">Fields with <span>*</span> are required.</div>
</div>

<?php
if(!empty( Yii::$app->session->getFlash('error'))) {
    echo  Yii::$app->session->getFlash('error');
}

if(!empty( Yii::$app->session->getFlash('success'))) {
    echo  Yii::$app->session->getFlash('success');
}
?>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= Html::a('Download Sample file', $path, ['class' =>"btn btn-primary"]) ?>
        </div>
    </div>
</div>

<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'class'=>'form']]); ?>

<?php
if (!Yii::$app->user->isGuest) {
    if (Yii::$app->getUser()->identity->USER_TYPE == 'admin') { ?>
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <div class="form-group req">
                    <?= $form->field($model, 'IMPORT_MERCHANT')->dropDownList($merchantArray, ['prompt' => 'Select Merchant'])->label(false) ?>
                </div>
            </div>
        </div>
        <?php
    }
}
?>

<?php
if (!Yii::$app->user->isGuest) {
    if (Yii::$app->getUser()->identity->USER_TYPE != 'partner') { ?>
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <div class="form-group req">
                    <?= $form->field($model, 'IMPORT_PARTNER')->dropDownList($partnerArray, ['prompt' => 'Select Partner'])->label(false) ?>
                </div>
            </div>
        </div>
        <?php
    }
}
?>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="form-group req">
                <div class="form-control file">
                    <?= $form->field($model,'CSV')->fileInput(['title'=>'Select CSV File', 'id' => 'uploadBtn' ])->label(false); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary lg-btn']) ?>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>