<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<h3>Forgot Password</h3>
<div class="formwrap">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form'],
        'fieldConfig' => [
            'template' => "{input}\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
    <?= Yii::$app->session->getFlash('error'); ?>
    
	   <?= Html::hiddenInput('forgot',1) ?>
    <ul class="formbox">
        <li>
            <div class="form-group ">
                <?= Html::input('text', 'email', null, ['class' => 'form-control','placeholder'=>'Email ID']) ?>
            </div>
        </li>
        <li class="btngroup">
            <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </li>
        <li>
            <a href="<?php echo \yii\helpers\Url::to(['/site/login']) ?>" class="link">back to login page</a>
            <br/>
        </li>
    </ul>

    <?php ActiveForm::end(); ?>

</div>
