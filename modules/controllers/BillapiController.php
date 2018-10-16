<?php

namespace app\modules\controllers;

use Yii;
use yii\web\UploadedFile;
use app\helpers\Checksum;
use yii\base\Hcontroller;
use app\modules\models\TblUtility;
use app\modules\models\TblProvider;
use app\modules\models\TblProviderBillUploadDetails;
use app\modules\models\TblProviderInvoice;
use app\modules\models\TblProviderBillDetails;
use app\modules\models\TblInvoiceBillDetails;
use app\modules\models\TblTranscationDetails;

class BillapiController extends Hcontroller
{
  public $enableCsrfValidation = false;
  // public function actionIndex()
  // {
  //   return $this->render('index');
  // }
  
  public function writeLog($mid, $data) {
    $filepath = realpath(Yii::$app->basePath)."/web/bbps/log/";
    $filename = $filepath . $mid . '.log';
    if (!file_exists($filename)) 
    {
      mkdir($filename, 0777, true);
    }
    $data = $data."\r\n";
    $log_file_data = $filename.'/log_' . date('d-M-Y') . '.log';
    file_put_contents($log_file_data, $data , FILE_APPEND);
  }
  
  public function actionAccount_register_response(){
    $post = Yii::$app->request->rawBody;
    $data2 = json_decode($post);
    $log_data = "REGISTER DATA RESPONSE : ".$post;
    $this->writeLog("Log_Data",$log_data);
    $model= new TblProviderBillDetails();
    $connection = Yii::$app->db; 
    $query="SELECT p.AIRPAY_MERCHANT_ID,p.AIRPAY_USERNAME,p.AIRPAY_PASSWORD,p.AIRPAY_SECRET_KEY from tbl_partner_master as p JOIN tbl_user_master as u ON p.PARTNER_ID = u.PARTNER_ID WHERE u.USER_ID=:user_id";
    $config = $connection
    ->createCommand($query);
    $config->bindValue(':user_id','1');
    $config_data = $config->queryAll();
    $chk = new Checksum();
    $privatekey =$chk->encrypt($config_data[0]['AIRPAY_USERNAME'].":|:".$config_data[0]['AIRPAY_PASSWORD'], $config_data[0]['AIRPAY_SECRET_KEY']);
    $checksum = md5($data2->REGISTERID."~".$data2->BILLERACCOUNTID."~".$data2->PROFILEID."~".$data2->CUSTOMERID."~".$data2->REQUESTNUMBER);
    if(($privatekey != $data2->PRIVATEKEY || $checksum != $data2->CHECKSUM)){
    	$log_data = "REGISTER DATA RESPONSE FROM P : Error in Authentication ";
    	$this->writeLog("Log_Data",$log_data);
      return json_encode(['status'=>400,"message"=>"Error in Authentication"]);
    } else {
      $get_provider = $connection->createCommand('Select PROVIDER_ID from tbl_provider_bill_details where ACCOUNT_NO=:account_no AND PROVIDER_BILL_DETAILS_ID=:provider_bill_details_id');
      $get_provider->bindValue(':account_no',$data2->ACCOUNTID);
      $get_provider->bindValue(':provider_bill_details_id',$data2->REQUESTNUMBER);
      $get_provider_data =  $get_provider->queryAll();
      if($data2->STATUS == 200){
        $status = $connection->createCommand()
        ->update('tbl_registered_account', ['REF_NO'=>$data2->BILLERACCOUNTID,'IS_REGISTERED'=>1], 'ACCOUNT_NO='.$data2->ACCOUNTID.' AND PROVIDE_ID='.$get_provider_data[0]['PROVIDER_ID'])
        ->execute();
        if($status){
        $log_data = "REGISTER DATA RESPONSE FROM P : UPLOADED SUCCESSFULLY ";
    		$this->writeLog("Log_Data",$log_data);
          return json_encode(['status'=>200,"message"=>"UPLOADED SUCCESSFULLY"]);
        } else {
        	 $log_data = "REGISTER DATA RESPONSE FROM P : ERROR IN UPLOADING ";
    		$this->writeLog("Log_Data",$log_data);
          return json_encode(['status'=>400,"message"=>"ERROR IN UPLOADING"]);
        }
      }else {
        $status = $connection->createCommand()
        ->update('tbl_registered_account', ['REF_NO'=>'-','IS_REGISTERED'=>1], 'ACCOUNT_NO='.$data2->ACCOUNTID.' AND PROVIDE_ID='.$get_provider_data[0]['PROVIDER_ID'])
        ->execute();
        $update_tbl_provider = $connection->createCommand()
        ->update('tbl_provider_bill_details', ['PAYMENT_STATUS'=>'failed','RESPONSE_NOT_RECIEVED'=>1], 'ACCOUNT_NO='.$data2->ACCOUNTID.' AND PROVIDER_ID='.$get_provider_data[0]['PROVIDER_ID'])
        ->execute();
        if($status && $update_tbl_provider){
        	$log_data = "REGISTER DATA RESPONSE FROM P : UPLOADED SUCCESSFULLY STATUS 400 ";
    		$this->writeLog("Log_Data",$log_data);
          return json_encode(['status'=>200,"message"=>"UPLOADED SUCCESSFULLY"]);
        } else {
        	$log_data = "REGISTER DATA RESPONSE FROM P : ERROR IN UPLOADING STATUS 400 ";
    		$this->writeLog("Log_Data",$log_data);
          return json_encode(['status'=>400,"message"=>"ERROR IN UPLOADING"]);
        }
      }
    }
  }
  
