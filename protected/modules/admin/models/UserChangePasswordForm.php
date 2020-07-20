<?php

class UserChangePasswordForm extends CFormModel {
    public $password;
    public $verifyPassword;
	
    public function rules() {
	return [
            ['password, verifyPassword', 'required', 'message' => Yii::t('models', 'user_changePassword_error_password')],
            ['password, verifyPassword', 'length', 'max' => 128, 'min' => 4],
            ['verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('models', 'user_changePassword_error_verifyPassword')],
        ];
    }

    public function attributeLabels()
    {
	return [
            'password' => Yii::t('models', 'user_changePassword_password'),
            'verifyPassword' => Yii::t('models', 'user_changePassword_verifyPassword'),
	];
    }
}

