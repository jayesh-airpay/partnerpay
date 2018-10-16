<?php

namespace app\controllers;

use app\helpers\generalHelper;
use app\models\Group;
use app\models\GroupInvoice;
use app\models\GroupInvoiceMap;
use app\models\ImportPartner;
use app\models\IntexImportPartner;
use app\models\Invoice;
use app\models\MerchantMaster;
use app\models\UserMaster;
use Yii;
use app\models\Partner;
use app\models\PartnerSearch;
use yii\base\Hcontroller;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\CategoryMaster;
/**
 * PartnerController implements the CRUD actions for Partner model.
 */
class PartnerController extends Hcontroller
{
    public function behaviors()
    {
       /* if(Yii::$app->user->identity->USER_TYPE == 'sale')
        {
            echo "['view']";
        } else {
            echo "asdf";

        }

        exit;*/
        //var_dump(Yii::$app->user->identity->USER_TYPE);
        //var_dump((!Yii::$app->user->isGuest && Yii::$app->user->identity->USER_TYPE == 'sale')); exit;
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','update','create','view','delete'],
                //'only' => ['view'],
                'rules' => [
                   [
                        //'actions' => (Yii::$app->user->identity->USER_TYPE == 'sale')?['view']:['index','update','create','view','delete'],
                        'actions' => ['view','index'],
                        'allow' => (!Yii::$app->user->isGuest && (Yii::$app->user->identity->USER_TYPE == 'approver' || Yii::$app->user->identity->USER_TYPE == 'payment'))?false:true,
                        'roles' => ['@'],
                    ],
                    [
                        //'actions' => (Yii::$app->user->identity->USER_TYPE == 'sale')?['view']:['index','update','create','view','delete'],
                        'actions' => ['create','update'],
                        'allow' => (!Yii::$app->user->isGuest && (Yii::$app->user->identity->USER_TYPE == 'partner' || Yii::$app->user->identity->USER_TYPE == 'approver' || Yii::$app->user->identity->USER_TYPE == 'payment'))?false:true,
                        'roles' => ['@'],
                    ],
                    [
                        //'actions' => (Yii::$app->user->identity->USER_TYPE == 'sale')?['view']:['index','update','create','view','delete'],
                        'actions' => ['import'],
                        'allow' => (!Yii::$app->user->isGuest && (Yii::$app->user->identity->USER_TYPE == 'admin' || Yii::$app->user->identity->USER_TYPE == 'merchant'))?true:false,
                        'roles' => ['@'],
                    ],
                    [
                        //'actions' => (Yii::$app->user->identity->USER_TYPE == 'sale')?['view']:['index','update','create','view','delete'],
                        'actions' => ['get-approver-list'],
                        'allow' => (!Yii::$app->user->isGuest && Yii::$app->user->identity->USER_TYPE == 'admin')?true:false,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => false,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @description Lists all PARTNER models
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $mid = $this->merchant_id;
        $searchModel = new PartnerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Partner model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $items = CategoryMaster::find()->select(['CAT_NAME'])->indexBy('CAT_ID')->column();
    
        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $items
        ]);
    }

