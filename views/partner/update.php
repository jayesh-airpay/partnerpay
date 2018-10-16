<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Hotel */

$this->title = 'Update Partner: ' . ' ' . $model->PARTNER_NAME;
$this->params['breadcrumbs'][] = ['label' => 'Partners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->PARTNER_NAME, 'url' => ['view', 'id' => $model->PARTNER_ID]];
$this->params['breadcrumbs'][] = 'Update';
?>

<?= $this->render('_form', [
	'model' => $model,
	'update' => 1,
]) ?>