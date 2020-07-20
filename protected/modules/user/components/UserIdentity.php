<?php

class UserIdentity extends CUserIdentity
{
    private $_id;
    const ERROR_EMAIL_INVALID = 7;
    const ERROR_STATUS_NOTACTIV = Users::USTATUS_NOACTIVE;
    const ERROR_STATUS_BAN = Users::USTATUS_BANNED;
    const ERROR_MAINTENANCE = 12;
    
    public function checkPassword()
    {
        if(strpos($this->username, "@")) {
            $user = Users::model()->notsafe()->findByAttributes(['email' => $this->username]);
	} else {
            $user = Users::model()->notsafe()->findByAttributes(['username' => $this->username]);
	}
        
        if($user === null) {
            if(strpos($this->username, "@")) {
		$this->errorCode = self::ERROR_EMAIL_INVALID;
            } else {
		$this->errorCode = self::ERROR_USERNAME_INVALID;
            }
        } elseif(Yii::app()->settings->get('system')['offSite'] && !$user->maintenance) {
            $this->errorCode = self::ERROR_MAINTENANCE;
        } elseif (Yii::app()->getModule('user')->encrypting($this->password) !== $user->password && $this->password !== $user->password) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } elseif($user->status == Users::USTATUS_NOACTIVE) {
            $this->errorCode = self::ERROR_STATUS_NOTACTIV;
        } elseif($user->status == Users::USTATUS_BANNED) {
            $this->errorCode=self::ERROR_STATUS_BAN;
        }
        
            return !$this->errorCode;
    }
    
    public function authenticate()
    {
        if(strpos($this->username, "@")) {
            $user = Users::model()->notsafe()->findByAttributes(['email' => $this->username]);
	} else {
            $user = Users::model()->notsafe()->findByAttributes(['username' => $this->username]);
	}
        
        if($user === null) {
            if(strpos($this->username, "@")) {
		$this->errorCode = self::ERROR_EMAIL_INVALID;
            } else {
		$this->errorCode = self::ERROR_USERNAME_INVALID;
            }
        } elseif(Yii::app()->settings->get('system')['offSite'] && !$user->maintenance) {
            $this->errorCode = self::ERROR_MAINTENANCE;
        } elseif (Yii::app()->getModule('user')->encrypting($this->password) !== $user->password && $this->password !== $user->password) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } elseif($user->status == Users::USTATUS_NOACTIVE) {
            $this->errorCode = self::ERROR_STATUS_NOTACTIV;
        } elseif($user->status == Users::USTATUS_BANNED) {
            $this->errorCode=self::ERROR_STATUS_BAN;
        } else {
            $this->_id = $user->id;
            $this->username = $user->username;
            $this->errorCode = self::ERROR_NONE;
        }
        
	return !$this->errorCode;
    }
    
    public function getId()
    {
	return $this->_id;
    }
}
