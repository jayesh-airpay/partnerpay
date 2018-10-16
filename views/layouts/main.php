<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    if(!Yii::$app->getUser()->getIsGuest() && Yii::$app->user->identity->USER_TYPE != 'partner') {

        /*echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => 'Home', 'url' => ['/merchant-master/index']],
                ['label' => 'Users', 'url' => ['/user-master/index']],
                Yii::$app->user->isGuest ?
                    ['label' => 'Login', 'url' => ['/site/login']] :
                    [
                        'label' => 'Logout (' . Yii::$app->user->identity->EMAIL . ')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ],
            ],
        ]);*/
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                Yii::$app->user->isGuest ? '':
                ['label' => 'Home', 'url' => ['site/dashboard']],
                Yii::$app->user->isGuest ? '':
                ['label' => 'Merchant', 'url' => ['/merchant-master/index']],
                Yii::$app->user->isGuest ? '':
                ['label' => 'Partners', 'url' => ['/partner']],
                Yii::$app->user->isGuest ? '':
                ['label' => 'Invoices', 'url' => ['/invoice']],
                Yii::$app->user->isGuest ? '':
                ['label' => 'Users', 'url' => ['/user/index']],
                Yii::$app->user->isGuest ?
                    ['label' => 'Login', 'url' => ['/site/login']] :
                    [
                        'label' => 'Logout (' . Yii::$app->user->identity->EMAIL . ')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ],
            ],
        ]);


    } else {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                Yii::$app->user->isGuest ? '':
                ['label' => 'Home', 'url' => ['site/dashboard']],
                Yii::$app->user->isGuest ? '':
                ['label' => 'Partners', 'url' => ['/partner/index']],
                Yii::$app->user->isGuest ? '':
                ['label' => 'Invoice', 'url' => ['/invoice/index']],
                Yii::$app->user->isGuest ? '':
                ['label' => 'Users', 'url' => ['/user/index']],
                /* ['label' => 'Contact', 'url' => ['/site/contact']],*/
                Yii::$app->user->isGuest ?
                    ['label' => 'Login', 'url' => ['/site/login']] :
                    [
                        'label' => 'Logout (' . Yii::$app->user->identity->EMAIL . ')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ],
            ],
        ]);

    }
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Partnerpay <?= date('Y') ?></p>

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
