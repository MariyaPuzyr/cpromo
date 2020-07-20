<?php

class UsersDeposit extends CActiveRecord
{
    const TYPE_ONDEP = 0;
    const TYPE_OFDEP = 1;
    
    public function tableName()
    {
	return '{{users_deposit}}';
    }

    public function rules()
    {
	return [
            ['user_id, operation_number, operation_date, operation_type, operation_summ, operation_summAll', 'required'],
            ['user_id, operation_type', 'numerical', 'integerOnly' => true],
            ['operation_summ, operation_summAll', 'numerical'],
            ['operation_number', 'length', 'max' => 11],
            ['operation_type', 'in', 'range' => [self::TYPE_ONDEP, self::TYPE_OFDEP]],
            ['operation_summ', 'checkOperationSumm'],
            ['operation_summ', 'numerical', 'min' => 1],
            ['id, user_id, operation_number, operation_date, operation_type, operation_summ, operation_summAll', 'safe', 'on' => 'search'],
	];
    }

    public function relations()
    {
	return [
            'usersBalances' => [self::HAS_MANY, 'UsersBalance', 'deposit_id'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => Yii::t('models', 'attr_id'),
            'user_id' => Yii::t('models', 'attr_user_id'),
            'operation_number' => Yii::t('models', 'attr_operation_number'),
            'operation_date' => Yii::t('models', 'attr_date'),
            'operation_type' => Yii::t('models', 'attr_type'),
            'operation_summ' => Yii::t('models', 'attr_summ'),
            'operation_summAll' => Yii::t('models', 'Deposit_operation_summAll'),
	];
    }
    
    public function scopes()
    {
        return [
            'order_id_desc' => ['order' => 'id DESC'],
            'order_id_desc_relation' => ['order' => 'rDeposit.id DESC']
        ];
    }

    public function search($size = false)
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('user_id', $this->user_id);
	$criteria->compare('operation_number', $this->operation_number, true);
	$criteria->compare('operation_date', $this->operation_date, true);
	$criteria->compare('operation_type', $this->operation_type);
	$criteria->compare('operation_summ', $this->operation_summ);
        $criteria->compare('operation_summAll', $this->operation_summAll);

	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => $size ? $size : 25
            ]
	]);
    }

    public static function model($className=__CLASS__)
    {
    	return parent::model($className);
    }
    
    public function onBeforeValidate($event) {
        $this->user_id = Yii::app()->user->id;
        $this->operation_date = date('Y-m-d H:i:s');
        $this->operation_summAll = $this->operation_type == self::TYPE_ONDEP ? self::getSummAll($this->user_id) + $this->operation_summ : self::getSummAll($this->user_id) - $this->operation_summ;
        $this->operation_number = MHelper::getOperationNumber('UsersDeposit', 'D');
        
        parent::onBeforeValidate($event);
    }
    
    public function checkOperationSumm()
    {
        if($this->operation_type == self::TYPE_OFDEP && $this->operation_summ){
            $nowMax = self::getSummAll($this->user_id);
            
            if($this->operation_summ > $nowMax)
                $this->addError('operation_summ', Yii::t('models', 'Deposit_attr_operation_summ_error_big'));
            
            $dPays = self::model()->findAllByAttributes(['user_id' => $this->user_id, 'operation_type' => self::TYPE_ONDEP]);
            if($dPays) {
                foreach($dPays as $dep)
                    if(MHelper::diffDate(date('Y-m-d'), $dep->operation_date) < Yii::app()->settings->get('system', 'deposit_pay_freeze_period'))
                        $freeze += $dep->operation_summ;
                    
                if($this->operation_summ > ($nowMax - $freeze))
                    $this->addError('operation_summ', Yii::t('models', 'Deposit_attr_operation_summ_error_bigFreeze'));
            }
        }elseif($this->operation_type == self::TYPE_ONDEP && $this->operation_summ){
            if($this->operation_summ > Yii::app()->user->finance->balance)
                $this->addError('operation_summ', Yii::t('models', 'Deposit_attr_operation_summ_error_bigBalance'));
        }
    }
    
    public function afterSave() {
        UsersBalance::model()->formRecord($this, $this->operation_type == self::TYPE_ONDEP ? UsersBalance::TYPE_ONDEP : UsersBalance::TYPE_OFDEP);
        parent::afterSave();
    }
    
    public static function getOperationType($code = null)
    {
        $_items = [
            self::TYPE_ONDEP => Yii::t('models', 'Deposit_attr_operation_type_ondep'),
            self::TYPE_OFDEP => Yii::t('models', 'Deposit_attr_operation_type_ofdep'),
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
    
    private static function getSummAll($user_id)
    {
        $data = self::model()->order_id_desc()->findByAttributes(['user_id' => $user_id])->operation_summAll;
        if(!$data)
            $data = 0;
        
        return $data;
    }
}
