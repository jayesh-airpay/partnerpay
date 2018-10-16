<?php

namespace app\controllers;

use Yii;
use app\models\TblGuestUserDoc;
use app\models\TblGuestUserDocSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * GuestUserDocController implements the CRUD actions for TblGuestUserDoc model.
 */
class GuestUserDocController extends Controller
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
        ];
    }

    /**
     * Lists all TblGuestUserDoc models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblGuestUserDocSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblGuestUserDoc model.
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
     * Creates a new TblGuestUserDoc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        //var_dump(Yii::$app->request->post());exit;
        $model = new TblGuestUserDoc();
        $model->scenario = 'insert';
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->USER_ID = Yii::$app->user->identity->USER_ID;
            $model->fileinput = UploadedFile::getInstance($model, 'fileinput');
       
            if($model->validate()) {      
                $file_name = md5($model->fileinput->baseName.time()) . '.' . $model->fileinput->extension;
                $model->FILE = $file_name;
         
                if($model->fileinput->saveAs('uploads/user_docs/'.$file_name)) {
                    if($model->save(false)) {
                        return $this->redirect(['view', 'id' => $model->ID]);
                    }
                }
            }
        } else {
            // echo '<pre>';print_r($model->getErrors());exit;
            return $this->render('create', [
                'model' => $model,
            ]);
        }


        /*if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }*/
    }

    /**
     * Updates an existing TblGuestUserDoc model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $t = time();
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->USER_ID = Yii::$app->user->identity->USER_ID;
            $model->fileinput = UploadedFile::getInstance($model, 'fileinput');
            if ($model->validate()) {
                if (!empty($model->fileinput)) {
                    $file_name = md5($model->fileinput->baseName.time()) . '.' . $model->fileinput->extension;
                    if($model->fileinput->saveAs('uploads/quotation/'.$file_name)) {
                        $model->FILE = $file_name;
                        if($model->save(false))  {
                            return $this->redirect(['view', 'id' => $model->ID]);
                        }
                    }
                } else {
                    if($model->save(false))  {
                        return $this->redirect(['view', 'id' => $model->ID]);
                    }
                }
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
        /*if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }*/
    }

    /**
     * Deletes an existing TblGuestUserDoc model.
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
     * Finds the TblGuestUserDoc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblGuestUserDoc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblGuestUserDoc::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