  public function actionBill_data_response(){
    $post = Yii::$app->request->rawBody;
    $log_data = "Bill DATA RESPONSE : ".$post;
    $this->writeLog("Log_Data",$log_data);
    $data2 = json_decode($post);
    $connection = Yii::$app->db;
    $query="SELECT p.AIRPAY_MERCHANT_ID,p.AIRPAY_USERNAME,p.AIRPAY_PASSWORD,p.AIRPAY_SECRET_KEY from tbl_partner_master as p JOIN tbl_user_master as u ON p.PARTNER_ID = u.PARTNER_ID WHERE u.USER_ID=:user_id";
    $config = $connection
    ->createCommand($query);
    $config->bindValue(':user_id','1');
    $config_data = $config->queryAll();
    $chk = new Checksum();
    $privatekey =$chk->encrypt($config_data[0]['AIRPAY_USERNAME'].":|:".$config_data[0]['AIRPAY_PASSWORD'], $config_data[0]['AIRPAY_SECRET_KEY']);
    $checksum = md5($data2->USER_ID."~".$data2->CUSTOMER_ID."~".$data2->ACCOUNTID."~".$data2->BILLAMOUNT."~".$data2->BILLID."~".$data2->BILLDUEDATE."~".$data2->BILLNUMBER."~".$data2->BILLERNAME."~".$data2->REGISTERID."~".$data2->BILLRSPID."~".$data2->REQUESTNUMBER);
    if($privatekey != $data2->PRIVATEKEY || $checksum != $data2->CHECKSUM){
      return json_encode(['status'=>400,"message"=>"Error in Authentication"]);
    } else {
      $model= new TblProviderBillDetails();
      $connection = Yii::$app->db;  
    
    $query="SELECT PAYMENT_STATUS,AMOUNT from tbl_provider_bill_details WHERE ACCOUNT_NO=:account_no AND PROVIDER_BILL_DETAILS_ID=:provider_bill_details_id";
        $check_next_month = $connection->createCommand($query);
        $check_next_month->bindValue(':account_no',$data2->ACCOUNTID);
        $check_next_month->bindValue(':provider_bill_details_id',$data2->REQUESTNUMBER);
        $check_next_month_data = $check_next_month->queryAll();
        if($check_next_month_data[0]['PAYMENT_STATUS'] == "" && $check_next_month_data[0]['AMOUNT']!=0){
          $query="INSERT into tbl_archived_provider_bill_details SELECT * FROM tbl_provider_bill_details WHERE ACCOUNT_NO=:account_no AND PROVIDER_BILL_DETAILS_ID=:provider_bill_details_id";
          $archieve = $connection
          ->createCommand($query);
          $archieve->bindValue(':account_no',$data2->ACCOUNTID);
          $archieve->bindValue(':provider_bill_details_id',$data2->REQUESTNUMBER);
          $archieve_data = $archieve->execute();
          if($archieve_data){
            $query="INSERT into tbl_provider_bill_details (PROVIDER_ID,REF_NO,REGISTER_BILLER_FLAG,REMOVED,IS_REGISTER,UTILITY_ID,USER_ID,ACCOUNT_NO, DETAILS,FNAME,LNAME,EMAIL,MOBILE_NUMBER) SELECT PROVIDER_ID,REF_NO,REGISTER_BILLER_FLAG,REMOVED,IS_REGISTER,UTILITY_ID,USER_ID,RESPONSE_NOT_RECIEVED,ACCOUNT_NO, DETAILS,FNAME,LNAME,EMAIL,MOBILE_NUMBER FROM tbl_provider_bill_details WHERE ACCOUNT_NO=:account_no AND PROVIDER_BILL_DETAILS_ID=:provider_bill_details_id";
            $check_next_month = $connection->createCommand($query);
            $check_next_month->bindValue(':account_no',$data2->ACCOUNTID);
            $check_next_month->bindValue(':provider_bill_details_id',$data2->REQUESTNUMBER);
            $check_next_month_data = $check_next_month->execute();
            $provider_bill_details_id = $connection->getLastInsertID();
            $connection->createCommand()
            ->update('tbl_provider_bill_details', ['DUE_DATE'=>date('Y-m-d H:i:s',strtotime($data2->BILLDUEDATE)),'AMOUNT'=>$data2->BILLAMOUNT,'REF_NO'=>$data2->REGISTERID,'BANK_BILL_ID'=>$data2->BILLID,'BILL_NUMBER'=>$data2->BILLNUMBER,'BILL_ID'=>$data2->BILLRSPID,'RESPONSE_NOT_RECIEVED'=>0], 'ACCOUNT_NO='.$data2->ACCOUNTID.' AND PROVIDER_BILL_DETAILS_ID='.$provider_bill_details_id)
            ->execute();
            $msg=$this->notification($data2->Invoice_no);
            
            $query2="DELETE FROM tbl_provider_bill_details WHERE  ACCOUNT_NO=:account_no AND PROVIDER_BILL_DETAILS_ID=:provider_bill_details_id";
            $registered = $connection
            ->createCommand($query2);
            $registered->bindValue(':account_no',$data2->ACCOUNTID);
            $registered->bindValue(':provider_bill_details_id',$data2->REQUESTNUMBER);
            $registered_data = $registered->execute();
            $msg=$this->notification($data2->Invoice_no);
            if(isset($msg)){
              return json_encode(['status'=>200,"message"=>"UPLOADED SUCCESSFULLY"]);
            } else {
              return json_encode(['status'=>400,"message"=>"ERROR IN UPLOADING"]);
            }
          }
        }else{
          if($check_next_month_data[0]['PAYMENT_STATUS'] == "") {
            
            $connection->createCommand()
            ->update('tbl_provider_bill_details', ['DUE_DATE'=>date('Y-m-d H:i:s',strtotime($data2->BILLDUEDATE)),'AMOUNT'=>$data2->BILLAMOUNT,'REF_NO'=>$data2->REGISTERID,'BANK_BILL_ID'=>$data2->BILLID,'BILL_NUMBER'=>$data2->BILLNUMBER,'BILL_ID'=>$data2->BILLRSPID,'RESPONSE_NOT_RECIEVED'=>0], 'ACCOUNT_NO='.$data2->ACCOUNTID.' AND PROVIDER_BILL_DETAILS_ID='.$data2->REQUESTNUMBER)
            ->execute();
            $msg=$this->notification($data2->Invoice_no);
            if(isset($msg)){
              return json_encode(['status'=>200,"message"=>"UPLOADED SUCCESSFULLY"]);
            } else {
              return json_encode(['status'=>400,"message"=>"ERROR IN UPLOADING"]);
            }
          }
          
          else{
            $query="INSERT into tbl_provider_bill_details (PROVIDER_ID,REF_NO,REGISTER_BILLER_FLAG,REMOVED,IS_REGISTER,UTILITY_ID,USER_ID,ACCOUNT_NO, DETAILS,FNAME,LNAME,EMAIL,MOBILE_NUMBER) SELECT PROVIDER_ID,REF_NO,REGISTER_BILLER_FLAG,REMOVED,IS_REGISTER,UTILITY_ID,USER_ID,RESPONSE_NOT_RECIEVED,ACCOUNT_NO, DETAILS,FNAME,LNAME,EMAIL,MOBILE_NUMBER FROM tbl_provider_bill_details WHERE ACCOUNT_NO=:account_no AND PROVIDER_BILL_DETAILS_ID=:provider_bill_details_id";
            $check_next_month = $connection->createCommand($query);
            $check_next_month->bindValue(':account_no',$data2->ACCOUNTID);
            $check_next_month->bindValue(':provider_bill_details_id',$data2->REQUESTNUMBER);
            $check_next_month_data = $check_next_month->execute();
            $provider_bill_details_id = $connection->getLastInsertID();
            $connection->createCommand()
            ->update('tbl_provider_bill_details', ['DUE_DATE'=>date('Y-m-d H:i:s',strtotime($data2->BILLDUEDATE)),'AMOUNT'=>$data2->BILLAMOUNT,'REF_NO'=>$data2->REGISTERID,'BANK_BILL_ID'=>$data2->BILLID,'BILL_NUMBER'=>$data2->BILLNUMBER,'BILL_ID'=>$data2->BILLRSPID,'RESPONSE_NOT_RECIEVED'=>0], 'ACCOUNT_NO='.$data2->ACCOUNTID.' AND PROVIDER_BILL_DETAILS_ID='.$provider_bill_details_id)
            ->execute();
            $msg=$this->notification($data2->Invoice_no);
          }
        }
        
        
    
      // $connection->createCommand()
      // ->update('tbl_provider_bill_details', ['DUE_DATE'=>date('Y-m-d H:i:s',strtotime($data2->BILLDUEDATE)),'AMOUNT'=>$data2->BILLAMOUNT,'REF_NO'=>$data2->REGISTERID,'BANK_BILL_ID'=>$data2->BILLID,'BILL_NUMBER'=>$data2->BILLNUMBER,'BILL_ID'=>$data2->BILLRSPID,'RESPONSE_NOT_RECIEVED'=>0], 'ACCOUNT_NO='.$data2->ACCOUNTID.' AND PROVIDER_BILL_DETAILS_ID='.$data2->REQUESTNUMBER)
      // ->execute();
      // //$msg=$this->notification($data2->Invoice_no);
      // if(true){
      //   return json_encode(['status'=>200,"message"=>"UPLOADED SUCCESSFULLY"]);
      // } else {
      //   return json_encode(['status'=>400,"message"=>"ERROR IN UPLOADING"]);
      // }
    }
  }
  
