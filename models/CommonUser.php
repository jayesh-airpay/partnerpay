<?php

namespace app\models;

use Yii;
use yii\helpers\Security;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tbl_user_merchant".
 *
 * @property integer $USER_ID
 * @property string $EMAIL
 * @property string $PASSWORD
 * @property string $USER_TYPE
 * @property integer $HOTEL_ID
 * @property string $FIRST_NAME
 * @property string $LAST_NAME
 * @property string $USER_STATUS
 * @property string $AUTH_KEY
 * @property string $ACCESS_TOKEN
 * @property double $CREATED_ON
 * @property double $UPDATED_ON
 */
class CommonUser extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {

//        $merchant = Yii::$app->controller->merchant_id;
        if(empty(Yii::$app->controller->merchant_id)) {
            return 'tbl_user_master';
        } else {
            return 'tbl_user_merchant';
        }

    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        /*if(!empty(Yii::$app->controller->db_name)) {
            return Yii::$app->get(Yii::$app->controller->db_name);
        } else {
            //echo "asdf"; exit;
            return Yii::$app->get('db');
        }*/
       return Yii::$app->get(!empty(Yii::$app->controller->DB_name)?Yii::$app->controller->DB_name:'db');
        //
    }





    public static function findIdentity($id)
    {
        return static::findOne(['USER_ID' => $id, 'USER_STATUS' => 'E']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {

        return static::findOne(['EMAIL' => $username, 'USER_STATUS' => 'E']);

    }

    /**


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->AUTH_KEY;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        //var_dump($password); echo "<br>";
        //var_dump($this->password); exit;
        return Yii::$app->security->validatePassword($password, $this->PASSWORD);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->PASSWORD = Yii::$app->security->generatePasswordHash($password);
    }
}
