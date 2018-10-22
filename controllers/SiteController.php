<?php

namespace app\controllers;

use app\components\DbDynamic;
use app\helpers\generalHelper;
use app\models\Invoice;
use app\models\MerchantMaster;
use app\models\Partner;
use app\models\UserMaster;
use app\models\UserMerchant;
use Yii;
use yii\base\Hcontroller;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

use app\models\Quotation;
use app\models\TblQuotationPartners;
use app\models\PoMaster;

class SiteController extends Hcontroller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'contact', 'about', 'dashboard', 'checksms'],
                'rules' => [
                    [
                        'actions' => ['index', 'contact', 'about', 'dashboard', 'checksms', 'dashboardnew'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionError()
    {
        $TRANSACTIONID = Yii::$app->request->post('TRANSACTIONID');

        $server_name = Yii::$app->getRequest()->getHeaders()->get('host');

        $domain_arr = explode(".", $server_name);

        $sub_domain = array_shift($domain_arr);
        if ($sub_domain == 'www') {
            $sub_domain = '';
        }

        if (!empty($sub_domain)) {
            $merchant = MerchantMaster::find()->where(['DOMAIN_NAME' => $sub_domain])->one();
            if (!empty($merchant)) {
                $this->merchant_id = $merchant->MERCHANT_ID;
            }
        }

        if (!empty($this->merchant_id)) {
            $partner = Partner::find()->andWhere(['MERCHANT_ID' => $this->merchant_id])->one();
            if (!empty($partner)) {
                $this->vendor_logo = $partner->VENDOR_LOGO;
            }
        } else {
            $invoice = Invoice::find()->where(['REF_ID' => $TRANSACTIONID])->one();

            if (!empty($invoice)) {
                $partner = $invoice->partner;
                $this->vendor_logo = $partner->VENDOR_LOGO;
            }
        }

        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }

    public function actionIndex()
    {
        return $this->render('dashboard');
    }

    public function actionLogin()
    {
        $str = Yii::$app->user->returnUrl;
        $guestUrl = str_replace("/partnerpay/web/", "", $str);
        $session = Yii::$app->session;
        $session->set('guesturl', $guestUrl);
        //var_dump(Yii::$app->getHomeUrl()); exit;
        Yii::$app->setComponents(['theme' => 'payment', 'view' => [
            'class' => 'yii\web\View',
            'theme' => [
                'baseUrl' => '@web/themes/login',
                'pathMap' => ['@app/views' => '@app/themes/login'],
            ],
        ]]);
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        //var_dump(Yii::$app->request->post()); echo "<br>"; exit;
        //var_dump($this->PASSWORD); exit;

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (Yii::$app->user->identity->USER_TYPE == 'guestuser' || Yii::$app->user->identity->USER_TYPE == 'partner') {
                if (strpos($str, 'assignquotation') == false) {
                    if (Yii::$app->user->identity->USER_TYPE == 'partner') {
                        if (!empty(Yii::$app->user->identity->MERCHANT_ID) && Yii::$app->user->identity->MERCHANT_ID == '53') {
                            return $this->redirect(['spicejet/agency']);
                        } else {
                            return $this->redirect(['site/dashboard']);
                        }
                    }
                    return $this->redirect(['guest-user-doc/index']);
                } else {
                    return $this->redirect([$session->get('guesturl')]);
                }
            }
            if (Yii::$app->user->identity->USER_TYPE == 'agent') {
                return $this->redirect(['site/dashboard']);
            }
            return $this->redirect(['site/dashboard']);


        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionTmp($id)
    {

        $password = 'admin';
        $val = Yii::$app->security->generatePasswordHash($password);

        $s = Yii::$app->security->validatePassword($password, $val);
        //var_dump($s); //exit("here");
        // var_dump($val); exit("here");

        /*$connection = new \yii\db\Connection([
            'dsn' => 'mysql:host=localhost;dbname=sample_merchant',
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
        ]);*/

        //$val = new DbDynamic();
        //$val->init(); exit;

        Yii::$app->setComponents([
            'db3' => [
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=localhost;dbname=sample_merchant',
                'username' => 'root',
                'password' => '123456',
                'charset' => 'utf8',
            ]
        ]);

        //var_dump($id); exit;
        $merchantDetails = MerchantMaster::find()->where(['MERCHANT_ID' => $id])->one();
        $dbconn = "DB_" . $merchantDetails->DB_NAME;
        $dbval = Yii::$app->$dbconn;
        //var_dump($dbval); exit;
        $result = Invoice::find()->one(Yii::$app->db3);
        echo "<pre>";
        print_r($result);
        exit;
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionForgotPassword()
    {
        Yii::$app->setComponents(['theme' => 'payment', 'view' => [
            'class' => 'yii\web\View',
            'theme' => [
                'baseUrl' => '@web/themes/login',
                'pathMap' => ['@app/views' => '@app/themes/login'],
            ],
        ]]);
        $msg = "";

        if (!empty($_POST['forgot'])) {
            if (!empty($_POST['email'])) {
                $email = $_POST['email'];

                $model = UserMaster::find()->where(['EMAIL' => $email])->one();

                if (!empty($model)) {
                    $is_error = false;
                    if ($model->USER_STATUS != 'E') {
                        $is_error = true;
                        $msg = '<div class="alert alert-danger">Incorrect username.</div>';
                    } elseif ($model->USER_TYPE != 'admin') {
                        $merchant = MerchantMaster::findOne($model->MERCHANT_ID);
                        if (empty($merchant) || $merchant->MERCHANT_STATUS != 'E') {
                            $is_error = true;
                            $msg = '<div class="alert alert-danger">Incorrect username.</div>';
                        }
                        if ($model->USER_TYPE == 'partner') {
                            $partner = Partner::findOne($model->PARTNER_ID);
                            if (empty($partner) || $partner->PARTNER_STATUS != 'E') {
                                $is_error = true;
                                $msg = '<div class="alert alert-danger">Incorrect username.</div>';
                            }
                        }
                    }

                    if (!$is_error) {
                        $gen_helper = new generalHelper();
                        $new_password = $gen_helper->random_string(10);

                        $model->PASSWORD = $new_password;
                        $model->REPEAT_PASSWORD = $new_password;

                        if ($model->save()) {
                            $gen_helper->sendForgotPasswordMail($model, $new_password);
                            $msg = '<div class="alert alert-success">New password has been sent to your registered mail id.</div>';
                        }
                    }
                } else {
                    $msg = '<div class="alert alert-danger">Please enter registered Email ID</div>';
                }
            } else {
                $msg = '<div class="alert alert-danger">Please enter Email ID</div>';
            }
        }

        Yii::$app->getSession()->setFlash('error', $msg);

        return $this->render('forgot_password');
    }

    public function actionDashboard()
    {
        //echo '<pre>';print_r(Yii::$app->user->identity->EMAIL);exit;
        $date = date('Y-m-d') . ' 00:00:00';
        $start_date = strtotime($date);
        $paid_count = Invoice::find()->andWhere(['INVOICE_STATUS' => 1])->count();
        $unpaid_count = Invoice::find()->andWhere(['INVOICE_STATUS' => 0])->count();
        $approved_count = Invoice::find()->andWhere(['IS_APPROVE' => '1'])->count();
        $total_count = Invoice::find()->count();


        /*  $total_amount_paid = Invoice::find()
              //->where(['PAYMENT_STATUS' => 'P'])
              ->andWhere('CREATED_ON > :start_date', [
                  ':start_date' => $start_date
              ])
              ->sum('TOTAL_AMOUNT');*/


        return $this->render('dashboard', [
            'paid_count' => $paid_count,
            'unpaid_count' => $unpaid_count,
            'total_count' => $total_count,
            'approved_count' => $approved_count,
            //'total_amount_paid' => $total_amount_paid
        ]);


    }

    public function actionChecksms()
    {

        $url = 'http://sms.nowpay.co.in';
        $myvars = 'phone_number=9137000308&message=' . urlencode('hi kinnari how are you');

        $request_headers = array();
        $request_headers[] = 'token:aff8ff6cdd77a969826f18d85ebb52d3';
        $request_headers[] = 'requesturl:' . $_SERVER['HTTP_HOST'];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        echo '<pre>';
        print_r($response);
        exit;
    }

    public function actionDashboardnew()
    {
        $qr_count = Quotation::find()->count();
        $po_count = PoMaster::find()->count();

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("SELECT count(tbl_invoice.INVOICE_ID) as INVOICE_ID FROM tbl_invoice join tbl_po_master on tbl_po_master.PO_ID = tbl_invoice.PO_ID join tbl_quotation_master on tbl_quotation_master.ID = tbl_po_master.QUOTATION_ID");
        $inv_count = $command->queryAll();// echo '<pre>';print_r( $inv_count);exit;
        $inv_count = $inv_count[0]['INVOICE_ID'];

        $command = $connection->createCommand("SELECT sum(tbl_quotation_partners.AMOUNT) as AMOUNT FROM tbl_quotation_master join tbl_quotation_partners on tbl_quotation_master.ASSIGN_PARTNER = tbl_quotation_partners.PARTNER_ID ", []);
        $qr_amount = $command->queryAll();

        $qr_amount = $qr_amount[0]['AMOUNT'];
        $po_amount = PoMaster::find()->sum('AMOUNT');

        $command = $connection->createCommand("SELECT sum(tbl_invoice.AMOUNT) as AMOUNT FROM tbl_invoice join tbl_po_master on tbl_po_master.PO_ID = tbl_invoice.PO_ID join tbl_quotation_master on tbl_quotation_master.ID = tbl_po_master.QUOTATION_ID");
        $inv_amount = $command->queryAll();
        $inv_amount = $inv_amount[0]['AMOUNT'];

        $qr_submitted = Quotation::find()->andWhere(['STATUS' => 'Submitted'])->count();
        $qr_processing = Quotation::find()->andWhere(['STATUS' => 'Processing'])->count();
        $qr_executed = Quotation::find()->andWhere(['STATUS' => 'Executed'])->count();
        $qr_expired = Quotation::find()->andWhere(['STATUS' => 'Expired'])->count();

        $po_pending = PoMaster::find()->andWhere(['STATUS' => ''])->count();
        $po_approved = PoMaster::find()->andWhere(['STATUS' => 'A'])->count();
        $po_rejected = PoMaster::find()->andWhere(['STATUS' => 'R'])->count();

        $command = $connection->createCommand("SELECT count(tbl_invoice.INVOICE_ID) as INVOICE_ID FROM tbl_invoice join tbl_po_master on tbl_po_master.PO_ID = tbl_invoice.PO_ID join tbl_quotation_master on tbl_quotation_master.ID = tbl_po_master.QUOTATION_ID where tbl_invoice.INVOICE_STATUS='1'");
        $inv_paid = $command->queryAll();
        $inv_paid = $inv_paid[0]['INVOICE_ID'];

        $command = $connection->createCommand("SELECT count(tbl_invoice.INVOICE_ID) as INVOICE_ID FROM tbl_invoice join tbl_po_master on tbl_po_master.PO_ID = tbl_invoice.PO_ID join tbl_quotation_master on tbl_quotation_master.ID = tbl_po_master.QUOTATION_ID where tbl_invoice.INVOICE_STATUS='0'");
        $inv_pending = $command->queryAll();
        $inv_pending = $inv_pending[0]['INVOICE_ID'];

        return $this->render('dashboard_new', [
            'qr_count' => $qr_count,
            'po_count' => $po_count,
            'inv_count' => $inv_count,
            'qr_amount' => $qr_amount,
            'po_amount' => $po_amount,
            'inv_amount' => $inv_amount,
            'qr_submitted' => $qr_submitted,
            'qr_processing' => $qr_processing,
            'qr_executed' => $qr_executed,
            'qr_expired' => $qr_expired,
            'po_pending' => $po_pending,
            'po_approved' => $po_approved,
            'po_rejected' => $po_rejected,
            'inv_paid' => $inv_paid,
            'inv_pending' => $inv_pending,
        ]);


        //echo '<pre>';print_r([$qr_count,$po_count,$invoice_count,$qr_amount,$po_amount,$inv_amount]);exit;
    }
}