    /**
     * Creates a new Partner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Partner();
        $model->setScenario('insert');
    	$user_model = new UserMaster();
        //$model->SURCHARGES = 0;
       
       
        $model->INVOICE_EMAIL_TEMPLATE = "Dear Customer,
Greetings!
Please click on the following link to make the payment for your transaction reference id {{{invoice_number}}}
You can access the soft copy of your invoice reference for your reservation by clicking here: {{{invoice_guest_url}}}
You may pay through Credit Card, Debit Card or Net banking etc. using the above link.

Thank You!
Team Partnerpay";
        ;
        $model->INVOICE_SMS_TEMPLATE = "Dear Customer, Please click on the following link to make the payment for your transaction reference id {{{invoice_number}}} ({{{invoice_guest_url}}})
Thank you!
Team {{{partner_name}}}";


        $model->REMINDER_INVOICE_EMAIL_TEMPLATE = "Dear Customer,
Greetings!
Please click on the following link to make the payment for your transaction reference id {{{invoice_number}}}
You can access the soft copy of your invoice reference for your reservation by clicking here: {{{invoice_guest_url}}}
You may pay through Credit Card, Debit Card or Net banking etc. using the above link.
\nThank You!\nTeam Partnerpay";
        ;
        $model->REMINDER_INVOICE_SMS_TEMPLATE = "Dear Customer, Please click on the following link to make the payment for your transaction reference id {{{invoice_number}}} ({{{invoice_guest_url}}})
Thank you!\nTeam {{{partner_name}}}";

        if ($model->load(Yii::$app->request->post())) {
      
            $merchant_data = Partner::find()->select('MERCHANT_ID')->where(['MERCHANT_ID'=>$model->MERCHANT_ID,'GSTNUM' => $model->GSTNUM])->one();
          //  echo '<pre>';print_r($merchant_data);exit;
            if(count($merchant_data) > 1){
               $model->addError('GSTNUM', 'GST Number already exists.');
            }
            $model->LOGO = UploadedFile::getInstance($model,'LOGO');
            if($model->save())  {
                $user_model->USER_TYPE = 'partner';
                $user_model->USER_STATUS = "E";
                $user_model->MOBILE = $model->MOBILE;
                $user_model->MERCHANT_ID = $model->MERCHANT_ID;
                $user_model->PARTNER_ID = $model->PARTNER_ID;
                $user_model->EMAIL = $model->EMAIL_ID;
                $user_model->FIRST_NAME = $model->PARTNER_NAME;
                $user_model->LAST_NAME = $model->PARTNER_NAME;
                $generated_password= $this->generate_password();
                //$password = Yii::$app->security->generatePasswordHash($generated_password);
                $user_model->PASSWORD = $generated_password;
                $user_model->REPEAT_PASSWORD = $generated_password;
                if ($user_model->save()) {
                    $ghelper = new generalHelper();
                    $ghelper->sendMerchantDetails($user_model, $generated_password);
                        
                        foreach($model->CATEGORIES as $key => $val){
                        $connection = Yii::$app->getDb();
                        $command = $connection->createCommand("insert into tbl_partner_categories (PARTNER_ID,CAT_ID,CREATED) values ('$model->PARTNER_ID','$val','$t')");
                        $result = $command->execute();
                        }
                    return $this->redirect(['view', 'id' => $model->PARTNER_ID]);
                }
                //return $this->redirect(['view', 'id' => $model->PARTNER_ID]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Partner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);  
        $gstnum = $model->GSTNUM;     


        if ($model->load(Yii::$app->request->post())) {
            $merchant_data = Partner::find()->select('GSTNUM')->where(['MERCHANT_ID'=>$model->MERCHANT_ID,'GSTNUM' => $model->GSTNUM])->one();
            
            // if($merchant_data->GSTNUM != $gstnum){
            //    $model->addError('GSTNUM', 'GST Number already exists.');
            // }else{
                $model->LOGO = UploadedFile::getInstance($model,'LOGO');
                if($model->save())  {
                
                   $connection = Yii::$app->getDb();
                   $connection->createCommand("DELETE FROM tbl_partner_categories WHERE PARTNER_ID='$id'")->execute();
                   $post = Yii::$app->request->post();
                   $CATEGORIES = $post['Partner']['CATEGORIES'];
                   foreach($CATEGORIES as $key => $val){
                    $connection = Yii::$app->getDb();
                    $command = $connection->createCommand("insert into tbl_partner_categories (PARTNER_ID,CAT_ID,CREATED) values ('$model->PARTNER_ID','$val','$t')");
                    $result = $command->execute();
                   }
                   
                   $connection = Yii::$app->getDb();
                   $connection->createCommand("update tbl_user_master set USER_TYPE='partner' WHERE PARTNER_ID='$id'")->execute();
                   
                    return $this->redirect(['view', 'id' => $model->PARTNER_ID]);
                }
           // }
        
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Partner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Partner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Partner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Partner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
   
   public function actionImport()
    {
        $model = new ImportPartner();
        //$model->setScenario('import');
        if ($model->load(Yii::$app->request->post())) {
            $model->CSV = UploadedFile::getInstance($model, 'CSV');
            $ins_record = 0;
            $fl_record = 0;

            if ($model->validate()) {
                $extension = explode('.', $model->CSV->name);
                $ext = end($extension);
                $filename = md5(time() . $model->CSV->name) . '.' . $ext;
               // if ($model->CSV->saveAs(Yii::$app->basePath . '/web/uploads/csv/' . $filename)) {
                    if (($fp = fopen($model->CSV->tempName, "r")) !== false) {
                        $i = 0;
                        while ($data = fgetcsv($fp, 99999)) {
                            $importmodel = new Partner();

                            $i++;
                            if ($i == 1) {
                                continue;
                            }
                             if (Yii::$app->getUser()->identity->USER_TYPE == 'admin') {
                                $importmodel->MERCHANT_ID = Yii::$app->request->post('ImportPartner')["IMPORT_MERCHANT_ID"];
                                $importmodel->AIRPAY_MERCHANT_ID = trim($data[0]);
                                $importmodel->AIRPAY_USERNAME = trim($data[1]);
                                $importmodel->AIRPAY_PASSWORD = trim($data[2]);
                                $importmodel->AIRPAY_SECRET_KEY = trim($data[3]);
                                $importmodel->PARTNER_NAME = trim($data[4]);
                                $importmodel->PARTNER_LOCATION = trim($data[5]);
                                $importmodel->MOBILE = trim($data[6]);
                                $importmodel->PARTNER_STATUS = 'E';
                                $importmodel->VAT_TAX = trim($data[7]);
                                $importmodel->SURCHARGES = trim($data[8]);
                                $importmodel->SERVICE_TAX = trim($data[9]);
                                $image_name = $this->download_remote_file(trim($data[10]));
                                $importmodel->VENDOR_LOGO = $image_name;
                                $importmodel->VENDOR_REFERENCE_ID = trim($data[11]);
                                $importmodel->EMAIL_ID = trim($data[12]);
                                $importmodel->APPROVER_ID = $this->get_Approver_id(trim($data[13]));
                                $importmodel->ACCOUNT_HOLDER_NAME = trim($data[14]);
                                $importmodel->ACCOUNT_TYPE = trim($data[15]);
                                $importmodel->ACCOUNT_NUMBER = trim($data[16]);
                                $importmodel->IFSC_CODE = trim($data[17]);
                                $importmodel->BANK_NAME = trim($data[18]);
                                $importmodel->BRANCH = trim($data[19]);
                                $importmodel->PHONE_NO = trim($data[20]);
                                $importmodel->BANK_ADDRESS = trim($data[21]);
                                $importmodel->CITY = trim($data[22]);
                                $importmodel->STATE = trim($data[23]);
                                $importmodel->CORPORATE_PAN_CARD_NUMBER = trim($data[24]);

                            } else if (Yii::$app->getUser()->identity->USER_TYPE == 'merchant') {
                                
                                $importmodel->MERCHANT_ID = Yii::$app->request->post('ImportPartner')["IMPORT_MERCHANT_ID"];
                                //$importmodel->AIRPAY_MERCHANT_ID = trim($data[0]);
                                //$importmodel->AIRPAY_USERNAME = trim($data[1]);
                                //$importmodel->AIRPAY_PASSWORD = trim($data[2]);
                                //$importmodel->AIRPAY_SECRET_KEY = trim($data[3]);
                                $importmodel->PARTNER_NAME = trim($data[2]);
                                $importmodel->PARTNER_LOCATION = trim($data[3]);
                                $importmodel->MOBILE = trim($data[4]);
                                $importmodel->PARTNER_STATUS = 'E';
                                $importmodel->VAT_TAX = trim($data[5]);
                                $importmodel->SURCHARGES = trim($data[6]);
                                $importmodel->SERVICE_TAX = trim($data[7]);
                                $image_name = $this->download_remote_file(trim($data[8]));
                                $importmodel->VENDOR_LOGO = $image_name;
                                $importmodel->VENDOR_REFERENCE_ID = trim($data[0]);
                                $importmodel->EMAIL_ID = trim($data[1]);
                                $importmodel->APPROVER_ID = $this->get_Approver_id(trim($data[9]));
                                $importmodel->ACCOUNT_HOLDER_NAME = trim($data[10]);
                                $importmodel->ACCOUNT_TYPE = trim($data[11]);
                                $importmodel->ACCOUNT_NUMBER = trim($data[12]);
                                $importmodel->IFSC_CODE = trim($data[13]);
                                $importmodel->BANK_NAME = trim($data[14]);
                                $importmodel->BRANCH = trim($data[15]);
                                $importmodel->PHONE_NO = trim($data[16]);
                                $importmodel->BANK_ADDRESS = trim($data[17]);
                                $importmodel->CITY = trim($data[18]);
                                $importmodel->STATE = trim($data[19]);
                                $importmodel->CORPORATE_PAN_CARD_NUMBER = trim($data[20]);
                                $importmodel->GSTNUM = trim($data[21]);
                            }
                            $importmodel->INVOICE_EMAIL_TEMPLATE = "Dear Customer,
Greetings!
Please click on the following link to make the payment for your transaction reference id {{{invoice_number}}}
You can access the soft copy of your invoice reference for your reservation by clicking here: {{{invoice_guest_url}}}
You may pay through Credit Card, Debit Card or Net banking etc. using the above link.

Thank You!
Team Partnerpay";
                            ;
                            $importmodel->INVOICE_SMS_TEMPLATE = "Dear Customer, Please click on the following link to make the payment for your transaction reference id {{{invoice_number}}} ({{{invoice_guest_url}}})
Thank you!
Team {{{partner_name}}}";


                            $importmodel->REMINDER_INVOICE_EMAIL_TEMPLATE = "Dear Customer,
Greetings!
Please click on the following link to make the payment for your transaction reference id {{{invoice_number}}}
You can access the soft copy of your invoice reference for your reservation by clicking here: {{{invoice_guest_url}}}
You may pay through Credit Card, Debit Card or Net banking etc. using the above link.
\nThank You!\nTeam Partnerpay";
                            ;
                            $importmodel->REMINDER_INVOICE_SMS_TEMPLATE = "Dear Customer, Please click on the following link to make the payment for your transaction reference id {{{invoice_number}}} ({{{invoice_guest_url}}})
Thank you!\nTeam {{{partner_name}}}";

                            if ($importmodel->save()) {
                                $ins_record++;
                            } else {
                               // echo '<pre>';print_r([$data,$importmodel->getErrors()]); exit;
                                $fl_record++;
                            }

                        }
                        fclose($fp);
                        if(!empty($ins_record))  {
                            Yii::$app->getSession()->setFlash('success', '<div class="alert alert-success">'.$ins_record.' Record(s) inserted successfully.</div>');
                        }
                        if(!empty($fl_record))  {
                            Yii::$app->getSession()->setFlash('error', '<div class="alert alert-danger">'.$fl_record.' Record(s) could not be insert because of the validation error(s).</div>');
                        }
                    }  else    {
                        Yii::$app->getSession()->setFlash('error', '<div class="alert alert-danger">Error occurred while uploading file to the server.</div>');
                    }

                }
           } 
            return $this->render('import', [
                'model' => $model,
            ]);

    }


    public function actionIntexImport()
    {
        $model = new IntexImportPartner();
    $group_save = [];
        //$model->setScenario('import');

        if ($model->load(Yii::$app->request->post())) {
            $model->TXT = UploadedFile::getInstance($model, 'TXT');
            $ins_record = 0;
            $fl_record = 0;

            if ($model->validate()) {
                $extension = explode('.', $model->TXT->name);
                $ext = end($extension);
                $filename = md5(time() . $model->TXT->name) . '.' . $ext;

                $raw_data = trim(file_get_contents($model->TXT->tempName));

                $raw_data = explode("\n", $raw_data);

                $first = array_shift($raw_data);
                $last = array_pop($raw_data);

				$i=0;
            	$invoice_map_arr = [];
                $group_save = [];
                foreach ($raw_data as $raw) {

                    if(!empty(trim($raw)))    {
                        /*$partner_name = trim(substr($raw, 234, 35));
                        $reference_id = trim(substr($raw, 409, 16));
                        $amount = trim(substr($raw, 435, 14));
                        $amount = (float) $amount; */
                    	$partner_name = trim(substr($raw, 234, 35));
                        //$reference_id = trim(substr($raw, 409, 16));
                        $reference_id = trim(substr($raw, 419, 6));
                        $amount = trim(substr($raw, 435, 14));
                        $amount = (float) $amount;
                        $ifsc_code = trim(substr($raw, 184, 16));
                        $bank_account_number = trim(substr($raw, 200, 16));
                        $pan_number =str_pad(mt_rand(1,9999999999),16);



