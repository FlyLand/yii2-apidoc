<?php
namespace landrain\components;

use Yii;

class UserIdentity extends \yii\base\UserIdentity
{
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$password=Yii::app()->getModule('jid')->password;
		if($password===null)
			throw new CException('Please configure the "password" property of the "jid" module.');
		elseif($password===false || $password===$this->password)
			$this->errorCode=self::ERROR_NONE;
        else
            $this->errorCode=self::ERROR_UNKNOWN_IDENTITY;
		return !$this->errorCode;
	}
}