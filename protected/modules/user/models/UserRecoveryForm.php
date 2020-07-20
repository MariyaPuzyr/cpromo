<?php

class UserRecoveryForm extends CFormModel
{
    public $username, $user_id, $verifyCode;
	
    public function rules()
    {
	return [
            ['username', 'required'],
            ['username', 'checkexists'],
            ['verifyCode', 'required', 'message' => Yii::t('models', 'user_recovery_verifyCode_empty')],
            ['verifyCode', 'captcha', 'allowEmpty' => false, 'message' => Yii::t('models', 'register_verifyCode_error')],
	];
    }

    public function attributeLabels()
    {
	return [
            'username' => Yii::t('models', 'user_recovery_attr_username'),
            'verifyCode' => Yii::t('models', 'user_recovery_attr_verifyCode'),
	];
    }
	
    public function checkexists($attribute, $params)
    {
	if(!$this->hasErrors()) {
            if(strpos($this->username, "@")) {
		$user = Users::model()->findByAttributes(['email' => $this->username]);
		if($user)
                    $this->user_id = $user->id;
            } else {
		$user = Users::model()->findByAttributes(['username' => $this->username]);
		if($user)
                    $this->user_id = $user->id;
            }
			
            if($user === null)
                $this->addError("username", Yii::t('models', 'user_recovery_username_error'));
            if($user->status == Users::USTATUS_BANNED)
                $this->addError("username", Yii::t('models', 'user_login_banned'));
	}
    }
}