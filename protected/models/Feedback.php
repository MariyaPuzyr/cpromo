<?php

class Feedback extends MBaseModel
{
    const CAT_PAY = 0;
    const CAT_OUT = 1;
    const CAT_PRO = 2;
    const CAT_REF = 3;
    const CAT_OTH = 4;
    
    const MSTATUS_SEND = 0;
    const MSTATUS_WORK = 1;
    const MSTATUS_COMPL = 2;
    const MSTATUS_CANC = 3;
    const MSTATUS_DISA = 4;
    
    public $referral_id;
    public $email;
    
    public function tableName()
    {
	return '{{feedback}}';
    }

    public function rules()
    {
	return [
            ['user_email, msg_number, msg_cat, msg_text, create_at', 'required'],
            ['user_id, msg_number, msg_cat, msg_status, update_uid', 'numerical', 'integerOnly' => true],
            ['msg_file', 'length', 'max' => 255],
            ['update_at', 'safe'],
            ['id, user_id, user_email, msg_number, msg_cat, msg_text, msg_file, msg_status, create_at, update_at, update_uid', 'safe', 'on' => 'search'],
	];
    }

    public function relations()
    {
	return [
            'updateU' => [self::BELONGS_TO, 'Users', 'update_uid'],
            'user' => [self::BELONGS_TO, 'Users', 'user_id'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => Yii::t('models', 'attr_id'),
            'user_id' => Yii::t('models', 'attr_user_id'),
            'user_email' => Yii::t('models', 'feedback_attr_user_email'),
            'msg_number' => Yii::t('models', 'feedback_attr_msg_number'),
            'msg_cat' => Yii::t('models', 'feedback_attr_msg_cat'),
            'msg_text' => Yii::t('models', 'feedback_attr_msg_text'),
            'msg_file' => Yii::t('models', 'feedback_attr_msg_file'),
            'msg_status' => Yii::t('models', 'attr_status'),
            'referral_id' => Yii::t('models', 'user_attr_referral_id'),
            'email' => Yii::t('models', 'user_attr_email'),
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'update_uid' => 'Update Uid',
	];
    }
    
    public function scopes()
    {
        return [
            'order_id_desc' => ['order' => 'id DESC'],
        ];
    }

    public function search($size = false)
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id',$this->id);
	$criteria->compare('user_id',$this->user_id);
        $criteria->compare('user_email',$this->user_email, true);
	$criteria->compare('msg_number',$this->msg_number);
        $criteria->compare('msg_cat',$this->msg_cat);
	$criteria->compare('msg_text',$this->msg_text, true);
	$criteria->compare('msg_file',$this->msg_file, true);
	$criteria->compare('msg_status',$this->msg_status);
	$criteria->compare('create_at',$this->create_at, true);
	$criteria->compare('update_at',$this->update_at, true);
	$criteria->compare('update_uid',$this->update_uid);

	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => $size ? $size : 10
            ]
	]);
    }
    
    public function onBeforeValidate($event) {
        if($this->isNewRecord) {
            $this->user_email = Yii::app()->user->model()->email;
            $this->msg_number = $this->generateNumber();
            $this->msg_status = self::MSTATUS_SEND;
            $this->create_at = date('Y-m-d H:i:s');
            
            if($this->msg_text)
                $this->msg_text = strip_tags($this->msg_text);
        } else {
            $this->update_at = date('Y-m-d H:i:s');
            $this->update_uid = Yii::app()->user->id;
        }

        parent::onBeforeValidate($event);
    }
    
    public static function typeCategory($code = null)
    {
        $_items = [
            self::CAT_PAY => Yii::t('models', 'feedback_attr_category_pay'),
            self::CAT_OUT => Yii::t('models', 'feedback_attr_category_out'),
            self::CAT_PRO => Yii::t('models', 'feedback_attr_category_pro'),
            self::CAT_REF => Yii::t('models', 'feedback_attr_category_ref'),
            self::CAT_OTH => Yii::t('models', 'feedback_attr_category_oth'),
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
    
    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
    
    public static function statusMessage($code = null)
    {
        $_items = [
            self::MSTATUS_SEND => Yii::t('models', 'feedback_attr_status_send'),
            self::MSTATUS_WORK => Yii::t('models', 'feedback_attr_status_work'),
            self::MSTATUS_COMPL => Yii::t('models', 'feedback_attr_status_compl'),
            self::MSTATUS_CANC => Yii::t('models', 'feedback_attr_status_canc'),
            self::MSTATUS_DISA => Yii::t('models', 'feedback_attr_status_disagree'),
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
    
    public static function statusMessageGrid($status)
    {
        $classes[] = 'badge py-1';
        return '<span class="badge py-1 badge-'.self::getStatusMessageClass($status).'">'.self::statusMessage($status).'</span>';
    }
    
    private static function getStatusMessageClass($code)
    {
        $_items = [
            self::MSTATUS_SEND => 'primary',
            self::MSTATUS_WORK => 'info',
            self::MSTATUS_COMPL => 'success',
            self::MSTATUS_CANC => 'warning',
            self::MSTATUS_CANC => 'danger'
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;  
    }
    
    private static function generateNumber()
    {
        $nArr = [];
        
        $numbers = self::model()->findAll(['select' => 'msg_number']);
        if($numbers) {
            foreach($numbers as $number) {
                $nArr[] = $number->msg_number;
            }
        }
        
        if(!in_array($nbr = rand(0000000, 9999999), $nArr)) {
            return $nbr;
        }
    }
}
