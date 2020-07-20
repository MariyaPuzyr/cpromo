<?php

/**
 * This is the model class for table "users_pays_paypal".
 *
 * The followings are the available columns in table 'users_pays_paypal':
 * @property integer $pay_id
 * @property double $mc_gross
 * @property string $mc_currency
 * @property string $payment_date
 * @property string $payment_status
 * @property string $business
 * @property string $receiver_email
 *
 * The followings are the available model relations:
 * @property UsersPays $pay
 */
class UsersPaysPaypal extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users_pays_paypal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pay_id, mc_gross, mc_currency, payment_date, payment_status, business, receiver_email', 'required'),
			array('pay_id', 'numerical', 'integerOnly'=>true),
			array('mc_gross', 'numerical'),
			array('mc_currency', 'length', 'max'=>5),
			array('payment_status', 'length', 'max'=>64),
			array('business, receiver_email', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pay_id, mc_gross, mc_currency, payment_date, payment_status, business, receiver_email', 'safe', 'on'=>'search'),
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
			'pay_id' => 'Pay',
			'mc_gross' => 'Mc Gross',
			'mc_currency' => 'Mc Currency',
			'payment_date' => 'Payment Date',
			'payment_status' => 'Payment Status',
			'business' => 'Business',
			'receiver_email' => 'Receiver Email',
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

		$criteria->compare('pay_id',$this->pay_id);
		$criteria->compare('mc_gross',$this->mc_gross);
		$criteria->compare('mc_currency',$this->mc_currency,true);
		$criteria->compare('payment_date',$this->payment_date,true);
		$criteria->compare('payment_status',$this->payment_status,true);
		$criteria->compare('business',$this->business,true);
		$criteria->compare('receiver_email',$this->receiver_email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsersPaysPaypal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
