<?php
if(isset($_POST['action']))
{
	
	if($_POST['action'] == 'companySearch')
	{
		$company_name = $_POST['company_name'];
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
				/*var_dump($result_array['data']['companies'][0]['legal_name']);
				exit;*/
				$response  = array(
				 'result' => 'Success',
				 'legal_name'=>$result_array['data']['companies'][0]['legal_name'],
                 'cin_number'=>$result_array['data']['companies'][0]['cin']		 
				);
				/*var_dump($response);
				exit;*/
				header('Content-Type: application/json');
                echo json_encode($response);
				
	}
}
?>