  public function actionPaymentstatus(){
    $post = Yii::$app->request->rawBody;
    $log_data = "MAKE PAYMENT DATA RESPONSE : ".$post;
    $this->writeLog("Log_Data",$log_data);
    $data2 = json_decode($post);
    $posted_checksum = $data2->CHECKSUM;
    unset($data2->CHECKSUM);
    $checksum = md5(json_encode($data2));
    if($posted_checksum != $checksum){
      $this->writeLog("Log_Data","CHECKSUM ERROR");
      return json_encode(['status'=>400,"message"=>"Error in Authentication"]);
    } else {
      if($data2->STATUS == 'Y'){
        $status = "success";
      } else if($data2->STATUS == 'N') {
        $status = "fail";
      } else {
        $status = "pending";
      }
      $connection = Yii::$app->db;  
      $update_status = $connection->createCommand()
      ->update('tbl_provider_bill_details', ['PAYMENT_STATUS'=>$status,'BANK_REF_PAYMENT_NUMBER'=>$data2->BANKREFNUMBER], 'ACCOUNT_NO='.$data2->AUTHENTICATOR.' AND BILL_ID='.$data2->VIEW_BILL_RSP_ID)
      ->execute();
      if($data2->STATUS == 'N'){
        $query2 = "INSERT into tbl_provider_bill_details (PROVIDER_BILL_UPLOAD_DETAILS_ID,PROVIDER_ID,REF_NO,REGISTER_BILLER_FLAG,REMOVED,IS_REGISTER,AMOUNT,UTILITY_ID,USER_ID,RESPONSE_NOT_RECIEVED,ACCOUNT_NO,DETAILS,BANK_BILL_ID,BILL_NUMBER,BILL_ID,DUE_DATE,FNAME,LNAME,EMAIL)  SELECT PROVIDER_BILL_UPLOAD_DETAILS_ID,PROVIDER_ID,REF_NO,REGISTER_BILLER_FLAG,REMOVED,IS_REGISTER,AMOUNT,UTILITY_ID,USER_ID,RESPONSE_NOT_RECIEVED,ACCOUNT_NO,DETAILS,BANK_BILL_ID,BILL_NUMBER,BILL_ID,DUE_DATE,FNAME,LNAME,EMAIL FROM tbl_provider_bill_details WHERE ACCOUNT_NO=:account_no AND BILL_ID =:bill_id";
        $insert_fail_account =  $connection->createCommand($query2);
        $insert_fail_account->bindValue(':account_no',$data2->AUTHENTICATOR);
        $insert_fail_account->bindValue(':bill_id',$data2->VIEW_BILL_RSP_ID);
        $insert_fail_account_data = $insert_fail_account->execute();
      }
      if($update_status){
        return json_encode(['status'=>200,"message"=>"UPLOADED SUCCESSFULLY"]);
      } else{
        return json_encode(['status'=>400,"message"=>"ERROR IN UPLOADING"]);
      }
    }
  }
  
