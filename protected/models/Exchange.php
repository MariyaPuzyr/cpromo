<?php

class Exchange extends CActiveRecord
{
    const RSTATUS_VISIB = 0;
    const RSTATUS_INVIS = 1;
    
    const OTYPE_BUY = 0;
    const OTYPE_SELL = 1;
    
    public function tableName()
    {
	return '{{exchange}}';
    }

    public function rules()
    {
	return [
            ['user_id, operation_date, operation_type, count, now_count', 'required'],
            ['user_id, operation_type, count, now_count, record_status', 'numerical', 'integerOnly' => true],
            ['count', 'checkCount'],
            ['count', 'numerical', 'min' => 1],
            ['id, user_id, operation_date, operation_type, count, now_count, record_status', 'safe', 'on' => 'search'],
	];
    }

    public function relations()
    {
	return [
            'user' => [self::BELONGS_TO, 'Users', 'user_id'],
            'exchangeHistories' => [self::HAS_MANY, 'ExchangeHistory', 'exchange_id'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => 'ID',
            'user_id' => 'User',
            'operation_date' => 'Operation Date',
            'operation_type' => 'Operation Type',
            'count' => Yii::t('models', 'exchange_attr_count'),
            'now_count' => 'Now Count',
            'record_status' => 'Record Status',
	];
    }
    
    public function search($size = false)
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id',$this->id);
	$criteria->compare('user_id',$this->user_id);
	$criteria->compare('operation_date',$this->operation_date,true);
	$criteria->compare('operation_type',$this->operation_type);
	$criteria->compare('count',$this->count);
	$criteria->compare('now_count',$this->now_count);
	$criteria->compare('record_status',$this->record_status);

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
    
    public function checkCount()
    {
        if($this->count) {
            $finance = Yii::app()->user->finance;
            $main = self::model()->find(['condition' => 'user_id ='.Yii::app()->user->id.' AND record_status ='.self::RSTATUS_VISIB.' AND DATE(operation_date) = "'.date('Y-m-d').'"']);
            if($main)
                $this->addError('count', Yii::t('models', 'exchange_order_error_exist'));
            
            if($this->scenario == 'addOrderToSell'){
                if($main)
                    $this->addError('models', Yii::t('models', 'exchange_count_error_orderExist'));
                
                if($this->count > $finance->coins)
                    $this->addError ('count', Yii::t('models', 'exchange_count_error_bigBalance'));
                
                if($this->count > ceil($finance->coins_all/2))
                    $this->addError('count', Yii::t('models', 'exchange_count_error_50procent'));
            }elseif($this->scenario == 'addOrderToBuy'){
                $coins = Coins::model()->findByPk(1);
                $priceSumm = $this->count*$coins->price;
                if($priceSumm > $finance->balance)
                    $this->addError('count', Yii::t('models', 'exchange_price_error_bigBalance'));
            }
        }
    }
    
    public function afterSave() {
        if($this->record_status == self::RSTATUS_INVIS){
            $coin = Coins::model()->findByPk(1);
            $coin->price = $coin->price * 0.01;
            $coin->save();
        }

        parent::afterSave();
    }
    
    public static function getOrderType($code = null)
    {
         $_items = [
            self::OTYPE_BUY => Yii::t('models', 'exchange_attr_operation_type_buy'),
            self::OTYPE_SELL => Yii::t('models', 'exchange_attr_operation_type_sell'),
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
}
