<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserMaster */

$this->title = 'Update User: ' . ' ' . $model->FIRST_NAME;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->FIRST_NAME, 'url' => ['view', 'id' => $model->USER_ID]];
$this->params['breadcrumbs'][] = 'Update';
?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


