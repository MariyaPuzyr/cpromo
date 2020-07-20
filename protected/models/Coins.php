<?php

class Coins extends CActiveRecord
{
    public function tableName()
    {
	return '{{coins}}';
    }

    public function rules()
    {
        return [
            ['id, startLimit, nowLimit, price', 'required'],
            ['id, startLimit, nowLimit', 'numerical', 'integerOnly' => true],
            ['price', 'numerical'],
            ['id, startLimit, nowLimit, price', 'safe', 'on' => 'search'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => 'ID',
            'startLimit' => 'Start Limit',
            'nowLimit' => 'Now Limit',
            'price' => 'Price',
	];
    }

    public function search()
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('startLimit', $this->startLimit);
	$criteria->compare('nowLimit', $this->nowLimit);
	$criteria->compare('price', $this->price);

	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
	]);
    }

    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
}
