<?php

class UserHistoryChange extends CActiveRecord
{
    public function tableName()
    {
	return '{{users_history_change}}';
    }

    public function rules()
    {
	return [
            ['user_id, change_field, change_value, update_at, update_uid', 'required'],
            ['user_id, update_uid', 'numerical', 'integerOnly' => true],
            ['change_field', 'length', 'max' => 255],
            ['id, user_id, change_field, change_value, update_at, update_uid', 'safe', 'on' => 'search'],
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
            'id' => 'ID',
            'user_id' => 'User',
            'change_field' => 'Change Field',
            'change_value' => 'Change Value',
            'update_at' => 'Update At',
            'update_uid' => 'Update Uid',
	];
    }

    public function search()
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('user_id', $this->user_id);
	$criteria->compare('change_field', $this->change_field, true);
	$criteria->compare('change_value', $this->change_value, true);
	$criteria->compare('update_at', $this->update_at, true);
	$criteria->compare('update_uid', $this->update_uid);

	return new CActiveDataProvider($this, [
            'criteria'=>$criteria,
	]);
    }

    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
}
