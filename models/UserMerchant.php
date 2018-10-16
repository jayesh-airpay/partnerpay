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
 * @property integer $PARTNER_ID
 * @property string $FIRST_NAME
 * @property string $LAST_NAME
 * @property string $USER_STATUS
 * @property string $AUTH_KEY
 * @property string $ACCESS_TOKEN
 * @property double $CREATED_ON
 * @property string $MOBILE
 * @property double $UPDATED_ON
 * @property Partner $partner
 * @property Invoice[] $INVOICE
 */
class UserMerchant extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    public $REPEAT_PASSWORD;
    public $INIT_PASSWORD;
    public $assignee_name;
    public $updated_at;
    public $created_at;

    const SCENARIO_INSERT = 'insert';
    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_merchant';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get(!empty(Yii::$app->controller->DB_name)?Yii::$app->controller->DB_name:'db');
    }

    /**
     * @inheritdoc
     */
  /*  public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }*/

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_INSERT]= $scenarios['default'] + ['PASSWORD', 'REPEAT_PASSWORD'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EMAIL', 'USER_TYPE', 'FIRST_NAME', 'LAST_NAME', 'USER_STATUS','MOBILE'], 'required'],
            [['USER_TYPE', 'USER_STATUS'], 'string'],
            [['PARTNER_ID'], 'integer'],
            [['EMAIL'], 'email'],
            [['EMAIL'], 'unique'],
            [['PARTNER_ID'], 'validPartner'],
            [['CREATED_ON', 'UPDATED_ON'], 'number'],
            [['PASSWORD', 'REPEAT_PASSWORD'], 'required', 'on'=>'insert'],
            [['PASSWORD', 'REPEAT_PASSWORD'], 'string', 'min'=>5, 'max'=>60],
            [['PASSWORD'], 'compare', 'compareAttribute'=>'REPEAT_PASSWORD'],
            [['EMAIL', 'FIRST_NAME', 'LAST_NAME'], 'string', 'max' => 50],
//            [['PASSWORD'], 'string', 'max' => 200],
            [['USER_STATUS'], 'string', 'max' => 1]
        ];
    }

    public function validPartner($attribute,$params) {
        if(!($this->USER_TYPE == 'admin') && empty($this->$attribute)) {
            $this->addError($attribute, 'Please select PARTNER');
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'USER_ID' => 'User',
            'EMAIL' => 'Email',
            'MOBILE' => 'Mobile',
            'PASSWORD' => 'Password',
            'USER_TYPE' => 'User  Type',
            'PARTNER_ID' => 'Vendor  ID',
            'FIRST_NAME' => 'First  Name',
            'LAST_NAME' => 'Last  Name',
            'USER_STATUS' => 'User  Status',
            'CREATED_ON' => 'Created  On',
            'UPDATED_ON' => 'Updated  On',
        ];
    }

    public static function find()
    {
        if(!Yii::$app->user->isGuest && !empty(Yii::$app->controller->merchant_id))  {

            if(Yii::$app->getUser()->identity->USER_TYPE == 'sale')    {
                return parent::find()->andWhere([
                    'USER_ID' => Yii::$app->getUser()->identity->USER_ID
                ]);
            }
        }
        return parent::find();

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getINVOICE()
    {
        return $this->hasMany(Invoice::className(), ['USER_ID' => 'ASSIGN_TO']);
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
        return Yii::$app->security->validatePassword($password, $this->INIT_PASSWORD);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partner::className(), ['PARTNER_ID' => 'PARTNER_ID']);
    }

    public function beforeValidate()
    {

        foreach ($this->attributes as $key => $value) {
            if (is_string($value)) {
                $this->$key = trim($value);
            }
        }
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {

        if(!empty($this->PASSWORD) && ($this->PASSWORD == $this->REPEAT_PASSWORD))  {
            $this->setPassword($this->PASSWORD);
            $this->INIT_PASSWORD = $this->PASSWORD;
        }   else    {
            $this->PASSWORD = $this->INIT_PASSWORD;
        }

        if($insert)  {
            $this->CREATED_ON = time();
            $this->ACCESS_TOKEN = $this->random_string(8);
            $this->AUTH_KEY = $this->random_string(6);
        }   else    {
            $this->UPDATED_ON = time();
        }
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        parent::afterFind();
        //reset the password to null because we don't want the hash to be shown.
        $this->INIT_PASSWORD = $this->PASSWORD;
        $this->PASSWORD = null;
        $this->assignee_name = $this->FIRST_NAME . ' ' . $this->LAST_NAME;

        if(!Yii::$app->user->isGuest && (Yii::$app->user->identity->USER_TYPE == 'admin' || Yii::$app->user->identity->USER_TYPE == 'cro'))    {
            if(!empty($this->hotel))    {
                $this->assignee_name = $this->hotel->PARTNER_NAME . ' - '.$this->FIRST_NAME . ' ' . $this->LAST_NAME;
            }
        }


    }

    public function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

    /*public function beforeValidate()
    {

        foreach ($this->attributes as $key => $value) {
            if (is_string($value)) {
                $this->$key = trim($value);
            }
        }
        $this->PARTNER_ID = Yii::$app->user->identity->PARTNER_ID;

        return parent::beforeValidate();
    }*/
}
