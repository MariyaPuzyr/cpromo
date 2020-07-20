<?php

class Users extends MBaseModel
{
    const USTATUS_BANNED = 9;
    const USTATUS_NOACTIVE = 0;
    const USTATUS_ACTIVE = 1;
    
    const STEMAIL_NO = 0;
    const STEMAIL_CONF = 1;
    
    const STATUSMIN = 1;
    const STATUSMAX = 5;
    
    public $loadFile;
    public $checkCode;
    public $statusNameShow;
    
    public $balance = 0;
    public $deposit = 0;
    public $coins = 0;
    public $countRefsFirstLevel = 0;
    
    public static function model($className=__CLASS__)
    {
    	return parent::model($className);
    }
    
    public function tableName()
    {
	return '{{users}}';
    } 
    
    public function behaviors(){
	return [
            'OnAfterSaveBehavior' => [
                'class' => 'application.modules.user.behaviors.OnAfterSaveBehavior'
            ],
	];
    }
    
    public function rules()
    {
	return [
            ['username, email, referral_id, status, create_at', 'required', 'on' => 'insert'],
            ['username, email, finance_payeer, finance_bitcoin, finance_card, finance_prfmoney, finance_usdtrc', 'unique'],
            ['username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u'],
            ['username', 'length', 'max' => 20, 'min' => 3],
            ['email', 'email'],
            ['lastname, firstname, middlename', 'length', 'max' => 128, 'min' => 3],
            ['phone', 'length', 'max' => 15, 'min' => '10'],
            ['status', 'in', 'range' => [self::USTATUS_BANNED, self::USTATUS_NOACTIVE,  self::USTATUS_ACTIVE]],
            ['emailConfirm', 'in', 'range' => [self::STEMAIL_NO, self::STEMAIL_CONF]],
            ['referral_id', 'length', 'min' => '2', 'max' => 7],
            ['create_at', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'],
            ['emailConfirm, phone, country, region, city, subscribe_news, subscribe_admin, subscribe_login, googleAuth, status, status_account, referral_level, cpassword,', 'numerical', 'integerOnly' => true],
            ['language', 'length', 'max' => 2, 'min' => 2],
            ['language', 'in', 'range' => array_keys(Yii::app()->params->languages)],
            ['passport', 'length', 'max' => 15, 'min' => 7],
            ['finance_card', 'numerical', 'integerOnly' => true],
            ['finance_card', 'length', 'max' => 22, 'min' => 15],
            ['finance_payeer', 'length', 'max' => 12, 'min' => 8],
            ['readwarning', 'length', 'max' => 1],
            ['finance_prfmoney', 'length', 'max' => 10, 'min' => 7],
            ['finance_bitcoin', 'length', 'max' => 128],
            ['finance_usdtrc', 'length', 'max' => 42, 'min' => 42],
            ['photo', 'length', 'max' => 255],
            ['loadFile', 'safe'],
            ['loadFile', 'file', 'types' => 'jpg,png', 'allowEmpty' => true],
            ['now_balance, now_deposit, now_profit', 'numerical'],
            ['now_coins', 'numerical', 'integerOnly' => true],
            ['id, referral_id, username, password, email, emailConfirm, lastname, firstname, middlename, birthday, phone, passport, country, region, city, finance_payeer, finance_card, finance_bitcoin, finance_prfmoney, finance_usdtrc, subscribe_news, subscribe_admin, subscribe_login, language, googleAuth, activkey, status, status_account, referral_level, now_balance, now_deposit, now_profit, now_coins, cpassword, readwarning, photo, create_at, create_uid, visible', 'safe', 'on' => 'search'],
        ];
    }
    
    public function relations()
    {
	return [
            'statusAccount' => [self::BELONGS_TO, 'SprStatuses', 'status_account'],
            'rBalance' => [self::HAS_MANY, 'UsersBalance', 'user_id'],
            'rCoins' => [self::HAS_MANY, 'CoinsMarket', 'user_id'],
            
            'create_user' => [self::BELONGS_TO, 'Users', 'create_uid'],
            'pays' => [self::HAS_MANY, 'UsersPays', 'user_id'],
            'profits' => [self::HAS_MANY, 'UsersProfits', 'user_id'],
            'outs' => [self::HAS_MANY, 'UsersOuts', 'user_id'],
            'referrals' => [self::HAS_MANY, 'UsersRelation', 'user_id'],
            'invites' => [self::HAS_MANY, 'UsersInvites', 'user_id'],
            'balance_history' => [self::HAS_MANY, 'UserslBalance', 'user_id'],
            'users_head' => [self::BELONGS_TO, 'UsersRelation', '', 'on' => 'user_ok.id = users_head.to_user'],
	];
    }
    
    public function attributeLabels()
    {
	return [
            'id' => Yii::t('core', 'attr_id'),
            'referral_id' => Yii::t('models', 'user_attr_referral_id'),
            'username' => Yii::t('models', 'user_attr_username'),
            'password' => Yii::t('models', 'user_attr_password'),
            'email' => Yii::t('models', 'user_attr_email'),
            'emailConfirm' => Yii::t('models', 'user_attr_emailConfirm'),
            'lastname' => Yii::t('models', 'user_attr_lastname'),
            'firstname' => Yii::t('models', 'user_attr_firstname'),
            'middlename' => Yii::t('models', 'user_attr_middlename'),
            'birthday' => Yii::t('models', 'user_attr_birthday'),
            'phone' => Yii::t('models', 'user_attr_phone'),
            'passport' => Yii::t('models', 'user_attr_passport'),
            'country' => Yii::t('models', 'user_attr_country'),
            'region' => Yii::t('models', 'user_attr_region'),
            'city' => Yii::t('models', 'user_attr_city'),
            'finance_payeer' => Yii::t('models', 'user_attr_finance_payeer'),
            'finance_bitcoin' => Yii::t('models', 'user_attr_finance_bitcoin'),
            'finance_card' => Yii::t('models', 'user_attr_finance_card'),
            'finance_prfmoney' => Yii::t('models', 'user_attr_finance_prfmoney'),
            'finance_usdtrc' => Yii::t('models', 'user_attr_finance_usdtrc'),
            'subscribe_news' => Yii::t('models', 'user_attr_subscribe_news'),
            'subscribe_admin' => Yii::t('models', 'user_attr_subscribe_admin'),
            'subscribe_login' => Yii::t('models', 'user_attr_subscribe_login'),
            'language' => Yii::t('models', 'user_attr_language'),
            'googleAuth' => Yii::t('models', 'user_attr_googleAuth'),
            'activkey' => Yii::t('models', 'user_attr_activkey'),
            'status' => Yii::t('models', 'attr_status'),
            'status_account' => Yii::t('models', 'user_attr_status_account'),
            'referral_level' => Yii::t('models', 'user_attr_referral_level'),
            'cpassword' => Yii::t('models', 'user_attr_cpassword'),
            'photo' => Yii::t('models', 'user_attr_photo'),
            'create_at' => Yii::t('models', 'attr_create_at'),
            'create_uid' => Yii::t('models', 'attr_create_uid'),
	];
    }
    
    public function scopes()
    {
        return [
            'banned' => ['condition' => 'status='.self::USTATUS_BANNED],
            'active' => ['condition' => 'status ='.self::USTATUS_ACTIVE],
            'subscribe' => [
                'select' => 'email, language',
                'condition' => 'subscribe = 1 AND status !=0'
            ],
            'notsafe' => [
                'select' => 'id, referral_id, username, password, email, emailConfirm, firstname, language, finance_payeer, finance_card, finance_bitcoin, finance_prfmoney, finance_usdtrc, activkey, create_at, status, status_account, referral_level, cpassword, readwarning, photo, googleAuth, googleAuth_key, subscribe_login, maintenance'
            ],
            'email_select' => [
                'select' => 'id, email, activkey'
            ],
            'only_id' => [
                'select' => 'id'
            ],
            'onlyRelation' => [
                'select' => 'id, referral_id, username, email, firstname, status_account, referral_level, now_balance, now_deposit, now_profit, now_coins, phone, photo, create_at'
            ],
            'lang_ru' => ['condition' => 'language="ru"'],
            'lang_en' => ['condition' => 'language="en"'],
            'lang_def' => ['condition' => 'language="" or language is null'],
            'order_id_desc' => ['order' => 'id DESC'],
            'order_id_desc_find' => ['order' => 't.id DESC'],
            'forProfile' => [
                'select' => 'id, referral_id, email, lastname, firstname, middlename, birthday, phone, passport, finance_payeer, finance_bitcoin, finance_prfmoney, finance_usdtrc, subscribe_news, subscribe_admin, subscribe_login, language, googleAuth, readwarning, photo, status_account'
            ],
            'onlyBalance' => ['select' => 'id, now_balance, now_deposit, now_profit, now_coins'],
        ];
    }
    
    public function search($size = false)
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('referral_id', $this->referral_id, true);
	$criteria->compare('username', $this->username, true);
	$criteria->compare('password', $this->password, true);
	$criteria->compare('email', $this->email, true);
        $criteria->compare('emailConfirm', $this->emailConfirm);
        $criteria->compare('lastname', $this->lastname, true);
        $criteria->compare('firstname', $this->firstname, true);
        $criteria->compare('middlename', $this->middlename, true);
        $criteria->compare('birthday', $this->birthday);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('passport', $this->passport, true);
        $criteria->compare('country', $this->country);
        $criteria->compare('region', $this->region);
        $criteria->compare('city', $this->city);
        $criteria->compare('finance_payeer', $this->finance_payeer, true);
        $criteria->compare('finance_card', $this->finance_card, true);
        $criteria->compare('finance_bitcoin', $this->finance_bitcoin, true);
        $criteria->compare('finance_prfmoney', $this->finance_prfmoney, true);
        $criteria->compare('finance_usdtrc', $this->finance_usdtrc, true);
        $criteria->compare('subscribe_news', $this->subscribe_news);
        $criteria->compare('subscribe_admin', $this->subscribe_admin);
        $criteria->compare('subscribe_login', $this->subscribe_login);
        $criteria->compare('language', $this->language, true);
        $criteria->compare('googleAuth', $this->googleAuth);
        $criteria->compare('activkey', $this->activkey, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('status_account', $this->status_account);
        $criteria->compare('referral_level', $this->referral_level);
        $criteria->compare('photo', $this->photo, true);
	$criteria->compare('create_at', $this->create_at, true);
	$criteria->compare('create_uid', $this->create_uid);
        $criteria->compare('visible', $this->visible);
	
	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => $size ? $size : 25
            ]
	]);
    }
    
    public static function generateReferralID()
    {
        $rIds = self::model()->findAll();
        foreach($rIds as $key) {
            $ids[] = $key->referral_id;
        }
            
        if(!in_array($rID = 'C'.rand(000000,999999), $ids))
            return $rID;
    }
    
    public static function userByRef($ref)
    {
        return self::model()->notsafe()->findByAttributes(['referral_id' => $ref])->id;
    }
    
    public static function findByEmail($email)
    {
        return self::model()->findByAttributes(['email' => $email]);
    }
    
    public static function statusUser($type, $code = null)
    {
        $_items = [
            'status' => [
                self::USTATUS_NOACTIVE => Yii::t('models', 'user_attr_status_noActive'),
                self::USTATUS_ACTIVE => Yii::t('models', 'user_attr_status_Active'),
            ],
            'emailConfirm' => [
                self::STEMAIL_NO => Yii::t('models', 'user_attr_emailConfirm_no'),
                self::STEMAIL_CONF => Yii::t('models', 'user_attr_emailConfirm_yes'),
            ]
        ];
        
        if(isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
	else
            return isset($_items[$type]) ? $_items[$type] : false;
    }
    
    public static function statusUserToBage($type, $code = null)
    {
        $_items = [
            'status' => [
                self::USTATUS_NOACTIVE => '<span class="badge badge-danger">'.Yii::t('models', 'user_attr_status_noActive').'</span>',
                self::USTATUS_ACTIVE => '<span class="badge badge-primary">'.Yii::t('models', 'user_attr_status_Active').'</span>',
            ],
            'emailConfirm' => [
                self::STEMAIL_NO => '<span class="badge badge-danger">'.Yii::t('models', 'user_attr_emailConfirm_no').'</span>',
                self::STEMAIL_CONF => '<span class="badge badge-success">'.Yii::t('models', 'user_attr_emailConfirm_yes').'</span>',
            ]
        ];
        
        if(isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
	else
            return isset($_items[$type]) ? $_items[$type] : false;
    }
    
    public function getWallet($pay_system)
    {
        return $this->{self::getFinTypeName($pay_system)};
    }
}
