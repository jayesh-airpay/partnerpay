<?php

namespace app\modules\spicejet\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\base\Hcontroller;
use app\helpers\Checksum;

class MerchantController extends HController
{
    public function actionIndex()
    {
        $connection = Yii::$app->db;
        $query="SELECT COMPANY_NAME,AGENT_DETAILS_ID,STAFF_NAME,EMAIL,PHONE FROM tbl_agent_details WHERE AGENT_ONBOARD_STATUS = '0' LIMIT 0, 2";
        $agent = $connection->createCommand($query);
        $agent_data = $agent->queryAll();
        $count_query = "SELECT COUNT(AGENT_DETAILS_ID) as total FROM tbl_agent_details WHERE AGENT_ONBOARD_STATUS = '0'";
        $agent_count = $connection->createCommand($count_query);
        $agent_count_data = $agent_count->queryAll();
        return $this->render('index',array('agent_details'=>$agent_data,'agent_count'=>$agent_count_data));
    }

    public function actionReject(){
        $connection = Yii::$app->db;
        $query="UPDATE tbl_agent_details SET AGENT_ONBOARD_STATUS='2' where AGENT_DETAILS_ID=:agent_details_id";
        $agent = $connection->createCommand($query);
        $agent->bindValue(':agent_details_id',Yii::$app->request->post('agent_details_id'));
        $agent_data = $agent->execute();
        if($agent_data){
            echo true;
        } else {
            echo false;
        }
    }

    public function actionGetagents(){
        $connection = Yii::$app->db;
        $page = Yii::$app->request->post('page');
        $offset=($page-1)*2;
        $agent_list_data =array();
        $query="SELECT COMPANY_NAME,AGENT_DETAILS_ID,STAFF_NAME,EMAIL,PHONE FROM tbl_agent_details WHERE AGENT_ONBOARD_STATUS = '0' AND COMPANY_NAME LIKE :company_name AND STAFF_NAME LIKE :staff_name AND PHONE LIKE :phone AND EMAIL LIKE :email LIMIT :offset, 2";
        $agent = $connection->createCommand($query);
        $agent->bindValue(':company_name','%'.Yii::$app->request->post('company').'%');
        $agent->bindValue(':staff_name','%'.Yii::$app->request->post('name').'%');
        $agent->bindValue(':phone','%'.Yii::$app->request->post('phone_number').'%');
        $agent->bindValue(':email','%'.Yii::$app->request->post('mail_id').'%');
        $agent->bindValue(':offset',$offset);
        $agent_data = $agent->queryAll();
        $agent_list_data['list_data'] = $agent_data;
        $count_query = "SELECT COUNT(AGENT_DETAILS_ID) as total FROM tbl_agent_details WHERE AGENT_ONBOARD_STATUS = '0' AND COMPANY_NAME LIKE :company_name AND STAFF_NAME LIKE :staff_name AND PHONE LIKE :phone AND EMAIL LIKE :email";
        $agent_count = $connection->createCommand($count_query);
        $agent_count->bindValue(':company_name','%'.Yii::$app->request->post('company').'%');
        $agent_count->bindValue(':staff_name','%'.Yii::$app->request->post('name').'%');
        $agent_count->bindValue(':phone','%'.Yii::$app->request->post('phone_number').'%');
        $agent_count->bindValue(':email','%'.Yii::$app->request->post('mail_id').'%');
        $agent_count_data = $agent_count->queryAll();
        $agent_list_data['count_data'] = $agent_count_data;
        echo json_encode($agent_list_data);
    }

