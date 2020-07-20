<?php

/**
 * This is the model class for table "users_outs_disabled".
 *
 * The followings are the available columns in table 'users_outs_disabled':
 * @property integer $id
 * @property integer $finance_payeer
 * @property integer $finance_prfmoney
 * @property integer $finance_usdtrc
 */
class UsersOutsDisabled extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users_outs_disabled';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, finance_payeer, finance_prfmoney, finance_usdtrc', 'required'),
			array('id, finance_payeer, finance_prfmoney, finance_usdtrc', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, finance_payeer, finance_prfmoney, finance_usdtrc', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'finance_payeer' => 'Finance Payeer',
			'finance_prfmoney' => 'Finance Prfmoney',
			'finance_usdtrc' => 'Finance Usdtrc',
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
		$criteria->compare('finance_payeer',$this->finance_payeer);
		$criteria->compare('finance_prfmoney',$this->finance_prfmoney);
		$criteria->compare('finance_usdtrc',$this->finance_usdtrc);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsersOutsDisabled the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
