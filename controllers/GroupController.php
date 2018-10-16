<?php

namespace app\controllers;

use app\helpers\Checksum;
use app\models\Client;
use app\models\Group;
use app\models\GroupInvoiceMap;
use app\models\GroupInvoiceUploadForm;
use app\models\Invoice;
use app\models\InvoiceSearch;
use app\models\Order;
use app\models\UserMaster;
use Yii;
use app\models\GroupInvoice;
use app\models\GroupInvoiceSearch;
use yii\base\Hcontroller;
use yii\filters\AccessControl;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * GroupController implements the CRUD actions for GroupInvoice model.
 */
class GroupController extends Hcontroller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
//                'only' => ['index', 'update', 'create', 'view', 'delete'],
                'rules' => [
                    [
                        'allow' => (!Yii::$app->user->isGuest && (Yii::$app->user->identity->USER_TYPE == 'merchant')) ? true : false,
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

    protected function addGroup()
    {
        $model = new Group();
        $model->MERCHANT_ID = Yii::$app->getUser()->getIdentity()->MERCHANT_ID;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->MERCHANT_ID = Yii::$app->getUser()->getIdentity()->MERCHANT_ID;
            if($model->save())  {
                return $this->refresh();
            }
        }
        return $this->render('create_group', [
            'model' => $model,
        ]);
    }

    /**
     * Lists all GroupInvoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $group_model = Group::find()->andWhere(['MERCHANT_ID' => Yii::$app->getUser()->getIdentity()->MERCHANT_ID])->one();

        if(empty($group_model)) {
            return $this->addGroup();
        }

        $searchModel = new GroupInvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GroupInvoice model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionDownloadInvoice($id)
    {
        $model = $this->findModel($id);
        $download_arr = [];
        $download_arr[] = ['Invoice Id', 'Amount', 'UTR Number', 'Brand Name', 'Branch Name', 'Payment Date'];
        foreach ($model->groupInvoiceMaps as $map_model)    {
            $payment_date = !empty($map_model->PAYMENT_DATE)?date('d M Y', $map_model->PAYMENT_DATE):'';
            $download_arr[] = [$map_model->INVOICE_ID, $map_model->invoice->TOTAL_AMOUNT, $map_model->UTR_NO,$map_model->BRAND,$map_model->BRANCH, $payment_date];
        }

        $this->array_to_csv_download($download_arr, 'group_invoice_utr.csv');

    }

    protected function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
        // open raw memory as file so no temp files needed, you might run out of memory though
        $f = fopen('php://output', 'w');
        // loop over the input array
        foreach ($array as $line) {
            // generate csv lines from the inner arrays
            //fputcsv($f, $line, $delimiter);        code till 02-03-2017
              fputcsv($f, $line); 
        }
        // reset the file pointer to the start of the file
        fseek($f, 0);
        // tell the browser it's going to be a csv file
        header('Content-Type: application/csv');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="'.$filename.'";');
        // make php send the generated csv lines to the browser
        fpassthru($f);
    }


    public function actionPayment($id)
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

        $model->scenario = GroupInvoice::SCENARIO_PAYMENT;
        $model->PAN_NO = 'temp';
        $model->PARTNER_NAME = 'temp';
        $model->PARTNER_ID = 1;

        if($model->INVOICE_STATUS == 1) {
            throw new HttpException(503, 'Invoice is Paid.');
        }

        $client = new Client();

        if(!empty($model->CLIENT_EMAIL))    {
            $client->EMAIL = $model->CLIENT_EMAIL;
        }else {
            if(!empty($model->group)){
                if(!empty($model->group->EMAIL)) {
                    $client->EMAIL = $model->group->EMAIL;
                }
                if(!empty($model->group->MOBILE)) {
                    $client->PHONE = $model->group->MOBILE;
                }

            }
        }

        if(isset($_POST['Client']) && isset($_POST['GroupInvoice']))
        {
            $client->attributes=$_POST['Client'];

            $model->attributes=$_POST['GroupInvoice'];

            if($client->validate() && $model->validate())  {
                $order = new Order();
                $order->INVOICE_ID = $id;
                $order->PAYMENT_STATUS = 0;
                $order->BELONGS_TO_GROUP = 'Y';
                $order->CREATED_ON = time();

                if($order->save())  {
                    $client->ORDER_ID = $order->ORDER_ID;
                    if($client->save()) {
                        $chk = new Checksum();
                        $alldata =  $chk->sanitizedParam($client->EMAIL).
                            $chk->sanitizedParam($client->FIRST_NAME).
                            $chk->sanitizedParam($client->LAST_NAME).
                            $chk->sanitizedParam($model->TOTAL_AMOUNT).
                            //$chk->sanitizedParam($model->REF_ID);
                            $chk->sanitizedParam($model->GROUP_INVOICE_ID);

                        $privatekey = $chk->encrypt($model->group->AIRPAY_MERCHANT_USERNAME.":|:".$model->group->AIRPAY_MERCHANT_PASSWORD, $model->group->AIRPAY_MERCHANT_SECRETE_KEY);
                        $checksum = $chk->calculateChecksum($alldata.date('Y-m-d'),$privatekey);

                        $post_data = array(
                            'buyerEmail'        => $chk->sanitizedParam($client->EMAIL),
                            'buyerPhone'        => $chk->sanitizedParam($client->PHONE),
                            'buyerFirstName'    => $chk->sanitizedParam($client->FIRST_NAME),
                            'buyerLastName'     => $chk->sanitizedParam($client->LAST_NAME),
                            'amount'            => $chk->sanitizedParam($model->TOTAL_AMOUNT),
//                            'orderid'           => $chk->sanitizedParam($client->ORDER_ID),
                            //'orderid'           => $chk->sanitizedParam($model->REF_ID),
                            'orderid'           => $chk->sanitizedParam($model->GROUP_INVOICE_ID),
                            'privatekey'        => $privatekey,
                            'checksum'          => $checksum,
                            'mercid'            => $model->group->AIRPAY_MERCHANT_KEY,
//                            'customvar'            => $model->AMOUNT.'|'.$model->GROUP_INVOICE_ID,
                            'customvar'         => 'is_groupinvoice=1',
                            'chmod'            => 'pgcc',
                            //'mer_dom'           => base64_encode(urlencode('http://localhost')),
                            'currency'          => '356',
                            'isocurrency'       => 'INR',
                        );

                        Yii::$app->session['transaction_order_id'] = $client->ORDER_ID;
                        Yii::info('data sent to airpay : '.json_encode($post_data), 'apiinfo');

                        return $this->renderPartial('/invoice/sendtoarpay', [
                            'post_data' => $post_data
                        ]);

                        Yi::$app->end();

                    } else {
                        var_dump($client->getErrors()); exit;
                    }
                } else {
                    var_dump($order->getErrors()); exit;
                }

            } else {
//                var_dump($model->getErrors());
//                var_dump($client->getErrors());
//                exit;
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
        Yii::$app->setComponents(['theme' => 'payment', 'view' => [
            'class' => 'yii\web\View',
            'theme' => [
                'baseUrl' => '@web/themes/payment',
                'pathMap' => ['@app/views' => '@app/themes/payment'],

            ],
        ]]);
    //echo "<pre>"; var_dump(Yii::$app->request->post('TRANSACTIONID')); var_dump($_POST); exit;

        $TRANSACTIONID = Yii::$app->request->post('TRANSACTIONID');
        $APTRANSACTIONID = Yii::$app->request->post('APTRANSACTIONID');
        $AMOUNT = Yii::$app->request->post('AMOUNT');
        $TRANSACTIONSTATUS = Yii::$app->request->post('TRANSACTIONSTATUS');
        $MESSAGE = Yii::$app->request->post('MESSAGE');;
        $ap_SecureHash = Yii::$app->request->post('ap_SecureHash');

        $log_data = isset($_POST) ? json_encode($_POST) : json_encode(array());
        Yii::info('airpay payment response  with data : ' . $log_data, 'apiinfo');

        $invoice = GroupInvoice::find()->where(['GROUP_INVOICE_ID' => $TRANSACTIONID])->one();

        if($invoice===null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if($TRANSACTIONSTATUS == 'c')   {
            $this->redirect(['view', 'id' => $invoice->INVOICE_ID]);
        }

        $order = Order::find()->where(['INVOICE_ID'=>$invoice->GROUP_INVOICE_ID, 'BELONGS_TO_GROUP' => 'Y'])->orderBy('CREATED_ON DESC')->one();

        if($order===null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $merchant_secure_hash = sprintf("%u", crc32 ($TRANSACTIONID.':'.$APTRANSACTIONID.':'.$AMOUNT.':'.$TRANSACTIONSTATUS.':'.$MESSAGE.':'.$invoice->group->AIRPAY_MERCHANT_KEY.':'.$invoice->group->AIRPAY_MERCHANT_USERNAME));

        if($ap_SecureHash != $merchant_secure_hash) {
            throw new HttpException(503, 'Secure Hash mismatch.');
        }

        $order->RECEIVED_AMOUNT = $AMOUNT;
        $order->TRANSACTION_ID = $APTRANSACTIONID;
        $order->TRANSACTION_MESSAGE = $MESSAGE;
        $order->TRANSACTION_STATUS = $TRANSACTIONSTATUS;

        $response_message = '';
        if($TRANSACTIONSTATUS == 200)   {
            $order->PAYMENT_STATUS = 1;
            $response_message = 'The transaction was successful.';
        }   else    {
            $order->PAYMENT_STATUS = 2;
            $response_message = 'Your transaction has failed';
        }

        $order->save();
        if($TRANSACTIONSTATUS == 200)   {
            $invoice->INVOICE_STATUS = 1;
             $invoice->PAYMENT_DATE = time();
        }

        $client = Client::find()->where(['ORDER_ID'=>$order->ORDER_ID])->orderBy('CLIENT_ID DESC')->one();

        //var_dump($invoice->save()); exit;
        if($invoice->save()) {
            if($order->PAYMENT_STATUS == 1) {
                foreach ($invoice->groupInvoiceMaps as $map_model)    {
                    $inv = $map_model->invoice;
                    $inv->PAID = $inv->TOTAL_AMOUNT;
                    $inv->BALANCE = 0;
                    $inv->INVOICE_STATUS = 1;
                    $inv->save(false);
                }

//                $ghelper = new generalHelper();
//                $ghelper->sendTransactionMail($order, $invoice, $client, $template);
            }
        }

        return $this->render('transaction_complete', [
            'response_message' => $response_message,
            'status' => ($TRANSACTIONSTATUS == 200),
            'invoice' => $invoice,
            'order' => $order,
            'client' => $client,
            'theme' => $this->getView()->theme
        ]);
    }

    /**
     * Creates a new GroupInvoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $group_model = Group::find()->andWhere(['MERCHANT_ID' => Yii::$app->getUser()->getIdentity()->MERCHANT_ID])->one();

        if(empty($group_model)) {
            return $this->addGroup();
        }

//        $model = new GroupInvoice();
        $model = new GroupInvoiceUploadForm();
        $model->group_id = $group_model->GROUP_ID;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->upload_file = UploadedFile::getInstance($model, 'upload_file');
            if ($model->validate()) {

                $file_path = $model->upload_file->tempName;

                $row = 1;
                $is_valid = true;
                if (($handle = fopen($file_path, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if($row == 1)   {
                            $row++;
                            continue;
                        }
                        $is_valid = $this->validateGroupInvoice($data, $group_model->GROUP_ID);
                        if(!$is_valid)  {
                            Yii::$app->getSession()->setFlash('error', 'Error on row: '. ($row + 1));
                            break;
                        }
                    }
                    fclose($handle);
                }
                if (($handle = fopen($file_path, "r")) !== FALSE) {
                    $total_amount = 0;
                    $invoice_map_arr = [];
                    $row = 1;
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if($row == 1)   {
                            $row++;
                            continue;
                        }

                        $partner_id = isset($data[0])?trim($data[0]):null;
                        $partner_name = isset($data[1])?trim($data[1]):null;
                        $amount = isset($data[2])?trim($data[2]):null;
                    	$brand = isset($data[3])?trim($data[3]):null;
                        $branchname = isset($data[4])?trim($data[4]):null;
                        $pan_no = isset($data[5])?trim($data[5]):null;

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

                        if($invoice->save(false))    {
                            $total_amount += $invoice->TOTAL_AMOUNT;
                            $invoice_map_arr[] = $invoice->INVOICE_ID;
                        }   else    {
                            //var_dump($invoice->getErrors()); exit;
                        }
                    }
                    fclose($handle);

                    if(!empty($invoice_map_arr))    {
                        $group_invoice = new GroupInvoice();

                        $group_invoice->scenario = GroupInvoice::SCENARIO_INSERT;
                        
                        $group_invoice->GROUP_ID = $group_model->GROUP_ID;
                        $group_invoice->AMOUNT = $total_amount;
                    	//$group_invoice->GI_REF_ID = $model->GI_REF_ID;
                        $group_invoice->save(false);
                        
                        foreach ($invoice_map_arr as $invoice_map_id)   {
                            $group_invoice_map = new GroupInvoiceMap();
                            $group_invoice_map->GROUP_INVOICE_ID = $group_invoice->GROUP_INVOICE_ID;
                            $group_invoice_map->INVOICE_ID = $invoice_map_id;
                        	$group_invoice_map->BRAND = $brand;
                            $group_invoice_map->BRANCH = $branchname;
                            $group_invoice_map->save(false);
                        }
                        Yii::$app->getSession()->setFlash('success', 'Invoice created successfully.');
                    }

                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    protected function validateGroupInvoice($data, $group_id)
    {
        $model = new GroupInvoice();
        $model->scenario = GroupInvoice::SCENARIO_INSERT;
        $model->GROUP_ID = $group_id;
        $model->PARTNER_ID = isset($data[0])?trim($data[0]):null;
        $model->PARTNER_NAME = isset($data[1])?trim($data[1]):null;
        $model->PAN_NO = isset($data[2])?trim($data[2]):null;
        $model->AMOUNT = isset($data[3])?trim($data[3]):null;
        return $model->validate();
    }

    public function actionUtrUpdate()
    {
        $group_model = Group::find()->andWhere(['MERCHANT_ID' => Yii::$app->getUser()->getIdentity()->MERCHANT_ID])->one();

        if(empty($group_model)) {
            return $this->addGroup();
        }

//        $model = new GroupInvoice();
        $model = new GroupInvoiceUploadForm();
        $model->group_id = $group_model->GROUP_ID;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->upload_file = UploadedFile::getInstance($model, 'upload_file');
            if ($model->validate()) {

                $file_path = $model->upload_file->tempName;

                $row = 1;
                $is_valid = true;
                if (($handle = fopen($file_path, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if($row == 1)   {
                            $row++;
                            continue;
                        }
                        $invoice_id = isset($data[0])?trim($data[0]):null;
                        $amount = isset($data[1])?trim($data[1]):null;
                        $utr_no = isset($data[2])?trim($data[2]):null;
                        $brand = isset($data[3])?trim($data[3]):null;
                        $branch = isset($data[4])?trim($data[4]):null;
                        $payment_date = isset($data[5])?trim($data[5]):null;
                        if(!empty($payment_date))   {
                            $payment_date = strtotime($payment_date);
                        }

                        $invoice_map = GroupInvoiceMap::find()->andWhere([
                            GroupInvoice::tableName().'.GROUP_ID' => $group_model->GROUP_ID,
                            GroupInvoice::tableName().'.INVOICE_STATUS' => 1,
                            'INVOICE_ID' => $invoice_id,

                        ])
                            ->joinWith('groupInvoice')
                            ->one();
                        if(!empty($invoice_map))    {
                            $invoice_map->UTR_NO = $utr_no;
                            $invoice_map->PAYMENT_DATE = $payment_date;
                            $invoice_map->update(false, ['UTR_NO', 'PAYMENT_DATE']);
                            Yii::$app->getSession()->setFlash('success', 'UTR No Updated successfully.');
                        }

                    }
                    fclose($handle);
                }
            }
        }

        return $this->render('utr_update', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing GroupInvoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->GROUP_INVOICE_ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GroupInvoice model.
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
     * Finds the GroupInvoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GroupInvoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GroupInvoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
