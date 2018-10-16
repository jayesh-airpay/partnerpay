<?php

namespace app\controllers;

use app\helpers\Checksum;
use app\helpers\generalHelper;
use app\helpers\invoiceHelper;
use app\models\Client;
use app\models\GroupInvoice;
use app\models\GroupInvoiceMap;
use app\models\MerchantMaster;
use app\models\Order;
use app\models\Partner;
use app\models\PoMaster;
use app\models\UserMaster;
use app\models\UserMerchant;
use Yii;
use app\models\Invoice;
use app\models\InvoiceSearch;
use yii\base\Hcontroller;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Hcontroller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','update','create','viewdetails','delete'],
                'rules' => [
                    [
                        'actions' => ['viewdetails','index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                	[
                        'actions' => ['create'],
                        //'allow' => (!Yii::$app->user->isGuest && Yii::$app->user->identity->USER_TYPE != 'merchant')?true:false,
                    	'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['viewdetails', 'create'],
                        'allow' => (!Yii::$app->user->isGuest && (Yii::$app->user->identity->USER_TYPE == 'approver' || Yii::$app->user->identity->USER_TYPE == 'payment'))?false:true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => (!Yii::$app->user->isGuest && Yii::$app->user->identity->USER_TYPE == 'partner')?true:false,
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

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\base\ExitException
     */
    public function actionView($id)
    {
        //$id = base64_decode($id);
        $service_tax = $vat_tax = $surchages = 0.00;
        Yii::$app->setComponents(['theme' => 'payment', 'view' => [
            'class' => 'yii\web\View',
            'theme' => [
                'baseUrl' => '@web/themes/payment',
                'pathMap' => ['@app/views' => '@app/themes/payment'],
            ],
        ]]);

        $model = $this->findModel($id);
        $model->scenario = Invoice::SCENARIO_PAYMENT;

        $partner = $model->partner;

       // echo '<pre>';print_r($partner);exit;
        if($partner===null) {
            //throw new NotFoundHttpException('The requested page does not exist.');
        }
        $this->vendor_logo = $partner->VENDOR_LOGO;

        if($model->INVOICE_STATUS == 1) {
            throw new HttpException(503, 'Invoice is Paid.');
        }

        if($model->DUE_DATE < time()) {
           //throw new HttpException(503, 'The invoice has expired.');
        }

        $client = new Client();

        if(!empty($model->CLIENT_EMAIL))    {
            $client->EMAIL = $model->CLIENT_EMAIL;
        }

        if(!empty($model->CLIENT_MOBILE))    {
            $client->PHONE = $model->CLIENT_MOBILE;
        }


        $mechant_user = UserMaster::find()->where([
            'USER_TYPE' => 'merchant',
            'MERCHANT_ID' => $partner->MERCHANT_ID
        ])->one();
        if(!empty($mechant_user))   {
            $client->FIRST_NAME = $mechant_user->FIRST_NAME;
            $client->LAST_NAME = $mechant_user->LAST_NAME;
        }


        if(isset($_POST['Client']) && isset($_POST['Invoice']))
        {
            $client->attributes=$_POST['Client'];

            $model->attributes=$_POST['Invoice'];
            if(empty($model->CLIENT_MOBILE))	{
                $model->CLIENT_MOBILE = null;
            }
             $model->scenario  = 'paymentpay';//echo '<pre>';print_r($model);exit;
            if($client->validate() && $model->validate())  {
               if(empty($partner->AIRPAY_USERNAME) && empty($partner->AIRPAY_PASSWORD) && empty($partner->AIRPAY_SECRET_KEY) && empty($partner->AIRPAY_MERCHANT_ID)) {
                    Yii::$app->getSession()->setFlash('error', '<div class="alert alert-danger">Airpay Merchant details not configured!</div>');
                    return $this->redirect(Yii::$app->request->referrer);
                }
                if($model->IS_CORPORATE != 'Y') {
                    $model->COMPANY_NAME = null;
                }
                //$model = Invoice::findOne($model->INVOICE_ID);
                $s = $model->update();
                //echo "<pre>";
                //var_dump($model->attributes); exit;
                $order = new Order();
                $order->INVOICE_ID = $id;
                $order->PAYMENT_STATUS = 0;
                $order->CREATED_ON = time();
                //var_dump($model->pay_amount); exit;
                if($order->save())  {
                    $client->ORDER_ID = $order->ORDER_ID;
                    if($client->save()) {
                        $chk = new Checksum();
                        $alldata =  $chk->sanitizedParam($client->EMAIL).
                            $chk->sanitizedParam($client->FIRST_NAME).
                            $chk->sanitizedParam($client->LAST_NAME).
                            $chk->sanitizedParam($model->pay_amount).
                            //$chk->sanitizedParam($model->REF_ID);
                            $chk->sanitizedParam($model->INVOICE_ID);

                        $privatekey = $chk->encrypt($partner->AIRPAY_USERNAME.":|:".$partner->AIRPAY_PASSWORD, $partner->AIRPAY_SECRET_KEY);
                        $checksum = $chk->calculateChecksum($alldata.date('Y-m-d'),$privatekey);

                        $post_data = array(
                            'buyerEmail'        => $chk->sanitizedParam($client->EMAIL),
                            'buyerPhone'        => $chk->sanitizedParam($client->PHONE),
                            'buyerFirstName'    => $chk->sanitizedParam($client->FIRST_NAME),
                            'buyerLastName'     => $chk->sanitizedParam($client->LAST_NAME),
                            'amount'            => $chk->sanitizedParam($model->pay_amount),
//                            'orderid'           => $chk->sanitizedParam($client->ORDER_ID),
                            //'orderid'           => $chk->sanitizedParam($model->REF_ID),
                            'orderid'           => $chk->sanitizedParam($model->INVOICE_ID),
                            'privatekey'        => $privatekey,
                            'checksum'          => $checksum,
                            'mercid'            => $partner->AIRPAY_MERCHANT_ID,
                            'customvar'            => $model->AMOUNT.'|'.$model->REF_ID,
                            //'mer_dom'           => base64_encode(urlencode('http://localhost')),
                            'currency'          => '356',
                            'isocurrency'       => 'INR',
                        );

                        Yii::$app->session['transaction_order_id'] = $client->ORDER_ID;
                        Yii::info('data sent to airpay : '.json_encode($post_data), 'apiinfo');

                        return $this->renderPartial('sendtoarpay', [
                            'post_data' => $post_data
                        ]);

                        Yii::$app->end();

                    } else {
                        var_dump($client->getErrors()); exit;
                    }
                } else {
                    var_dump($order->getErrors()); exit;
                }

            } else {
                //echo '<pre>';print_r($model->getErrors());exit;
                return $this->render('payment', [
                    'model' => $model,
                    'client' => $client,
                    'theme' => $this->getView()->theme
                ]);
            }
        }

        return $this->render('payment', [
            'model' => $model,
            'client' => $client,
            'theme' => $this->getView()->theme
        ]);
    }

    public function actionPaymentResponse()
    {
    	if(strpos(Yii::$app->request->post('CUSTOMVAR'), 'BBPS') !== false){
           return  Yii::$app->runAction('/bbps/default/paymentresponse', $_POST);
        } else {
        Yii::$app->setComponents(['theme' => 'payment', 'view' => [
            'class' => 'yii\web\View',
            'theme' => [
                'baseUrl' => '@web/themes/payment',
                'pathMap' => ['@app/views' => '@app/themes/payment'],

            ],
        ]]);
    	$is_groupinvoice = false;

        $TRANSACTIONID = Yii::$app->request->post('TRANSACTIONID');
        $APTRANSACTIONID = Yii::$app->request->post('APTRANSACTIONID');
        $AMOUNT = Yii::$app->request->post('AMOUNT');
        $TRANSACTIONSTATUS = Yii::$app->request->post('TRANSACTIONSTATUS');
        $MESSAGE = Yii::$app->request->post('MESSAGE');
    	$CUSTOMVAR = Yii::$app->request->post('CUSTOMVAR');
        $ap_SecureHash = Yii::$app->request->post('ap_SecureHash');

        $log_data = isset($_POST) ? json_encode($_POST) : json_encode(array());
        Yii::info('airpay payment response  with data : ' . $log_data, 'apiinfo');
        
        if(!empty($CUSTOMVAR)) {
            $is_groupinvoice = ($CUSTOMVAR == 'is_groupinvoice=1');
        }
    
       if($is_groupinvoice) {
            
            return Yii::$app->runAction('group/payment-response', $_POST);
            

        } else {

        $invoice = Invoice::find()->where(['INVOICE_ID' => $TRANSACTIONID])->one();

        if($invoice===null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if($TRANSACTIONSTATUS == 'c')   {
            $this->redirect(['view', 'id' => $invoice->INVOICE_ID]);
        }

        $order = Order::find()->where(['INVOICE_ID'=>$invoice->INVOICE_ID, 'BELONGS_TO_GROUP' => 'N'])->orderBy('CREATED_ON DESC')->one();

        if($order===null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

//        $invoice = $this->findModel($order->INVOICE_ID);

        $partner = $invoice->partner;
        $this->vendor_logo = $partner->VENDOR_LOGO;
        $template = $partner->INVOICE_EMAIL_TEMPLATE;
       	$bank_logo = $partner->merchant->BANK_LOGO;

        $merchant_secure_hash = sprintf("%u", crc32 ($TRANSACTIONID.':'.$APTRANSACTIONID.':'.$AMOUNT.':'.$TRANSACTIONSTATUS.':'.$MESSAGE.':'.$partner->AIRPAY_MERCHANT_ID.':'.$partner->AIRPAY_USERNAME));

        if($ap_SecureHash != $merchant_secure_hash) {
            throw new HttpException(503, 'Secure Hash mismatch.');
        }

        $order->RECEIVED_AMOUNT = $AMOUNT;
        $order->TRANSACTION_ID = $APTRANSACTIONID;
        $order->TRANSACTION_MESSAGE = $MESSAGE;
        $order->TRANSACTION_STATUS = $TRANSACTIONSTATUS;

        $client = Client::find()->where(['ORDER_ID'=>$order->ORDER_ID])->orderBy('CLIENT_ID DESC')->one();

        $response_message = '';
        if($TRANSACTIONSTATUS == 200)   {
            $order->PAYMENT_STATUS = 1;
            $response_message = 'The transaction was successful.';
        }   else    {
            $order->PAYMENT_STATUS = 2;
            $response_message = 'Your transaction has failed';
        }

        $order->save();
        $total_amount = $this->getInvoiceTotal($invoice->INVOICE_ID);
        if($total_amount > $invoice->TOTAL_AMOUNT)    {
            $total_amount = $invoice->TOTAL_AMOUNT;
        }

        $invoice->PAID = $total_amount;
        //var_dump($total_amount); //exit;
        //var_dump( $invoice->AMOUNT);
        //var_dump( $invoice->TOTAL_AMOUNT); //exit;
        $invoice->BALANCE = $invoice->TOTAL_AMOUNT - $invoice->PAID;
       // var_dump( $invoice->BALANCE); exit;
        if($invoice->BALANCE < 0)
            $invoice->BALANCE = 0;

        if($invoice->TOTAL_AMOUNT == $total_amount)
            $invoice->INVOICE_STATUS = 1;

        $client = Client::find()->where(['ORDER_ID'=>$order->ORDER_ID])->orderBy('CLIENT_ID DESC')->one();
        if(empty($invoice->CLIENT_MOBILE))	{
            $invoice->CLIENT_MOBILE = null;
        }

        //var_dump($invoice->save()); exit;
        if($invoice->save()) {
            if($order->PAYMENT_STATUS == 1) {
                $ghelper = new generalHelper();
                $ghelper->sendTransactionMail($order, $invoice, $client, $template);
            }
        }

        return $this->render('transaction_complete', [
            'response_message' => $response_message,
            'status' => ($TRANSACTIONSTATUS == 200),
            'invoice' => $invoice,
            'order' => $order,
            'client' => $client,
            'theme' => $this->getView()->theme,
        	'bank_logo' => $bank_logo
        ]);
      }
     }
    }

    protected function getInvoiceTotal($invoice_id) {
        $mdl = Order::find()
            ->select('INVOICE_ID, RECEIVED_AMOUNT')
            ->andWhere(['INVOICE_ID' => $invoice_id])
            ->andWhere(['PAYMENT_STATUS' => 1]);

        $sum = $mdl->sum('RECEIVED_AMOUNT');
        //echo "<pre>"; var_dump($sum); exit;
        return !empty($sum)?$sum:0;
    }
    
    public function actionSendReminder($id) {
        $model = $this->findModel($id);
        $invoice_helper = new invoiceHelper();
        if(empty($model->CLIENT_EMAIL)) {
            Yii::$app->getSession()->setFlash('error', "Client Email id not defined!");
            return $this->redirect(Yii::$app->request->referrer);
        }
        if(empty($model->CLIENT_MOBILE)) {
                    Yii::$app->getSession()->setFlash('error', "Client Mobile number not defined!");
                    return $this->redirect(Yii::$app->request->referrer);
        }

        $sentmail = $invoice_helper->sendInvoiceReference($model);
        $sent = $invoice_helper->sendInvoiceReferenceSMS($model, true);

            //$sent = $invoice_helper->sendInvoiceReferenceSMS($model, true);
            if($sentmail && $sent){
                Yii::$app->getSession()->setFlash('success', '<div class="alert alert-success">Reminder sent successfully.</div>');
            } else {
                Yii::$app->getSession()->setFlash('error', '<div class="alert alert-danger">Error occurred while sending Reminder.</div>');
            }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSendSms($id) {
        $model = $this->findModel($id);

        $invoice_helper = new invoiceHelper();
        if(!empty($model->CLIENT_MOBILE))   {
            $sent = $invoice_helper->sendInvoiceReferenceSMS($model, true);
            if($sent){
                Yii::$app->getSession()->setFlash('success', '<div class="alert alert-success">SMS sent successfully.</div>');
            } else {
                Yii::$app->getSession()->setFlash('error', '<div class="alert alert-danger">Error occurred while sending SMS.</div>');
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param $id
     * Send Invoice Mail to Client
     */
    public function actionSendMail($id) {
        $model = $this->findModel($id);
        if(empty($model->CLIENT_EMAIL)) {
            Yii::$app->getSession()->setFlash('error', "Client Email id not defined!");
            return $this->redirect(Yii::$app->request->referrer);
        }
        $ihelper = new invoiceHelper();
        if($ihelper->sendInvoiceReference($model)) {
            Yii::$app->getSession()->setFlash('success', '<div class="alert alert-success">Mail sent successfully.</div>');
        }   else    {
            Yii::$app->getSession()->setFlash('error', '<div class="alert alert-danger">Error occurred while sending mail.</div>');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

     public function actionApprove($id) {
        $model = $this->findModel($id);
        $model->IS_APPROVE = 1;
        $update = $model->update();
        //var_dump($update); exit;

            if($update){
                Yii::$app->getSession()->setFlash('success', '<div class="alert alert-success">Invoice approved successfully.</div>');
            } else {
                Yii::$app->getSession()->setFlash('error', '<div class="alert alert-danger">Error occurred while approving invoice.</div>');
            }


        return $this->redirect(Yii::$app->request->referrer);
    }



    /**
     * @param $id
     * Get airpay transaction details
     */

    public function actionGetAirpayStatus($id)
    {
        $model = $this->findModel($id);
        $invoice=$model;
        $msg ='';
        if($invoice->INVOICE_STATUS==1){
            $msg = '<div class="alert alert-danger">Transaction already paid</div>';
            Yii::$app->getSession()->setFlash('error',$msg);
            return $this->redirect(Yii::$app->request->referrer);
        }

        $order= Order::find()->where(['INVOICE_ID' =>$id])->orderBy('CREATED_ON DESC')->one(); // $params is not needed
        if($order===null) {
            $msg = '<div class="alert alert-danger">Transaction not available</div>';
            Yii::$app->getSession()->setFlash('error',$msg);
            return $this->redirect(Yii::$app->request->referrer);
        }

        if(!empty($order))
        {
            $partner = Partner::find()->where(['PARTNER_ID'=>$model->PARTNER_ID])->one();
            if(!empty($partner)){
                $company_id=$partner->PARTNER_ID;
            }

            $airpay_id = 0;

            if(!empty($_GET['airpayid']))  {
                $airpay_id = trim($_GET['airpayid']);
            }

            $mercid = $partner->AIRPAY_MERCHANT_ID;
            $merchant_txnId=$model->REF_ID;
            $username=$partner->AIRPAY_USERNAME;
            $password=$partner->AIRPAY_PASSWORD;
            $secret=$partner->AIRPAY_SECRET_KEY;

            $checksum_helper= new Checksum();
            $privatekey = $checksum_helper->encrypt($username.":|:".$password, $secret);

            $helper =new generalHelper();

            $url = 'https://payments.airpay.co.in/order/verify.php';
            if(empty($airpay_id))   {
                $data = array('mercid' => $mercid, 'merchant_txnId' => $merchant_txnId, 'privatekey'=> $privatekey);
            }   else    {
                $data = array('mercid' => $mercid, 'airpayId' => $airpay_id, 'privatekey'=> $privatekey);

            }

            $result= $helper->sendDataOverPost($url, $data, 'POST', $timeout=30, $port=443);



            $tmp_xml = simplexml_load_string($result, null, LIBXML_NOCDATA);
            //echo $tmp_xml;exit;
            $tmp_json = json_encode($tmp_xml);
            $data_array = json_decode($tmp_json, true);
            //echo "<pre>";
            //var_dump($data_array);exit;

            if($data_array['TRANSACTION']['TRANSACTIONSTATUS']==200)
            {
                $order->RECEIVED_AMOUNT=$data_array['TRANSACTION']['AMOUNT'];
                $order->PAYMENT_STATUS=1;
                $order->TRANSACTION_ID=$data_array['TRANSACTION']['APTRANSACTIONID'];
                $order->TRANSACTION_STATUS=$data_array['TRANSACTION']['TRANSACTIONSTATUS'];
                $order->TRANSACTION_MESSAGE=$data_array['TRANSACTION']['MESSAGE'];

                $merchant_secure_hash = sprintf("%u", crc32 ($merchant_txnId.':'.$order->TRANSACTION_ID.':'.$order->RECEIVED_AMOUNT.':'.$order->TRANSACTION_STATUS.':'.$order->TRANSACTION_MESSAGE.':'.$mercid.':'.$username));

                $this->render('airpay_status', array(
                    'TRANSACTIONID' => $merchant_txnId,
                    'APTRANSACTIONID' => $order->TRANSACTION_ID,
                    'AMOUNT' => $order->RECEIVED_AMOUNT,
                    'TRANSACTIONSTATUS' => $order->TRANSACTION_STATUS,
                    'MESSAGE' => $order->TRANSACTION_MESSAGE,
                    'merchant_secure_hash' => $merchant_secure_hash,
                    'company' => $partner,
                    'order_id' => $order->ORDER_ID
                ));
                $msg = '<div class="alert alert-success">Transaction status updated successfully  </div>';
            } else {
                $msg = '<div class="alert alert-danger">Transaction details not available</div>';
            }
            Yii::$app->getSession()->setFlash('error',$msg);
        }
        return $this->redirect(Yii::$app->request->referrer);

    }

    public function actionSetOrder() {
        if(!empty($_POST['PAYMENT_ORDER_ID']))  {
            Yii::$app->session['transaction_order_id'] = $_POST['PAYMENT_ORDER_ID'];
        }
    }

    /**
     * Displays a single Invoice model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewdetails($id)
    {
        $mid = Yii::$app->request->get('mid');
        $model = $this->findModel($id);
        if($model->ISSUE_DATE < time()) {
            //throw new HttpException(503, 'The invoice has expired.');
        }
        if(!empty($model->ISSUE_DATE)) {
            $model->ISSUE_DATE = date("d-m-Y", $model->ISSUE_DATE);
        }

        if(!empty($model->DUE_DATE)) {
            $model->DUE_DATE = date("d-m-Y", $model->DUE_DATE);
        }

        return $this->render('details', [
            'model' => $model
            //'mid' => $mid,
        ]);
    }

    public function actionMarkPaid()
    {
        $id = Yii::$app->request->post('invoice_id');
        $comment = Yii::$app->request->post('comment');
        $comment= addslashes($comment);
        $invoice = Invoice::findOne($id);
        if($invoice===null){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $invoice->INVOICE_STATUS = 1;
        $invoice->PAYMENT_CUSTOM_MESSAGE = $comment;
        $invoice->PAID = $invoice->TOTAL_AMOUNT;
        $update = $invoice->Update($invoice->INVOICE_ID);

        if($update){
            $msg = '<div class="alert alert-success">Invoice marked as paid successfully.</div>';
             Yii::$app->getSession()->setFlash('error',$msg);
            $create_url = \yii\helpers\Url::to(['viewdetails', 'id' => $invoice->INVOICE_ID]);
            return $this->redirect($create_url);
        }

    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {  
        //code uploaded on 02-07-2018
        $MERCHANT_ID = Yii::$app->user->identity->MERCHANT_ID;
        $merchant = MerchantMaster::findOne($MERCHANT_ID);
        $isQR = $merchant->CREATE_QR;
        //
        $model = new Invoice();
        //$model->EXPIRY_DATE = date('d-M-Y, h:i a',strtotime(date('Y-m-d H:i:s'). ' +7 day'));

        if($model->load(Yii::$app->request->post())) {
            $model->ATTACHMENTPDF = UploadedFile::getInstance($model,'ATTACHMENTPDF');


            if(Yii::$app->getUser()->getIdentity()->USER_TYPE == 'partner'){
                $mechant_user = UserMaster::find()->where([
                    'USER_TYPE' => 'merchant',
                    'MERCHANT_ID' => Yii::$app->getUser()->getIdentity()->MERCHANT_ID
                ])->one();
            } else {
                //$partner_details = Partner::find()->where(['PARTNER_ID'=>$model->PARTNER_ID])->one();
                $partner_details = UserMaster::find()->where([
                    'USER_ID' => $model->ASSIGN_TO
                ])->one();
                if(!empty($partner_details)){
                    $mechant_user = UserMaster::find()->where([
                        'USER_TYPE' => 'merchant',
                        'MERCHANT_ID' => $partner_details->MERCHANT_ID
                    ])->one();
                }

            }


            if(!empty($mechant_user))   {
                $model->CLIENT_EMAIL = $mechant_user->EMAIL;
                $model->CLIENT_MOBILE = $mechant_user->MOBILE;
            }

            if ($model->validate()) {

                if($model->save()) {
                    if(!empty($model->ATTACHMENTPDF))  {
                        $extension = explode('.',$model->ATTACHMENTPDF->name);
                        $ext = end($extension);
                        $filename = md5(time().$model->ATTACHMENTPDF->name).'.'.$ext;
                        if($model->ATTACHMENTPDF->saveAs(Yii::$app->basePath.'/web/uploads/attachment/'.$filename)) {
                            if(!empty($model->ATTACHMENT))  {
                                unlink(Yii::$app->basePath.'/web/uploads/attachment/'.$model->ATTACHMENT);
                            }
                            $attach_model = Invoice::findOne($model->INVOICE_ID);
                            $attach_model->ATTACHMENT = $filename;
                            $s = $attach_model->update();
                        }
                    }
                    $ihelper = new invoiceHelper();
                    $ihelper->sendInvoiceReferenceSMS($model);
                    $ihelper->sendInvoiceReference($model);
                    $create_url = \yii\helpers\Url::to(['viewdetails', 'id' => $model->INVOICE_ID]);
                    return $this->redirect($create_url);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'isQR' => $isQR, 
        ]);
    }

    /**
     * Updates an existing Invoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {   $MERCHANT_ID = Yii::$app->user->identity->MERCHANT_ID;
        $merchant = MerchantMaster::findOne($MERCHANT_ID);
        $isQR = $merchant->CREATE_QR;
        $model = $this->findModel($id);

         $model->ISSUE_DATE = date("d-M-Y, h:i a", $model->ISSUE_DATE);
        $model->DUE_DATE = date("d-M-Y, h:i a", $model->DUE_DATE);

        if(isset($_POST['Invoice'])) {
            $model->attributes=$_POST['Invoice'];
            //echo "<pre>";
            //var_dump($model->attributes); exit;
            $model->IS_CORPORATE=isset($_POST['Invoice']['IS_CORPORATE'])?$_POST['Invoice']['IS_CORPORATE']:'N';
            if($model->IS_CORPORATE != 1) {
                $model->COMPANY_NAME = '';
            }
            $model->ATTACHMENTPDF=UploadedFile::getInstance($model,'ATTACHMENTPDF');

            if($model->save()) {

                if(!empty($model->ATTACHMENTPDF))  {
                    $ext = end(explode('.',$model->ATTACHMENTPDF->name));
                    $filename = md5(time().$model->ATTACHMENTPDF->name).'.'.$ext;
                    if($model->ATTACHMENTPDF->saveAs(Yii::$app->basePath.'/uploads/attachment/'.$filename)) {
                        if(!empty($model->ATTACHMENT))  {
                            unlink(Yii::$app->basePath.'/uploads/attachment/'.$model->ATTACHMENT);
                        }
                        $attach_model = Invoice::findOne($model->INVOICE_ID);
                        $attach_model->ATTACHMENT = $filename;
                        $save1 = $attach_model->update();
                        echo "<pre>";
                        var_dump($save1); exit;
                    }
                }
                $create_url = \yii\helpers\Url::to(['viewdetails', 'id' => $model->INVOICE_ID]);
                if(!empty(Yii::$app->request->get('mid'))) {
                    $create_url .= '?mid=' . Yii::$app->request->get('mid');
                }

                return $this->redirect($create_url);
            } else {
                //var_dump($model->getErrors()); exit("here");
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            $ihelper = new invoiceHelper();
            $ihelper->sendInvoiceReferenceSMS($model,$messageTemplate);
        }else {
            return $this->render('update', [
                'model' => $model,
                'isQR' => $isQR
            ]);
        }
    }

    /**
     * Deletes an existing Invoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    


     public function actionCreatepoinvoices(){
        if(Yii::$app->request->post()){
            //echo '<pre>';print_r($_POST);exit;
            $post = Yii::$app->request->post();
            $issuedate = strtotime($_POST['issuedate']);
            $duedate = strtotime($_POST['duedate']);
            $partner_id = Yii::$app->getUser()->getIdentity()->PARTNER_ID;
            $assign_to = Yii::$app->getUser()->getIdentity()->USER_ID;
            $mechant_user = UserMaster::find()->where([
                    'USER_TYPE' => 'merchant',
                    'MERCHANT_ID' => Yii::$app->getUser()->getIdentity()->MERCHANT_ID
            ])->one();
 
            for($i=1;$i<$_POST['count'];$i++){
                $poid = $_POST['po'.$i];
                $refid = $_POST['refnum'.$i];
                if(!empty($refid)){
                    $invamt = $_POST['invamt'.$i];
                    $taxamt = $_POST['txamt'.$i];
                    $totalamt = $invamt + $taxamt;
                    
                    //file upload
                    $file_tmp =$_FILES['updoc'.$i]['tmp_name'];
                    $extension = explode('.',$_FILES['updoc'.$i]['name']);
                    $ext = end($extension);
                    $filename = md5(time().$_FILES['updoc'.$i]['name']).'.'.$ext;
                    move_uploaded_file($file_tmp,Yii::$app->basePath.'/web/uploads/attachment/'.$filename);
            
                    //ends here
                    $tym = time();
                    $connection = Yii::$app->getDb();
                    $sql = "insert into tbl_invoice (PARTNER_ID,ASSIGN_TO,REF_ID,PO_ID,CLIENT_EMAIL,CLIENT_MOBILE,ATTACHMENT,OG_AMOUNT,AMOUNT_TAX,AMOUNT,TOTAL_AMOUNT,BALANCE,ISSUE_DATE,DUE_DATE,CREATED_ON) values ('$partner_id','$assign_to','$refid','$poid','$mechant_user->EMAIL','$mechant_user->MOBILE','$filename','$invamt','$taxamt','$totalamt','$totalamt','$totalamt','$issuedate','$duedate','$tym')";

       
                    $command = $connection->createCommand($sql);

                    $result = $command->execute();
                    $id = Yii::$app->db->getLastInsertID();
                    $model = $this->findModel($id);
                    $ihelper = new invoiceHelper();
                    $ihelper->sendInvoiceReferenceSMS($model);
                    $ihelper->sendInvoiceReference($model);
                }
            }

           return $this->redirect(['/invoice']);
  
        }
    }

    /**
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

        public function actionGetinvoiceamt(){
        $post = Yii::$app->request->post('PO_ID');
        $invamt = [];
        $invoice_data = Invoice::find()
        ->select('INVOICE_ID, PO_ID,SUM(OG_AMOUNT) as OG_AMOUNT')
        ->groupBy('PO_ID')
        ->where(['PO_ID' => $post])
      //  ->andWhere(['DUE_DATE' ,'', 1])
        ->andWhere('DUE_DATE >= :today', [':today' => strtotime(date('d-m-Y'))])
        ->all();
        
        if(count($invoice_data)){
            foreach ($invoice_data as $key => $value) {
                $po_data = PoMaster::find()->select('PO_ID,AMOUNT')->where(['PO_ID' => $value->PO_ID])->one();
                $invamt[] =  [
                              'INVOICE_ID' => $value->INVOICE_ID,
                              'PO_ID' => $value->PO_ID,
                              'AMOUNT' => $po_data->AMOUNT,
                              'INV_AMT' => $value->OG_AMOUNT ];
            }
        }else{
                $po_data = PoMaster::find()
                ->select('PO_ID,AMOUNT')
                ->where([
                    'PO_ID' => $post,
                ])->all();
                
                if(count($po_data)){
                    foreach ($po_data as $key => $value) {
                        $invamt[] =  [
                                      'PO_ID' => $value->PO_ID,
                                      'AMOUNT' => $value->AMOUNT,
                                     ];
                    }
                }       
        }
        
        echo json_encode($invamt);exit;
        
    }

     public function actionPolisting(){
     
        $cuser = \Yii::$app->user->identity;
        $mercid = $cuser->MERCHANT_ID;
        $partnerid = $_POST['partnerid'];
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("SELECT tbl_po_master.PO_ID,tbl_po_master.PO_NUMBER,tbl_po_master.AMOUNT as poamt,sum(tbl_invoice.AMOUNT) as invamt from tbl_po_master left join tbl_invoice on tbl_po_master.PO_ID = tbl_invoice.PO_ID where tbl_po_master.MERCHANT_ID= '$mercid' and tbl_po_master.PARTNER_ID='$partnerid' and  tbl_po_master.STATUS='A' group by tbl_po_master.PO_ID", []);
        $podata = $command->queryAll();

       $html = '<option value="">Select</option>';
       foreach ($podata as $key => $value) {
           if($value['invamt'] < $value['poamt']){
              $html .= '<option value="'.$value['PO_ID'].'">'.$value['PO_NUMBER'].'</option>';
           }
       }
     
      echo $html;exit;
     }

     public function actionDownloadreport(){
              if(Yii::$app->request->post()){
            $qpi = Yii::$app->request->post('qpi');
            $cat = implode(',',$_POST['cat']);
              
            $cat = \app\models\CategoryMaster::find()->all();
            $listData = yii\helpers\ArrayHelper::map($cat, 'CAT_ID', 'CAT_NAME');
       
            $connection = Yii::$app->getDb();
        
            $data = [];
            // output headers so that the file is downloaded rather than displayed
            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename="report.csv"');
             
            // do not cache the file
            header('Pragma: no-cache');
            header('Expires: 0');

            // create a file pointer connected to the output stream
            $file = fopen('php://output', 'w');
           
            if($qpi == '1'){
           
                $command = $connection->createCommand("select tbl_quotation_master.NAME,	tbl_quotation_master.DESCRIPTION, tbl_quotation_master.CAT_ID, tbl_quotation_master.DUE_DATE, tbl_quotation_master.STATUS as QSTATUS,	tbl_quotation_master.ASSIGN_PARTNER,tbl_quotation_master.ASSIGN_DATE,tbl_quotation_master.NEW_PARTNER_EMAIL,tbl_po_master.PARTNER_ID,tbl_po_master.SAP_REFERENCE,tbl_po_master.PO_NUMBER,tbl_po_master.DATE_OF_CREATION,tbl_po_master.AMOUNT,tbl_po_master.IS_PAID,tbl_po_master.QUOTATION_ID,tbl_po_master.STATUS,tbl_invoice.PARTNER_ID as INPARTNER_ID,tbl_invoice.REF_ID,tbl_invoice.PO_ID,tbl_invoice.CLIENT_EMAIL,tbl_invoice.CLIENT_MOBILE,tbl_invoice.AMOUNT as INAMOUNT,tbl_invoice.ISSUE_DATE,tbl_invoice.INVOICE_STATUS from tbl_quotation_master left join tbl_po_master on tbl_po_master.QUOTATION_ID = tbl_quotation_master.ID left join tbl_invoice on tbl_invoice.PO_ID = tbl_po_master.PO_ID");
            
            $result = $command->queryAll();
             
            // send the column headers
            fputcsv($file, array('NAME', 'DESCRIPTION', 'CAT_ID', 'DUE_DATE','STATUS','ASSIGN_PARTNER','ASSIGN_DATE','NEW_PARTNER_EMAIL','PARTNER_ID', 'SAP_REFERENCE', 'PO_NUMBER', 'DATE_OF_CREATION','AMOUNT', 'IS_PAID','QUOTATION_ID','STATUS','PARTNER_IDs', 'REF_ID', 'PO_ID', 'AMOUNT','CLIENT_EMAIL', 'CLIENT_MOBILE','ISSUE_DATE','INVOICE_STATUS'));

            foreach($result as $key => $val){
                $partner = Partner::find()->where(['PARTNER_ID'=>$val['ASSIGN_PARTNER']])->one();
                
                $data['NAME'] = $val['NAME'];
                $data['DESCRIPTION'] = $val['DESCRIPTION'];
                $data['CAT_ID'] = $listData[$val['CAT_ID']];//$val['CAT_ID'];
                $data['DUE_DATE'] = date('d-m-Y',$val['DUE_DATE']);
                $data['STATUS'] = $val['QSTATUS'];
                $data['ASSIGN_PARTNER'] = $partner->PARTNER_NAME;//$val['ASSIGN_PARTNER'];
                $data['ASSIGN_DATE'] = date('d-m-Y',$val['ASSIGN_DATE']);
                $data['NEW_PARTNER_EMAIL'] = $val['NEW_PARTNER_EMAIL'];
                $data['PARTNER_ID'] = $partner->PARTNER_NAME;//$val['PARTNER_ID'];
                $data['SAP_REFERENCE'] = $val['SAP_REFERENCE'];
                $data['PO_NUMBER'] = $val['PO_NUMBER'];
                $data['DATE_OF_CREATION'] = date('d-m-Y',$val['DATE_OF_CREATION']);
                $data['AMOUNT'] = $val['AMOUNT'];
                $data['IS_PAID'] = $val['IS_PAID'];
                $data['QUOTATION_ID'] = $val['QUOTATION_ID'];
                $data['STATUSs'] = $val['STATUS'];
                $data['PARTNER_IDs'] = $val['INPARTNER_ID'];
                $data['REF_ID'] = $val['REF_ID'];
                $data['PO_ID'] = $val['PO_ID'];
                $data['AMOUNTs'] = $val['INAMOUNT'];
                $data['CLIENT_EMAIL'] = $val['CLIENT_EMAIL'];
                $data['CLIENT_MOBILE'] = $val['CLIENT_MOBILE'];
                $data['ISSUE_DATE'] = date('d-m-Y',$val['ISSUE_DATE']);
                $data['INVOICE_STATUS'] = $val['INVOICE_STATUS'];                
               fputcsv($file, $data);
            }

            fclose($file);
            exit;
            
            }else if($qpi == '2'){
            
          
             
            $command = $connection->createCommand("select NAME,	DESCRIPTION, CAT_ID, DUE_DATE, STATUS,	ASSIGN_PARTNER,ASSIGN_DATE,NEW_PARTNER_EMAIL from tbl_quotation_master where CAT_ID in ('".$cat."') and STATUS ='".$_POST['status']."'");
            
            $result = $command->queryAll();
             
            // send the column headers
            fputcsv($file, array('NAME', 'DESCRIPTION', 'CAT_ID', 'DUE_DATE','STATUS','ASSIGN_PARTNER','ASSIGN_DATE','NEW_PARTNER_EMAIL'));

            foreach($result as $key => $val){
                $data['NAME'] = $val['NAME'];
                $data['DESCRIPTION'] = $val['DESCRIPTION'];
                $data['CAT_ID'] = $val['CAT_ID'];
                $data['DUE_DATE'] = date('d-m-Y',$val['DUE_DATE']);
                $data['STATUS'] = $val['STATUS'];
                $data['ASSIGN_PARTNER'] = $val['ASSIGN_PARTNER'];
                $data['ASSIGN_DATE'] = date('d-m-Y',$val['ASSIGN_DATE']);
                $data['NEW_PARTNER_EMAIL'] = $val['NEW_PARTNER_EMAIL'];
                fputcsv($file, $data);
            }

            fclose($file);
            exit;
            
            }else if($qpi == '3'){
             
               $command = $connection->createCommand("select tbl_po_master.PARTNER_ID,tbl_po_master.SAP_REFERENCE,tbl_po_master.PO_NUMBER,tbl_po_master.DATE_OF_CREATION,tbl_po_master.AMOUNT,tbl_po_master.IS_PAID,tbl_po_master.QUOTATION_ID,tbl_po_master.STATUS from tbl_po_master join tbl_quotation_master on tbl_quotation_master.ID = tbl_po_master.QUOTATION_ID where tbl_quotation_master.CAT_ID in ('".$cat."') and tbl_quotation_master.STATUS ='".$_POST['status']."'");
            $result = $command->queryAll();
             
            // send the column headers
            fputcsv($file, array('PARTNER_ID', 'SAP_REFERENCE', 'PO_NUMBER', 'DATE_OF_CREATION','AMOUNT', 'IS_PAID','QUOTATION_ID','STATUS'));

            foreach($result as $key => $val){
                $data['PARTNER_ID'] = $val['PARTNER_ID'];
                $data['SAP_REFERENCE'] = $val['SAP_REFERENCE'];
                $data['PO_NUMBER'] = $val['PO_NUMBER'];
                $data['DATE_OF_CREATION'] = $val['DATE_OF_CREATION'];
                $data['AMOUNT'] = $val['AMOUNT'];
                $data['IS_PAID'] = $val['IS_PAID'];
                $data['QUOTATION_ID'] = $val['QUOTATION_ID'];
                $data['STATUS'] = $val['STATUS'];
                fputcsv($file, $data);
            }

            fclose($file);
            exit;
            
            }else if($qpi == '4'){
             
               $command = $connection->createCommand("select tbl_invoice.PARTNER_ID,tbl_invoice.REF_ID,tbl_invoice.PO_ID,tbl_invoice.CLIENT_EMAIL,tbl_invoice.CLIENT_MOBILE,tbl_invoice.AMOUNT,tbl_invoice.ISSUE_DATE,tbl_invoice.  INVOICE_STATUS from tbl_invoice join tbl_po_master on tbl_po_master.PO_ID = tbl_invoice.PO_ID join tbl_quotation_master on tbl_quotation_master.ID = tbl_po_master.QUOTATION_ID  where tbl_quotation_master.CAT_ID in ('".$cat."') and tbl_quotation_master.STATUS ='".$_POST['status']."'");
            
              $result = $command->queryAll();
             
            // send the column headers
            fputcsv($file, array('PARTNER_ID', 'REF_ID', 'PO_ID', 'AMOUNT','CLIENT_EMAIL', 'CLIENT_MOBILE','ISSUE_DATE','INVOICE_STATUS'));

            foreach($result as $key => $val){
                $data['PARTNER_ID'] = $val['PARTNER_ID'];
                $data['REF_ID'] = $val['REF_ID'];
                $data['PO_ID'] = $val['PO_ID'];
                $data['AMOUNT'] = $val['AMOUNT'];
                $data['CLIENT_EMAIL'] = $val['CLIENT_EMAIL'];
                $data['CLIENT_MOBILE'] = $val['CLIENT_MOBILE'];
                $data['ISSUE_DATE'] = date('d-m-Y',$val['ISSUE_DATE']);
                $data['INVOICE_STATUS'] = $val['INVOICE_STATUS'];
                fputcsv($file, $data);
            }

            fclose($file);
            exit;
            }
             
         }
//       
     }
}
