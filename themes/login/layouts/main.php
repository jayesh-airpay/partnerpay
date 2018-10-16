<?php
/**
 * Created by PhpStorm.
 * User: nandana
 * Date: 21/3/16
 * Time: 11:08 AM
 */
\app\assets\AppAsset::register($this);
$theme = $this->theme;
$this->beginPage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<title>Partnerpay</title>-->
	<title><?php echo Yii::$app->controller->page_title; ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $theme->baseUrl. '/images/favicon.ico';?>">
    <?php $this->head(); ?>
    <link rel="stylesheet" href="<?php echo $theme->baseUrl. '/css/font-awesome.css';?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo $theme->baseUrl. '/css/custom.css'?>" type="text/css" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
   </head>

<body class="pagefix">
<?php
$this->beginBody();
?>
<div class="index">
    <div class="indexpage">
        <div class="container">
            <div class="logo">
            	<div class="logoimg partnerpay"><img alt="Partnerpay" src="<?php echo $theme->baseUrl. '/images/partnerpay-logo.png';?>"/></div>
            	<?php if(!empty(Yii::$app->controller->bank_logo)) { ?>
            	<div class="logoimg banklogo"><img alt="banklogo" src="<?php echo \yii\helpers\Url::to('/uploads/bank_logo/'.Yii::$app->controller->bank_logo); ?>"/></div> <?php
				} ?>
            </div>



                <!-----------------Form start here---------------------->
                <?php echo $content; ?>
                <!-----------------Form End here---------------------->


        </div>
    </div><!--/close .indexpage-->
</div>
<div class="footer">
    <div class="container copyright"><p>Copyright &copy; <?php echo date("Y"); ?> by airpay.</p></div>
</div>
<?php
$this->endBody();
?>
</body>
</html>
<?php
$this->endPage();
?>