  // public function actionVerify_register_no(){
  //   $connection = Yii::$app->db;
  //   $query="Select b.PROVIDER_BILL_DETAILS_ID,b.INVOICE_ID,b.PROVIDER_ID,b.ACCOUNT_NO,b.USER_ID from tbl_provider_bill_details as b JOIN tbl_registered_account as r on b.ACCOUNT_NO=r.ACCOUNT_NO where r.IS_REGISTERED=0";
  //   $verify_register = $connection
  //   ->createCommand($query);
  //   $verify_register_data = $verify_register->queryAll();
  //   foreach($verify_register_data as $key=>$value){
  //     $query1="SELECT p.AIRPAY_MERCHANT_ID,p.AIRPAY_USERNAME,p.AIRPAY_PASSWORD,p.AIRPAY_SECRET_KEY from tbl_partner_master as p JOIN tbl_user_master as u ON p.PARTNER_ID = u.PARTNER_ID WHERE u.USER_ID=:user_id";
  //     $config = $connection
  //     ->createCommand($query1);
  //     $config->bindValue(':user_id',$value['USER_ID']);
  //     $config_data = $config->queryAll();
  //     $chk = new Checksum();
  //     $privatekey =$chk->encrypt($config_data[0]['AIRPAY_USERNAME'].":|:".$config_data[0]['AIRPAY_PASSWORD'], $config_data[0]['AIRPAY_SECRET_KEY']);
  //     $checksum = md5($data2->USER_ID."~".$data2->CUSTOMER_ID."~".$data2->ACCOUNTID."~".$data2->BILLAMOUNT."~".$data2->BILLID."~".$data2->BILLDUEDATE."~".$data2->BILLNUMBER."~".$data2->BILLERNAME."~".$data2->REGISTERID."~".$data2->BILLRSPID."~".$data2->REQUESTNUMBER);
  //     $api_data= [  
  //       "requestid"=>$value['PROVIDER_BILL_DETAILS_ID'],
  //       "privatekey"=>$privatekey,
  //       "mercid"=>$config_data[0]['AIRPAY_MERCHANT_ID'],
  //       "checksum"=>$checksum,
  //       "customerid"=>$value['USER_ID'],
  //       "billerid"=>$value['PROVIDER_ID'],
  //       "account_id"=>$value['ACCOUNT_NO']
  //     ];
  //     $apidata= json_encode($api_data);
  //     $curl = curl_init("https://payments.airpay.co.in/bbps/verifybiller.php");
  //     curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
  //     //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  //     curl_setopt($curl, CURLOPT_POST, 1);
  //     curl_setopt($curl, CURLOPT_POSTFIELDS,$apidata);
  //     $curl_response = curl_exec($curl);
  //     curl_close($curl);
  //     $data2 = json_decode($curl_response);
  //     $get_provider = $connection->createCommand('Select PROVIDER_ID from tbl_provider_bill_details where ACCOUNT_NO=:account_no AND PROVIDER_BILL_DETAILS_ID=:provider_bill_details_id');
  //     $get_provider->bindValue(':account_no',$data2->ACCOUNTID);
  //     $get_provider->bindValue(':provider_bill_details_id',$data2->REQUESTNUMBER);
  //     $get_provider_data =  $get_provider->queryAll();
  //     $status = $connection->createCommand()
  //     ->update('tbl_registered_account', ['REF_NO'=>$data2->BILLERACCOUNTID,'IS_REGISTERED'=>1], 'ACCOUNT_NO='.$data2->ACCOUNTID.' AND PROVIDE_ID='.$get_provider_data[0]['PROVIDER_ID'])
  //     ->execute();
  //   }
  // }
  
