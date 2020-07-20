<?php

class UsersPaysPayeer extends MBaseModel
{
    public $merchant = '1044613279';
    public $key = 'zQojSUDXujUgEfT0';
    public $key_dop = 'AXb2Kw23NondPNU53w';
    public $priceCurr = 'USD';
    public $pay_summ;
    public $price;
    
    public function tableName()
    {
	return '{{users_pays_payeer}}';
    }

    public function rules()
    {
	return [
            ['pay_id, action_number, sign', 'required'],
            ['pay_id, payeer_id, payeer_transferID', 'numerical', 'integerOnly' => true],
            ['sign, payeer_client_email', 'length', 'max' => 128],
            ['payeer_status, payeer_summa_out', 'length', 'max' => 10],
            ['payeer_ps', 'length', 'max' => 6],
            ['payeer_client_account', 'length', 'max' => 8],
            ['pay_id, action_number, sign, payeer_id, payeer_status, payeer_ps, payeer_transferID, payeer_payDate, payeer_client_account, payeer_client_email, payeer_summa_out', 'safe', 'on' => 'search'],
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
            'sign' => 'Sign',
            'payeer_id' => 'Payeer',
            'payeer_status' => 'Payeer Status',
            'payeer_ps' => 'Payeer Ps',
            'payeer_transferID' => 'Payeer Transfer',
            'payeer_client_account' => 'Payeer Client Account',
            'payeer_summa_out' => 'Payeer Summa Out',
	];
    }

    public function search()
    {
	$criteria = new CDbCriteria;

	$criteria->compare('pay_id', $this->pay_id);
	$criteria->compare('sign', $this->sign, true);
	$criteria->compare('payeer_id', $this->payeer_id);
	$criteria->compare('payeer_status', $this->payeer_status, true);
	$criteria->compare('payeer_ps', $this->payeer_ps, true);
	$criteria->compare('payeer_transferID', $this->payeer_transferID);
	$criteria->compare('payeer_client_account', $this->payeer_client_account, true);
        $criteria->compare('payeer_client_email', $this->payeer_client_email, true);
	$criteria->compare('payeer_summa_out', $this->payeer_summa_out, true);

        return new CActiveDataProvider($this, [
            'criteria'=>$criteria,
	]);
    }
    
    public function afterSave() {
        if(self::statusPayeerToModel($this->payeer_status) != UsersPays::PSTATUS_WAIT)
            UsersPays::model()->changeStatus($this->pay_id, $this->statusPayeerToModel($this->payeer_status) ? $this->statusPayeerToModel($this->payeer_status) : UsersPays::PSTATUS_ERROR);
        elseif(self::statusPayeerToModel($this->payeer_status) == UsersPays::PSTATUS_ERROR) {
            UsersPays::model()->changeStatus($this->pay_id, UsersPays::PSTATUS_ERROR);
        }
        
        parent::afterSave();
    }
    
    public function formPayRecord($data)
    {
        $this->pay_id = $data->id;
        $this->action_number = $data->operation_number;
        $this->pay_summ = $data->operation_summ;
        if($this->pay_summ && is_numeric($this->pay_summ)) {
            $this->price = $this->formPayPrice($this->pay_summ);
            
            $arHash = [$this->merchant, $this->action_number, $this->price, $this->priceCurr, base64_encode('')];
            $arHash[] = $this->key;
            $this->sign = $this->createSign($arHash);
        }
    }
    
    public static function formPayPrice($summ)
    {
        return number_format($summ, 2, '.', '');
    }
    
    public static function createSign($arHash)
    {
        return strtoupper(hash('sha256', implode(":", $arHash)));
    }
    
    public static function statusPayeerToModel($code)
    {
        $_items = [
            'success' => UsersPays::PSTATUS_COMPL,
            'fail' => UsersPays::PSTATUS_ERROR,
            'cancelled' => UsersPays::PSTATUS_CANC
        ];
        
        return $_items[$code];
    }
    
    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
}
