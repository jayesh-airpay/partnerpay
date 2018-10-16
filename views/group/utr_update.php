<?php
/**
 * Created by PhpStorm.
 * User: akshay
 * Date: 27/5/16
 * Time: 7:10 AM
 */

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GroupInvoice */

$this->title = 'Update UTR';
$this->params['breadcrumbs'][] = ['label' => 'Group Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-invoice-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