  // public function actionVerify_view_bill(){
  //   $connection = Yii::$app->db;
  //   $query="Select b.PROVIDER_BILL_DETAILS_ID,b.INVOICE_ID,b.PROVIDER_ID,b.ACCOUNT_NO,b.USER_ID from tbl_provider_bill_details as b JOIN tbl_registered_account as r on b.ACCOUNT_NO=r.ACCOUNT_NO where b.RESPONSE_NOT_RECIEVED=1";
  //   $verify_view_bill = $connection
  //   ->createCommand($query);
  //   $verify_view_bill_data = $config->queryAll();
  //   foreach($verify_view_bill_data as $key=>$value){
  //     $query1="SELECT p.AIRPAY_MERCHANT_ID,p.AIRPAY_USERNAME,p.AIRPAY_PASSWORD,p.AIRPAY_SECRET_KEY from tbl_partner_master as p JOIN tbl_user_master as u ON p.PARTNER_ID = u.PARTNER_ID WHERE u.USER_ID=:user_id";
  //     $config = $connection
  //     ->createCommand($query1);
  //     $config->bindValue(':user_id',$value['USER_ID']);
  //     $config_data = $config->queryAll();
  //     $chk = new Checksum();
  //     $privatekey =$chk->encrypt($config_data[0]['AIRPAY_USERNAME'].":|:".$config_data[0]['AIRPAY_PASSWORD'], $config_data[0]['AIRPAY_SECRET_KEY']);
  //     $checksum = md5($data2->USER_ID."~".$data2->CUSTOMER_ID."~".$data2->ACCOUNTID."~".$data2->BILLAMOUNT."~".$data2->BILLID."~".$data2->BILLDUEDATE."~".$data2->BILLNUMBER."~".$data2->BILLERNAME."~".$data2->REGISTERID."~".$data2->BILLRSPID."~".$data2->REQUESTNUMBER);
  //     $api_data = [  
  //       "requestid"=>$value['PROVIDER_BILL_DETAILS_ID'],
  //       "privatekey"=>$privatekey,
  //       "mercid"=>$config_data[0]['AIRPAY_MERCHANT_ID'],
  //       "checksum"=>$checksum,
  //       "customerid"=>$value['USER_ID'],
  //       // "registerid"=>"196",
  //       "accountid"=>$value['ACCOUNT_NO']
  //     ];
  //     $apidata= json_encode($api_data);
  //     $curl = curl_init("https://payments.airpay.co.in/bbps/verifybill.php");
  //     curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
  //     //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  //     curl_setopt($curl, CURLOPT_POST, 1);
  //     curl_setopt($curl, CURLOPT_POSTFIELDS,$apidata);
  //     $curl_response = curl_exec($curl);
  //     curl_close($curl);
  //     $data2 = json_decode($curl_response);
  //     $status = $connection->createCommand()
  //     ->update('tbl_provider_bill_details', ['DUE_DATE'=>date('Y-m-d H:i:s',strtotime($data2->BILLDUEDATE)),'AMOUNT'=>$data2->BILLAMOUNT,'REF_NO'=>$data2->BBPS_REGISTER_RES_ID,'BANK_BILL_ID'=>$data2->BILLID,'BILL_NUMBER'=>$data2->BILLNUMBER,'BILL_ID'=>$data2->VIEW_BILL_RSP_ID,'RESPONSE_NOT_RECIEVED'=>0], 'ACCOUNT_NO='.$data2->ACCOUNTID.' AND PROVIDER_BILL_DETAILS_ID='.$data2->REQUESTNUMBER)
  //     ->execute();
  //   }
  // }
  
