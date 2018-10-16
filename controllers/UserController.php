<?php

namespace app\controllers;

use app\helpers\generalHelper;
use app\models\MerchantMaster;
use app\models\Partner;
use Yii;
use app\models\UserMaster;
use app\models\MerchantAccessRuleMaster;
use app\models\UserMasterSearch;
use yii\base\Hcontroller;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserMasterController implements the CRUD actions for UserMaster model.
 */
class UserController extends Hcontroller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','update','create','view','delete'],
                'rules' => [
                    [
                        'actions' => ['index','update','create','view'],
                        'allow' => true,
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
     * Lists all UserMaster models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new UserMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserMaster model.
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
     * Creates a new UserMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserMaster();
        $model->setScenario('insert');
        $password = Yii::$app->request->post('UserMaster')["PASSWORD"];
    	if(Yii::$app->request->post('UserMaster')["USER_STATUS"]) {
            $model->USER_STATUS = 'E';
        } else {
            $model->USER_STATUS = 'D';
        }
        
        $userid = Yii::$app->user->identity->USER_ID;
        $categories = Yii::$app->request->post('UserMaster')['CATEGORIES'];
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if(empty($categories)) {
				$sql = "SELECT CAT_ID FROM tbl_category_master WHERE CAT_STATUS = 'E'";
				$conn = Yii::$app->db->createCommand($sql);
				$s = $conn->queryAll();
				if(!empty($s)){
					foreach($s as $cats) {
						$conn = Yii::$app->db->createCommand()->insert('tbl_merchant_access_rules', [
							'USER_ID' => $userid,
							'CAT_ID' => $cats['CAT_ID'],
							'CREATED_ON' => time(),
							'UPDATED_ON' => time(),
						])->execute();
					}
				}
			}
			else {
				foreach($categories as $val) {
					$conn = Yii::$app->db->createCommand()->insert('tbl_merchant_access_rules', [
							'USER_ID' => $userid,
							'CAT_ID' => $val,
							'CREATED_ON' => time(),
							'UPDATED_ON' => time(),
						])->execute();
				}
			}
			
            $ghelper = new generalHelper();
            $ghelper->sendMerchantDetails($model, $password);
            return $this->redirect(['view', 'id' => $model->USER_ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionGetList()
    {
        if(Yii::$app->request->post('ajax') == 'true') {
            $type = Yii::$app->request->post('type');
            $user_id = Yii::$app->request->post('user_id');
             if($type == 'merchant' || $type == 'partner' || $type == 'approver' || $type == 'payment' || $type == 'muser'){
                $merchant_detail = MerchantMaster::find()->select('MERCHANT_ID, MERCHANT_NAME')->andWhere(['MERCHANT_STATUS' => 'E'])->all();
            }

            $user = UserMaster::findOne($user_id);
            $option ='';
            $sel ='';
            $option .= '<option value="">Select Merchant</option>';
            if(!empty($merchant_detail)) {
                foreach ($merchant_detail as $row) {
                    if(!empty($user) && $user->MERCHANT_ID == $row['MERCHANT_ID']){
                        $sel = 'selected';
                    } else {
                        $sel = '';
                    }
                    $option .= '<option value="' . $row['MERCHANT_ID'] . '" '.$sel.'>' . $row['MERCHANT_NAME']. '</option>';
                }
                echo  $option;
            } else {
                echo 0;
            }
        }
    }

    public function actionGetPartnerList()
    {
        if (Yii::$app->request->post('ajax') == 'true') {
            $option = '<option value="">Select Partner</option>';
            $type = Yii::$app->request->post('type');
            $mid = Yii::$app->request->post('mid');
            $selected = Yii::$app->request->post('selected');
            if(!empty($mid)){
                $partners = Partner::find()->select('PARTNER_ID, PARTNER_NAME')->andWhere(['PARTNER_STATUS' => 'E', 'MERCHANT_ID' => $mid])->all();
            }
            $sel ='';

            if(!empty($partners)) {
                foreach ($partners as $row) {
                    $sel = '';
                    if($row->PARTNER_ID == $selected)    {
                        $sel = ' selected="selected" ';
                    }

                    $option .= '<option value="' . $row['PARTNER_ID'] . '" '.$sel.'>' . $row['PARTNER_NAME']. '</option>';
                }
            }
            echo  $option;
        }
    }

    /**
     * Updates an existing UserMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
//        $model->setScenario('update');
        if($model->USER_STATUS == 'E') {
            $model->USER_STATUS = 1;
        } else {
            $model->USER_STATUS = 0;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //delete from tbl_merchant_access_rules
            /*$userid = Yii::$app->user->identity->USER_ID;
            $conn = Yii::$app->db->createCommand()->delete('tbl_merchant_access_rules', [
                        'USER_ID' => $userid,
                    ])->execute();
            
            // insert new categories into tbl_merchant_access_rules    
            $categories = Yii::$app->request->post('UserMaster')['CATEGORIES'];
            //echo '<pre>';print_r($_POST);exit;
            foreach ($categories as $val) {
                $conn = Yii::$app->db->createCommand()->insert('tbl_merchant_access_rules', [
                        'USER_ID' => $userid,
                        'CAT_ID' => $val,
                        'CREATED_ON' => time(),
                        'UPDATED_ON' => time(),
                    ])->execute();
            }*/
            
            return $this->redirect(['view', 'id' => $model->USER_ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UserMaster model.
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
     * Finds the UserMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        //var_dump($model = UserMaster::findOne($id)); exit;
        if (($model = UserMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
