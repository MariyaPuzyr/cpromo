<?php

class UserLoginForm extends CFormModel
{
    public $username;
    public $password;
    public $rememberMe;
    public $code;
    public $verifyCode;
    
    public function rules()
    {
        return [
            ['username', 'required', 'message' => Yii::t('models', 'user_login_username_empty')],
            ['password', 'required', 'message' => Yii::t('models', 'user_login_password_empty')],
//            ['code', 'required', 'message' => Yii::t('models', 'user_login_code_empty'), 'on' => 'checkVerify'],
//            ['code', 'checkCode', 'on' => 'checkVerify'],
//            ['verifyCode', 'required', 'message' => Yii::t('models', 'login_verifyCode_empty'), 'except' => 'checkVerify'],
//            ['verifyCode', 'captcha', 'allowEmpty' => false, 'message' => Yii::t('models', 'user_register_verifyCode_error')],
            ['password', 'checkPassword'],
	];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('models', 'user_login_username'),
            'password' => Yii::t('models', 'user_login_password'),
            'code' => Yii::t('models', 'user_login_code'),
            'verifyCode' => Yii::t('models', 'login_verifyCode'),
    	];
    }
    
    public function checkCode()
    {
//        return true;
//        if(strpos($this->username, "@"))
//            $user = Users::model()->notsafe()->findByAttributes(['email' => $this->username]);
//        else
//            $user = Users::model()->notsafe()->findByAttributes(['username' => $this->username]);
//
//
//        if($user) {
//            if($user->googleAuth) {
//                Yii::import('vendor.googleauth.GoogleAuthenticator');
//                $ga = new GoogleAuthenticator();
//                $code = $ga->getCode($user->googleAuth_key);
//
//                if($this->code && ($this->code != $code))
//                    $this->addError('code', Yii::t('models', 'user_login_code_error'));
//            } else {
//                if($this->code != UsersVerify::model()->order_id_desc()->findByAttributes(['email' => $user->email])->code)
//                    $this->addError('code', Yii::t('models', 'user_login_code_error'));
//            }
//        }
    }
    
    public function checkPassword()
    {
        if(!$this->hasErrors()) {
            $identity = new UserIdentity($this->username, $this->password);
            $identity->checkPassword();
            
            switch($identity->errorCode){
		case UserIdentity::ERROR_NONE:
                    $duration = $this->rememberMe ? Yii::app()->controller->rememberTime*24*30 : 0;
                    Yii::app()->user->login($identity, $duration);
                    break;
                case UserIdentity::ERROR_EMAIL_INVALID:
                    $this->addError("username", Yii::t('models', 'login_user_username_error'));
                    break;
		case UserIdentity::ERROR_USERNAME_INVALID:
                    $this->addError('username', Yii::t('models', 'login_user_username_error'));
                    break;
		case UserIdentity::ERROR_PASSWORD_INVALID:
                    $this->addError('username', Yii::t('models', 'user_login_username_error'));
                    break;
                case UserIdentity::ERROR_STATUS_NOTACTIV:
                    $this->addError("username", Yii::t('models', 'user_login_noactive'));
                    break;
		case UserIdentity::ERROR_STATUS_BAN:
                    $this->addError("username", Yii::t('models', 'user_login_banned'));
                    break;
                case UserIdentity::ERROR_MAINTENANCE:
                    $this->addError("username", Yii::t('models', 'user_login_maintenance'));
                    break;
            }
        }
    }
    
    public function authenticate()
    {
	if(!$this->hasErrors()) {
            $identity = new UserIdentity($this->username, $this->password);
            $identity->authenticate();

            switch($identity->errorCode){
		case UserIdentity::ERROR_NONE:
                    $duration = $this->rememberMe ? Yii::app()->controller->rememberTime*24*30 : 0;
                    Yii::app()->user->login($identity, $duration);
                    break;
                case UserIdentity::ERROR_EMAIL_INVALID:
                    $this->addError("username", Yii::t('models', 'login_user_username_error'));
                    break;
		case UserIdentity::ERROR_USERNAME_INVALID:
                    $this->addError('username', Yii::t('models', 'login_user_username_error'));
                    break;
		case UserIdentity::ERROR_STATUS_NOTACTIV:
                    $this->addError("username", Yii::t('models', 'user_login_noactive'));
                    break;
		case UserIdentity::ERROR_STATUS_BAN:
                    $this->addError("username", Yii::t('models', 'user_login_banned'));
                    break;
                case UserIdentity::ERROR_PASSWORD_INVALID:
                    $this->addError('username', Yii::t('models', 'user_login_username_error'));
                    break;
            }
	}
    }
}
