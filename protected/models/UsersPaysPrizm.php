<?php

/**
 * This is the model class for table "users_pays_prizm".
 *
 * The followings are the available columns in table 'users_pays_prizm':
 * @property integer $id
 * @property integer $pay_id
 * @property string $tr_id
 * @property string $tr_date
 * @property integer $tr_timestamp
 * @property string $pzm
 * @property double $pay_summ
 * @property string $pay_message
 * @property integer $pay_status
 *
 * The followings are the available model relations:
 * @property UsersPays $pay
 */
class UsersPaysPrizm extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users_pays_prizm';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tr_id, tr_date, tr_timestamp, pzm, pay_summ, pay_status', 'required'),
			array('pay_id, tr_timestamp, pay_status', 'numerical', 'integerOnly'=>true),
			array('pay_summ', 'numerical'),
			array('tr_id, pay_message', 'length', 'max'=>255),
			array('pzm', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pay_id, tr_id, tr_date, tr_timestamp, pzm, pay_summ, pay_message, pay_status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'pay' => array(self::BELONGS_TO, 'UsersPays', 'pay_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pay_id' => 'Pay',
			'tr_id' => 'Tr',
			'tr_date' => 'Tr Date',
			'tr_timestamp' => 'Tr Timestamp',
			'pzm' => 'Pzm',
			'pay_summ' => 'Pay Summ',
			'pay_message' => 'Pay Message',
			'pay_status' => 'Pay Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('pay_id',$this->pay_id);
		$criteria->compare('tr_id',$this->tr_id,true);
		$criteria->compare('tr_date',$this->tr_date,true);
		$criteria->compare('tr_timestamp',$this->tr_timestamp);
		$criteria->compare('pzm',$this->pzm,true);
		$criteria->compare('pay_summ',$this->pay_summ);
		$criteria->compare('pay_message',$this->pay_message,true);
		$criteria->compare('pay_status',$this->pay_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsersPaysPrizm the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
