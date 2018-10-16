<?php
/**
 * Created by PhpStorm.
 * User: nandana
 * Date: 11/2/16
 * Time: 5:38 PM
 */


namespace app\components;

use app\models\MerchantMaster;

class DbDynamic extends \yii\base\Component {

    public $merchant_id;

    public function init() {
      /*$databaseList = $this->getDatabase();
        foreach($databaseList as $database) {
            $dbname = "DB_".$database;
            \Yii::$app->setComponents([
                $dbname => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'mysql:host=localhost;dbname='.$database,
                    'username' => 'root',
                    'password' => '123456',
                    'charset' => 'utf8',
                ]
            ]);
        }*/
        parent::init();
    }

   /* public function getDatabase()
    {
        $databaseNameArray = [];
        $databaseList = MerchantMaster::find()->all();
        if(!empty($databaseList)) {
            foreach($databaseList as $data) {
                $databaseNameArray[] = $data->DB_NAME;
            }
        }
        return $databaseNameArray;

    }*/
}