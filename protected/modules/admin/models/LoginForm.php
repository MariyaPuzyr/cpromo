<?php

class LoginForm extends CFormModel
{
    public $username;
    public $password;
    private $_identity;

    public function rules()
    {
        return [
            ['username', 'required', 'message' => UserModule::t('models', 'login_username_empty')],
            ['password', 'required', 'message' => UserModule::t('models', 'login_password_empty')],
            ['password', 'authenticate'],
	];
    }

    public function authenticate()
    {
	$this->_identity = new UserIdentity($this->username,$this->password);
	if(!$this->_identity->authenticate())
            $this->addError("username", UserModule::t('models', 'login_user_error'));
    }

    public function getIdentity()
    {
	return $this->_identity;
    }
}
