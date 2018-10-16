<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/payment.css" rel="stylesheet">
	
</head>
<body>

<div id="wrapper" style="">
    <section id="home">
        <div style="">
                <div class="logoWrapper"style="">
                    <a href="/">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/partnerpay-logo.png" alt="Partnerpay Logo" title="Partnerpay Logo" border="0">
                    </a>
              </div>
            </div>
           
      </section>

    <?php echo $content; ?>
</div>

<footer>
    <div style="">
        <div id="footer">

           <div id="footerAddress">&copy; Copyrights  2016<a href="mailto:info@digitalhathi.com"></a></div>

        </div>
    </div>
</footer>
</body>
</html>
