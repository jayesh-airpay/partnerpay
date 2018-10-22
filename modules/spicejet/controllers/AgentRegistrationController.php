<?php

namespace app\modules\spicejet\controllers;
use Yii;
use yii\web\UploadedFile;
use yii\base\Hcontroller;
use app\helpers\Checksum;
use app\helpers\generalHelper;
class AgentRegistrationController extends HController
{
    public function actionIndex()
    {
		/*$connection = Yii::$app->db;
        $query="SELECT BANK_NAME,BANK_CODE FROM tbl_bank";
        $bank = $connection->createCommand($query);
        $bank_data = $bank->queryAll();*/
		return $this->render('registration');
        
    }
	
	public function actionGetpannumber(){
        $connection = Yii::$app->db;
        $pan_id = Yii::$app->request->post('pan_id');
        $result_data =array();
        $query="SELECT AGENT_DETAILS_ID FROM tbl_agent_details WHERE PAN_NO LIKE :pan_id";
        $pan_data = $connection->createCommand($query);
        $pan_data->bindValue(':pan_id','%'.Yii::$app->request->post('pan_id').'%');
		
        $pan_id_data = $pan_data->queryAll();
		if(empty($pan_id_data))
		{
			$result_data['status'] = 0;
		}
		else
		{
			$result_data['status'] = 1;
		}
        echo json_encode($result_data);
    }
	
