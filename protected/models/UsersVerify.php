<?php

class UsersVerify extends CActiveRecord
{
    public function tableName()
    {
	return 'users_verify';
    }

    public function rules()
    {
        return [
            ['email, code, verify_date', 'required'],
            ['code', 'numerical', 'integerOnly' => true],
            ['email', 'length', 'max' => 50],
            ['id, email, code, verify_date', 'safe', 'on' => 'search'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => 'ID',
            'email' => 'Email',
            'code' => 'Code',
            'verify_Date' => 'Verify Date',
	];
    }
    
    public function scopes()
    {
        return [
            'order_id_desc' => ['order' => 'id DESC']
        ];
    }

    public function search()
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('email', $this->email, true);
	$criteria->compare('code', $this->code);
	$criteria->compare('verify_date', $this->verify_Date, true);

	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
	]);
    }

    public static function model($className=__CLASS__)
    {
    	return parent::model($className);
    }
    
    public function checkVerify($email, $code)
    {
        $model = self::model()->findByAttributes(['email' => $email]);
        return ($model && $model->code == $code) ? true : false;
    }
}
