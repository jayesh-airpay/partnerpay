<?php
/**
 * Created by IntelliJ IDEA.
 * User: akshay
 * Date: 13/8/14
 * Time: 4:15 PM
 */
namespace app\helpers;

use app\models\Invoice;
use app\models\MerchantMaster;
use app\models\UserMaster;
use yii\helpers\Html;

class invoiceHelper {
    public function sendInvoiceReference($invoice)  {
        $cc = '';

        $name='=?UTF-8?B?'.base64_encode('Payments Manager').'?=';
        $subject='=?UTF-8?B?'.base64_encode('Invoice #'. $invoice->REF_ID).'?=';
        $headers="From: $name <".\Yii::$app->params['paymentEmail'].">\r\n".
            "Cc: ".$cc."\r\n".
//            "Reply-To: ".$model->email."\r\n".
            "MIME-Version: 1.0\r\n".
            "Content-type:text/html;charset=UTF-8";

        $invoice_url = '';
        $merchant_details = MerchantMaster::find()->where(['MERCHANT_ID' => $invoice->partner->MERCHANT_ID])->one();
        if(!empty($merchant_details)){
            $domain = $merchant_details->DOMAIN_NAME;
            $domain_url = "http://".$domain.".partnerpay.co.in";
        }


        $invoice_url = Html::a('Invoice #'.$invoice->REF_ID,  $domain_url.'/invoice/view/'.$invoice->INVOICE_ID);
        //var_dump($invoice_url); exit;

        $body = '<p><p style="font-family:arial;font-size:14px">Dear Sir / Madam,</p>
      <p style="font-family:arial;font-size:14px">Greetings!</p>
      <p style="font-family:arial;font-size:14px">You can access the soft copy of your invoice/booking reference for your reservation by clicking here: '. $invoice_url .'</p>
      <p style="font-family:arial;font-size:14px">You may pay through Credit Card, Debit Card or Net banking etc. using the above link.</p>
      <p style="font-family:arial;font-size:14px">Please feel free to contact us for any further clarification or details.</p>
      <br>
      <span style="font-size:14px;font-family:arial">Best
        Regards</span></p>';

        if(!empty($invoice->partner))  {
            $body = !empty($invoice->partner->INVOICE_EMAIL_TEMPLATE)?nl2br($invoice->partner->INVOICE_EMAIL_TEMPLATE):$body;

        }

        $body = str_replace('{{{invoice_number}}}', $invoice->REF_ID, $body);
       // $body = str_replace('{{{currency_symbol}}}', 'Rs', $body);
       // $body = str_replace('{{{invoice_total}}}', $invoice->AMOUNT, $body);

        $body = str_replace('{{{invoice_guest_url}}}', $invoice_url, $body);
        $body = str_replace('{{{partner_name}}}', urlencode($invoice->partner->PARTNER_NAME), $body);



        if(mail($invoice->CLIENT_EMAIL,$subject,$body,$headers))    {
            $model = Invoice::findOne($invoice->INVOICE_ID);
            $model->MAIL_SENT = 'Y';
            $s = $model->update();
            //var_dump($s); exit;
            return true;
        }
        return false;
    }
    