    public function actionApprove(){
        $connection = Yii::$app->db;
        $query="UPDATE tbl_agent_details SET AGENT_ID=:agent_id,PASSWORD=:password,AGENT_ONBOARD_STATUS='1' WHERE AGENT_DETAILS_ID=:agent_details_id";
        $agent = $connection->createCommand($query);
        $agent->bindValue(':agent_id',Yii::$app->request->post('agents_id'));
        $agent->bindValue(':password',Yii::$app->request->post('password'));
        $agent->bindValue(':agent_details_id',Yii::$app->request->post('agent_details_id'));
        $agent_data = $agent->execute();
        if($agent_data){
            $query1="SELECT PROPRIETOR_NAME,CITY,PROPRIETOR_MOBILE,STAFF_EMAIL,PROPRIETOR_EMAIL,PHONE FROM tbl_agent_details WHERE AGENT_DETAILS_ID = :agent_details_id";
            $agent_to_partner = $connection->createCommand($query1);
            $agent_to_partner->bindValue(':agent_details_id',Yii::$app->request->post('agent_details_id'));
            $agent_to_partner_data = $agent_to_partner->queryAll();
            $name = explode(' ',$agent_to_partner_data[0]['STAFF_EMAIL']);
            if($name[1] == null){
                $name[1]="";
            }
            $query2="INSERT INTO tbl_partner_master (PARTNER_NAME,PARTNER_LOCATION,MERCHANT_ID,EMAIL_ID,MOBILE,PARTNER_STATUS,CREATED_ON) VALUES(:partner_name,:partner_location,'53',:email,:mobile,'E',:created_on)";
            $add_to_partner = $connection->createCommand($query2);
            $add_to_partner->bindValue(':partner_name',$agent_to_partner_data[0]['PROPRIETOR_NAME']);
            $add_to_partner->bindValue(':partner_location',$agent_to_partner_data[0]['CITY']);
            $add_to_partner->bindValue(':email',$agent_to_partner_data[0]['PROPRIETOR_EMAIL']);
            $add_to_partner->bindValue(':mobile',$agent_to_partner_data[0]['PROPRIETOR_MOBILE']);
            $add_to_partner->bindValue(':created_on',strtotime(date()));
            $add_to_partner_status = $add_to_partner->execute();
            $partner_id = $connection->getLastInsertID();
            $password_without = $this->generate_password();
            $password = Yii::$app->security->generatePasswordHash($password_without);
            $query3="INSERT INTO tbl_user_master (MERCHANT_ID,PARTNER_ID,USER_TYPE,EMAIL,PASSWORD,FIRST_NAME,LAST_NAME,MOBILE,USER_STATUS,CREATED_ON) VALUES ('53',:partner_id,'partner',:email,:password,:fname,:lname,:mobile,'E',:created_on)";
            $add_to_user = $connection->createCommand($query3);
            $add_to_user->bindValue(':partner_id',$partner_id);
            $add_to_user->bindValue(':email',$agent_to_partner_data[0]['PROPRIETOR_EMAIL']);
            $add_to_user->bindValue(':password',$password);
            $add_to_user->bindValue(':fname',$name[0]);
            $add_to_user->bindValue(':lname',$name[1]);
            $add_to_user->bindValue(':mobile',$agent_to_partner_data[0]['PROPRIETOR_MOBILE']);
            $add_to_user->bindValue(':created_on',strtotime(date()));
            $add_to_user_status = $add_to_user->execute();
            if($add_to_user_status){
                echo $password_without.'true';
            }else{
                echo false;
            }
        }
    }
    
    public function generate_password()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $result = '';
        for ($i = 0; $i < 5; $i++) {
            $result .= $characters[mt_rand(0, 61)];
        }

