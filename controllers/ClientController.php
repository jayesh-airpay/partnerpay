<?php

namespace app\controllers;

use yii\base\Hcontroller;

class ClientController extends Hcontroller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
