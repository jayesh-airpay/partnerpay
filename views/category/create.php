<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CategoryMaster */

$this->title = 'Create Category';
$this->params['breadcrumbs'][] = ['label' => 'Category', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
	'model' => $model,
]) ?>