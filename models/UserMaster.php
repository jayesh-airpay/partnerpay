<?php

namespace app\models;

use Yii;
use yii\helpers\Security;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "tbl_user_master".
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
 * @property double $UPDATED_ON
 * @property double $MOBILE
 * @property integer $MERCHANT_ID
 */
class UserMaster extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    public $REPEAT_PASSWORD;
    public $INIT_PASSWORD;
    public $CREATE_QR;
	public $CATEGORIES;
    public $updated_at;
    public $created_at;

    const SCENARIO_INSERT = 'insert';
    const SCENARIO_UPDATE = 'update';
    //public $auth_key;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_master';
    }

    /*public static function getDb()
    {
        //return Yii::$app->get('db1');
        $aid =Yii::$app->request->get('aid');
        $merchantDetails = MerchantMaster::find()->where(['MERCHANT_ID' =>$aid])->one();
        if(!empty($merchantDetails)) {
            $dbconn = "DB_".$merchantDetails->DB_NAME;
            return Yii::$app->get($dbconn);
        } else {
            return Yii::$app->get('DB_sample');
        }
    }*/

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

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
            [['EMAIL'],'email'],
            ['EMAIL', 'unique', 'targetClass' => UserUnique::className()],
            [['USER_TYPE', 'USER_STATUS'], 'string'],                          
            [['PARTNER_ID'], 'required', 'when' => function($model) {
                return ($model->USER_TYPE == 'partner');
            }, 'whenClient' => "function (attribute, value) {
                return $('#usermaster-user_type').val() == 'partner';
            }"],
            [['MERCHANT_ID'], 'required', 'when' => function($model) {
                return ($model->USER_TYPE != 'admin');
            }, 'whenClient' => "function (attribute, value) {
                return $('#usermaster-user_type').val() != 'admin';
            }"],
            //['MOBILE', 'match', 'pattern' => '/^[0-9]{10,10}$/', 'message'=>'Enter valid Mobile Number.'],
            [['MOBILE'], 'number', 'min' => 1000000000, 'max' => 9999999999, 'message'=>'Number is invalid.', 'tooSmall'=>'Number is invalid.', 'tooBig'=>'Number is invalid.'],
            [['CREATED_ON', 'UPDATED_ON'], 'number'],
            [['PASSWORD', 'REPEAT_PASSWORD'], 'required', 'on'=>'insert'],
            [['PASSWORD', 'REPEAT_PASSWORD'], 'string', 'min'=>5, 'max'=>60],
            [['REPEAT_PASSWORD'], 'compare', 'compareAttribute'=>'PASSWORD'],
            [['EMAIL', 'FIRST_NAME', 'LAST_NAME'], 'string', 'max' => 50],
//            [['PASSWORD'], 'string', 'max' => 32],
            [['AUTH_KEY', 'ACCESS_TOKEN', 'PARTNER_ID', 'CATEGORIES'], 'safe']
        ];
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
            'MERCHANT_ID' => 'Merchant',
            'PARTNER_ID' => 'Partner',
            'PASSWORD' => 'Password',
            'USER_TYPE' => 'User  Type',
            //'PARTNER_ID' => 'PARTNER ID',
            'FIRST_NAME' => 'First  Name',
            'LAST_NAME' => 'Last  Name',
            'USER_STATUS' => 'User  Status',
            'ACCESS_TOKEN' => 'Access Token',
            'AUTH_KEY' => 'Auth Key',
            'CREATED_ON' => 'Created  On',
            'UPDATED_ON' => 'Updated  On',
			'CATEGORIES' => 'Categories',
			'CREATE_QR' => 'Create QR Flag',
        ];
    }



    public static function findIdentity($id)
    {
        return static::findOne(['USER_ID' => $id]);
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
        $merchant_id = self::getSubDomainMerchantId();
        if(!empty($merchant_id))    {
            return static::findOne(['EMAIL' => $username, 'USER_STATUS' => 'E', 'MERCHANT_ID' => $merchant_id]);
        }
        return static::findOne(['EMAIL' => $username]);
    }

    public static function findByEmail($username)
    {
        $merchant_id = self::getSubDomainMerchantId();
        if(!empty($merchant_id))    {
            return static::findOne(['EMAIL' => $username, 'MERCHANT_ID' => $merchant_id]);
        }
        return static::findOne(['EMAIL' => $username]);

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

    public function beforeValidate()
    {
        foreach ($this->attributes as $key => $value) {
            if (is_string($value)) {
                $this->$key = trim($value);
            }
        }

        if(Yii::$app->getUser()->identity->USER_TYPE == 'partner') {
            $this->USER_TYPE = 'partner';
            $this->MERCHANT_ID = Yii::$app->getUser()->identity->MERCHANT_ID;
            $this->PARTNER_ID = Yii::$app->getUser()->identity->PARTNER_ID;
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
         if($this->USER_TYPE == 'merchant' || $this->USER_TYPE == 'approver' || $this->USER_TYPE == 'payment'){
            if($this->PARTNER_ID == '' || $this->PARTNER_ID == null){
                $this->PARTNER_ID = 0;
            }
        }

        if($insert)  {
            $this->CREATED_ON = time();
        	$this->USER_STATUS = "E";
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
        $this->INIT_PASSWORD = $this->PASSWORD;
        $this->PASSWORD = null;
    }

    public function random_string($length)
	{
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

    public static function find()
    {
        //var_dump(Yii::$app->getUser()->identity->MERCHANT_ID); exit;
        if(!Yii::$app instanceof \yii\console\Application) {
            if(!Yii::$app->user->isGuest) {
                if(Yii::$app->getUser()->identity->USER_TYPE == 'partner') {
                    return parent::find()->andWhere([
                        'USER_TYPE' => 'partner',
                        'MERCHANT_ID' => Yii::$app->getUser()->identity->MERCHANT_ID
                    ]);
                }
                if(Yii::$app->getUser()->identity->USER_TYPE == 'merchant'){
                    return parent::find()->andWhere([
                        'MERCHANT_ID' => Yii::$app->getUser()->identity->MERCHANT_ID
                    ]);
                }
            }
        }

        return parent::find();
    }

    public static function getSubDomainMerchantId()
	{
        $merchant_id = null;
        $server_name = Yii::$app->getRequest()->getHeaders()->get('host');
        $domain_arr = explode(".", $server_name);
        $sub_domain = array_shift($domain_arr);
        if(in_array($sub_domain, ['www', 'localhost', 'partnerpay']))    {
            $sub_domain = null;
        }

        if(!empty($sub_domain)) {
            $merchant = MerchantMaster::find()->where(['DOMAIN_NAME' => $sub_domain])->one();

            if(!empty($merchant))   {
                $merchant_id = $merchant->MERCHANT_ID;
            }
        }
        return $merchant_id;
    }
}