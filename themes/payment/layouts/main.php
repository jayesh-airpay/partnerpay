<?php
/**
 * Created by PhpStorm.
 * User: nandana
 * Date: 21/3/16
 * Time: 11:08 AM
 */
\app\assets\AppAsset::register($this);
$this->beginPage();
$theme = $this->theme;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= \yii\helpers\Html::csrfMetaTags() ?>
    <title>Partnerpay</title>
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
<?php
$this->beginBody();
?>
<body>
<div class="index">
    <div class="indexpage">
        <div class="container">
            <?php if(!empty(Yii::$app->controller->vendor_logo))    {   ?>
            <div class="logo"><img alt="hotel-logo" src="<?php echo Yii::$app->request->baseUrl.'/uploads/vendor_logo/'.Yii::$app->controller->vendor_logo;?>"/></div>
            <?php } else    {   ?>
                <div class="logo"><img alt="hotel-logo" src="<?php echo Yii::$app->request->baseUrl; ?>/themes/partnerpay/images/partnerpay-logo.png"/></div>
            <?php } ?>



            <?php
                echo $content;
            ?>
        </div>
    </div><!--/close .indexpage-->
</div>


    <div class="footer">
        <div class="container copyright"><p>Copyright &copy; <?php echo date("Y"); ?> by airpay.</p></div>
    </div>

    <?php
    //$this->registerJsFile($theme->baseUrl.'/js/bootstrap.min.js', ['depends' => [\yii\web\JqueryAsset::className()], 'position' => \yii\web\View::POS_END]);
    $this->registerJsFile($theme->baseUrl.'/js/bootstrap.file-input.js', ['depends' => [\yii\web\JqueryAsset::className()], 'position' => \yii\web\View::POS_END]);
    $this->registerJsFile($theme->baseUrl.'/js/custom.js', ['depends' => [\yii\web\JqueryAsset::className()], 'position' => \yii\web\View::POS_END]);
    $this->endBody();
    ?>

</body>
</html>
<?php
$this->endPage();
?>
