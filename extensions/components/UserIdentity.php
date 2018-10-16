<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	/*public function authenticate()
	{
		$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}*/
	public function authenticate()
	{
		$user=\app\models\UserMaster::model()->findByAttributes(array('EMAIL' => $this->username, 'USER_STATUS' => 'E'));

		if(empty($user))    {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        }   elseif($user->INIT_PASSWORD !== md5($this->password))    {
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        }   else    {
            $this->errorCode=self::ERROR_NONE;
            Yii::app()->user->setState('user_id' , $user->USER_ID);
            Yii::app()->user->setState('user_type' , $user->USER_TYPE);
            //Yii::app()->user->setState('hotel_id' , $user->HOTEL_ID);
        }
		return !$this->errorCode;
	}
}