	public function actionGetbankdata(){
        $connection = Yii::$app->db;
        $response = array();
        $query="SELECT BANK_NAME,BANK_CODE FROM tbl_bank";
		$bank = $connection->createCommand($query);
        $bank_data = $bank->queryAll();
		$response['status'] = true;
		$response['result'] = $bank_data;
		//var_dump(json_encode($response));exit;
        echo json_encode($response);
    }
   //to save database
   public function actionSavedata(){
	   
	    $data = [];
		$is_error = false;
        $message = [];
		$message_error = [];
		$airpay_mid = '19378';
		$airpay_user_name = '5610027';
		$airpay_password_value =  'A3IEpPKn';
		$airpay_secrete_key = '5q9M2W1uKe67B3Ab';
	    $connection = Yii::$app->db; 
		$token = array();
		$carduniquecode = array();
		
		//call all api
		$count_value = Yii::$app->request->post('save_card_count');
		$count_len = (int)trim($count_value);
		for($j=0;$j<=$count_len;$j++)
		   {
			   $payment_card_type = Yii::$app->request->post('select_card_type_'.$j);
			   $card_nickname_api = Yii::$app->request->post('card_nick_name_'.$j);
			   $card_number_api = Yii::$app->request->post('card_number_'.$j);
			   $expiry_year_api = Yii::$app->request->post('card_exp_year_'.$j);
			   $expiry_month_api = Yii::$app->request->post('card_exp_month_'.$j);
			   //$card_cvv = Yii::$app->request->post('cvv_number_'.$j);
			   $email_id_api = Yii::$app->request->post('email_id');
		       $phone_id_api = Yii::$app->request->post('phone_id');
			   $bank_id = Yii::$app->request->post('select_bank_'.$j);
			  
			    if(empty($bank_id))
			   {
				      $checksumobj =  new Checksum();
					  $privatekey= $checksumobj->encrypt($airpay_user_name.":|:".$airpay_password_value, $airpay_secrete_key);
					  $savecard_url = 'https://ca.airpay.co.in/api.php';
					  $savecard_data = [
						'action' => 'saveCard',
						'type' => 'consumer',
						'respType' => 'json',
						'mercid' => $airpay_mid,
						'privatekey' => $privatekey,
						'token' => '',
						'cardnumber' => $card_number_api,
						'cardholdername' => $card_nickname_api,
						'expirymonth' => $expiry_month_api,
						'expiryyear' => $expiry_year_api,
						'email' => $email_id_api,
						'mobile' => $phone_id_api
                    ];
			
                    $general_helper  =  new generalHelper();
                    //$savecard_result  =  $general_helper->sendDataOverPost($savecard_url, $savecard_data, 'POST', $timeout = 30, $port = 443);
			        //var_dump($savecard_result);exit;	
                  $savecard_result  = '{
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
				$save_card_api_response  =  json_decode($savecard_result,true);
				if($save_card_api_response['status'] != '200') {
					$is_error = true;
					$message_error['card_number_'.$card_number_api] =  'save card api failed for card number '.$card_number_api;
				}
				else{
					if($save_card_api_response['result']['token'] != null)
					{
					  $token[$j] = $save_card_api_response['result']['token'];
					}
					else{
						$token[$j] = '';
					}
					$carduniquecode[$j] = $save_card_api_response['result']['carduniquecode'];
				}				
			   }
		   }
		   
		   if($is_error){
			    $data  = ['Result' => "Fail", "Message" => $message_error];
		   }
		   else{
			   /*var_dump($token[0]);
			   var_dump($carduniquecode[0]);*/
			  // register user
			  $PARTNER_ID_VALUE = '0';
			  $AGENT_GROUP_ID = '0';
		      $AGENT_ID = '0';
		      $PASSWORD = '0';
		      $AGENT_ONBOARD_STATUS = '0';
		      $AGENT_STATUS = '0';
		      $company_name_id = Yii::$app->request->post('company_name_id');
		      $business_reg_num_id = Yii::$app->request->post('business_reg_num_id');
		      $status_value = implode(",",Yii::$app->request->post('status_value'));
		      $address_value_id = Yii::$app->request->post('address_value_id');
		      $city_id = Yii::$app->request->post('city_id');
		      $state_id = Yii::$app->request->post('state_id');
		      $country_id = Yii::$app->request->post('country_id');
		      $pin_id = Yii::$app->request->post('pin_id');
		      $airport_id = Yii::$app->request->post('airport_id');
		      $email_id = Yii::$app->request->post('email_id');
		      $phone_id = Yii::$app->request->post('phone_id');
		      $fax_id = Yii::$app->request->post('fax_id');
		      $permant_account_id = Yii::$app->request->post('permant_account_id');
		      $pan_number_id = Yii::$app->request->post('pan_number_id');
		      $partner_id = Yii::$app->request->post('partner_id');
		      $partner_position_id = Yii::$app->request->post('partner_position_id');
		      $partner_contact_id = Yii::$app->request->post('partner_contact_id');
		      $partner_email_id = Yii::$app->request->post('partner_email_id');
		      $staff_id = Yii::$app->request->post('staff_id');
		      $staff_position_id = Yii::$app->request->post('staff_position_id');
		      $staff_contact_id = Yii::$app->request->post('staff_contact_id');
		      $staff_email_id = Yii::$app->request->post('staff_email_id');
		
		      $add_user_query = "INSERT INTO tbl_agent_details(PARTNER_ID, COMPANY_NAME, BUSINESS_REG_NO, ADDRESS, LEGAL_STATUS, CITY, STATE, COUNTRY, PINCODE, NEAREST_AIRPORT, EMAIL, PHONE, FAX, IATA_NO, PAN_NO, PROPRIETOR_NAME, PROPRIETOR_POSITION, PROPRIETOR_MOBILE, PROPRIETOR_EMAIL, STAFF_NAME, STAFF_POSITION, STAFF_MOBILE, STAFF_EMAIL, AGENT_GROUP_ID, AGENT_ID, PASSWORD, AGENT_ONBOARD_STATUS, AGENT_STATUS, CREATED_ON) VALUES (:PARTNER_ID_VALUE, :company_name_id, :business_reg_num_id, :address_value_id, :status_value, :city_id, :state_id, :country_id, :pin_id, :airport_id, :email_id, :phone_id, :fax_id, :permant_account_id, :pan_number_id, :partner_id,:partner_position_id, :partner_contact_id, :partner_email_id, :staff_id, :staff_position_id, :staff_contact_id, :staff_email_id, :AGENT_GROUP_ID, :AGENT_ID, :PASSWORD, :AGENT_ONBOARD_STATUS, :AGENT_STATUS, :created_on)";
	          $add_user = $connection->createCommand($add_user_query);
		      $add_user->bindValue(':PARTNER_ID_VALUE','1');
		      $add_user->bindValue(':company_name_id',$company_name_id);
		      $add_user->bindValue(':business_reg_num_id',$business_reg_num_id);
		      $add_user->bindValue(':address_value_id',$address_value_id);
		      $add_user->bindValue(':status_value','1');
		      $add_user->bindValue(':city_id',$city_id);
		      $add_user->bindValue(':state_id',$state_id);
		      $add_user->bindValue(':country_id',$country_id);
		      $add_user->bindValue(':pin_id',$pin_id);
		      $add_user->bindValue(':airport_id',$airport_id);
		      $add_user->bindValue(':email_id',$email_id);
		      $add_user->bindValue(':phone_id',$phone_id);
		      $add_user->bindValue(':fax_id',$fax_id);
		      $add_user->bindValue(':permant_account_id',$permant_account_id);
		      $add_user->bindValue(':pan_number_id',$pan_number_id);
		      $add_user->bindValue(':partner_id',$partner_id);
		      $add_user->bindValue(':partner_position_id',$partner_position_id);
		      $add_user->bindValue(':partner_contact_id',$partner_contact_id);
		      $add_user->bindValue(':partner_email_id',$partner_email_id);
		      $add_user->bindValue(':staff_id',$staff_id);
		      $add_user->bindValue(':staff_position_id',$staff_position_id);
		      $add_user->bindValue(':staff_contact_id',$staff_contact_id);
		      $add_user->bindValue(':staff_email_id',$staff_email_id);
		      $add_user->bindValue(':AGENT_GROUP_ID','0');
		      $add_user->bindValue(':AGENT_ID','0');
		      $add_user->bindValue(':PASSWORD','0');
		      $add_user->bindValue(':AGENT_ONBOARD_STATUS','0');
		      $add_user->bindValue(':AGENT_STATUS','0');
		      $add_user->bindValue(':created_on',time());
		      $add_user->execute();
              $add_user_status = $connection->getLastInsertID();
			   if($add_user_status)
				{
					$agent_payment_config_id = '';
					$data_insert_count = '';
					for($i=0;$i<=$count_len;$i++)
					{
						$token_value = '';
					    $carduniquecode_value = '';
						
						$token_value = $token[$i];
					    $carduniquecode_value = $carduniquecode[$i];
						/*var_dump($token[$i]);
			            var_dump($carduniquecode[$i]);exit;*/
						$agent_payment_config_id = '';
						
						$payment_card_type = Yii::$app->request->post('select_card_type_'.$i);
						$card_nickname = Yii::$app->request->post('card_nick_name_'.$i);
						$card_number = Yii::$app->request->post('card_number_'.$i);
						$expiry_year = Yii::$app->request->post('card_exp_year_'.$i);
						$expiry_month = Yii::$app->request->post('card_exp_month_'.$i);
						$card_cvv = Yii::$app->request->post('cvv_number_'.$i);
						$bank_id = Yii::$app->request->post('select_bank_'.$i);
						/*$token = '';
						$card_unique_code = '';*/
						/*var_dump($token[0]);
						var_dump($card_unique_code[0]);exit;*/
						$add_card_query = "INSERT INTO tbl_agent_payment_config(AGENT_DETAILS_ID, GROUP_ID, CARD_TYPE, CARD_NUMBER, NAME, EXPIRY_YEAR, EXPIRY_MONTH, CVV, TOKEN_NO, CARD_UNIQUE_CODE, STATUS, CREATED_ON, BANK_ID) VALUES (:agent_detail_id, :group_id, :card_type, :card_number, :name, :expiry_year, :expiry_month, :cvv, :token, :card_unique_code, :status, :created_on, :bank_id)";
						$add_card = $connection->createCommand($add_card_query);
						$add_card->bindValue(':agent_detail_id','1');
						$add_card->bindValue(':group_id','1');
						$add_card->bindValue(':card_type',!empty($payment_card_type)?$payment_card_type:'');
						$add_card->bindValue(':card_number',!empty($card_number)?$card_number:'');
						$add_card->bindValue(':name',!empty($card_nickname)?$card_nickname:'');
						$add_card->bindValue(':expiry_year',!empty($expiry_year)?$expiry_year:'');
						$add_card->bindValue(':expiry_month',!empty($expiry_month)?$expiry_month:'');
						$add_card->bindValue(':cvv',!empty($card_cvv)?$card_cvv:'');
						$add_card->bindValue(':token',$token_value);
						$add_card->bindValue(':card_unique_code',$carduniquecode_value);
						$add_card->bindValue(':status','0');
						$add_card->bindValue(':created_on',time());
						$add_card->bindValue(':bank_id',!empty($bank_id)?$bank_id:'');
						$add_card->execute();
						$agent_payment_config_id = $connection->getLastInsertID();
						if(!empty($agent_payment_config_id))
						{
							$data_insert_count = $i;
						}	
					}
					//to check final validation
					if( $data_insert_count == $count_value)
					{
						$data  = ['Result' => "success", "Message" => 'all data is saved'];
					}
					else
					{
						$data  = ['Result' => "Fail", "Message" => 'cards data insert mismatched'];
					}
				}	
		   }
		
		
	   
	    header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($data);
   }
}
