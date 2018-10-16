<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Invoice */

$this->title = 'Create Invoice';
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<?php if($isQR == 'N'){ ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
<?php } else {?>
    <?= $this->render('formQR', [
        'model' => $model,
    ]) ?>
<?php }?>

