<?php

class UsersPaysCoinpayments extends CActiveRecord
{
    public $merchant_id = '0fdc5f21e9eb0f6865ecfb47eaac8981';
    public $ipn_secret = 'sfIU1eXdGl099ffs0JiZ';
    public $success_url = '/finance/payByCoinsResult?pay_status=success';
    public $cancel_url = '/finance/payByCoinsResult?pay_status=cancel';
    public $ipn_url = '/finance/payByCoinsResult';
    
    public $statuses = [
        '-2' => 'PayPal Refund or Reversal',
	'-1' => 'Cancelled / Timed Out',
	'0' => 'Waiting for buyer funds',
	'1' => 'We have confirmed coin reception from the buyer',
	'2' => 'Queued for nightly payout (if you have the Payout Mode for this coin set to Nightly)',
	'3' => 'PayPal Pending (eChecks or other types of holds)',
	'100' => 'Payment Complete. We have sent your coins to your payment address or 3rd party payment system reports the payment complete'
    ]; 
    
    public function tableName()
    {
    	return '{{users_pays_coinpayments}}';
    }

    public function rules()
    {
	return [
            ['pay_id, item_name', 'required'],
            ['pay_id', 'numerical', 'integerOnly' => true],
            ['amount1, amount2', 'numerical'],
            ['txn_id', 'length', 'max' => 50],
            ['item_name', 'length', 'max' => 11],
            ['currency1, currency2', 'length', 'max' => 6],
            ['status', 'length', 'max' => 4],
            ['received_confirms, status_text', 'length', 'max' => 255],
            ['pay_id, txn_id, item_name, amount1, amount2, currency1, currency2, status, status_text, received_confirms', 'safe', 'on'=>'search'],
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
            'txn_id' => 'Txn',
            'item_name' => 'Item Name',
            'amount1' => 'Amount1',
            'amount2' => 'Amount2',
            'currency1' => 'Currency1',
            'currency2' => 'Currency2',
            'status' => 'Status',
            'status_text' => 'Status Text',
            'received_confirms' => 'Received Confirms',
	];
    }

    public function search()
    {
	$criteria = new CDbCriteria;

	$criteria->compare('pay_id', $this->pay_id);
	$criteria->compare('txn_id', $this->txn_id, true);
	$criteria->compare('item_name', $this->item_name, true);
	$criteria->compare('amount1', $this->amount1);
	$criteria->compare('amount2', $this->amount2);
	$criteria->compare('currency1', $this->currency1, true);
	$criteria->compare('currency2', $this->currency2, true);
	$criteria->compare('status', $this->status, true);
	$criteria->compare('status_text', $this->status_text, true);
	$criteria->compare('received_confirms', $this->received_confirms, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
	]);
    }

    public static function model($className=__CLASS__)
    {
    	return parent::model($className);
    }
}
