<?php

namespace app\controllers;

use app\helpers\generalHelper;
use app\models\Invoice;
use app\models\UserMaster;
use app\models\UserMerchant;
use app\models\UserMerchantForm;
use Yii;
use app\models\MerchantMaster;
use app\models\MerchantMasterSearch;
use yii\base\Hcontroller;
use yii\base\Model;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * MerchantMasterController implements the CRUD actions for MerchantMaster model.
 */
class MerchantMasterController extends Hcontroller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [

            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'update', 'create', 'view', 'delete'],
                'rules' => [
                    [
                        'actions' => ['update', 'create'],
                        'allow' => (!Yii::$app->user->isGuest && (Yii::$app->user->identity->USER_TYPE == 'admin')) ? true : false,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => (!Yii::$app->user->isGuest && (Yii::$app->user->identity->USER_TYPE != 'partner')) ? true : false,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => false,
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
     * Lists all MerchantMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        //var_dump(Yii::$app->user->identity->USER_TYPE);exit;
        $searchModel = new MerchantMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionInvoice()
    {
        $invoicesRes = '';
        $mid = Yii::$app->request->get('mid');
        if (!empty($mid)) {
            $merchant = MerchantMaster::find()->select('DB_NAME')->where(['MERCHANT_ID' => $mid])->one();
            //var_dump($merchant->DB_NAME); exit;
            if (!empty($merchant)) {
                Yii::$app->setComponents([
                    'db3' => [
                        'class' => 'yii\db\Connection',
                        'dsn' => 'mysql:host=localhost;dbname=' . $merchant->DB_NAME,
                        'username' => Yii::$app->params['DbUsername'],
                        'password' => Yii::$app->params['DbPassword'],
                        'charset' => 'utf8',
                    ]
                ]);
                //var_dump(Yii::$app->db); echo "<br>==========================================================";
                //var_dump(Yii::$app->components); exit;
                $invoicesRes = Invoice::find()->where(['INVOICE_STATUS' => 1])->one(Yii::$app->db3);
                if ($invoicesRes === null) {
                    throw new NotFoundHttpException(404, 'The requested page does not exist.');
                }
                //echo "<pre>";
                //var_dump(Yii::$app->db5); exit;
                //echo "<pre>";
                //var_dump($invoicesRes);

                exit;
            }

        }

        return $this->render('invoice', [
            'dataProvider' => $invoicesRes,

        ]);


    }

    /**
     * Displays a single MerchantMaster model.
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
     * Creates a new MerchantMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {  //echo "<pre>";
       // print_r(Yii::$app->request->post('MerchantMaster')['MERCHANT_STATUS']);
        //exit;
        $model = new MerchantMaster(['scenario' => MerchantMaster::SCENARIO_INSERT]);
        //$user_model = new UserMaster();
//        $model->scenario = MerchantMaster::SCENARIO_INSERT;\
       // $user_model->setScenario('insert');
        //$user_model->USER_TYPE = 'merchant';
       // $user_model->MERCHANT_ID = 1;
        if(Yii::$app->request->post('MerchantMaster')['MERCHANT_STATUS']){
            $model->MERCHANT_STATUS = 'E';
        } else {
            $model->MERCHANT_STATUS = 'D';
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->LOGO = UploadedFile::getInstance($model, 'LOGO');
            if($model->validate())  {
               // $password = $user_model->PASSWORD;
                if ($model->save()) {
                    //$user_model->MERCHANT_ID = $model->MERCHANT_ID;
                   // if ($user_model->save()) {
                        //$ghelper = new generalHelper();
                        //$ghelper->sendMerchantDetails($user_model, $password);
                        return $this->redirect(['view', 'id' => $model->MERCHANT_ID]);
                  //  }
                }
            }
        }
        return $this->render('create', [
            'model' => $model,
           //'usermodel' => $user_model,
        ]);

    }

    public function actionCreate_old()
    {
        $model = new MerchantMaster(['scenario' => MerchantMaster::SCENARIO_INSERT]);
        $user_model = new UserMaster();
//        $model->scenario = MerchantMaster::SCENARIO_INSERT;\
        $user_model->setScenario('insert');
        $user_model->USER_TYPE = 'merchant';
        $user_model->MERCHANT_ID = 1;

        if ($model->load(Yii::$app->request->post()) && $user_model->load(Yii::$app->request->post())) {
            $model->LOGO = UploadedFile::getInstance($model, 'LOGO');
            if(Model::validateMultiple([$model, $user_model]))  {
                $password = $user_model->PASSWORD;
                if ($model->save()) {
                    $user_model->MERCHANT_ID = $model->MERCHANT_ID;
                    if ($user_model->save()) {
                        $ghelper = new generalHelper();
                        $ghelper->sendMerchantDetails($user_model, $password);
                        return $this->redirect(['view', 'id' => $model->MERCHANT_ID]);
                    }
                }
            }
        }
        return $this->render('create', [
            'model' => $model,
            'usermodel' => $user_model,
        ]);

    }


    public function insertUser($mid, $user)
    {
        /* $dbname = "DB_".$database;
         Yii::$app->setComponents([
             $dbname => [
                 'class' => 'yii\db\Connection',
                 'dsn' => 'mysql:host=localhost;dbname='.$database,
                 'username' => 'root',
                 'password' => '123456',
                 'charset' => 'utf8',
             ]
         ]);*/
        //$ghelper = new generalHelper();
        // $password = Yii::$app->security->generatePasswordHash($user->PASSWORD);
        //$sql = "INSERT INTO `tbl_user_master`(`EMAIL`, `PASSWORD`, `USER_TYPE`, `MERCHANT_ID`, `PARTNER_ID`, `FIRST_NAME`, `LAST_NAME`, `USER_STATUS`, `ACCESS_TOKEN`, `AUTH_KEY`, `CREATED_ON`)
        //VALUES
        // ('".$user->EMAIL."','".$password."','merchant',0,'".$mid."','".$user->FIRST_NAME."','".$user->LAST_NAME."','E','".$ghelper->random_string(10)."','".$ghelper->random_string(8)."',". time().")";

        ////$command = Yii::$app->$dbname->createCommand($sql);
        // $command = Yii::$app->db->createCommand($sql);
        // $s = $command->execute();
        //if($s) {
        //    $ghelper->sendMerchantDetails($user, $user->PASSWORD);
        //   }

    }

    /**
     * Updates an existing MerchantMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = MerchantMaster::SCENARIO_UPDATE;
        //echo "<pre>"; var_dump($model); exit;
        if($model->MERCHANT_STATUS == 'E'){
            $model->MERCHANT_STATUS = 1;
        } else {
            $model->MERCHANT_STATUS = 0;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->LOGO = UploadedFile::getInstance($model, 'LOGO');
            if($model->save())  {
                return $this->redirect(['view', 'id' => $model->MERCHANT_ID]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MerchantMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
//    public function actionDelete($id)
//    {
//
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }

    /**
     * Finds the MerchantMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MerchantMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MerchantMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
