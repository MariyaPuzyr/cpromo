<?php

class UserRegisterForm extends Users
{
    public $verifyPassword;
    public $verifyCode;
    public $agree;
    public $ref;
    public $denyNames = ['admin', 'user', 'administrator', 'system', 'god', 'support', 'adm', 'sup'];
	
    public function rules() {
        $rules = [
            ['username', 'required', 'message' => Yii::t('models', 'user_register_username_empty')],
            ['email', 'required', 'message' => Yii::t('models', 'user_register_email_empty')],
            ['password', 'required', 'message' => Yii::t('models', 'user_register_password_empty')],
            ['verifyPassword', 'required', 'message' => Yii::t('models', 'user_register_verifyPassword_empty')],
            ['username', 'length', 'max' => 20, 'min' => 3, 'message' => Yii::t('models', 'user_register_username_length')],
            ['password', 'length', 'max' => 128, 'min' => 4, 'message' => Yii::t('models', 'user_register_username_length')],
            ['firstname', 'required'],
            ['email', 'email'],
            ['username', 'checkName'],
            ['email', 'checkEmailInvite', 'on' => 'mainRegister'],
            ['username', 'unique', 'message' => Yii::t('models', 'user_register_username_unique')],
            ['email', 'unique', 'message' => Yii::t('models', 'user_register_email_unique')],
            ['verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('models', 'user_register_password_verifypassword')],
            ['username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => Yii::t('models', 'user_register_username_symbols')],
//            ['verifyCode', 'required', 'message' => Yii::t('models', 'user_register_verifyCode_empty')],
//            ['verifyCode', 'captcha', 'allowEmpty' => !extension_loaded('gd'), 'message' => Yii::t('models', 'user_register_verifyCode_error'), 'except' => 'mainRegister'],
            ['agree', 'required', 'requiredValue' => 1, 'message' => Yii::t('models', 'user_register_agree_required')],
            ['ref', 'length', 'min' => '2', 'max' => 7, 'message' => Yii::t('models', 'user_register_ref_length')],
            ['ref', 'checkRef', 'on' => 'insert', 'except' => 'mainRegister'],
        ]; 
        
        if(Yii::app()->settings->get('system', 'enableLockRegister'))
            array_push($rules, ['referral_id', 'required']);
        
        return $rules;
    }
    
    public function attributeLabels() {
        $attributeLabels =  [
            'agree' => Yii::t('models', 'user_register_attr_agree'),
            'verifyPassword' => Yii::t('models', 'user_register_attr_verifyPassword'),
            'verifyCode' => Yii::t('models', 'user_register_attr_verifyCode'),
            'ref' =>  Yii::t('models', 'user_register_attr_ref'),
        ];
        
        return CMap::mergeArray(parent::attributeLabels(), $attributeLabels);
    }
    
    public function checkRef()
    {
        if($this->ref) {
            $referral = $this->findByAttributes(['referral_id' => $this->ref]);
            
            if($referral) {
                if(in_array($referral->status, [0, -1])) {
                    $this->addError('ref', Yii::t('models', 'user_register_ref_error'));
                }
            } else
                $this->addError('ref', Yii::t('models', 'user_register_ref_error'));
        }
    }
    
    public function checkEmailInvite()
    {
        $email = UsersInvite::model()->findByAttributes(['invite_email' => $this->email]);
        if($email) {
            if(Yii::app()->user->id !== $email->user_id)
                $this->addError('email', Yii::t('models', 'user_register_email_alreadyInviteNotYou'));
            else
                $this->addError('email', Yii::t('models', 'user_register_email_alreadyInvite'));
        }
    }
    
    public function checkName()
    {
        if($this->username && in_array(mb_strtolower($this->username), $this->denyNames)) {
            $this->addError('username', UserModule::t('models', 'register_username_unique'));
        }
    }
}
