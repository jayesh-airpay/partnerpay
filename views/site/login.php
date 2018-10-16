<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<h3>Login</h3>
<p>Please fill out the following form with your login credentials</p>

<div class="formwrap">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form'],
        'fieldConfig' => [
            'template' => "{input}\n<div class=\"ermsg\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

     <ul class="formbox">
       <li>
            <?= $form->field($model, 'username')->textInput(['placeholder'=>'Username','class'=>'form-control']); ?>
       </li>
       <li>
            <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Password']) ?>
       </li>
       <li>
		<div class="col-sm-12">
			<?= Html::a('Forgot Password?', ['site/forgot-password'],['class'=>'link'])?>
		</div>
       </li>
         <li class="btngroup">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary ', 'name' => 'login-button']) ?>

        </li>
     </ul>

<?php ActiveForm::end(); ?>
</div><!--/close .login-form-->