    public function sendInvoiceReferenceSMS($invoice, $printmsg = false)  {
        $bcc_sms = '';
        $message = \Yii::$app->params['sms']['message'];
        $signature = 'airpay';
        if(!empty($invoice->partner))  {
            $message = !empty($invoice->partner->INVOICE_SMS_TEMPLATE)?$invoice->partner->INVOICE_SMS_TEMPLATE:$message;

        }
        $merchant_details = MerchantMaster::find()->where(['MERCHANT_ID' => $invoice->partner->MERCHANT_ID])->one();
        if(!empty($merchant_details)){
            $domain = $merchant_details->DOMAIN_NAME;
            $domain_url = "http://".$domain.".partnerpay.co.in";
        }

        if(!empty($invoice->INVOICE_BITLY_URL)){
            $short_url = $invoice->INVOICE_BITLY_URL;
        } else {
            $bitly_url = $domain_url.'/invoice/view/'.$invoice->INVOICE_ID;
            $short_url = $this->bitlyDetails($bitly_url);
        }
        if(empty($short_url)){
            $short_url = $domain_url.'/invoice/view/'.$invoice->INVOICE_ID;
        }

        $message = str_replace('{{{invoice_number}}}', $invoice->REF_ID, $message);
        $message = str_replace('{{{currency_symbol}}}', 'Rs', $message);
        $message = str_replace('{{{invoice_total}}}', $invoice->TOTAL_AMOUNT, $message);
        //$message = str_replace('{{{invoice_guest_url}}}', \Yii::$app->getUrlManager()->createAbsoluteUrl(['/invoice/view','id'=>$invoice->INVOICE_ID]), $message);
        $message = str_replace('{{{invoice_guest_url}}}', $short_url, $message);
        $message = str_replace('{{{partner_name}}}', urlencode($invoice->partner->PARTNER_NAME), $message);


        $sms_data = \Yii::$app->params['sms']['data'];
        $sms_data = str_replace('{{{phone_number}}}', $invoice->CLIENT_MOBILE, $sms_data);
        $sms_data = str_replace('{{{message}}}', urlencode($message), $sms_data);

        $sms_data = str_replace('{{{partner_name}}}', urlencode($invoice->partner->PARTNER_NAME), $sms_data);

        //$sms_data = str_replace('{{{signature}}}', urlencode($signature), $sms_data);
    	$sms_data = str_replace('{{{signature}}}', ($signature), $sms_data);

        $ch = curl_init(\Yii::$app->params['sms']['url'] . $sms_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
       // var_dump($response); exit;
        if($response)    {
            if(empty($invoice->INVOICE_BITLY_URL)){
                $model = Invoice::findOne($invoice->INVOICE_ID);
                $model->INVOICE_BITLY_URL = $short_url;
                $s = $model->update();
                return true;
            }

        }

        \Yii::info('Invoice reference sms sent to '.$invoice->CLIENT_MOBILE. ' for invoice id #'.$invoice->INVOICE_ID. ' with response : "'.$response.'"', 'smsinfo');

        return true;
    }


    function sendReminderMail($invoice) {
        $cc = '';
        $user_emails = UserMaster::find()->where(['MERCHANT_ID'=>$invoice->partner->MERCHANT_ID,'USER_TYPE' => 'merchant'])->one();

        if(!empty($user_emails)) {
            $cc = $user_emails->EMAIL;
        }
        $cc = '';

        $name='=?UTF-8?B?'.base64_encode('Payment Manager').'?=';
        $subject='=?UTF-8?B?'.base64_encode('Invoice #'. $invoice->REF_ID).'?=';
        $headers="From: $name <".\Yii::$app->params['paymentEmail'].">\r\n".
            "Cc: ".$cc."\r\n".
//            "Reply-To: ".$model->email."\r\n".
            "MIME-Version: 1.0\r\n".
            "Content-type:text/html;charset=UTF-8";

        $invoice_url = '';
        $merchant_details = MerchantMaster::find()->where(['MERCHANT_ID' => $invoice->partner->MERCHANT_ID])->one();
        if(!empty($merchant_details)){
            $domain = $merchant_details->DOMAIN_NAME;
            $domain_url = "http://".$domain.".partnerpay.co.in";
        }


        $invoice_url = Html::a('Invoice #'.$invoice->REF_ID,  $domain_url.'/invoice/view/'.$invoice->INVOICE_ID);
        //var_dump($invoice_url); exit;

        $body = '<p><p style="font-family:arial;font-size:14px">Dear Sir / Madam,</p>
      <p style="font-family:arial;font-size:14px">Greetings!</p>
      <p style="font-family:arial;font-size:14px">You can access the soft copy of your invoice/booking reference for your reservation by clicking here: '. $invoice_url .'</p>
      <p style="font-family:arial;font-size:14px">You may pay through Credit Card, Debit Card or Net banking etc. using the above link.</p>
      <p style="font-family:arial;font-size:14px">Please feel free to contact us for any further clarification or details.</p>
      <br>';



        if(!empty($invoice->partner))  {
            $body = !empty($invoice->partner->INVOICE_EMAIL_TEMPLATE)?nl2br($invoice->partner->INVOICE_EMAIL_TEMPLATE):$body;

        }

        $body = str_replace('{{{invoice_number}}}', $invoice->REF_ID, $body);        // $body = str_replace('{{{currency_symbol}}}', 'Rs', $body);
        // $body = str_replace('{{{invoice_total}}}', $invoice->AMOUNT, $body);

        $body = str_replace('{{{invoice_guest_url}}}', $invoice_url, $body);
        $body = str_replace('{{{partner_name}}}', urlencode($invoice->partner->PARTNER_NAME), $body);

        $body .=' <br><br><span style="font-size:14px;font-family:arial">Best
        Regards</span></p>';

        //$invoice->CLIENT_EMAIL = 'nandana@digitalhathi.com';

        if(mail($invoice->CLIENT_EMAIL,$subject,$body,$headers)){
            \Yii::info('Invoice reference mail sent to '.$invoice->CLIENT_EMAIL. ' for invoice id #'.$invoice->INVOICE_ID, 'smsinfo');
            return true;
        } else {
            return false;
        }
    }

    public function sendReminderSMS($invoice, $printmsg = false)  {
        $bcc_sms = '';
        $message = \Yii::$app->params['sms']['message'];
        $signature = 'airpay';
        if(!empty($invoice->partner))  {
            $message = !empty($invoice->partner->INVOICE_SMS_TEMPLATE)?$invoice->partner->INVOICE_SMS_TEMPLATE:$message;

        }
        $merchant_details = MerchantMaster::find()->where(['MERCHANT_ID' => $invoice->partner->MERCHANT_ID])->one();
        if(!empty($merchant_details)){
            $domain = $merchant_details->DOMAIN_NAME;
            $domain_url = "http://".$domain.".partnerpay.co.in";
        }
        if(!empty($invoice->INVOICE_BITLY_URL)){
            $short_url = $invoice->INVOICE_BITLY_URL;
        } else {
            $bitly_url = $domain_url.'/invoice/view'.$invoice->INVOICE_ID;
            $short_url = $this->bitlyDetails($bitly_url);
        }
        if(empty($short_url)){
            $short_url = Html::a('Invoice #'.$invoice->REF_ID,  $domain_url.'/invoice/view/'.$invoice->INVOICE_ID);
        }


        //var_dump($invoice_url); exit;

        $message = str_replace('{{{invoice_number}}}', $invoice->REF_ID, $message);
        $message = str_replace('{{{currency_symbol}}}', 'Rs', $message);
        $message = str_replace('{{{invoice_total}}}', $invoice->AMOUNT, $message);
        $message = str_replace('{{{invoice_guest_url}}}', $short_url, $message);
        $message = str_replace('{{{partner_name}}}', urlencode($invoice->partner->PARTNER_NAME), $message);


        $sms_data = \Yii::$app->params['sms']['data'];
        $sms_data = str_replace('{{{phone_number}}}', $invoice->CLIENT_MOBILE, $sms_data);
        $sms_data = str_replace('{{{message}}}', urlencode($message), $sms_data);

        $sms_data = str_replace('{{{partner_name}}}', urlencode($invoice->partner->PARTNER_NAME), $sms_data);

        $sms_data = str_replace('{{{signature}}}', urlencode($signature), $sms_data);

        $ch = curl_init(\Yii::$app->params['sms']['url'] . $sms_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        \Yii::info('Invoice reference sms sent to '.$invoice->CLIENT_MOBILE. ' for invoice id #'.$invoice->INVOICE_ID. ' with response : "'.$response.'"', 'smsinfo');

        return true;
    }

    public function bitlyDetails($url)
    {
        /*$client_id = '8c2503df357ecae2dbf32a50fb164402fa8bb5a1';
        $client_secret = '6f210e61170b65c0e4bd9d84933c5604fd9a21e6';
        $user_access_token = '7b9abca814bb961ece89c574818d3187451f2fd0';
        $user_login = 'airpaypayments';
        $user_api_key = '7b9abca814bb961ece89c574818d3187451f2fd0';

        $params = array();
        $params['access_token'] = $user_access_token;
        $params['longUrl'] = $url;
        $params['domain'] = 'j.mp';
        $btly = new Bitly();
        //var_dump($btly); exit;
        $results = $btly->bitly_get('shorten', $params);
       // var_dump($results);
        \Yii::info('Betly url for this "'.$url.'" having response  : "'.$results.'"', 'smsinfo');
        return($results['data']['url']);*/
    
    	$api_key = '83209e8f9626a8f3c0b565ea113c39bc991f4874';
        $username = 'adminapi';
        $fields = "username=".$username."&api_key=".$api_key."&long_url=".$url."";
        $url1 = 'http://www.arpy.in/api/shorten.json';
        $results = $this->sendDataOverPost($url1, $fields, "POST", $timeout=30, $port=80);
        //$results = stripslashes($results);
        $decoded_array = json_decode($results, true);
    	//\Yii::info('Bitly url for this "'.$url.'" having response  : "'.$results.'"', 'smsinfo');
        return $decoded_array['data']['Link']['short_url'];
    }

	function sendDataOverPost($url, $fields, $method, $timeout=30, $port=80) {
        $ch = curl_init();
        if (strtoupper($method) == 'POST') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }
        else {
            curl_setopt($ch, CURLOPT_URL, $url.$fields);
        }
        if($port == 443) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ch, CURLOPT_PORT , $port);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,$timeout);
        $result = curl_exec($ch);

        if($result === false) {
            //writeLog(INFO_LOG_PATH, 'UNABLE sendDataOverPost '.curl_error($ch).' URL :'.$url.' :: Data');
        }
        curl_close($ch);
        return $result;
    }
} 
