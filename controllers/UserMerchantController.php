<?php

namespace app\controllers;

use Yii;
use app\models\UserMerchant;
use app\models\UserMerchantSearch;
use yii\base\Hcontroller;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserMerchantController implements the CRUD actions for UserMerchant model.
 */
class UserMerchantController extends Hcontroller
{
    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','update','create','view','delete'],
                'rules' => [
                    [
                        'actions' => ['index','update','create','view','delete'],
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
     * Lists all UserMerchant models.
     * @return mixed
     */
    public function actionIndex()
    {
        //var_dump(Yii::$app->controller->merchant_id); exit;
        $searchModel = new UserMerchantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserMerchant model.
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
     * Creates a new UserMerchant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserMerchant();
        $model->setScenario(UserMerchant::SCENARIO_INSERT);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(Yii::$app->user->identity->USER_TYPE == 'sale'){
                return $this->redirect(['index']);
            } else {
                return $this->redirect(['view', 'id' => $model->USER_ID]);
            }

        } else {

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UserMerchant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
//        $model->setScenario('update');
        $model->PASSWORD='';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->USER_ID]);
        } else {
            //var_dump($model->getErrors()); exit;
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UserMerchant model.
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
     * Finds the UserMerchant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserMerchant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserMerchant::findOne($id)) !== null) {

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
