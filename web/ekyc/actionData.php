<?php
if(isset($_POST['action']))
{
	
	if($_POST['action'] == 'companySearch')
	{
		$company_name = $_POST['company_name'];
		/*$result_array = array();
		$result_new = array();*/
		$header = array(
                    'Accept: application/json',
					'x-api-version: 1.2',
                    'x-api-key: Vljv4ZoNATaq1OJLPk6oA2kvT6mUxEJl7E8IiC3b'
                );

                $ch = curl_init('https://api.probe42.in/probe_lite/companies?limit=25&filters=%7B%22nameStartsWith%22%3A%22'.$company_name.'%22%7D');
                
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $result = curl_exec($ch);
				//$result = '{"metadata":{"api_version":"1.2"},"data":{"companies":[{"legal_name":"AIRPAY PAYMENT SERVICES PRIVATE LIMITED","cin":"U72900MH2012PTC229364"}],"has_more":false,"total_count":1}}';
				$result_array = json_decode($result,true);
				$response  = array(
				 'result' => 'Success',
				 'legal_name'=>$result_array['data']['companies'][0]['legal_name'],
                 'cin_number'=>$result_array['data']['companies'][0]['cin'],		 
				);
				/*var_dump($result_array['data']['companies'][0]['legal_name']);
				exit;*/
				
				if(!empty($response['cin_number']))
				{
					$cin_number = $response['cin_number'];
					$ch = curl_init('https://api.probe42.in/probe_lite/companies/'.$cin_number.'');
                
                   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

                   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                   curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

				   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				   
				   $result1 = curl_exec($ch);
				   /*$result1 = '{"metadata":{"api_version":"1.2","last_updated":"2018-02-08"},"data":{"company":{"authorized_capital":13500000,"cin":"U72900MH2012PTC229364","efiling_status":"Active","incorporation_date":"2012-04-09","legal_name":"AIRPAY PAYMENT SERVICES PRIVATE LIMITED","paid_up_capital":11122070,"sum_of_charges":0,"registered_address":{"address_line1":"104 SIR VITHALDAS CHAMBERS,","address_line2":"16 MUMBAI SAMACHAR MARG, FORT.","city":"MUMBAI","pincode":"400023","state":"MAHARASHTRA"},"classification":"Private Limited Indian Non-Government Company","status":"Unlisted","next_cin":null,"last_agm_date":"2017-09-28","last_filing_date":"2017-03-31","email":"kunal@airpay.co.in"}}}
';*/
                   
				   /*var_dump($result);
				   exit;*/
				   $result_new = json_decode($result1,true);
				   $address_value = $result_new['data']['company']['registered_address']['address_line1'].''.$result_new['data']['company']['registered_address']['address_line2'];
				   /*var_dump($result_new);
				   exit;*/
				   $response_new = array(
				   'result' => 'Success',
				   'legal_name'=>$result_new['data']['company']['legal_name'],
                   'cin_number'=>$result_new['data']['company']['cin'],
				   'authorized_capital'=>$result_new['data']['company']['authorized_capital'],
				   'efiling_status'=>$result_new['data']['company']['efiling_status'],
				   'incorporation_date'=>$result_new['data']['company']['incorporation_date'],
				   'paid_up_capital'=>$result_new['data']['company']['paid_up_capital'],
				   'classification'=>$result_new['data']['company']['classification'],
				   'status'=>$result_new['data']['company']['status'],
				   'last_agm_date'=>$result_new['data']['company']['last_agm_date'],
				   'last_filing_date'=>$result_new['data']['company']['last_filing_date'],
				   'email'=>$result_new['data']['company']['email'],
				   'address'=>$address_value,
				   'city'=>$result_new['data']['company']['registered_address']['city'],
				   'pincode'=>$result_new['data']['company']['registered_address']['pincode'],
				   'state'=>$result_new['data']['company']['registered_address']['state'],
				   );
				   /*var_dump($response_new);
				   exit;*/
				   header('Content-Type: application/json');
                  echo json_encode($response_new);
				   
					
				}
				/*header('Content-Type: application/json');
                echo json_encode($response);*/
				
	}
}
?>