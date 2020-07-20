<?php

class UsersPaysBit extends MBaseModel
{
    public $blockchain_root = "https://blockchain.info/";
    public $blockchain_receive_root = "https://api.blockchain.info/";
    public $secret = "51238a6ec0a36585b4b98861e5ead49d";
    public $xpub = "xpub6BmUZuETzebmE3bPkue54wU16U9MRf8PPHoTM1V71K3ruZV3TyqBzAJUxRA868YCppi6bJJ5YCVr6f9jzkuDrkn4erLufHGdNf2RbNoeCWz";
    public $api_key = "7ccb48d0-b611-4408-812a-fc41b1257c23";
    public $gap_limit = 300;

    public function tableName()
    {
	return '{{users_pays_bit}}';
    }

    public function rules()
    {
	return [
            ['pay_id, price_in_usd, price_in_btc, product_url', 'required'],
            ['pay_id', 'numerical', 'integerOnly' => true],
            ['price_in_usd, price_in_btc', 'numerical'],
            ['address', 'length', 'max' => 36],
            ['pay_id, price_in_usd, price_in_btc, product_url, address', 'safe', 'on' => 'search'],
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
            'price_in_usd' => 'Price In Usd',
            'price_in_btc' => 'Price In Btc',
            'product_url' => 'Product Url',
            'address' => 'Address',
	];
    }

    public function search()
    {
	$criteria = new CDbCriteria;

	$criteria->compare('pay_id', $this->pay_id);
	$criteria->compare('price_in_usd', $this->price_in_usd);
	$criteria->compare('price_in_btc', $this->price_in_btc);
	$criteria->compare('product_url', $this->product_url, true);
	$criteria->compare('address', $this->address, true);

	return new CActiveDataProvider($this, [
            'criteria'=>$criteria,
	]);
    }
    
    public function formPay($data)
    {
        $this->pay_id = $data->id;
        $this->price_in_usd = $data->operation_summ;
        $this->price_in_btc = file_get_contents($this->blockchain_root."tobtc?currency=USD&value=".$this->price_in_usd);
        $this->product_url = $data->operation_number;
    }
    
    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
}
