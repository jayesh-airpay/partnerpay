<?php
/**
 * Created by IntelliJ IDEA.
 * User: akshay
 * Date: 11/7/14
 * Time: 5:05 PM
 */
namespace app\helpers;

use app\models\Invoice;
use app\models\MerchantMaster;
use app\models\Partner;
use app\models\UserMaster;
use app\models\UserMerchant;
use yii\bootstrap\Html;

class generalHelper
{
    function sendTransactionMail($order, $invoice, $client, $template)
    {
        $cc = '';
        $user_emails = UserMaster::find()->where(['MERCHANT_ID' => $invoice->partner->MERCHANT_ID, 'USER_TYPE' => 'merchant'])->one();
        $merchant_details = MerchantMaster::find()->where(['MERCHANT_ID' => $invoice->partner->MERCHANT_ID])->one();
        if(!empty($merchant_details)){
            $domain = $merchant_details->DOMAIN_NAME;
            $domain_url = "http://".$domain."partnerpay.co.in";
        }


        if (!empty($user_emails)) {
            $cc = $user_emails->EMAIL;
        }
        $name = '=?UTF-8?B?' . base64_encode('Payment Manager') . '?=';
        $subject = '=?UTF-8?B?' . base64_encode('Congratulations! Ref ID ' . $invoice->REF_ID . ' Transaction is Successful') . '?=';
        $headers = "From: $name <" . \Yii::$app->params['noreplyEmail'] . ">\r\n" .
            "Cc: " . $cc . "\r\n" .
//            "Reply-To: ".$model->email."\r\n".
            "MIME-Version: 1.0\r\n" .
            "Content-type:text/html;charset=UTF-8";

        if (empty($template)) {
            $body = '<div style="font-family:arial;font-size:12px;line-height:16px;">Dear ' . ucfirst(strtolower($client->FIRST_NAME)) . ' ' . ucfirst(strtolower($client->LAST_NAME)) . ',
                        <br><br>Thank you for choosing ' . \Yii::$app->params['Name'] . '!
                        <br><br>
                        We confirm the receipt of Rs. ' . $order->RECEIVED_AMOUNT . ' against invoice reference no.
                        ' . $invoice->REF_ID . ' on ' . date('Y, F d', $order->CREATED_ON) . '
                        <br><br>
                        Your transaction reference no. is ' . $order->TRANSACTION_ID . '
                        <br><br>
                        Best Regards,<br>
                        ' . \Yii::$app->params['Name'] . '</div>';
        } else {
            $body = $template;
            $invoice_url = Html::a('Invoice #'.$invoice->REF_ID,  $domain_url.'/invoice/view/'.$invoice->INVOICE_ID);
            $body = str_replace('{{{invoice_number}}}', $invoice->INVOICE_ID, $body);

            $body = str_replace('{{{invoice_guest_url}}}', $invoice_url, $body);
            $body = str_replace('{{{partner_name}}}', urlencode($invoice->partner->PARTNER_NAME), $body);

        }
        mail($client->EMAIL, $subject, $body, $headers);
    }

    /**
     * @param $assignee_id
     * @return string
     */
    public static function getAssigneeName($assignee_id)   {
        $assignee = UserMaster::find()->where(['USER_ID'=>$assignee_id])->one();
        $assignee_name = $assignee->FIRST_NAME." ".$assignee->LAST_NAME;
        return $assignee_name;
    }


