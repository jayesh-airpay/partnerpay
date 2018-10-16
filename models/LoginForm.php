<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            //var_dump($this->password); exit;

            if(!$user)  {
                $this->addError('username', 'Incorrect username.');
            }   elseif(!$user->validatePassword($this->password))   {
                $this->addError($attribute, 'Incorrect password.');
            }   else    {
                if($user->USER_STATUS != 'E')   {
                    $this->addError('username', 'Incorrect username.');
                }   elseif($user->USER_TYPE != 'admin')   {
                    $merchant = MerchantMaster::findOne($user->MERCHANT_ID);
                    if(empty($merchant) || $merchant->MERCHANT_STATUS != 'E')   {
                        $this->addError('username', 'Incorrect username.');
                    }
                    if($user->USER_TYPE =='partner')    {
                        $partner = Partner::findOne($user->PARTNER_ID);
                        if(empty($partner) || $partner->PARTNER_STATUS != 'E')   {
                            $this->addError('username', 'Incorrect username.');
                        }
                    }
                }
            }

            /*if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }*/
            //echo "asdefgh"; exit;
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = UserMaster::findByUsername($this->username);

        }

        return $this->_user;
    }
}
