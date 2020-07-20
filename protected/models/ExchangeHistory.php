<?php

class ExchangeHistory extends CActiveRecord
{
    public $now_count;
    
    public function tableName()
    {
	return '{{exchange_history}}';
    }

    public function rules()
    {
	return [
            ['exchange_id, operation_date, operation_summ, price_perOne, user_id, count', 'required'],
            ['exchange_id, user_id, count', 'numerical', 'integerOnly' => true],
            ['operation_summ, price_perOne', 'numerical'],
            ['count', 'numerical', 'min' => 20],
            ['operation_summ, count', 'checkOperation'],
            ['id, exchange_id, operation_date, operation_summ, price_perOne, user_id, count', 'safe', 'on' => 'search'],
	];
    }

    public function relations()
    {
	return [
            'exchange' => [self::BELONGS_TO, 'Exchange', 'exchange_id'],
            'user' => [self::BELONGS_TO, 'Users', 'user_id'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => 'ID',
            'exchange_id' => 'Exchange',
            'operation_date' => 'Operation Date',
            'operation_summ' => 'Operation Summ',
            'price_perOne' => 'Price Per One',
            'user_id' => 'User',
            'count' => Yii::t('models', 'exchange_attr_count'),
	];
    }

    public function search()
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id',$this->id);
	$criteria->compare('exchange_id',$this->exchange_id);
	$criteria->compare('operation_date',$this->operation_date,true);
	$criteria->compare('operation_summ',$this->operation_summ);
	$criteria->compare('price_perOne',$this->price_perOne);
	$criteria->compare('user_id',$this->user_id);
	$criteria->compare('count',$this->count);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
	]);
    }

    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
    
    public function onBeforeValidate($event) {
        $this->operation_date = date('Y-m-d H:i:s');
        $this->user_id = Yii::app()->user->id;
        $this->price_perOne = Coins::model()->findByPk(1)->price;
        $this->operation_summ = MHelper::formatCurrency($this->count * $this->price_perOne);
        
        parent::onBeforeValidate($event);
    }
    
    public function checkOperation()
    {
        if($this->count){
            $balance = Yii::app()->user->finance;
            if($this->scenario == 'buyCoins') {
                if($this->operation_summ > $balance->balance)
                    $this->addError('count', Yii::t('models', 'exchange_price_error_bigBalance'));
            
                if($this->now_count < $this->count)
                    $this->addError('count', Yii::t('models', 'exchange_count_error_noCP'));
            }else{
                if($this->count > $balance->coins)
                    $this->addError('count', Yii::t('models', 'exchange_count_error_noYourCP'));
                
                if($this->now_count < $this->count)
                    $this->addError('count', Yii::t('models', 'exchange_count_error_bigCP'));
            }
        }
    }
    
    public function afterSave()
    {
        $exchange = $this->exchange;
        
        $cMarket = new CoinsMarket;
        $cMarket->setScenario($this->scenario == 'buyCoins' ? 'buyCoins' : 'sellCoins');
        $cMarket->operation_summ = $this->operation_summ;
        $cMarket->from_exchange = true;
        $cMarket->count = $this->count;
        $cMarket->user_id = $exchange->user_id;
        $cMarket->to_user = Yii::app()->user->id;
        $cMarket->validate();
        if(!$cMarket->save())
            print_r($cMarket->getErrors());
        
        $cMarketM = new CoinsMarket;
        $cMarketM->setScenario($this->scenario == 'buyCoins' ? 'sellCoins' : 'buyCoins');
        $cMarketM->operation_summ = $this->operation_summ;
        $cMarketM->from_exchange = true;
        $cMarketM->count = $this->count;
        $cMarketM->user_id = Yii::app()->user->id;
        $cMarketM->to_user = $exchange->user_id;
        $cMarketM->validate();
        if(!$cMarketM->save())
            print_r($cMarketM->getErrors());
        
        
        Exchange::model()->updateByPk($this->exchange_id, ['now_count' => $this->now_count - $this->count]);
        if(($this->now_count - $this->count) == 0)
            Exchange::model()->updateByPk($this->exchange_id, ['record_status' => Exchange::RSTATUS_INVIS]);
        
        parent::afterSave();
    }
}