    function convert_number_to_words($number)
    {

        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' rupees ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string)$fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }


    /**
     * @param $url ,$fields, $method, $timeout=30, $port=443
     * @return string
     * @description to make curl call to given url by given method
     */
    public function sendDataOverPost($url, $fields, $method, $timeout = 30, $port = 443)
    {
        $ch = curl_init();
        $User_Agent = 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31';
        $request_headers = array();
        $request_headers[] = 'User-Agent: ' . $User_Agent;
        $request_headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        if (strtoupper($method) == 'POST') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url . $fields);
        }
        if ($port == 443) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ch, CURLOPT_PORT, $port);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $result = curl_exec($ch);
        curl_close($ch);
        //error_log($url.$fields,0);
        //error_log($result,0);
        return $result;
    }

    function sendForgotPasswordMail($user, $new_password)
    {
        $cc = '';
        $name = '=?UTF-8?B?' . base64_encode('Partnerpay') . '?=';
        $subject = '=?UTF-8?B?' . base64_encode('Your Password has been reset.') . '?=';
        //$subject='Your new Password';
        $headers = "From: $name <" . \Yii::$app->params['noreplyEmail'] . ">\r\n" .
            "Cc: " . $cc . "\r\n" .
            //            "Reply-To: ".$model->email."\r\n".
            "MIME-Version: 1.0\r\n" .
            "Content-type:text/html;charset=UTF-8";

        $body = '<div style="font-family:arial;font-size:12px;line-height:16px;">Dear ' . ucfirst(strtolower($user->FIRST_NAME)) . ' ' . ucfirst(strtolower($user->LAST_NAME)) . ',
        <br/><br/>
        As requested, we have reset your Partnerpay password.<br><br>

        Below are your new credentials: <br><br>

        Username: ' . $user->EMAIL . '<br>
        Password: ' . $new_password . ' <br><br>

        <br><br>
        Thank you!<br> ' . \Yii::$app->name . ' Team
        </div>';

        mail($user->EMAIL, $subject, $body, $headers);
    }


    function sendMerchantDetails($user, $password)
    {
        $cc = '';
        $merchant_details = MerchantMaster::find()->where(['MERCHANT_ID'=>$user->MERCHANT_ID])->one();

        $name = '=?UTF-8?B?' . base64_encode('Partnerpay') . '?=';
        $headers = "From: $name <" . \Yii::$app->params['noreplyEmail'] . ">\r\n" .
            "Cc: " . $cc . "\r\n" .
            //            "Reply-To: ".$model->email."\r\n".
            "MIME-Version: 1.0\r\n" .
            "Content-type:text/html;charset=UTF-8";
        if($user->USER_TYPE == 'partner') {

            $subject = '=?UTF-8?B?' . base64_encode('Start Creating invoices for '. $merchant_details->MERCHANT_NAME) . '?=';

            $body = '<div style="font-family:arial;font-size:12px;line-height:16px;">Dear ' . ucfirst(strtolower($user->FIRST_NAME)) . ' ' . ucfirst(strtolower($user->LAST_NAME)) . ',
        <br><br>Your Partner account has been successfully created for Partnerpay. <br><br>
        You can use the following account details to login and start creating your invoices for '. $merchant_details->MERCHANT_NAME . '<br><br>Url : http://' . $merchant_details->DOMAIN_NAME . '.partnerpay.co.in<br>Email Id : ' . $user->EMAIL . '<br>

        Password : ' . $password . '
        <br><br>
        Thank you!<br> Team
        ' . \Yii::$app->params['Name'] . '</div>';
        }
        if($user->USER_TYPE == 'merchant') {
            $subject = '=?UTF-8?B?' . base64_encode('Start Paying your invoices now!') . '?=';

            $body = '<div style="font-family:arial;font-size:12px;line-height:16px;">Dear ' . ucfirst(strtolower($user->FIRST_NAME)) . ' ' . ucfirst(strtolower($user->LAST_NAME)) . ',
        <br><br>Your Merchant account has been successfully created for Partnerpay. <br><br>
You can use the following account details to login and start paying your invoices.  <br><br>Url : http://' . $merchant_details->DOMAIN_NAME . '.partnerpay.co.in<br>Email Id : ' . $user->EMAIL . '<br>
        Password : ' . $password . '
        <br><br>
        Thank you!<br> Team
        ' . \Yii::$app->params['Name'] . '</div>';
        }

        mail($user->EMAIL, $subject, $body, $headers);
    }

    public function random_string($length)
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

       function sendQuotation($email, $quotation_id,$partner_id,$mid=null)
    {
        $merchant_details = MerchantMaster::find()->where(['MERCHANT_ID'=>$mid])->one();
        $q = base64_encode($quotation_id);
        $p = base64_encode($partner_id);
        //$url = \yii\helpers\Url::to(['/quotation/assignquotation?q='.$q.'&p='.$p]);
        $url = 'http://'.$merchant_details->DOMAIN_NAME.'.partnerpay.co.in/quotation/assignquotation?q='.$q.'&p='.$p; 
        $cc = '';
        $name = '=?UTF-8?B?' . base64_encode('Partnerpay') . '?=';
        $subject = '=?UTF-8?B?' . base64_encode('New Quotation Request.') . '?=';
        //$subject='Your new Password';
        $headers = "From: $name <" . \Yii::$app->params['noreplyEmail'] . ">\r\n" .
            "Cc: " . $cc . "\r\n" .
            //            "Reply-To: ".$model->email."\r\n".
            "MIME-Version: 1.0\r\n" .
            "Content-type:text/html;charset=UTF-8";

        $body = '<div style="font-family:arial;font-size:12px;line-height:16px;">Dear Partner,
        <br/><br/>
        You have a new Quotation kindly fill by clicking on below URL.<br/>

        Click  <a href="'.$url.'">here</a>

        <br><br>
        Thank you!<br> ' . \Yii::$app->name . ' Team
        </div>';

        mail($email, $subject, $body, $headers);
    
    }

    function sendQuotationVendor($email, $quotation_id,$partner_id,$mid=null,$partnername,$quotation){
        $q = base64_encode($quotation_id);
        $p = base64_encode($partner_id);
        //$url = \yii\helpers\Url::to(['/quotation/assignquotation?q='.$q.'&p='.$p]);
        $url = 'http://'.$merchant_details->DOMAIN_NAME.'.partnerpay.co.in/quotation/assignquotation?q='.$q.'&p='.$p; 
        $cc = '';
        $name = '=?UTF-8?B?' . base64_encode('Partnerpay') . '?=';
        $subject = '=?UTF-8?B?' . base64_encode('New Quotation Request.') . '?=';
        //$subject='Your new Password';
        $headers = "From: $name <" . \Yii::$app->params['noreplyEmail'] . ">\r\n" .
            "Cc: " . $cc . "\r\n" .
            //            "Reply-To: ".$model->email."\r\n".
            "MIME-Version: 1.0\r\n" .
            "Content-type:text/html;charset=UTF-8";
    
       $body = '<div style="font-family:arial;font-size:12px;line-height:16px;">
                Hello '.$partnername.',
                <p>A request for quotation '.$quotation->NAME.' has been raised by [MERCHANT NAME] on '.date('d-m-Y',$model->CREATED).'.</p>
                <p>You can view the quotation details at '.$_SERVER['SERVER_NAME'].' by following the steps below -</p>
                <p>Login to Partnerpay >'.$_SERVER['SERVER_NAME'].'/site/login</p>
                <p>Please send your quotation to '.\Yii::$app->user->identity->FIRST_NAME.' '.\Yii::$app->user->identity->LAST_NAME.' before the deadline date of '.date('d-m-Y',$quotation->CREATED).' to avoid quotation
                cancellation.</p>
                <p>In case of any further clarification or assistance, kindly write back to us at support@airpay.co.in 
                <p>Regards,</p>
                <p>Team Airpay</p>';
       
    }
    //function added by jayesh on 27-12-2017
    function sendUserDetails($user, $password)
    {
        $merchant_details = MerchantMaster::find()->where(['MERCHANT_ID'=>$user->MERCHANT_ID])->one();
        $cc = '';
        $name = '=?UTF-8?B?' . base64_encode('Partnerpay') . '?=';
        $headers = "From: $name <" . \Yii::$app->params['noreplyEmail'] . ">\r\n" .
            "Cc: " . $cc . "\r\n" .
            //            "Reply-To: ".$model->email."\r\n".
            "MIME-Version: 1.0\r\n" .
            "Content-type:text/html;charset=UTF-8";
        if($user->USER_TYPE == 'partner') {

            $subject = '=?UTF-8?B?' . base64_encode('Start Creating invoices for '. $merchant_details->MERCHANT_NAME) . '?=';

            $body = '<div style="font-family:arial;font-size:12px;line-height:16px;">Dear ' . ucfirst(strtolower($user->FIRST_NAME)) . ' ' . ucfirst(strtolower($user->LAST_NAME)) . ',
        <br><br>Your Partner account has been successfully created for Partnerpay. <br><br>
        You can use the following account details to login and start creating your invoices for '. $merchant_details->MERCHANT_NAME . '<br><br>Url : http://' . $merchant_details->DOMAIN_NAME . '.partnerpay.co.in<br>Email Id : ' . $user->EMAIL . '<br>

        Password : ' . $user->PASSWORD . '
        <br><br>
        Thank you!<br> Team
        ' . \Yii::$app->params['Name'] . '</div>';
        }
    
       if($user->USER_TYPE == 'guestuser'){
            $subject = '=?UTF-8?B?' . base64_encode('Start Creating invoices for '. $merchant_details->MERCHANT_NAME) . '?=';

            $body = '<div style="font-family:arial;font-size:12px;line-height:16px;">Dear User,
            <br><br>Your guest account has been successfully created for Partnerpay. <br><br>
            You can use the following account details to login and start creating your invoices for '. $merchant_details->MERCHANT_NAME . '<br><br>Url : http://' . $merchant_details->DOMAIN_NAME . '.partnerpay.co.in<br>Email Id : ' . $user->EMAIL . '<br>

            Password : ' . $user->OG_PASSWORD . '
            <br><br>
            Thank you!<br> Team
            ' . \Yii::$app->params['Name'] . '</div>';
        }    
    
    
        $result = mail($user->EMAIL, $subject, $body, $headers);
        if($result) {
            return true;
        } else {
            return false;
        }
    }


     function sendAssignPartnerApprovalInfo($email)
    {
        
        $cc = '';
        $name = '=?UTF-8?B?' . base64_encode('Partnerpay') . '?=';
        $subject = '=?UTF-8?B?' . base64_encode('Quotation Approval.') . '?=';
        //$subject='Your new Password';
        $headers = "From: $name <" . \Yii::$app->params['noreplyEmail'] . ">\r\n" .
            "Cc: " . $cc . "\r\n" .
            //            "Reply-To: ".$model->email."\r\n".
            "MIME-Version: 1.0\r\n" .
            "Content-type:text/html;charset=UTF-8";

        $body = '<div style="font-family:arial;font-size:12px;line-height:16px;">Dear Partner,
        <br/><br/>
          Your Request is approved by merchant.<br/>

        <br><br>
        Thank you!<br> ' . \Yii::$app->name . ' Team
        </div>';

        mail($email, $subject, $body, $headers);
    
    }

     function sendMerchantQuoteSubmitInfo($email)
    {
       // echo $email;exit;
        $cc = '';
        $name = '=?UTF-8?B?' . base64_encode('Partnerpay') . '?=';
        $subject = '=?UTF-8?B?' . base64_encode('Quotation Approval.') . '?=';
        //$subject='Your new Password';
        $headers = "From: $name <" . \Yii::$app->params['noreplyEmail'] . ">\r\n" .
            "Cc: " . $cc . "\r\n" .
            //            "Reply-To: ".$model->email."\r\n".
            "MIME-Version: 1.0\r\n" .
            "Content-type:text/html;charset=UTF-8";

        $body = '<div style="font-family:arial;font-size:12px;line-height:16px;">Dear Merchant,
        <br/><br/>
            There is a submission on your quotation request.<br/>

        <br><br>
        Thank you!<br> ' . \Yii::$app->name . ' Team
        </div>';

        mail($email, $subject, $body, $headers);
    
    }

//     function testtxnmail(){
//        require_once __DIR__.'/PHPMailer/PHPMailerAutoload.php';
//        $from = 'noreply@channelpartner.nowpay.co.in';
//        $to = 'mishrarahul824@gmail.com';
       
      
//     }
    function ccMasking($number, $maskingCharacter = 'x') {
         return substr($number, 0, 4) . str_repeat($maskingCharacter, strlen($number) - 8) . substr($number, -4);
    }
} 
