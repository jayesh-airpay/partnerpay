<?php

namespace app\modules\spicejet\controllers;

use app\helpers\Checksum;
use app\helpers\generalHelper;
use yii\web\Controller;

class ActionController extends Controller
{
    public function actionDopayment()
    {
        $partner_id                      =      isset(\Yii::$app->request->post()['partner_id'])?\Yii::$app->request->post()['partner_id']:'';
        $amount                          =      isset(\Yii::$app->request->post()['amount'])?\Yii::$app->request->post()['amount']:'';
        $agent_payment_config_id         =      isset(\Yii::$app->request->post()['agent_payment_config_id'])?\Yii::$app->request->post()['agent_payment_config_id']:'';
        $connection                      =      \Yii::$app->db;
        $get_agent_detais                =      $connection->createCommand('SELECT A.*,B.*,C.*,D.* FROM tbl_agent_details as A INNER JOIN tbl_partner_master as B ON A.PARTNER_ID = B.PARTNER_ID INNER JOIN tbl_agent_group AS C ON C.AGENT_GROUP_ID = A.AGENT_GROUP_ID INNER JOIN tbl_agent_group_payment_limit AS D ON D.GROUP_ID = C.AGENT_GROUP_ID  WHERE A.PARTNER_ID=:partner_id');
        $get_agent_detais->bindValue(':partner_id',$partner_id);
        $get_agency_data                 =      $get_agent_detais->queryOne();
        $agent_details_id                =      $get_agency_data['AGENT_DETAILS_ID'];
        $get_agent_cards                 =      $connection->createCommand('SELECT A.* FROM tbl_agent_payment_config as A WHERE A.AGENT_DETAILS_ID =:agent_details_id AND A.AGENT_PAYMENT_CONFIG_ID = :agent_payment_config_id');
        $get_agent_cards->bindValue(':agent_details_id',$agent_details_id);
        $get_agent_cards->bindValue(':agent_payment_config_id',$agent_payment_config_id);
        $card                            =      $get_agent_cards->queryOne();


        $data                            =      [];
        $message                         =      [];
        $is_error                        =      false;
        if(empty($partner_id)) {
            $is_error                    =      true;
            $message['partner_id']       =      "Partner does not exist.";
        }
        if(empty($amount)) {
//        if(true) {
            $is_error                    =      true;
            $message['amount']           =      "Please enter amount.";
        }
        $regex = '/^[0-9]{1,6}\.[0-9]{2,2}$/';
        if(!preg_match($regex,$amount)) {
//        if(true) {
            $is_error                    =      true;
            $message['amount']           =      "Please enter valid amount.";
        }
        if(empty($agent_payment_config_id)) {
//        if(true) {
            $is_error                    =      true;
            $message['cards']            =      "Please select payment card.";
        }
        if($is_error) {
            $data                        =      ['Result' => "Fail", "Message" => $message];
        } else {
            $data                        =      ['Result' => "Success", "Message" => $message];
        }
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($data);
    }
    public function actionSendtoairpay()
    {
        $partner_id                      =      isset(\Yii::$app->request->post()['partner_id'])?\Yii::$app->request->post()['partner_id']:'';
        $amount                          =      isset(\Yii::$app->request->post()['transaction_amount'])?\Yii::$app->request->post()['transaction_amount']:'';
        $agent_payment_config_id         =      isset(\Yii::$app->request->post()['agent_payment_config_id'])?\Yii::$app->request->post()['agent_payment_config_id']:'';
        $connection                      =      \Yii::$app->db;
        $get_agent_detais                =      $connection->createCommand('SELECT A.*,B.*,C.*,D.* FROM tbl_agent_details as A INNER JOIN tbl_partner_master as B ON A.PARTNER_ID = B.PARTNER_ID INNER JOIN tbl_agent_group AS C ON C.AGENT_GROUP_ID = A.AGENT_GROUP_ID INNER JOIN tbl_agent_group_payment_limit AS D ON D.GROUP_ID = C.AGENT_GROUP_ID  WHERE A.PARTNER_ID=:partner_id');
        $get_agent_detais->bindValue(':partner_id',$partner_id);
        $get_agency_data                 =      $get_agent_detais->queryOne();

        $agent_details_id                =      $get_agency_data['AGENT_DETAILS_ID'];
        $get_agent_cards                 =      $connection->createCommand('SELECT A.* FROM tbl_agent_payment_config as A WHERE A.AGENT_DETAILS_ID =:agent_details_id AND A.AGENT_PAYMENT_CONFIG_ID = :agent_payment_config_id');
        $get_agent_cards->bindValue(':agent_details_id',$agent_details_id);
        $get_agent_cards->bindValue(':agent_payment_config_id',$agent_payment_config_id);
        $card                            =      $get_agent_cards->queryOne();
        $merchant_transaction_id         =      time();

        $create_invoice_query            =      "INSERT INTO tbl_invoice (PARTNER_ID, REF_ID, AMOUNT, TOTAL_AMOUNT, ISSUE_DATE, INVOICE_STATUS, CREATED_ON, CLIENT_EMAIL , CLIENT_MOBILE) VALUES(:partner_id, :ref_id, :amount, :total_amount, :issue_date, :invoice_status, :created_on, :client_email, :client_mobile)";
        $create_invoice                  =      $connection->createCommand($create_invoice_query);
        $create_invoice->bindValue(':partner_id',$partner_id);
        $create_invoice->bindValue(':ref_id',$merchant_transaction_id);
        $create_invoice->bindValue(':amount',$amount);
        $create_invoice->bindValue(':total_amount',$amount);
        $create_invoice->bindValue(':issue_date',time());
        $create_invoice->bindValue(':invoice_status','0');
        $create_invoice->bindValue(':created_on',time());
        $create_invoice->bindValue(':client_email',!empty($get_agency_data['EMAIL'])?$get_agency_data['EMAIL']:'');
        $create_invoice->bindValue(':client_mobile',!empty($get_agency_data['PHONE'])?$get_agency_data['PHONE']:'');
        $create_invoice->execute();
        $invoice_id                      =      $connection->getLastInsertID();

        if(!empty($invoice_id)) {
            $create_order_query              =      "INSERT INTO tbl_order (INVOICE_ID, PAYMENT_METHOD, RECEIVED_AMOUNT, PAYMENT_STATUS, TRANSACTION_ID, TRANSACTION_STATUS, TRANSACTION_MESSAGE, CREATED_ON) VALUES(:invoice_id, :payment_method, :received_amount, :payment_status, :transaction_id, :transaction_status, :transaction_message, :created_on)";
            $create_order                    =      $connection->createCommand($create_order_query);
            $create_order->bindValue(':invoice_id',$invoice_id);
            $create_order->bindValue(':payment_method','1');
            $create_order->bindValue(':received_amount','0.00');
            $create_order->bindValue(':payment_status',0);
            $create_order->bindValue(':transaction_id',0);
            $create_order->bindValue(':transaction_status',0);
            $create_order->bindValue(':transaction_message','');
            $create_order->bindValue(':created_on',time());
            $create_order->execute();
            $order_id                        =      $connection->getLastInsertID();
        }

        $post_data                             =      [];
        $post_data['buyerEmail']               =      !empty($get_agency_data['EMAIL'])?$get_agency_data['EMAIL']:'';
        $post_data['buyerPhone']               =      !empty($get_agency_data['PHONE'])?$get_agency_data['PHONE']:'';
        $name                                  =      explode(" ", $card['NAME']);
        $post_data['buyerFirstName']           =      !empty($name[0])?$name[0]:'fname';
        $post_data['buyerLastName']            =      !empty($name[1])?$name[1]:'lname';
        $post_data['buyerAddress']             =      '';
        $post_data['amount']                   =      !empty($amount)?$amount:'0.00';
        $post_data['buyerCity']                =      !empty($get_agency_data['CITY'])?$get_agency_data['CITY']:'';
        $post_data['buyerState']               =      !empty($get_agency_data['STATE'])?$get_agency_data['STATE']:'';
        $post_data['buyerPinCode']             =      !empty($get_agency_data['PINCODE'])?$get_agency_data['PINCODE']:'';
        $post_data['buyerCountry']             =      !empty($get_agency_data['COUNTRY'])?$get_agency_data['COUNTRY']:'';
        $post_data['orderid']                  =      $merchant_transaction_id;
        $checksumobj                           =      new Checksum();
        $alldata                               =      $post_data['buyerEmail'].$post_data['buyerFirstName'].$post_data['buyerLastName'].$post_data['buyerAddress'].$post_data['buyerCity'].$post_data['buyerState'].$post_data['buyerCountry'].$post_data['amount'].$post_data['orderid'];
        $privatekey                            =      $checksumobj->encrypt($get_agency_data['AIRPAY_USERNAME'].":|:".$get_agency_data['AIRPAY_PASSWORD'], $get_agency_data['AIRPAY_SECRET_KEY']);
        $checksum                              =      $checksumobj->calculateChecksum($alldata.date('Y-m-d'),$privatekey);
        $post_data['privatekey']               =      $privatekey;
        $post_data['checksum']                 =      $checksum;
        $post_data['mercid']                   =      $get_agency_data['AIRPAY_MERCHANT_ID'];
        $post_data['channel']                  =      'pg';
        $post_data['card_num']                 =      $card['CARD_NUMBER'];//'';//
        $post_data['card_cvv']                 =      $card['CVV'];//'';//$card['CVV'];
        //$post_data['token']                    =      $card['TOKEN_NO'];
        //$post_data['carduniquecode']           =      $card['CARD_UNIQUE_CODE'];
        $post_data['expiry_mm']                =      $card['EXPIRY_MONTH'];//'';//$card['EXPIRY_MONTH'];
        $post_data['expiry_yy']                =      $card['EXPIRY_YEAR'];//'';//$card['EXPIRY_YEAR'];
        $post_data['currency']                 =      '356';
        $post_data['isocurrency']              =      'INR';
        $post_data['customvar']                =      'partner_id '.$partner_id.'|agent_id '.$agent_details_id;
        $url                                   =      'https://payments.airpay.co.in/pay/directindexapi.php';
        return $this->renderPartial('sendtoairpay',
            [
                'post_data' => $post_data,
                'url'       => $url,
            ]
        );
    }
    public function actionAddcards() {
        $partner_id                      =      isset(\Yii::$app->request->post()['add_card_partner_id'])?\Yii::$app->request->post()['add_card_partner_id']:'';
        $payment_card_type               =      isset(\Yii::$app->request->post()['payment_card_type'])?\Yii::$app->request->post()['payment_card_type']:'';
        $card_type                       =      isset(\Yii::$app->request->post()['card_type'])?\Yii::$app->request->post()['card_type']:'';
        $card_number                     =      isset(\Yii::$app->request->post()['card_number'])?\Yii::$app->request->post()['card_number']:'';
        $card_nickname                   =      isset(\Yii::$app->request->post()['card_nickname'])?\Yii::$app->request->post()['card_nickname']:'';
        $expiry_month                    =      isset(\Yii::$app->request->post()['expiry_month'])?\Yii::$app->request->post()['expiry_month']:'';
        $expiry_year                     =      isset(\Yii::$app->request->post()['expiry_year'])?\Yii::$app->request->post()['expiry_year']:'';
        $card_cvv                        =      isset(\Yii::$app->request->post()['card_cvv'])?\Yii::$app->request->post()['card_cvv']:'';
        $connection                      =      \Yii::$app->db;
        $get_agent_detais                =      $connection->createCommand('SELECT A.*,B.*,C.*,D.* FROM tbl_agent_details as A INNER JOIN tbl_partner_master as B ON A.PARTNER_ID = B.PARTNER_ID INNER JOIN tbl_agent_group AS C ON C.AGENT_GROUP_ID = A.AGENT_GROUP_ID INNER JOIN tbl_agent_group_payment_limit AS D ON D.GROUP_ID = C.AGENT_GROUP_ID  WHERE A.PARTNER_ID=:partner_id');
        $get_agent_detais->bindValue(':partner_id',$partner_id);
        $get_agency_data                 =      $get_agent_detais->queryOne();
        $agent_details_id                =      $get_agency_data['AGENT_DETAILS_ID'];
        $data                            =      [];
        $message                         =      [];
        $is_error                        =      false;
        if(empty($partner_id)) {
            $is_error                                  =      true;
            $message['partner_id']                     =      "Partner does not exist.";
        }
        if(empty($payment_card_type)) {
//        if(true) {
            $is_error                                  =      true;
            $message['payment_card_type']              =      "Please select payment card type.";
        }
        if(empty($card_type)) {
//        if(true) {
            $is_error                                  =      true;
            $message['card_type']                      =      "Please select card type.";
        }
        if(empty($card_number)) {
//        if(true) {
            $is_error                                  =      true;
            $message['card_number']                    =      "Please select card number.";
        }
        if(empty($card_nickname)) {
//        if(true) {
            $is_error                                  =      true;
            $message['card_nickname']                  =      "Please enter card nick name.";
        }
        if(empty($card_nickname)) {
//        if(true) {
            $is_error                                  =      true;
            $message['expiry_month']                   =      "Please select expiry month.";
        }
        if(empty($card_cvv)) {
//        if(true) {
            $is_error                                  =      true;
            $message['card_cvv']                       =      "Please enter card cvv";
        }
        if($is_error) {
            $data                                      =      ['Result' => "Fail", "Message" => $message];
        } else {
            $checksumobj                           =      new Checksum();
            $privatekey                            =      $checksumobj->encrypt($get_agency_data['AIRPAY_USERNAME'].":|:".$get_agency_data['AIRPAY_PASSWORD'], $get_agency_data['AIRPAY_SECRET_KEY']);
            $savecard_url = 'https://ca.airpay.co.in/api.php';
            $savecard_data = [
                'action' => 'saveCard',
                'type' => 'consumer',
                'respType' => 'json',
                'mercid' => $get_agency_data['AIRPAY_MERCHANT_ID'],
                'privatekey' => $privatekey,
                'token' => '',
                'cardnumber' => $card_number,
                'cardholdername' => $card_nickname,
                'expirymonth' => $expiry_month,
                'expiryyear' => $expiry_year,
                'email' => $get_agency_data['EMAIL'],
                'mobile' => $get_agency_data['PHONE']
            ];
            $general_helper                            =      new generalHelper();
            //$savecard_result                           =      $general_helper->sendDataOverPost($savecard_url, $savecard_data, 'POST', $timeout = 30, $port = 443);
            $savecard_result                           =      '{
  "status": "200",
  "message": "Card added successfully",
  "statusdescription": "OK",
  "result": {
    "card_expiry": "1121",
    "card_issuer": "visa",
    "carduniquecode": "AuWWRQxqlFgZGBUJfDRr1yDZOEHtSZOW",
    "pos_ipn_url": null,
    "pos_ipn_port": null,
    "token": null
  }
}';
            $save_card_api_response             =      json_decode($savecard_result,true);
            if(!empty($save_card_api_response['status']) && $save_card_api_response['status'] == '200') {
            //if(false) {
                $add_card_query                            =      "INSERT INTO tbl_agent_payment_config(AGENT_DETAILS_ID, GROUP_ID, CARD_TYPE, CARD_NUMBER, NAME, EXPIRY_YEAR, EXPIRY_MONTH, CVV, TOKEN_NO, CARD_UNIQUE_CODE, STATUS, CREATED_ON, BANK_ID) VALUES (:agent_detail_id, :group_id, :card_type, :card_number, :name, :expiry_year, :expiry_month, :cvv, :token, :card_unique_code, :status, :created_on, :bank_id)";
                $add_card                                  =      $connection->createCommand($add_card_query);
                $add_card->bindValue(':agent_detail_id',!empty($get_agency_data['AGENT_DETAILS_ID'])?$get_agency_data['AGENT_DETAILS_ID']:'');
                $add_card->bindValue(':group_id',!empty($get_agency_data['AGENT_GROUP_ID'])?$get_agency_data['AGENT_GROUP_ID']:'');
                $add_card->bindValue(':card_type',!empty($payment_card_type)?$payment_card_type:'');
                $add_card->bindValue(':card_number',!empty($card_number)?$card_number:'');
                $add_card->bindValue(':name',!empty($card_nickname)?$card_nickname:'');
                $add_card->bindValue(':expiry_year',!empty($expiry_year)?$expiry_year:'');
                $add_card->bindValue(':expiry_month',!empty($expiry_month)?$expiry_month:'');
                $add_card->bindValue(':cvv',!empty($card_cvv)?$card_cvv:'');
                $add_card->bindValue(':token',!empty($token)?$token:'');
                $add_card->bindValue(':card_unique_code',!empty($card_unique_code)?$card_unique_code:'');
                $add_card->bindValue(':status','0');
                $add_card->bindValue(':created_on',time());
                $add_card->bindValue(':bank_id',0);
                $add_card->execute();
                $agent_payment_config_id                   =      $connection->getLastInsertID();
                $message                                   =      'Card saved successfully.';
                $data                                      =      ['Result' => "Success", "Message" => $message];
            } else {
                $message['api']                            =      'Save card api failed';
                $data                                      =      ['Result' => "Fail", "Message" => $message];
            }

        }
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($data);
    }

    public function actionAddbanks()
    {
        $partner_id     =   isset(\Yii::$app->request->post()['add_bank_partner_id']) ? \Yii::$app->request->post()['add_bank_partner_id'] : '';
        $payment_type   =   isset(\Yii::$app->request->post()['payment_type']) ? \Yii::$app->request->post()['payment_type'] : '';
        $bank           =   isset(\Yii::$app->request->post()['bank']) ? \Yii::$app->request->post()['bank'] : '';
        $connection     =   \Yii::$app->db;
        $get_agent_detais = $connection->createCommand('SELECT A.*,B.*,C.*,D.* FROM tbl_agent_details as A INNER JOIN tbl_partner_master as B ON A.PARTNER_ID = B.PARTNER_ID INNER JOIN tbl_agent_group AS C ON C.AGENT_GROUP_ID = A.AGENT_GROUP_ID INNER JOIN tbl_agent_group_payment_limit AS D ON D.GROUP_ID = C.AGENT_GROUP_ID  WHERE A.PARTNER_ID=:partner_id');
        $get_agent_detais->bindValue(':partner_id', $partner_id);
        $get_agency_data = $get_agent_detais->queryOne();
        $agent_details_id = $get_agency_data['AGENT_DETAILS_ID'];
        $data = [];
        $message = [];
        $is_error = false;
        if (empty($partner_id)) {
            $is_error = true;
            $message['partner_id'] = "Partner does not exist.";
        }
        if (empty($payment_type)) {
//        if(true) {
            $is_error = true;
            $message['payment_type'] = "Please select payment type.";
        }
        if (empty($bank)) {
//        if(true) {
            $is_error = true;
            $message['bank'] = "Please select bank.";
        }
        if ($is_error) {
            $data = ['Result' => "Fail", "Message" => $message];
        } else {
            $add_card_query = "INSERT INTO tbl_agent_payment_config(AGENT_DETAILS_ID, GROUP_ID, CARD_TYPE, CARD_NUMBER, NAME, EXPIRY_YEAR, EXPIRY_MONTH, CVV, TOKEN_NO, CARD_UNIQUE_CODE, STATUS, CREATED_ON, BANK_ID) VALUES (:agent_detail_id, :group_id, :card_type, :card_number, :name, :expiry_year, :expiry_month, :cvv, :token, :card_unique_code, :status, :created_on, :bank_id)";
            $add_card = $connection->createCommand($add_card_query);
            $add_card->bindValue(':agent_detail_id', !empty($get_agency_data['AGENT_DETAILS_ID']) ? $get_agency_data['AGENT_DETAILS_ID'] : '');
            $add_card->bindValue(':group_id', !empty($get_agency_data['AGENT_GROUP_ID']) ? $get_agency_data['AGENT_GROUP_ID'] : '');
            $add_card->bindValue(':card_type', !empty($payment_card_type) ? $payment_card_type : '');
            $add_card->bindValue(':card_number', !empty($card_number) ? $card_number : '');
            $add_card->bindValue(':name', !empty($card_nickname) ? $card_nickname : '');
            $add_card->bindValue(':expiry_year', !empty($expiry_year) ? $expiry_year : '');
            $add_card->bindValue(':expiry_month', !empty($expiry_month) ? $expiry_month : '');
            $add_card->bindValue(':cvv', !empty($card_cvv) ? $card_cvv : '');
            $add_card->bindValue(':token', !empty($token) ? $token : '');
            $add_card->bindValue(':card_unique_code', !empty($card_unique_code) ? $card_unique_code : '');
            $add_card->bindValue(':status', '0');
            $add_card->bindValue(':created_on', time());
            $add_card->bindValue(':bank_id', $bank);
            $add_card->execute();
            $agent_payment_config_id = $connection->getLastInsertID();
            $message = 'Bank added successfully.';
            $data = ['Result' => "Success", "Message" => $message];
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json');
            echo json_encode($data);
        }
    }
    public function actionExporttransactions() {
        $connection             =       \Yii::$app->db;
        $partner_id             =       isset(\Yii::$app->request->get()['partner_id']) ? \Yii::$app->request->get()['partner_id'] : '';
        $agent_details_id       =       isset(\Yii::$app->request->get()['agent_id']) ? \Yii::$app->request->get()['agent_id'] : '';
        $Rec_txt2               =       '';
        $Rec_txt2               .=      '"Date","Transaction Id","Transaction Type","Status","Amount","PG/Bank Name"' . "\n";
        $transactions_query     =       "SELECT A.*,B.* FROM tbl_invoice as A INNER JOIN tbl_order as B ON A.INVOICE_ID = B.INVOICE_ID WHERE PARTNER_ID = :partner_id AND A.AGENT_ID  =:agent_id ORDER BY A.CREATED_ON DESC";
        $get_transactions       =       $connection->createCommand($transactions_query);
        $get_transactions->bindValue(':partner_id',$partner_id);
        $get_transactions->bindValue(':agent_id',$agent_details_id);
        $transactions           =       $get_transactions->queryAll();
        foreach ($transactions as $transaction) {
            $Rec_txt2 .= '"' . date("d-m-Y",$transaction['CREATED_ON']) . '", "' .  $transaction['REF_ID'] . '", "Sale" , "'.$transaction['TRANSACTION_MESSAGE'].'" , "'.$transaction['RECEIVED_AMOUNT'].'", "ICICI Bank PG"' . "\n";
        }
        header("Content-type:text/octet-stream");
        header("Content-Disposition:attachment;filename=transactions.csv");
        echo $Rec_txt2;
    }
}