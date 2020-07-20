<?php

class UsersPaysPrfmoney extends CActiveRecord
{
    public $url = 'https://perfectmoney.com/api/step1.asp';
    public $units = 'USD';
    public $resultUrl = '/finance/payByPrfmoneyResult?status=result';
    public $successUrl = '/finance/payByPrfmoneyResult?status=success';
    public $failureUrl = '/finance/payByPrfmoneyResult?status=fail';
    public $suggested_memo = 'For service on IT platform';
    public $account = 'U9107635';
    public $alternateSecret = 'T5DW5363IuwiydMIJzPRsFHkI';
    
    protected $hash;

    public function tableName()
    {
	return '{{users_pays_prfmoney}}';
    }
    
    public function init() {
        $this->hash = strtoupper(md5($this->alternateSecret));
        parent::init();
    }

    public function rules()
    {
	return [
            ['pay_id, account, amount, units, suggested_memo, status', 'required'],
            ['pay_id, bath_num, status, timestamp', 'numerical', 'integerOnly' => true],
            ['amount', 'numerical'],
            ['account, payer_account', 'length', 'max' => 50],
            ['units', 'length', 'max' => 6],
            ['pay_id, account, amount, units, bath_num, suggested_memo, status, payer_account, timestamp, hashIn', 'safe', 'on' => 'search'],
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
            'pay_id' => 'Pay',
            'account' => 'Acount',
            'amount' => 'Amount',
            'units' => 'Units',
            'bath_num' => 'Bath Num',
	];
    }

    public function search()
    {
	$criteria = new CDbCriteria;

	$criteria->compare('pay_id',$this->pay_id);
	$criteria->compare('account',$this->account, true);
	$criteria->compare('amount',$this->amount);
	$criteria->compare('units',$this->units, true);
	$criteria->compare('bath_num',$this->bath_num, true);
        $criteria->compare('suggested_memo', $this->suggested_memo, true);
	$criteria->compare('status', $this->status, true);
        $criteria->compare('payer_account', $this->payer_account, true);
        $criteria->compare('timestamp', $this->timestamp);
        $criteria->compare('hashIn', $this->hashIn, true);

	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
	]);
    }

    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
    
    public function checkHash($data) {
        $params = [
            $data['PAYMENT_ID'],
            $data['PAYEE_ACCOUNT'],
            $data['PAYMENT_AMOUNT'],
            $data['PAYMENT_UNITS'],
            $data['PAYMENT_BATCH_NUM'],
            $data['PAYER_ACCOUNT'],
            $this->hash,
            $data['TIMESTAMPGMT'],
        ];

        $hash = strtoupper(md5(implode(':', $params)));
        return ($hash == $data['V2_HASH']) ? true : false;
    }
}
