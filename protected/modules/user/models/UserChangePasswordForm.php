<?php

class UserChangePasswordForm extends CFormModel {
    public $oldPassword;
    public $password;
    public $verifyPassword;
	
    public function rules() {
	return Yii::app()->controller->id == 'recovery' ? [
            ['password, verifyPassword', 'required'],
            ['password, verifyPassword', 'length', 'max' => 128, 'min' => 4, 'message' => Yii::t('models', 'user_cpassword_error_passwordLength')],
            ['verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('models', 'user_cpassword_error_passwordVerifyPassword')],
        ] : Yii::app()->user->model()->cpassword ? [
            ['password, verifyPassword', 'required'],
            ['password, verifyPassword', 'length', 'max' => 128, 'min' => 4, 'message' => Yii::t('models', 'user_cpassword_error_passwordLength')],
            ['verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('models', 'user_cpassword_error_passwordVerifyPassword')],
        ] : [
            ['password, verifyPassword', 'required'],
            ['oldPassword, password, verifyPassword', 'length', 'max' => 128, 'min' => 4, 'message' => Yii::t('models', 'user_cpassword_error_passwordLength')],
            ['verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('models', 'user_cpassword_error_passwordVerifyPassword')],
            #['oldPassword', 'verifyOldPassword'],
        ];
    }

    public function attributeLabels()
    {
	return [
            'oldPassword' => Yii::t('models', 'user_cpassword_attr_oldPassword'),
            'password' => Yii::t('models', 'user_cpassword_attr_password'),
            'verifyPassword' => Yii::t('models', 'user_cpassword_attr_verifyPassword'),
	];
    }
	
    public function verifyOldPassword($attribute, $params)
    {
	if (Users::model()->notsafe()->findByPk(Yii::app()->user->id)->password != Yii::app()->getModule('user')->encrypting($this->$attribute))
            $this->addError($attribute, Yii::t('models', 'user_cpassword_error_oldPassword'));
    }
}

