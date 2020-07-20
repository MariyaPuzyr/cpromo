<?php

class UserHistoryLogin extends CActiveRecord
{
    public function tableName()
    {
	return '{{users_history_login}}';
    }

    public function rules()
    {
    	return [
            ['user_id, login_time, login_ip, login_client', 'required'],
            ['user_id', 'numerical', 'integerOnly' => true],
            ['login_ip', 'length', 'max' => 15],
            ['login_client', 'length', 'max' => 500],
            ['id, user_id, login_time, login_ip, login_client', 'safe', 'on' => 'search'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => Yii::t('models', 'attr_id'),
            'user_id' => Yii::t('models', 'attr_user_id'),
            'login_time' => Yii::t('models', 'user_history_login_login_time'),
            'login_ip' => Yii::t('models', 'user_history_login_login_ip'),
            'login_client' => Yii::t('models', 'user_history_login_login_client'),
	];
    }
    
    public function scopes()
    {
        return [
            'order_id' => [
                'order' => 'id DESC'
            ]
        ];
    }

    public function search($size = null)
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('user_id', $this->user_id);
	$criteria->compare('login_time', $this->login_time, true);
	$criteria->compare('login_ip', $this->login_ip, true);
	$criteria->compare('login_client', $this->login_client, true);

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
}