                        $number = '1500003756605519';

//                        $match = preg_match('/1500003756605519(.*)/', $raw_data2, $dd);

//                        $tmp_string = $dd[0];

//                        $email = trim(substr($tmp_string, 319, 469));
                        $email = 'test@test.com';
                        $mobile = '1926000078';

                        //$partner = Partner::find()->andWhere(['VENDOR_REFERENCE_ID' => $reference_id])->one();
                        //this changed on 13-4-2017 due to issue on some invoices due to reference number
                       $partner = Partner::find()->andWhere(['ACCOUNT_NUMBER' => $bank_account_number])->one();
                    
                    
                        if(empty($partner)) {
                            $partner = new Partner();
                            $partner->PARTNER_NAME = $partner_name;
                            $partner->LOGO = '';
                            $partner->PARTNER_LOCATION = '';
                            $partner->MOBILE = $mobile;
                            $partner->EMAIL_ID = $email;
                            $partner->MERCHANT_ID = Yii::$app->user->identity->MERCHANT_ID;
                            $partner->VENDOR_REFERENCE_ID = $reference_id;
                        	$partner->IFSC_CODE = $ifsc_code;
                            $partner->ACCOUNT_NUMBER = $bank_account_number;
                            $partner->CORPORATE_PAN_CARD_NUMBER = $pan_number;

                            $partner->save(false);
                        }

