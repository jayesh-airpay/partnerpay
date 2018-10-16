<?php
/**
 * Created by PhpStorm.
 * User: akshay
 * Date: 2/9/16
 * Time: 6:36 PM
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


$merchantArray = [];

if (!Yii::$app->user->isGuest) {
    if (Yii::$app->getUser()->identity->USER_TYPE == 'admin') {
        $merchant_detail = \app\models\MerchantMaster::find()->select('MERCHANT_ID, MERCHANT_NAME')->andWhere(['MERCHANT_STATUS' => 'E'])->all();
        if(!empty($merchant_detail)){
            foreach($merchant_detail as $merchant) {
                $merchantArray[$merchant["MERCHANT_ID"]] = $merchant["MERCHANT_NAME"];
            }
        }
    }
}
?>

    <div class="page-header">
        <h4>Import Partner Invoices</h4>
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

<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data', 'class'=>'form']]); ?>

    <!--<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?/*= Html::a('Download Sample file', $path, ['class' =>"btn btn-primary"]) */?>
        </div>
    </div>
</div>-->

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="form-group req">
                <div class="form-control file">
                    <?= $form->field($model,'TXT')->fileInput(['title'=>'Select Text File', 'id' => 'uploadBtn' ])->label(false); ?>
                </div>
            </div>
        </div>
    </div>


<?php
            if (!Yii::$app->user->isGuest) {
                if (Yii::$app->getUser()->identity->USER_TYPE == 'admin') {      ?>
                    <div class="row">
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group req">
                                <?= $form->field($model, 'IMPORT_MERCHANT_ID')->dropDownList($merchantArray, ['prompt' => 'Select Merchant'])->label(false) ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                if (Yii::$app->user->identity->USER_TYPE == 'merchant') { ?>

                    <?php echo $form->field($model, 'IMPORT_MERCHANT_ID')->hiddenInput(['value' => Yii::$app->user->identity->MERCHANT_ID])->label(false);
                } ?>

            <?php }
            ?>


    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary lg-btn']) ?>
            </div>
        </div>
    </div>


<?php ActiveForm::end(); ?>