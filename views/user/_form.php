<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserMaster */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
if (!$model->isNewRecord) {
    $this->registerJs('
    $(".field-usermaster-merchant_id").show();
    //showHidedropdown("' . $model->USER_TYPE . '",' . $model->USER_ID . ');
', \yii\web\View::POS_READY);
}
else {
	if(Yii::$app->user->identity->USER_TYPE == "merchant") {
		$model->MERCHANT_ID = Yii::$app->user->identity->MERCHANT_ID;
		$sql = "SELECT CREATE_QR FROM tbl_merchant_master WHERE MERCHANT_ID = '".$model->MERCHANT_ID."'";
		$conn = Yii::$app->db->createCommand($sql);
		$s = $conn->queryAll();
		$model->CREATE_QR = $s[0]['CREATE_QR'];
	}

    $this->registerJs('
    $(".field-usermaster-merchant_id").hide();
', \yii\web\View::POS_READY);
}

$this->registerJs('
var user_id = "'.$model->USER_ID.'";
', \yii\web\View::POS_HEAD);

$this->registerJs('
function showHidedropdown(user, user_id) {
    $("#usermaster-merchant_id").html("");
    if(user != "") {
        var url = "' . \yii\helpers\Url::to(['/user/get-list']) . '";
        var post_data  = "ajax=true&' . Yii::$app->request->csrfParam . '=' . Yii::$app->request->csrfToken . '&type="+user+"&user_id="+user_id;
        $.post(url, post_data, function (data) {
                $("#merchant_dropdown").addClass("req");
                $("#usermaster-merchant_id").html(data);
                $("#usermaster-merchant_id").change();
        }).fail(function (data) {});
    }
}

function loadPartnerDropdown(mid,user) {
    var partner_selected = "'.$model->PARTNER_ID.'";
    //var merchant_id = $("#usermaster-merchant_id").val();
    var merchant_id = mid;
    $("#usermaster-partner_id").html("");
    if(merchant_id != "" && merchant_id != 0 && merchant_id != null) {
      if(user == "partner") {
          $("#partner_dropdown").addClass("req");
      } else {
         $("#partner_dropdown").removeClass("req");
      }
        var url = "' . \yii\helpers\Url::to(['/user/get-partner-list']) . '";
        var post_data  = "ajax=true&' . Yii::$app->request->csrfParam . '=' . Yii::$app->request->csrfToken . '&mid="+mid+"&selected="+partner_selected;
        console.log(url);
        $.post(url, post_data, function (data) {
            $("#usermaster-partner_id").html(data);
        }).fail(function (data) {});
    }
}

', yii\web\View::POS_READY, 'merchant-function');

$this->registerJs('
$("#usermaster-user_type").change(function () {
    var user = $("#usermaster-user_type").val();
    var merchant_id = $("#usermaster-merchant_id").val();
    $(".field-usermaster-merchant_id").show();
    showHidedropdown(user,user_id);
    if(user == "admin" || user == ""){
        $(".field-usermaster-merchant_id").hide();
        $(".field-usermaster-partner_id").hide();
    }
    showHidedropdown(user, user_id);
    if(user == "merchant"){
        $(".field-usermaster-partner_id").hide();
    }

    if(user == "partner")  {
        $(".field-usermaster-partner_id").show();
        loadPartnerDropdown(merchant_id,user);
    }   else    {
        loadPartnerDropdown(merchant_id,user);
        $(".field-usermaster-partner_id").hide();
    }
});

$("#usermaster-merchant_id").change(function () {
    var user_type = $("#usermaster-user_type").val();
    var merchant_id = $("#usermaster-merchant_id").val();

    if(user_type == "partner")  {
        $(".field-usermaster-partner_id").show();
        loadPartnerDropdown(merchant_id,user_type);
    }   else    {
        loadPartnerDropdown(merchant_id, user_type);
        $(".field-usermaster-partner_id").hide();
    }
});

$("#usermaster-user_type").change();
//$("#usermaster-merchant_id").change();
', yii\web\View::POS_READY, 'merchant');

$this->registerJs('
$(document).ready(function() {
    $("#categories").hide();
})
', \yii\web\View::POS_READY);

$this->registerJs('
$("#usermaster-user_type").change(function(e) {
	var str = $("#usermaster-user_type").val();
	if(str == "muser") {
		$("#categories").show();
	}
	else {
		$("#categories").hide();
	}
})
', \yii\web\View::POS_READY);
?>

<?php
if(Yii::$app->getUser()->identity->USER_TYPE == 'admin' || Yii::$app->getUser()->identity->USER_TYPE == 'merchant') {
	$cat_detail = \app\models\CategoryMaster::find()->select('CAT_ID, CAT_NAME')->andWhere(['CAT_STATUS' => 'E'])->all();
	if(!empty($cat_detail)){
		foreach($cat_detail as $cat) {
			$catArray[$cat["CAT_ID"]] = $cat["CAT_NAME"];
		}
	}
}

if(Yii::$app->getUser()->identity->USER_TYPE == 'admin' || Yii::$app->getUser()->identity->USER_TYPE == 'merchant') {
	$uid = Yii::$app->getUser()->identity->USER_ID;
	$sql = "SELECT b.CAT_ID, a.CAT_NAME FROM tbl_merchant_access_rules b INNER JOIN tbl_category_master a ON a.CAT_ID = b.CAT_ID WHERE b.USER_ID = '".$uid."'";
	$conn = Yii::$app->db->createCommand($sql);
	$s = $conn->queryAll();
	if(!empty($s)){
		foreach($s as $cat1) {
			$catChkArray[$cat1["CAT_ID"]] = $cat1["CAT_NAME"];
		}
	}
}
?>

<div class="page-header">
    <h4><?= ($model->isNewRecord)?'Create':'Update' ?> Users</h4>
    <div class="fieldstx">Fields with <span>*</span> are required.</div>
</div>

<?php $form = ActiveForm::begin([
    'enableClientValidation' => true,
    'class'=>'form'
]); ?>


<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'FIRST_NAME')->textInput(['maxlength' => 50, 'placeholder'=>'First Name'])->label(false) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'LAST_NAME')->textInput(['maxlength' => 50, 'placeholder'=>'Last Name'])->label(false) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'MOBILE')->textInput(['maxlength' => 10, 'placeholder'=>'Mobile'])->label(false) ?>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?= $form->field($model, 'EMAIL')->textInput(['maxlength' => 70, 'placeholder'=>'Email'])->label(false) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group <?= ($model->isNewRecord)?'req':'' ?>">
            <?php echo $form->field($model, 'PASSWORD')->passwordInput(['maxlength' => 50, 'placeholder'=>'Password'])->label(false); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group <?= ($model->isNewRecord)?'req':'' ?>req">
            <?php echo $form->field($model, 'REPEAT_PASSWORD')->passwordInput(['maxlength' => 50, 'placeholder'=>'Repeat Password'])->label(false); ?>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group req">
            <?php
            if (Yii::$app->user->identity->USER_TYPE != 'partner') {
                $type = [];
                if (Yii::$app->user->identity->USER_TYPE == 'admin') {
                    $type = ['admin' => 'Admin', 'merchant' => 'Merchant Admin', 'partner' => 'Merchant Partner', 'approver' => 'Merchant Approver', 'payment' => 'Merchant Payment', 'muser' => 'Merchant User'];
                    echo $form->field($model, 'USER_TYPE')->dropDownList($type, ['prompt' => 'Select User Type', 'options' => [$model->USER_TYPE => ['selected' => true]]])->label(false);
                } else if (Yii::$app->user->identity->USER_TYPE == 'merchant') {
					if($model->CREATE_QR == 'Y') {
						$type = ['merchant' => 'Merchant Admin', 'partner' => 'Merchant Partner', 'approver' => 'Merchant Approver', 'payment' => 'Merchant Payment', 'muser' => 'Merchant User'];
					}
					else {
						$type = ['merchant' => 'Merchant Admin', 'partner' => 'Merchant Partner', 'approver' => 'Merchant Approver', 'payment' => 'Merchant Payment'];
					}
                    
                    echo $form->field($model, 'USER_TYPE')->dropDownList($type, ['prompt' => 'Select User Type', 'options' => [$model->USER_TYPE => ['selected' => true]]])->label(false);
                }
            }?>
        </div>
    </div>
</div>

<?php if(!$model->isNewRecord) { ?>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="onoffswitch req">
                <?= $form->field($model, 'USER_STATUS')->dropDownList(['E' => 'Enable','D' => 'Disable'])->label(false) ?>
            </div>
        </div>
    </div>

<?php } ?>

<?php if(Yii::$app->user->identity->USER_TYPE == 'admin') { ?>
    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="form-group" id="merchant_dropdown">
                <?php echo $form->field($model, 'MERCHANT_ID')->dropDownList([], ['prompt' => '', 'id' => 'usermaster-merchant_id'])->label(false);?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="form-group" id="partner_dropdown">
                <?php echo $form->field($model, 'PARTNER_ID')->dropDownList([], ['prompt' => '', 'id' => 'usermaster-partner_id'])->label(false); ?>
            </div>
        </div>
    </div>

<?php } else if (Yii::$app->user->identity->USER_TYPE == 'merchant' || Yii::$app->user->identity->USER_TYPE == 'approver' || Yii::$app->user->identity->USER_TYPE == 'payment' || Yii::$app->user->identity->USER_TYPE == 'muser') { ?>

    <?php echo $form->field($model, 'MERCHANT_ID')->hiddenInput(['value'=> $model->MERCHANT_ID])->label(false); ?>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="form-group" id="partner_dropdown">
                <?php echo $form->field($model, 'PARTNER_ID')->dropDownList([], ['prompt' => '', 'id' => 'usermaster-partner_id'])->label(false); ?>
            </div>
        </div>
    </div>
<?php } ?>

<?php if((Yii::$app->user->identity->USER_TYPE == "admin") || (Yii::$app->user->identity->USER_TYPE == "merchant" && $model->CREATE_QR == 'Y')) { ?>
<div class="row">
    <div class="col-sm-6 col-md-6">
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <?php if(!$model->isNewRecord) {
                    ?>
                    <div class="checkbox">
                        <div id="categories">
                            <?php
                            foreach($catArray as $key => $val) {
                                if(in_array($val,$catChkArray)) {
                                    echo "<input type='checkbox' name='UserMaster[CATEGORIES][]' value='".$key."' checked>";
                                    echo $val;
                                    echo "<br>";
                                }
                                else {
                                    echo "<input type='checkbox' name='UserMaster[CATEGORIES][]' value='".$key."'>";
                                    echo $val;
                                    echo "<br>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                else {
                    ?>
                    <div class="checkbox">
                        <div id="categories">
                            <?= $form->field($model, 'CATEGORIES')->checkboxList($catArray)->label(false) ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
}
?>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary lg-btn' : 'btn btn-primary lg-btn']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>