                        $partner_id = $partner->PARTNER_ID;
                        $brand = '';
                        $branchname = '';
                        $pan_no = '';
						
                        $invoice = new Invoice();
                        $invoice->PARTNER_ID = $partner_id;
                        $invoice->ASSIGN_TO = Yii::$app->getUser()->getIdentity()->USER_ID;
                        $invoice->REF_ID = $pan_no;
                        $invoice->AMOUNT = $amount;
                        $invoice->SERVICE_TAX = 0.00;
                        $invoice->VAT = 0.00;
                        $invoice->TOTAL_AMOUNT = $amount;
                        $invoice->DUE_DATE = 0;
                        $invoice->BELONGS_TO_GROUP = 'Y';

                        $total_amount = 0;

                        

                        if($invoice->save(false)) {
                       
                            $total_amount =  $total_amount + $invoice->TOTAL_AMOUNT;
                            $invoice_map_arr[$i] = $invoice->INVOICE_ID;
                         
                        	$group_save['Amount'][$i] = $invoice->TOTAL_AMOUNT;
                        	$group_save['Invoice'][$i] = $invoice->INVOICE_ID;
                     
                        }   else    {
                            //var_dump($invoice->getErrors()); exit;
                        }						

                        
                    }
					   $i++;
                }
         
            	$total_amount = array_sum( $group_save['Amount']);
                    if(!empty($group_save['Invoice']))    {
                        $group_invoice = new GroupInvoice();

                        $group_invoice->scenario = GroupInvoice::SCENARIO_INSERT;

                        $group_model = Group::find()->andWhere(['MERCHANT_ID' => 			Yii::$app->getUser()->getIdentity()->MERCHANT_ID])->one();

                        $group_invoice->GROUP_ID = $group_model->GROUP_ID;
                        $group_invoice->AMOUNT = $total_amount;
                        //$group_invoice->GI_REF_ID = $model->GI_REF_ID;
                        $group_invoice->save(false);

                        foreach ($group_save['Invoice'] as $invoice_map_id)   {
                            $group_invoice_map = new GroupInvoiceMap();
                            $group_invoice_map->GROUP_INVOICE_ID = $group_invoice->GROUP_INVOICE_ID;
                            $group_invoice_map->INVOICE_ID = $invoice_map_id;
                            $group_invoice_map->BRAND = $brand;
                            $group_invoice_map->BRANCH = $branchname;
                            $group_invoice_map->save(false);
                        }
                        Yii::$app->getSession()->setFlash('success', '<div class="alert alert-success">Invoice created successfully.</div>');
                    }

            }
        }

        return $this->render('intex-import', [
            'model' => $model,
        ]);
    }

	public function download_remote_file($file_url)
    {
        $valid = @getimagesize($file_url);
        $image_name = "partnerpay-logo.png";
        if($valid) {
            $img = pathinfo($file_url);
            $exp = explode('?',$img['extension']);
            $extension = $exp[0];
            if($extension == 'jpg' || $extension == 'png' || $extension == 'gif' || $extension == 'jpeg'){
                $filename = md5(time().$img['basename']).'.'.$extension;
                $save_to = Yii::$app->basePath.'/web/uploads/vendor_logo/'.$filename;
                $content = file_get_contents($file_url);
                $s = file_put_contents($save_to, $content);
                $image_name = $filename;

            }
        }

        return $image_name;
    }

	 public function  get_Approver_id($email)
    {
        $approver_id = 0;
        if(!empty($email)) {
            $approver_data = UserMaster::find()->select('USER_ID')->where(['EMAIL'=>$email, 'USER_TYPE'=>'approver'])->one();
            if(!empty($approver_data)) {
                $approver_id = $approver_data['USER_ID'];
            }

        }

        return $approver_id;

    }

	public function actionGetApproverList()
    {
        if (Yii::$app->request->post('ajax') == 'true') {
            $selected = Yii::$app->request->post('selected');

            $merchant_id = Yii::$app->request->post('merchant_id');
            $option = '';
            $sel = '';
            $option .= '<option value="">Select Approver</option>';

            if (!empty($merchant_id)) {
                $approver_detail = UserMaster::find()->select('USER_ID, FIRST_NAME, LAST_NAME')->andWhere(['USER_STATUS' => 'E', 'USER_TYPE' => 'approver', 'MERCHANT_ID' => $merchant_id])->all();
                if (!empty($approver_detail)) {
                    foreach ($approver_detail as $approver) {
                        $sel = '';
                        if($approver->USER_ID == $selected)    {
                            $sel = ' selected="selected" ';
                        }
                        //$approverarray[$approver["USER_ID"]] = $approver["FIRST_NAME"]." ".$approver["LAST_NAME"];
                        $option .= '<option value="' . $approver['USER_ID'] . '" ' . $sel . '>' . $approver["FIRST_NAME"] . " " . $approver["LAST_NAME"] . '</option>';
                    }
                }
                echo $option;
            } else {
                echo 0;
            }
        }
    }

    protected function generate_password()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $result = '';
        for ($i = 0; $i < 5; $i++) {
            $result .= $characters[mt_rand(0, 61)];
        }

        return $result;
    }
}
