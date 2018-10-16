<?php

namespace app\controllers;

use app\models\ImportPO;
use Yii;
use app\models\PoMaster;
use app\models\Quotation;
use app\models\PoMasterSearch;
use yii\base\Hcontroller;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\MerchantMaster;
use app\helpers\generalHelper;
/**
 * PoController implements the CRUD actions for PoMaster model.
 */
class PoController extends Hcontroller
{
    public function behaviors()
    {
        /*return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];*/
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','update','create','view','import'],
                'rules' => [
                    [
                        'actions' => ['index','update','create','view','changestatus','invoiceamt','quotationlist','updateapi','testapi'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['import'],
                        'allow' => (!Yii::$app->user->isGuest && Yii::$app->user->identity->USER_TYPE != 'partner')?true:false,
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
     * Lists all PoMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PoMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PoMaster model.
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
     * Creates a new PoMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//     public function actionCreate()
//     {
//         $model = new PoMaster();

//         if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             return $this->redirect(['view', 'id' => $model->PO_ID]);
//         } else {
//             //var_dump($model->getErrors());exit;
//             return $this->render('create', [
//                 'model' => $model,
//             ]);
//         }
//     }

 public function actionCreate()
    {

        $model = new PoMaster();
        $qr_id = Yii::$app->request->get('qr');
       
        if(Yii::$app->user->identity->USER_TYPE == 'merchant'){
            $merchant = MerchantMaster::findOne(Yii::$app->user->identity->MERCHANT_ID);
            $isPo = $merchant->CREATE_QR;
        }
 
        if($isPo == 'Y'){
          $model->scenario = 'withqr';
        }
    
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             foreach($_POST['charge_name'] as $key => $val){
                    $t = time();
                    $connection = Yii::$app->getDb();
                    $charge_val = $_POST['charge_value'][$key];
                    $command = $connection->createCommand("insert into  tbl_po_tax (PO_ID,CHARGE_NAME,CHARGE_VALUE,CREATED) values ('$model->PO_ID','$val','$charge_val','$t')");
                    $result = $command->execute();                
             }

             
            return $this->redirect(['view', 'id' => $model->PO_ID]);
        } else {
            $isPo = 'N';
            if(Yii::$app->user->identity->USER_TYPE == 'merchant'){
                $merchant = MerchantMaster::findOne(Yii::$app->user->identity->MERCHANT_ID);
                $isPo = $merchant->CREATE_QR;
            }
            //var_dump($model->getErrors());exit;
            return $this->render('create', [
                'model' => $model,
                'isPo'  => $isPo,
                'qr_id' => $qr_id
            ]);
        }
    }

    /**
     * Updates an existing PoMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
//     public function actionUpdate($id)
//     {
//         $model = $this->findModel($id);

//         if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             return $this->redirect(['view', 'id' => $model->PO_ID]);
//         } else {
//             return $this->render('update', [
//                 'model' => $model,
//             ]);
//         }
//     }

   public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
               $connection = Yii::$app->getDb();
               $command = $connection->createCommand("delete from tbl_po_tax where PO_ID =".$model->PO_ID);
               $result = $command->execute();
             
             foreach($_POST['charge_name'] as $key => $val){
                    $t = time();
                    $connection = Yii::$app->getDb();
                    $charge_val = $_POST['charge_value'][$key];
                    $command = $connection->createCommand("insert into  tbl_po_tax (PO_ID,CHARGE_NAME,CHARGE_VALUE,CREATED) values ('$model->PO_ID','$val','$charge_val','$t')");
                    $result = $command->execute();                
             }
            return $this->redirect(['view', 'id' => $model->PO_ID]);
        } else {
             $isPo = 'N';
            if(Yii::$app->user->identity->USER_TYPE == 'merchant'){
                $merchant = MerchantMaster::findOne(Yii::$app->user->identity->MERCHANT_ID);
                $isPo = $merchant->CREATE_QR;
            }

            return $this->render('update', [
                'model' => $model,
                'isPo'  => $isPo,
            ]);
        }
    }

    /**
     * Deletes an existing PoMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the PoMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PoMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PoMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionImport()
    {
        $model = new ImportPO();
        if ($model->load(Yii::$app->request->post())) {
            $model->CSV = UploadedFile::getInstance($model, 'CSV');
            $ins_record = 0;
            $fl_record = 0;

            if ($model->validate()) {
                $extension = explode('.', $model->CSV->name);
                $ext = end($extension);
                $filename = md5(time() . $model->CSV->name) . '.' . $ext;
                if (($fp = fopen($model->CSV->tempName, "r")) !== false) {
                    $i = 0;
                    while ($data = fgetcsv($fp, 99999)) {
                        $importmodel = new PoMaster();
                        $i++;
                        if ($i == 1) {
                            continue;
                        }

                        $importmodel->MERCHANT_ID = $model->IMPORT_MERCHANT;
                        $importmodel->PARTNER_ID = $model->IMPORT_PARTNER;
                        $importmodel->SAP_REFERENCE = $data[0];
                        $importmodel->PO_NUMBER = trim($data[1]);
                        $importmodel->DATE_OF_CREATION = strtotime(trim($data[2]));
                        $importmodel->AMOUNT = trim($data[3]);

                        if ($importmodel->save()) {
                            $ins_record++;
                        } else {
                            $fl_record++;
                        }
                    }
                    fclose($fp);
                    if(!empty($ins_record)) {
                        Yii::$app->getSession()->setFlash('success', '<div class="alert alert-success">'.$ins_record.' Record(s) inserted successfully.</div>');
                    }
                    if(!empty($fl_record)) {
                        Yii::$app->getSession()->setFlash('error', '<div class="alert alert-danger">'.$fl_record.' Record(s) could not be insert because of the validation error(s).</div>');
                    }
                }  else {
                    Yii::$app->getSession()->setFlash('error', '<div class="alert alert-danger">Error occurred while uploading file to the server.</div>');
                }
            }
        }
        return $this->render('import', [
            'model' => $model,
        ]);

    }

       public function actionChangestatus(){
        $post = Yii::$app->request->post();
        $id = $post['id'];
        $status = $post['status'];
        $model = $this->findModel($id);

        $QUOTATION_ID = $model->QUOTATION_ID;
        $PO_ID = $model->PO_ID;
        $PDF_ATTACHMENT = $model->PDF_ATTACHMENT;
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("update tbl_po_master set STATUS = '$status',PDF_ATTACHMENT = '$PDF_ATTACHMENT' where PO_ID=".$id);
        $res = $command->execute();

        if($res){
            echo '1';exit;
        }else{
            echo '0';exit;
        }
    }

    public function actionInvoiceamt(){
      
        $po_num = Yii::$app->request->post('po_id');
        $res = PoMaster::find()->orderBy(['PO_ID'=>SORT_DESC])->where(['PO_NUMBER' => $po_num])->one();

        $tax_amount = $this->getTaxAmount($po_num);
        
        if($res){
            echo json_encode(['AMOUNT' => $res->AMOUNT,'TAX' => $tax_amount]);exit;
        }else{
            echo 0;exit;
        }
    }

    public function getTaxAmount($po_num){
        $res = PoMaster::find()->orderBy(['PO_ID'=>SORT_DESC])->where(['PO_NUMBER' => $po_num])->one();
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("select * from tbl_po_tax where PO_ID =".$res->PO_ID.' order by PO_ID desc');
        $result = $command->queryAll();
        $tax = 0;
      
        if(count($result)){
            foreach ($result as $key => $value) {
                $tax += $value['CHARGE_VALUE'];
            }

            $total_tax = $res->AMOUNT + ($res->AMOUNT * $tax) / 100;
        }

        return $tax;
    }

    public function actionQuotationlist(){
        $id = Yii::$app->request->post('id');
        $data = Quotation::find()->where(['STATUS' => 'Executed'])->andWhere(['ASSIGN_PARTNER' => $id])->all();

        $html = '<option>No Record Found</option>';
        
        if(count($data)){
            $html = '';
            foreach($data as $k => $v){
               $html .= '<option value="'.$v->ID.'">'.$v->NAME.'</option>';
            }
        }
        
        echo $html;exit;
    }

    public function actionUpdateapi($quotation_id,$po_id){
        
        $quotation = Quotation::findOne($quotation_id);
        $merchant = MerchantMaster::findOne($quotation->MERCHANT_ID);
        $po = PoMaster::findOne($po_id);
        $fileHash = md5_file(Yii::$app->basePath.'/web/uploads/pdf/'.$po->PDF_ATTACHMENT);
        $ghelper = new generalHelper();
        $url = 'http://188.166.113.173:8080/AirpayBlockchain/api/airpay/podetails';
        $method = 'POST';
        $session = Yii::$app->session;
        $data = [
           'poId' => $po->PO_ID,
           'poHash' =>  $fileHash,
           'contractAddress' => $quotation->CONTACT_ADDRESS,
           'privateKey' => $merchant->AUXESIS_PRIVATE_KEY
        ];
        
        Yii::info('update  api hit for post data : ' . json_encode($data));
        $result = $ghelper->sendDataOverPost($url, json_encode($data), $method,'300','8080');
        Yii::info('update api hit response data : ' . $result);
        $result = json_decode($result,true);

        
        $po->CONTACT_ADDRESS = $result['result']['contractAddress'];
        $po->TRANSACTION_HASH = $result['result']['transactionHash'];
        $po->save(false);
    }

       
}
