<?php
/**
 * Created by PhpStorm.
 * User: nandana
 * Date: 19/2/16
 * Time: 12:24 PM
 */
?>
<?php  \app\assets\AppAsset::register($this)?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Airpay</title>
    <script type="text/javascript">
        function submitForm(){
            var form = document.forms[0];
            form.submit();
        }
    </script>
</head>
<body onload="javascript:submitForm()">
<center>
    <table width="500px;">
        <tr>
            <td align="center" valign="middle">Do Not Refresh or Press Back <br/> Redirecting to Airpay</td>
        </tr>
        <tr>
            <td align="center" valign="middle">
                <form action="<?php echo Yii::$app->params['url'] ?>" method="post">
                    <?php
                    foreach($post_data as $k => $v)   {
                        echo '<input type="hidden" name="'. $k .'" value="'. $v .'">';
                    }
                    ?>

                </form>
            </td>

        </tr>
    </table>
</center>
</body>
</html>