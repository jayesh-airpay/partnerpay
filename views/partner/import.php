
<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 07-06-2016
 * Time: 16:24
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
    <h4>Import Partners</h4>
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
            <?php //var_dump(Yii::$app->getUser()->identity->USER_TYPE); exit;
            if (Yii::$app->getUser()->identity->USER_TYPE == 'admin') {
                $filename = "admin_importpartner_sample.csv";
            }
            if (Yii::$app->getUser()->identity->USER_TYPE == 'merchant') {
                $filename = "merchant_importpartner_sample.csv";
                //$path = Yii::$app->homeUrl . 'uploads/csv/merchant_importpartner_sample.csv';
            }
            $path = Yii::$app->homeUrl . 'uploads/sample_files/' . $filename;
            //var_dump($path); exit;
            ?>
            <?= Html::a('Download Sample file', $path, ['class' =>"btn btn-primary"]) ?>

        </div>
    </div>
</div>
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
                <?= $form->field($model,'CSV')->fileInput(['title'=>'Select CSV File', 'id' => 'uploadBtn' ])->label(false); ?>
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