<?php

class UserChangeEmailForm extends CFormModel 
{
    public $email;
    
    public function rules() {
	return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'verify']
        ];
    }

    public function attributeLabels()
    {
	return [
            'email' => Yii::t('models', 'user_attr_new_email'),
	];
    }
	
    public function verify()
    {
        if($this->email) {
            $uses = Users::model()->email_select()->findByAttributes(['email' => $this->email]);
            if($uses)
                $this->addError('email', Yii::t('models', 'user_attr_new_email_error_uses'));
        }
    }
}

