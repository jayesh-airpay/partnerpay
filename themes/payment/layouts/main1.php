<?php \app\assets\AppAsset::register($this) ?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $theme = $this->theme;
    $path = Yii::$app->request->baseUrl;

    ?>
    <meta charset="utf-8">
    <title><?php echo Yii::$app->name; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!--<link href="<?php /*echo $theme->baseUrl; */?>/css/payment.css" rel="stylesheet">-->
    <?php $this->registerCssFile($theme->baseUrl. '/css/payment.css',['depends' => [\yii\bootstrap\BootstrapAsset::className()]]); ?>
    <?php $this->head() ?>

	<style>
		.logoWrapper {margin: 0 0 25px; border-bottom:1px solid #ddd; padding: 0 0 25px;}
		#wrapper{min-height: 100%; height: auto; margin: 0 auto -60px;  padding: 0 0 60px;}
		@media only screen and (max-width: 767px) {
			img {width: auto; max-width: 100%; text-align: center; margin: 0 auto; display: block;}
		}
		</style>
</head>
<body>
<?php $this->beginBody() ?>
<div id="wrapper">
    <section id="home">       
                <div class="logoWrapper">
                    <a href="#">
                        <img src="<?php echo Yii::$app->request->baseUrl.'/uploads/vendor_logo/'.Yii::$app->controller->vendor_logo;?>" alt="Partnerpay Logo" title="Partnerpay Logo" border="0">
                    </a>              
            </div>

      </section>

    <?php echo $content; ?>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Partnerpay <?php echo date("Y"); ?></p>

    </div>
</footer> 
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