        return $result;
    }

    public function actionGetgroups(){
        $connection = Yii::$app->db;
        $group_list_data =array();
        $offset=(Yii::$app->request->post('page')-1)*2;
        $query="SELECT AGENT_GROUP_ID,GROUP_NAME,STATUS,CREATED_DATE FROM tbl_agent_group LIMIT :offset, 2";
        $group = $connection->createCommand($query);
        $group->bindValue(':offset',$offset);
        $group_data = $group->queryAll();
        $count_query = "SELECT COUNT(AGENT_GROUP_ID) as total FROM tbl_agent_group";
        $group_count = $connection->createCommand($count_query);
        $group_count_data = $group_count->queryAll();
        $group_list_data['list_data'] = $group_data;
        $group_list_data['count_data'] = $group_count_data;
        echo json_encode($group_list_data);
    }

    public function actionGroupstatus(){
        $connection = Yii::$app->db;
        $query="UPDATE tbl_agent_group SET STATUS=:group_status,MODIFIED_DATE=:modified_on where AGENT_GROUP_ID=:agent_group_id";
        $group_status_update = $connection->createCommand($query);
        $group_status_update->bindValue(':group_status',Yii::$app->request->post('group_status'));
        $group_status_update->bindValue(':agent_group_id',Yii::$app->request->post('id'));
        $group_status_update->bindValue(':modified_on',strtotime(date('d-m-Y')));
        $group_status_update_data = $group_status_update->execute();
        if($group_status_update_data){
            echo true;
        }else{
            echo false;
        }
    }

    public function actionAddgroup(){
        $connection = Yii::$app->db;
        $query="INSERT INTO tbl_agent_group (GROUP_NAME,STATUS,CREATED_DATE) VALUES(:group_name,:status,:created_on)";
        $add_group = $connection->createCommand($query);
        $add_group->bindValue(':group_name',Yii::$app->request->post('name'));
        $add_group->bindValue(':status',Yii::$app->request->post('status'));
        $add_group->bindValue(':created_on',strtotime(date('d-m-Y')));
        $add_group_data = $add_group->execute();
        if($add_group_data){
            echo true;
        }else{
            echo false;
        }
    }

    public function actionGetagentdetail(){
        $connection = Yii::$app->db;
        $query="SELECT COMPANY_NAME,AGENT_DETAILS_ID,STATE,CITY,PAN_NO FROM tbl_agent_details WHERE AGENT_ONBOARD_STATUS = '1' AND AGENT_ID=:agent_id";
        $add_group = $connection->createCommand($query);
        $add_group->bindValue(':agent_id',Yii::$app->request->post('id'));
        $add_group_data = $add_group->queryAll();
        if(sizeof($add_group_data)>0){
            $add_group_data[0]['status']=1;
            echo json_encode($add_group_data[0]);
        } else {
            echo json_encode(['status'=>0]);
        }
    }

    public function actionMapagent(){
        $connection = Yii::$app->db;
        $query="UPDATE tbl_agent_details SET AGENT_GROUP_ID=:agent_group_id,UPDATED_ON=:modified_on,AGENT_STATUS=:agent_status where AGENT_DETAILS_ID=:agent_details_id";
        $group_agent_update = $connection->createCommand($query);
        $group_agent_update->bindValue(':agent_group_id',Yii::$app->request->post('groupid'));
        $group_agent_update->bindValue(':agent_status',Yii::$app->request->post('agent_group_status'));
        $group_agent_update->bindValue(':modified_on',strtotime(date('d-m-Y')));
        $group_agent_update->bindValue(':agent_details_id',Yii::$app->request->post('id'));
        $group_agent_update_data = $group_agent_update->execute();
        if($group_agent_update_data){
            echo true;
        }else{
            echo false;
        }
    }

    public function actionGetgrouplist(){
        $connection = Yii::$app->db;
        $query="SELECT AGENT_GROUP_ID,GROUP_NAME FROM tbl_agent_group WHERE STATUS='1'";
        $group = $connection->createCommand($query);
        $group_data = $group->queryAll();
        echo json_encode($group_data);
    }

    public function actionAddgrouplimit(){
        $connection = Yii::$app->db;
        $query="INSERT INTO tbl_agent_group_payment_limit (GROUP_ID,NETBANKING_LIMIT,CREDIT_CARD_UPPER_LIMIT,CREDIT_CARD_LOWER_LIMIT,DEBIT_CARD_LIMIT,OVERALL_GROUP_LINIT,CREATED_ON) VALUES(:group_id,:netbanking_limit,:credit_card_upper_limit,:credit_card_lower_Limit,:debit_card_limit,:overall_group_limit,:created_on)";
        $add_to_limit = $connection->createCommand($query);
        $add_to_limit->bindValue(':group_id',Yii::$app->request->post('id'));
        $add_to_limit->bindValue(':netbanking_limit',Yii::$app->request->post('nb'));
        $add_to_limit->bindValue(':credit_card_upper_limit',Yii::$app->request->post('ccul'));
        $add_to_limit->bindValue(':credit_card_lower_Limit',Yii::$app->request->post('ccll'));
        $add_to_limit->bindValue(':debit_card_limit',Yii::$app->request->post('dc'));
        $add_to_limit->bindValue(':overall_group_limit',Yii::$app->request->post('overall'));
        $add_to_limit->bindValue(':created_on',strtotime(date()));
        $add_to_limit_status = $add_to_limit->execute();
        if($add_to_limit_status){
            echo true;
        }else{
            echo false;
        }
    }

    public function actionGetcards(){
        $connection = Yii::$app->db;
        $page = Yii::$app->request->post('page');
        $offset=($page-1)*2;
        $card_list_data =array();
        $query="SELECT ad.EMAIL,ad.AGENT_ID,pc.AGENT_PAYMENT_CONFIG_ID,pc.CARD_NUMBER FROM tbl_agent_payment_config as pc JOIN tbl_agent_details as ad on pc.AGENT_DETAILS_ID=ad.AGENT_DETAILS_ID WHERE ad.AGENT_ONBOARD_STATUS = '1' AND pc.STATUS='0' AND ad.AGENT_ID LIKE :agent_id AND ad.EMAIL LIKE :email AND pc.CARD_NUMBER LIKE :card_number LIMIT :offset, 2";
        $card = $connection->createCommand($query);
        $card->bindValue(':offset',$offset);
        $card->bindValue(':agent_id','%'.Yii::$app->request->post('agent_id').'%');
        $card->bindValue(':email','%'.Yii::$app->request->post('email').'%');
        $card->bindValue(':card_number','%'.Yii::$app->request->post('payment_search').'%');
        $card_data = $card->queryAll();
        $card_list_data['list_data'] = $card_data;
        $count_query = "SELECT COUNT(AGENT_PAYMENT_CONFIG_ID) as total FROM tbl_agent_payment_config as pc JOIN tbl_agent_details as ad on pc.AGENT_DETAILS_ID=ad.AGENT_DETAILS_ID WHERE ad.AGENT_ONBOARD_STATUS = '1' AND pc.STATUS='0' AND ad.AGENT_ID LIKE :agent_id AND ad.EMAIL LIKE :email AND pc.CARD_NUMBER LIKE :card_number";
        $card_count = $connection->createCommand($count_query);
        $card_count->bindValue(':agent_id','%'.Yii::$app->request->post('agent_id').'%');
        $card_count->bindValue(':email','%'.Yii::$app->request->post('email').'%');
        $card_count->bindValue(':card_number','%'.Yii::$app->request->post('payment_search').'%');
        $card_count_data = $card_count->queryAll();
        $card_list_data['count_data'] = $card_count_data;
        echo json_encode($card_list_data);
    }
    
    public function actionRejectcard(){
        $connection = Yii::$app->db;
        $query="UPDATE tbl_agent_payment_config SET STATUS='2' where AGENT_PAYMENT_CONFIG_ID=:agent_payment_config_id";
        $agent = $connection->createCommand($query);
        $agent->bindValue(':agent_payment_config_id',Yii::$app->request->post('AGENT_PAYMENT_CONFIG_ID'));
        $agent_data = $agent->execute();
        if($agent_data){
            echo true;
        } else {
            echo false;
        }
    }

    public function actionCheckagentingroup(){
        $connection = Yii::$app->db;
        $query="SELECT ad.EMAIL,ad.AGENT_ID,pc.AGENT_PAYMENT_CONFIG_ID,pc.GROUP_ID,pc.CARD_NUMBER FROM tbl_agent_payment_config as pc JOIN tbl_agent_details as ad on pc.AGENT_DETAILS_ID=ad.AGENT_DETAILS_ID WHERE pc.GROUP_ID != '0' AND pc.AGENT_PAYMENT_CONFIG_ID = :agent_payment_config_id";
        $card_details = $connection->createCommand($query);
        $card_details->bindValue(':agent_payment_config_id',Yii::$app->request->post('agent_payment_config_id'));
        $card_details_data = $card_details->queryAll();
        if(sizeof($card_details_data)>0){
            $card_details_data[0]['status']=1;
            echo json_encode($card_details_data[0]);
        } else {
            echo json_encode(['status'=>0]);
        }
    }

    public function actionApprovecard(){
        $connection = Yii::$app->db;
        $query="UPDATE tbl_agent_group SET GROUP_MOBILE=:group_mobile where AGENT_GROUP_ID=:group_id";
        $group_mobile = $connection->createCommand($query);
        $group_mobile->bindValue(':group_id',Yii::$app->request->post('groupid'));
        $group_mobile->bindValue(':group_mobile',Yii::$app->request->post('mobile_number'));
        $group_mobile_data = $group_mobile->execute();
        $query1="UPDATE tbl_agent_payment_config SET STATUS='1' where AGENT_PAYMENT_CONFIG_ID=:agent_payment_config_id";
        $card_approve = $connection->createCommand($query1);
        $card_approve->bindValue(':agent_payment_config_id',Yii::$app->request->post('id'));
        $card_approve_data = $card_approve->execute();
        if($card_approve_data){
            echo true;
        }else{
            echo false;
        }
    }

    public function actionGetaccounts(){
        $connection = Yii::$app->db;
        $page = Yii::$app->request->post('page');
        $offset=($page-1)*2;
        $account_list_data =array();
        $query="SELECT USER_ID,FIRST_NAME,LAST_NAME,EMAIL,CREATED_ON FROM tbl_user_master WHERE MERCHANT_ID = '53' AND 	PARTNER_ID = '0' LIMIT :offset, 2";
        $account = $connection->createCommand($query);
        $account->bindValue(':offset',$offset);
        $account_data = $account->queryAll();
        $account_list_data['list_data'] = $account_data;
        $count_query = "SELECT COUNT(USER_ID) as total FROM tbl_user_master WHERE MERCHANT_ID = '53' AND PARTNER_ID = '0'";
        $account_count = $connection->createCommand($count_query);
        $account_count_data = $account_count->queryAll();
        $account_list_data['count_data'] = $account_count_data;
        echo json_encode($account_list_data);
    }

    public function actionUpdateaccount(){
        $connection = Yii::$app->db;
        $query1="UPDATE tbl_user_master SET FIRST_NAME=:fname,LAST_NAME=:lname,MOBILE=:mob,EMAIL=:mail where USER_ID=:userid";
        $account_update = $connection->createCommand($query1);
        $account_update->bindValue(':fname',Yii::$app->request->post('firstname'));
        $account_update->bindValue(':lname',Yii::$app->request->post('lastname'));
        $account_update->bindValue(':mob',Yii::$app->request->post('mobile_number'));
        $account_update->bindValue(':mail',Yii::$app->request->post('email'));
        $account_update->bindValue(':userid',Yii::$app->request->post('id'));
        $account_update_data = $account_update->execute();
        if($account_update_data){
            echo true;
        }else{
            echo false;
        }
    }

    public function actionAddaccount(){
        $connection = Yii::$app->db;
        $password_without = $this->generate_password();
        $password = Yii::$app->security->generatePasswordHash($password_without);
        $query3="INSERT INTO tbl_user_master (MERCHANT_ID,USER_TYPE,EMAIL,PASSWORD,FIRST_NAME,LAST_NAME,MOBILE,USER_STATUS,CREATED_ON) VALUES ('53','merchant',:email,:password,:fname,:lname,:mobile,'E',:created_on)";
        $add_to_user = $connection->createCommand($query3);
        $add_to_user->bindValue(':email',Yii::$app->request->post('email'));
        $add_to_user->bindValue(':password',$password);
        $add_to_user->bindValue(':fname',Yii::$app->request->post('firstname'));
        $add_to_user->bindValue(':lname',Yii::$app->request->post('lastname'));
        $add_to_user->bindValue(':mobile',Yii::$app->request->post('mobile_number'));
        $add_to_user->bindValue(':created_on',strtotime(date('d-m-Y')));
        $add_to_user_status = $add_to_user->execute();
        if($add_to_user_status){
            echo true;
        }else{
            echo false;
        }
    }
}
