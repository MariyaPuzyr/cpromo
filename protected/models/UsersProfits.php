<?php

class UsersProfits extends MBaseModel
{
    const TYPE_SYSTEM = 0;
    const TYPE_USER = 1;
    const TYPE_BUYSTAT = 2;
    
    public function tableName()
    {
	return '{{users_profits}}';
    }

    public function rules()
    {
	return [
            ['user_id, operation_number, operation_date, operation_type, operation_summ, operation_percent', 'required'],
            ['user_id, operation_type, from_user, from_level', 'numerical', 'integerOnly' => true],
            ['from_summ', 'numerical'],
            ['operation_number', 'length', 'max' => 11],
            ['operation_number', 'unique'],
            ['id, user_id, operation_number, operation_date, operation_type, operation_summ, operation_percent, from_user, from_summ, from_level', 'safe', 'on' => 'search'],
	];
    }

    public function relations()
    {
	return [
            'fromUser' => [self::BELONGS_TO, 'Users', 'from_user'],
            'user' => [self::BELONGS_TO, 'Users', 'user_id'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => Yii::t('models', 'attr_id'),
            'user_id' => Yii::t('models', 'attr_user_id'),
            'operation_number' => Yii::t('models', 'attr_operation_number'),
            'operation_date' => Yii::t('models', 'attr_date'),
            'operation_type' => Yii::t('models', 'Profits_attr_profit_type'),
            'operation_summ' => Yii::t('models', 'attr_summ'),
            'operation_percent' => Yii::t('models', 'Profits_attr_operation_percent'),
            'from_user' => Yii::t('models', 'Profits_attr_from_user'),
            'from_summ' => Yii::t('models', 'Profits_attr_from_summ'),
            'from_level' => Yii::t('models', 'Profits_attr_from_level'),
	];
    }

    public function scopes()
    {
        return [
            'order_id_desc' => ['order' => 'id DESC'],
            'order_id_desc_find' => ['order' => 't.id DESC']
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
	$criteria->compare('operation_summ', $this->operation_summ, true);
	$criteria->compare('operation_percent', $this->operation_percent, true);
	$criteria->compare('from_user', $this->from_user);
        $criteria->compare('from_summ', $this->from_summ);
        $criteria->compare('from_level', $this->from_level);

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
    
    public function formBonusOnStatus($user_id, $operation_summ, $levelBonus, $priceStatus)
    {
        $model = self::model();
        $model->isNewRecord = true;
        $model->user_id = $user_id;
        $model->operation_number = MHelper::getOperationNumber('UsersProfits', 'B');
        $model->operation_date = date('Y-m-d H:i:s');
        $model->operation_type = self::TYPE_SYSTEM;
        $model->operation_summ = $operation_summ > 0 ? ($operation_summ * $levelBonus)/100 : ($priceStatus * $levelBonus)/100;
        $model->operation_percent = $levelBonus;
        $model->save();
        
        UsersBalance::model()->formRecord($model, UsersBalance::TYPE_PROFIT);
    }
    
    public static function profitType($code = null)
    {
        $_items = [
            self::TYPE_SYSTEM => Yii::t('models', 'Profits_attr_profit_type_system'),
            self::TYPE_USER => Yii::t('models', 'Profits_attr_profit_type_referral'),
            self::TYPE_BUYSTAT => Yii::t('models', 'Profits_attr_profit_type_buystat'),
        ];
                
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }

    public static function profitTypeGrid($code)
    {
        $classes[] = 'badge py-1';
        $classes[] = $code == self::TYPE_SYSTEM ? 'badge-primary' : 'badge-info';
        
        return '<span class="'.implode(' ', $classes).'">'. self::model()->profitType($code).'</span>';
    }
    
    public static function profitTypeWithColor($code)
    {
        $class = $code == self::TYPE_SYSTEM ? 'text-success' : 'text-dark';
        return '<span class="'.$class.'">'.self::model()->profitType($code).'</span>';
    }
    
    
}
