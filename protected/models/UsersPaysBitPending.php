<?php

class UsersPaysBitPending extends MBaseModel
{
    public function tableName()
    {
	return '{{users_pays_bit_pending}}';
    }

    public function rules()
    {
	return [
            ['transaction_hash, value, pay_id', 'required'],
            ['pay_id, count', 'numerical', 'integerOnly' => true],
            ['value', 'numerical'],
            ['transaction_hash', 'length', 'max' => 64],
            ['transaction_hash, value, count, pay_id', 'safe', 'on' => 'search'],
	];
    }

    public function relations()
    {
	return [
            'pay' => [self::BELONGS_TO, 'UsersPays', 'pay_id'],
	];
    }

    public function attributeLabels()
    {
    	return [
            'transaction_hash' => 'Transaction Hash',
            'value' => 'Value',
            'pay_id' => 'Pay',
	];
    }

    public function search()
    {
	$criteria = new CDbCriteria;

	$criteria->compare('transaction_hash', $this->transaction_hash, true);
	$criteria->compare('value', $this->value);
        $criteria->compare('count', $this->count);
	$criteria->compare('pay_id', $this->pay_id);

	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
	]);
    }
    
    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
}
