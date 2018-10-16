<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserMaster */

$this->title = 'Update Category: ' . ' ' . $model->CAT_ID;
$this->params['breadcrumbs'][] = ['label' => 'Category', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->FIRST_NAME, 'url' => ['view', 'id' => $model->CAT_ID]];
$this->params['breadcrumbs'][] = 'Update';
?>

<?= $this->render('_form', [
	'model' => $model,
]) ?>