  // public function actionVerify_make_payment(){
  //   $connection = Yii::$app->db;
  //   $query="Select b.INVOICE_ID,b.BILL_ID,b.AMOUNT,b.ACCOUNT_NO,b.USER_ID,t.AIRPAY_ID, from tbl_provider_bill_details as b JOIN tbl_transcation_details as t on b.INVOICE_ID=t.INVOICE_ID where b.PAYMENT_STATUS='pending'";
  //   $verify_payment = $connection
  //   ->createCommand($query);
  //   $verify_payment_data = $verify_payment->queryAll();
  //   foreach($verify_payment_data as $key=>$value){
  //     $query1="SELECT p.AIRPAY_MERCHANT_ID,p.AIRPAY_USERNAME,p.AIRPAY_PASSWORD,p.AIRPAY_SECRET_KEY from tbl_partner_master as p JOIN tbl_user_master as u ON p.PARTNER_ID = u.PARTNER_ID WHERE u.USER_ID=:user_id";
  //     $config = $connection
  //     ->createCommand($query1);
  //     $config->bindValue(':user_id',$value['USER_ID']);
  //     $config_data = $config->queryAll();
  //     $chk = new Checksum();
  //     $privatekey =$chk->encrypt($config_data[0]['AIRPAY_USERNAME'].":|:".$config_data[0]['AIRPAY_PASSWORD'], $config_data[0]['AIRPAY_SECRET_KEY']);
  //     $checksum = md5($data2->USER_ID."~".$data2->CUSTOMER_ID."~".$data2->ACCOUNTID."~".$data2->BILLAMOUNT."~".$data2->BILLID."~".$data2->BILLDUEDATE."~".$data2->BILLNUMBER."~".$data2->BILLERNAME."~".$data2->REGISTERID."~".$data2->BILLRSPID."~".$data2->REQUESTNUMBER);
  //     $api_data = [  
  //       "privatekey"=>$privatekey,
  //       "mercid"=>$config_data[0]['AIRPAY_MERCHANT_ID'],
  //       "airpay_id"=>$value['AIRPAY_ID'],
  //       "checksum"=>$checksum,     
  //       "viewbillresponseid"=>$value['BILL_ID'],
  //       "amount"=>$value['AMOUNT']
  //     ];
  //   }
  //   $apidata= json_encode($api_data);
  //   $curl = curl_init("https://payments.airpay.co.in/bbps/verifypayment.php");
  //   curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
  //   //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  //   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  //   curl_setopt($curl, CURLOPT_POST, 1);
  //   curl_setopt($curl, CURLOPT_POSTFIELDS,$apidata);
  //   $curl_response = curl_exec($curl);
  //   curl_close($curl);

