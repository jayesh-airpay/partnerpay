<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;
use yii\console\Controller;
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
use yii\validators\EmailValidator;
use yii\validators\RequiredValidator;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AsdController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
   
	public function actionIndex(){
    $connection = Yii::$app->db;
    // $query="SELECT AIRPAY_MERCHANT_ID,AIRPAY_USERNAME,AIRPAY_PASSWORD,AIRPAY_SECRET_KEY from tbl_partner_master WHERE PARTNER_ID=:partner_id";
    // $config = $connection
    // ->createCommand($query);
    // $config->bindValue(':partner_id',$data['PARTNER_ID']);
    // $config_data = $config->queryAll();
    // $chk = new Checksum();
    // $privatekey =$chk->encrypt($config_data[0]['AIRPAY_USERNAME'].":|:".$config_data[0]['AIRPAY_PASSWORD'], $config_data[0]['AIRPAY_SECRET_KEY']);
    $data = [
      "privatekey"=>"",
      "checksum"=>"",
      "mercid"=>"1",
    ];
    $api_data=json_encode($data);
    $url="https://payments.airpay.co.in/bbps/getBillerId.php";
    $billerdata = $this->api_call($url,$api_data);
    $log_data="GET BILLER ID API RESPONSE : ".json_encode($billerdata);
    $this->writeLog("Log_Data",$log_data);
    foreach($billerdata['BILLERDATA'] as $value){
      $connection = Yii::$app->db;
      $query="SELECT utility_id from tbl_utility where utility_name=:utility";
      $check_utility = $connection->createCommand($query);
      $check_utility->bindValue(':utility',$value['BILLER_CATEGORY']);
      $check_utility_data = $check_utility->queryAll();
      if(sizeof($check_utility_data)>0){
        $utility_id=$check_utility_data[0]['utility_id'];
      }else{
        $data=Yii::$app->user->identity;
        $query1="INSERT into tbl_utility (utility_name,user_id) VALUES (:utility_name,:user)";
        $check_utility = $connection
        ->createCommand($query1);
        $check_utility->bindValue(':utility_name',$value['BILLER_CATEGORY']);
        $check_utility->bindValue(':user',1);
        $check_utility_data = $check_utility->execute();
        $utility_id = $connection->getLastInsertID();
      }
      $query3='SELECT provider_id from tbl_provider where BILLER_MASTER_ID = :biller_master_id';
      $check_provider=$connection->createCommand($query3);
      $check_provider->bindValue(':biller_master_id',$value['BILLER_MASTER_ID']);
      $check_provider_data = $check_provider->execute();
      if($check_provider_data){
        $query4 = "UPDATE tbl_provider SET FIELDS=:fields,VALIDATIONS=:validations WHERE BILLER_MASTER_ID=:biller_master_id";
        $update_provider=$connection->createCommand($query4);
        $update_provider->bindValue(':biller_master_id',$value['BILLER_MASTER_ID']);
        $update_provider->bindValue(':fields',$value['FIELDNAMES']);
        $update_provider->bindValue(':validations',$value['VALIDATION']);
        $update_provider_data = $update_provider->execute();
      	// print_r($value['VALIDATION']);
      }else{  
        $query2 = "INSERT into tbl_provider (utility_id,provider_name,FIELDS,BILLER_MASTER_ID,VALIDATIONS) SELECT * FROM (SELECT :utility_id as utility_id,:provider_name,:fields,:biller_master_id,:validations) AS tmp
        WHERE NOT EXISTS (
          SELECT provider_name FROM tbl_provider WHERE provider_name = :provider_name
          )";
          $provider_update=$connection->createCommand($query2);
          $provider_update->bindValue(':utility_id',$utility_id);
          $provider_update->bindValue(':provider_name',$value['BILLER_NAME']);
          $provider_update->bindValue(':fields',$value['FIELDNAMES']);
          $provider_update->bindValue(':biller_master_id',$value['BILLER_MASTER_ID']);
          $provider_update->bindValue(':validations',$value['VALIDATION']);
          $provider_update_data = $provider_update->execute();
        }
      }
    }
	
	 public function api_call($url,$api_data,$wallet=""){
    $curl = curl_init($url);
  	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
  	$user_agent ='Mozilla/5.0 (Windows NT 6.2; WOW64; 
    rv:28.0) Gecko/20100101 Firefox/28.0)';
  	$headers=array();
  	// $headers[] = 'User-Agent: '. $user_agent;
  	if($wallet){
          $headers[] = 'Content-Type: multipart/form-data';
     } else {
          $headers[] = 'Content-Type: application/json';
    }
  	//$headers[] = 'Content-Type: application/json';
	$headers[] = 'Cache-Control: no-cache';
  	curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS,$api_data);
    $curl_response = curl_exec($curl);
    curl_close($curl);
    // print_r($curl_response);
    // exit;
    return json_decode($curl_response,true);
  }
	
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
	
}
