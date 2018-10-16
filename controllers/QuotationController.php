<?php

namespace app\controllers;


use Yii;
use app\models\Quotation;
use app\models\TblQuotationPartners;
use app\models\TblQuotationPartnersSearch;
use app\models\QuotationSearch;
use app\models\CategoryMaster;
use app\models\PartnerCategory;
use app\models\Partner;
use yii\db\Query;
//use yii\web\HController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\helpers\generalHelper;
use yii\base\Hcontroller;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use app\models\UserMaster;
/**
 * QuotationController implements the CRUD actions for Quotation model.
 */
class QuotationController extends HController
{
    public function behaviors()
    {
        return [

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index','create','update','view','delete','getpartners','assignquotation','listofapplicants','updateapplicant','listofquotationsrequest','listofquotationsassigned'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ], 
        ];
    }

    /**
     * Lists all Quotation models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new QuotationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Quotation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Quotation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    { 
        
        $model = new Quotation();

        $model->scenario = 'insert';
        $t = time();
        //echo '<pre>';print_r(Yii::$app->request->post());exit;
        $model->MERCHANT_ID = Yii::$app->user->identity->MERCHANT_ID;
        if($model->load(Yii::$app->request->post())) {
         
           // $ext = pathinfo($_FILES['Quotation']['tmp_name'], PATHINFO_EXTENSION);echo $ext;
         if(!empty($model->NEW_PARTNER_EMAIL)){
            $emails = explode(',',$model->NEW_PARTNER_EMAIL);
            foreach($emails as $em){
            
            if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                $model->addError('NEW_PARTNER_EMAIL',$em.' is not a valid Email id ');
                 return $this->render('create', [
                    'model' => $model,
               ]);
            }
            
            $res = UserMaster::findBySql("SELECT * FROM tbl_user_master where EMAIL='$em' and MERCHANT_ID='$model->MERCHANT_ID'")->all();
           
             if(count($res)){
               $model->addError('NEW_PARTNER_EMAIL',$em.' Email id already exists.');
                 return $this->render('create', [
                    'model' => $model,
                    'post' => Yii::$app->request->post()
               ]);
             }
            }
         }
          
            //echo '<pre>';
            $due_date = strtotime($model->DUE_DATE);

            $model->DUE_DATE = $due_date;

            $model->fileinput = UploadedFile::getInstance($model, 'fileinput');
            //echo '<pre>';var_dump([$model->validate(),$model->getErrors()]);exit;
            if($model->validate()) {
               
                $file_name = md5($model->fileinput->baseName.time()) . '.' . $model->fileinput->extension;
                $model->FILE = $file_name;
                $model->STATUS = 'Submitted';
           // echo '<pre>';print_r($model);exit;
                if($model->fileinput->saveAs('uploads/quotation/'.$file_name)) {
                    if($model->save(false)) {
                        $post = Yii::$app->request->post('Quotation');
                        foreach($post['PARTNERS'] as $key => $val){
                           
                            $connection = Yii::$app->getDb();
                            $command = $connection->createCommand("insert into  tbl_quotation_partners (QUOTATION_ID,PARTNER_ID,CREATED) values ('$model->ID','$val','$t')");
                            $result = $command->execute();
                            $partner_data = Partner::find()->select('*')->where(['PARTNER_ID'=>$val])->one();
                            $user_data    = UserMaster::find()->select('*')->where(['PARTNER_ID'=>$val])->one();
                            if(!empty($partner_data->EMAIL_ID)) {
                                $ghelper = new generalHelper();
                                $ghelper->sendQuotation($partner_data->EMAIL_ID, $model->ID, $val,$model->MERCHANT_ID);
                           
                             $ghelper->sendQuotationVendor($partner_data->EMAIL_ID, $model->ID, $val,$model->MERCHANT_ID,$partner_data->PARTNER_NAME,$model);
                            }
                        }
                    
                   
                        //wild card entry of partner
                       if(!empty($model->NEW_PARTNER_EMAIL)){
                             
                       foreach($emails as $e){
                       
                        $connection = Yii::$app->getDb();
                        $new_partner_command = $connection->createCommand("insert into  tbl_partner_master (PARTNER_NAME,EMAIL_ID,MERCHANT_ID, CREATED_ON) values ('guest','$e','$model->MERCHANT_ID','$t')");
                        $result = $new_partner_command->execute();
                        $partner_id = Yii::$app->db->lastInsertID;
                   
                       
                        $user_model = new UserMaster();
                        $user_model->USER_TYPE = 'guestuser';
                        $user_model->USER_STATUS = "E";
                        $user_model->MOBILE = '';
                        $user_model->MERCHANT_ID = $model->MERCHANT_ID;
                        $user_model->PARTNER_ID = $partner_id;
                        //$user_model->PARTNER_ID = 0;

                        $user_model->EMAIL = $e;
                        $user_model->FIRST_NAME = '';
                        $user_model->LAST_NAME = '';
                        $generated_password= $this->generate_password();
                        //var_dump($generated_password);exit;
                        $user_model->PASSWORD = $generated_password;
                        $user_model->OG_PASSWORD = $generated_password;
                        $user_model->REPEAT_PASSWORD = $generated_password;
                        if ($user_model->save(false)) {
                            //echo '<pre>';print_r($user_model);exit;
                            $connection = Yii::$app->getDb();
                            $command = $connection->createCommand("insert into  tbl_quotation_partners (QUOTATION_ID,PARTNER_ID,CREATED) values ('$model->ID','$partner_id','$t')");
                            $result = $command->execute();
                           
                            $ghelper = new generalHelper();
                            $ghelper->sendUserDetails($user_model,$generated_password);
                            $ghelper->sendQuotation($model->NEW_PARTNER_EMAIL, $model->ID, $user_model->PARTNER_ID,$model->MERCHANT_ID);
                        }
                       }
                       
                       }
                      
                        /*code written in scratch*/
                        return $this->redirect(['view', 'id' => $model->ID]);
                    }
                }

            }
        
            return $this->render('create', [
                'model' => $model,
            ]);
            
        } else {
            // echo '<pre>';print_r($model->getErrors());exit;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Quotation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        
        $t = time();
        if($model->STATUS == 'Executed'){
           Yii::$app->session->setFlash('success', "Cannot update Quotation already approved.");
          return $this->redirect(['index']);
           //echo 'Cannot update Quotation already approved.';exit;
        }
        if (Yii::$app->request->isPost) {
           $umodel = $this->findModel($id);
           $og_due_date = $umodel->DUE_DATE;
           $model->load(Yii::$app->request->post());
            $emails = explode(',',$model->NEW_PARTNER_EMAIL);
            $guestuseremails = $_POST['guestpartnes'];
            $guestuseremails = explode(',',$guestuseremails);
            $same_emails =array_intersect($emails,$guestuseremails);
            $diff_emails = array_diff($emails,$guestuseremails);
      
            

            $due_date = strtotime($model->DUE_DATE);
           // $model->DUE_DATE = $due_date;
            
            $model->fileinput = UploadedFile::getInstance($model, 'fileinput');
            if ($model->validate()) {
            
          if(!empty($model->NEW_PARTNER_EMAIL)){
            $emails = explode(',',$model->NEW_PARTNER_EMAIL);
            foreach($emails as $em){
            
            if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                $model->addError('NEW_PARTNER_EMAIL',$em.' is not a valid Email id ');
                 return $this->render('create', [
                    'model' => $model,
               ]);
            }
            
            $res = UserMaster::findBySql("SELECT * FROM tbl_user_master where EMAIL='$em' and MERCHANT_ID!='$model->MERCHANT_ID'")->all();
           
              if(count($res)){
               $model->addError('NEW_PARTNER_EMAIL',$em.' Email id already exists.');
                 return $this->render('create', [
                    'model' => $model,
               ]);
              }
            }
          
          }
            
          
               if (!empty($_FILES['Quotation']['name']['fileinput']) || ($due_date != $og_due_date)) {
                 
                    $model->DUE_DATE = $due_date;
                  if (!empty($_FILES['Quotation']['name']['fileinput'])){
                    $file_name = md5($model->fileinput->baseName.time()) . '.' . $model->fileinput->extension;
                    @$model->fileinput->saveAs('uploads/quotation/'.$file_name); 
                        @$model->FILE = $file_name;
                  }
                        if($model->save(false))  {
                            $post = Yii::$app->request->post('Quotation');
                            TblQuotationPartners::deleteAll("QUOTATION_ID = :quotation_id",[':quotation_id' => $id]);
                            foreach($post['PARTNERS'] as $key => $val){
                                $connection = Yii::$app->getDb();
                                
                                $command = $connection->createCommand("insert into  tbl_quotation_partners (QUOTATION_ID,PARTNER_ID,CREATED) values ('$model->ID','$val','$t')");
                                $result = $command->execute();
                                $partner_data = Partner::find()->select('EMAIL_ID')->where(['PARTNER_ID'=>$val])->one();
                      
                                $user_data    = UserMaster::find()->select('*')->where(['PARTNER_ID'=>$val])->one();
                                if(!empty($partner_data->EMAIL_ID)) {
                                    $ghelper = new generalHelper();
                                    $ghelper->sendQuotation($partner_data->EMAIL_ID, $model->ID, $val,$model->MERCHANT_ID);
                                }
                            }
                            
                            foreach($same_emails as $ke => $va) {
                              $user = UserMaster::find()->where(['EMAIL' => $va])->one();
                             $connection = Yii::$app->getDb();
                                
                                $command = $connection->createCommand("insert into  tbl_quotation_partners (QUOTATION_ID,PARTNER_ID,CREATED) values ('$model->ID','$user->PARTNER_ID','$t')");
                                $result = $command->execute();
                              $ghelper->sendQuotation($user->EMAIL, $model->ID, $user->PARTNER_ID,$model->MERCHANT_ID);
                            }
                        
                            $qmodel = Quotation::findOne($id);
                            $qmodel->STATUS = 'Submitted';
                            $qmodel->save(false);
                            //return $this->redirect(['view', 'id' => $model->ID]);
                        }
                    
                } else {
                    
                    $qpartners = $_POST['qpartnes'];
                    $model->DUE_DATE = $due_date;
                    if($model->save(false))  { 
                        $post = Yii::$app->request->post('Quotation');
                       
                       // TblQuotationPartners::deleteAll("QUOTATION_ID = :quotation_id",[':quotation_id' => $id]);
                        foreach($post['PARTNERS'] as $key => $val){
                          if(!in_array($val,$qpartners)){
                           
                            $connection = Yii::$app->getDb();
                            
                            $command = $connection->createCommand("insert into  tbl_quotation_partners (QUOTATION_ID,PARTNER_ID,CREATED) values ('$model->ID','$val','$t')");
                            $result = $command->execute();
                            $partner_data = Partner::find()->select('EMAIL_ID')->where(['PARTNER_ID'=>$val])->one();
                      
                            $user_data    = UserMaster::find()->select('*')->where(['PARTNER_ID'=>$val])->one();
                            if(!empty($partner_data->EMAIL_ID)) {
                            
                                  
                                 $ghelper = new generalHelper();
                                 $ghelper->sendQuotation($partner_data->EMAIL_ID, $model->ID, $val,$model->MERCHANT_ID);
                              }
                            }
                        
                        }
                       
                        //return $this->redirect(['view', 'id' => $model->ID]);
                    }
                }
                   if(!empty($model->NEW_PARTNER_EMAIL)){
                    
                     foreach($diff_emails as $e){
                        
                         $connection = Yii::$app->getDb();
                        $new_partner_command = $connection->createCommand("insert into  tbl_partner_master (PARTNER_NAME,EMAIL_ID,MERCHANT_ID, CREATED_ON) values ('guest','$e','$model->MERCHANT_ID','$t')");
                        $result = $new_partner_command->execute();
                        $partner_id = Yii::$app->db->lastInsertID;
                       
                     
                        $user_model = new UserMaster();
                        $user_model->USER_TYPE = 'guestuser';
                        $user_model->USER_STATUS = "E";
                        $user_model->MOBILE = '';
                        $user_model->MERCHANT_ID = $model->MERCHANT_ID;
                        $user_model->PARTNER_ID = $partner_id;
                        //$user_model->PARTNER_ID = 0;

                        $user_model->EMAIL = $e;
                        $user_model->FIRST_NAME = '';
                        $user_model->LAST_NAME = '';
                        $generated_password= $this->generate_password();
                        //var_dump($generated_password);exit;
                        $user_model->PASSWORD = $generated_password;
                        $user_model->OG_PASSWORD = $generated_password;
                        $user_model->REPEAT_PASSWORD = $generated_password;
                        if ($user_model->save(false)) {
                            //echo '<pre>';print_r($user_model);exit;
                            $connection = Yii::$app->getDb();
                            $command = $connection->createCommand("insert into  tbl_quotation_partners (QUOTATION_ID,PARTNER_ID,CREATED) values ('$model->ID','$partner_id','$t')");
                            $result = $command->execute();
                           
                            $ghelper = new generalHelper();
                            $ghelper->sendUserDetails($user_model,$generated_password);
                            $ghelper->sendQuotation($e, $model->ID, $user_model->PARTNER_ID,$model->MERCHANT_ID);
                        }
                       }
                   }
                 return $this->redirect(['view', 'id' => $model->ID]);
                     
            }
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
        /*$post = Yii::$app->request->post('Quotation');
        foreach($post['ASSIGN_PARTNER'] as $key => $val){
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("insert into  tbl_quotation_partners (QUOTATION_ID,PARTNER_ID,CREATED) values ('$model->ID','$val','$t')");
            $result = $command->execute();
        }
        return $this->redirect(['view', 'id' => $model->ID]);
    } else {
        return $this->render('update', [
            'model' => $model,
        ]);
    }*/
    }

    /**
     * Deletes an existing Quotation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = TblQuotationPartners::deleteAll("QUOTATION_ID = :quotation_id",[':quotation_id' => $id]);
            //->where("QUOTATION_ID = :quotation_id",[':quotation_id' => $id])
            //->all();
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionGetpartners(){
        $catId = Yii::$app->request->post('catId');
        $merId = Yii::$app->request->post('merId');
        //$categories = PartnerCategory::find()->select('PARTNER_ID')->where(['CAT_ID' => $catId])->asArray()->all();
        $query = new Query();
        $query	->select(['tbl_partner_master.PARTNER_ID', 'tbl_partner_master.PARTNER_NAME'])
            ->from('tbl_partner_master')
            ->join(	'INNER JOIN',
                'tbl_partner_categories',
                'tbl_partner_categories.PARTNER_ID =tbl_partner_master.PARTNER_ID'
            )->where(['tbl_partner_master.MERCHANT_ID' => $merId, 'tbl_partner_categories.CAT_ID'=>$catId]);
        $command = $query->createCommand();
        $categories = $command->queryAll();
        $prt = '';
        $html = '';
        if(count($categories)){
            foreach($categories as $key => $value) {
                $prt .= $value['PARTNER_ID'].',';
            }
            $prt = rtrim($prt,",");
            $partner = Partner::find()->select('PARTNER_ID,PARTNER_NAME')->where('PARTNER_ID IN('.$prt.')')->asArray()->all();


            foreach($partner as $k => $v){
                $html .= '<option value="'.$v['PARTNER_ID'].'">'.$v['PARTNER_NAME'].'</option>';
            }
        }else{
            $html = '<option value="">No Record Found</option>';
        }
        echo $html;
    }

    public function actionAssignquotation(){

        if(Yii::$app->request->post()){

            $qid = Yii::$app->request->post('qid');
            $pid = Yii::$app->request->post('pid');
            $amount = Yii::$app->request->post('amount');
            $model = $this->findModel($qid);
        
            if(!is_numeric($amount)){
                 $model->addError('AMOUNT',' Amount is invalid.');
                 return $this->render('create', [
                    'model' => $model,
               ]);
            }
            
            if($model->STATUS == 'Executed'){
                echo 'Cannot update Quotation already approved.';exit;
            }
        
            $d = TblQuotationPartners::find()->where('PARTNER_ID = :pid', [':pid' => $pid])->andWhere('QUOTATION_ID = :qid', [':qid' => $qid])->one();
            
            if(!empty($d->PARTNER_UPLOADED_DOC)){
                return $this->redirect(['index']);
            }
            if(!empty($model->FILE))  {
          
                $model->FILE = UploadedFile::getInstance($model,'FILE');
                $filename = md5(time().$model->FILE->name).'.'.$model->FILE->extension;
                if($model->FILE->saveAs(Yii::$app->basePath.'/web/uploads/quotation/'.$filename)) {
                    if(!empty($model->FILE))  {
                        unlink(Yii::$app->basePath.'/web/uploads/quotation/'.$model->FILE);
                    }
                    $model->FILE = $filename;
                }
            }

            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("update  tbl_quotation_partners set AMOUNT=".$amount.", PARTNER_UPLOADED_DOC='".$filename."',UPDATED ='".time()."'  where QUOTATION_ID = ".$qid." and PARTNER_ID = ".$pid);
            $result = $command->execute();
            
            $model = Quotation::findOne($qid);
            $model->STATUS = 'Processing';
            $model->save(false);
        
            $mer = UserMaster::find()->where(['MERCHANT_ID' => $model->MERCHANT_ID])->andWhere(['PARTNER_ID' => '0'])->one();         
       
            $ghelper = new generalHelper();
            $ghelper->sendMerchantQuoteSubmitInfo($mer->EMAIL);
                
            \Yii::$app->getSession()->setFlash('success', '');
             if(Yii::$app->user->identity->USER_TYPE == 'partner'){
                      return $this->redirect(['quotation/listofquotationsrequest']);  
             }
            //return $this->redirect(['guest-user-doc/index']);
        return $this->redirect(['quotation/listofquotationsrequest']);
        }

        $qid = base64_decode(Yii::$app->request->get('q'));
        $pid = base64_decode(Yii::$app->request->get('p'));
        $model = $this->findModel($qid);
        $disbale = '';
        $q = Quotation::findOne($qid);
        if($q->STATUS == 'Executed'){
           $disable = 'disable';
        }
    
        $d = TblQuotationPartners::find()->where('PARTNER_ID = :pid', [':pid' => $pid])->andWhere('QUOTATION_ID = :qid', [':qid' => $qid])->one();

        return $this->render('assignquotation', [
            'model' => $model,
            'qid' => $qid,
            'pid' => $pid,
            'count' => $d,
            'disable' => $disable
        ]);
    }


    public function actionListofapplicants($id){
        $model = $this->findModel($id);
        $searchModel = new TblQuotationPartnersSearch();
        $d['TblQuotationPartnersSearch']['QUOTATION_ID'] = $id;
        Yii::$app->request->queryParams = $d;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
     
        return $this->render('listquotation', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id' => $id,
            'assign_partner' => $model->ASSIGN_PARTNER
        ]);
    }

    public function actionUpdateapplicant(){
        $qid = Yii::$app->request->get('q');
        $pid = Yii::$app->request->get('p');

        $partner_data = Partner::findOne($pid);
       
        if($partner_data->PARTNER_NAME == 'guest'){
            echo '2';exit;
        }

        $QPres = TblQuotationPartners::find()->where(['PARTNER_ID' => $pid])->andWhere(['QUOTATION_ID' => $qid])->one();
    
        if(!$QPres->PARTNER_UPLOADED_DOC){
           echo '3';exit;
        }
       
        $quotation = Quotation::findOne($qid);   
       
        if($quotation->STATUS == 'Executed'){
           echo '4';exit;
        }
    
        $quotation->ASSIGN_PARTNER = $QPres->PARTNER_ID;
       
        if($quotation->update(false)){
            $model = Quotation::findOne($qid);
            $model->STATUS = 'Executed';
            $model->save(false);
           
            $ghelper = new generalHelper();
            $ghelper->sendAssignPartnerApprovalInfo($partner_data->EMAIL_ID);
            echo '1';exit;
        }else{
            echo '0';exit;
        }
    }

    /**
     * Finds the Quotation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Quotation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Quotation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
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

    public function actionExpireQuotation() {
        $connection = Yii::$app->getDb();
        $quotations = Quotation::find()->all();
        foreach ($quotations as $quotation) {
            if($quotation->STATUS != 'Processing') {
                if($quotation->DUE_DATE > strtotime(date('Y-m-d'. '00:00:00'))){
                    $command = $connection->createCommand("update  tbl_quotation_master set STATUS = 'Expired' where QUOTATION_ID = ".$quotation->ID);
                    $result = $command->execute();
                }
            }
        }
    }


    public function actionListofquotationsrequest(){
        $searchModel = new TblQuotationPartnersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
     
        return $this->render('listofquotationrequest', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionListofquotationsassigned(){
        $searchModel = new TblQuotationPartnersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('listofquotationassigned', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

public function actionQramt(){
   $qr_id = Yii::$app->request->post('QR_ID');
   $qr = $this->findModel($qr_id);
   $response = ['success' => 0,'amount' => ''];
   if(!empty($qr)){
     $partner = $qr->ASSIGN_PARTNER;
     $qr_partner = TblQuotationPartners::find()->where(['PARTNER_ID' => $partner])->andWhere(['QUOTATION_ID' => $qr_id])->one();
     if(!empty($qr_partner)){
       $response = ['success' => 1,'amount' => $qr_partner->AMOUNT];
     }
   }

   echo json_encode($response);exit;
}
}