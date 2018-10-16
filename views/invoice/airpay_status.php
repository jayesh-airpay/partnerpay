<?php
/**
 * Created by PhpStorm.
 * User: nandana
 * Date: 29/2/16
 * Time: 1:00 PM
 */

 \app\assets\AppAsset::register($this);

$this->registerJs('
document.getElementById("order-form").submit();
setTimeout(function(){
    document.getElementById("success-form").submit();
    window.location.href = "'.Yii::$app->request->referrer.'"
}, 3000);
', \yii\web\View::POS_READY, 'onload_event');
?>



<p>
    Please wait...
</p>
<form id="order-form" method="post" action="<?php echo Yii::$app->request->baseUrl; ?>/invoice/set-order" target="_blank">
    <input type="hidden" name="PAYMENT_ORDER_ID" value="<?=$order_id;?>">
    <input type="submit" style="display: none">
</form>
<form id="success-form" method="post" action="<?php echo Yii::$app->request->baseUrl; ?>/invoice/payment-response" target="_blank">
    <input type="hidden" name="TRANSACTIONID" value="<?=$TRANSACTIONID;?>"><br>
    <input type="hidden" name="APTRANSACTIONID" value="<?=$APTRANSACTIONID;?>"><br>
    <input type="hidden" name="AMOUNT" value="<?=$AMOUNT;?>"><br>
    <input type="hidden" name="TRANSACTIONSTATUS" value="<?=$TRANSACTIONSTATUS;?>"><br>
    <input type="hidden" name="MESSAGE" value="<?=$MESSAGE;?>"><br>
    <input type="hidden" name="ap_SecureHash" value="<?=$merchant_secure_hash;?>"><br>
    <input type="submit" style="display: none">
</form>