  //   $data2= json_encode($curl_response);
  //   if($data2->STATUS == 'Y'){
  //     $status = "success";
  //   } else if($data2->STATUS == 'N') {
  //     $status = "fail";
  //   } else {
  //     $status = "pending";
  //   }
  //   $update_status = $connection->createCommand()
  //     ->update('tbl_provider_bill_details', ['PAYMENT_STATUS'=>$status,'BANK_REF_PAYMENT_NUMBER'=>$data2->BANKREFNUMBER], 'ACCOUNT_NO='.$data2->AUTHENTICATOR.' AND BILL_ID='.$data2->VIEW_BILL_RSP_ID)
  //     ->execute();
  //     if($data2->STATUS == 'N'){
  //       $query2 = "INSERT into tbl_provider_bill_details (PROVIDER_BILL_UPLOAD_DETAILS_ID,PROVIDER_ID,REF_NO,REGISTER_BILLER_FLAG,REMOVED,IS_REGISTER,AMOUNT,UTILITY_ID,USER_ID,RESPONSE_NOT_RECIEVED,ACCOUNT_NO,DETAILS,BANK_BILL_ID,BILL_NUMBER,BILL_ID,DUE_DATE,FNAME,LNAME,EMAIL)  SELECT PROVIDER_BILL_UPLOAD_DETAILS_ID,PROVIDER_ID,REF_NO,REGISTER_BILLER_FLAG,REMOVED,IS_REGISTER,AMOUNT,UTILITY_ID,USER_ID,RESPONSE_NOT_RECIEVED,ACCOUNT_NO,DETAILS,BANK_BILL_ID,BILL_NUMBER,BILL_ID,DUE_DATE,FNAME,LNAME,EMAIL FROM tbl_provider_bill_details WHERE ACCOUNT_NO=:account_no AND BILL_ID =:bill_id";
  //       $insert_fail_account =  $connection->createCommand($query2);
  //       $insert_fail_account->bindValue(':account_no',$data2->AUTHENTICATOR);
  //       $insert_fail_account->bindValue(':bill_id',$data2->VIEW_BILL_RSP_ID);
  //       $insert_fail_account_data = $insert_fail_account->execute();
  //     }
  // }
}
