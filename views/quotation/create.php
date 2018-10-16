<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Quotation */

$this->title = 'Quotation Request';
$this->params['breadcrumbs'][] = ['label' => 'Quotations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quotation-create">

<!--     <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
        'post' => $post
    ]) ?>

</div>
