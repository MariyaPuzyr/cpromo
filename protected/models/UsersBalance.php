<?php

class UsersBalance extends MBaseModel
{
    const TYPE_INVEST = 0;
    const TYPE_OUT = 1;
    const TYPE_PROFIT = 2;
    const TYPE_BUYSTATUS = 3;
    const TYPE_BUYCOIN = 4;
    const TYPE_SALECOIN = 5;
    const TYPE_ONDEP = 6;
    const TYPE_OFDEP = 7;
    const TYPE_PROFITCOIN = 8;
    const TYPE_PROFITSTAT = 9;
    const TYPE_PROFITCOINSELL = 10;
    const TYPE_PROCENTEX = 11;
    
    public function tableName()
    {
	return '{{users_balance}}';
    }

    public function rules()
    {
	return [
            ['user_id, operation_number, operation_date, operation_summ, operation_summAll, operation_type, operation_system', 'required'],
            ['user_id, operation_type, operation_system, pay_id, out_id, profit_id, buystatus_id, deposit_id, coinmarket_id', 'numerical', 'integerOnly' => true],
            ['operation_summ, operation_summAll', 'numerical'],
            ['operation_number', 'length', 'max' => 11],
            ['operation_type', 'in', 'range' => array_keys($this->operationType())],
            ['operation_system', 'in', 'range' => array_keys($this->getFinType())],
            ['id, user_id, operation_number, operation_date, operation_summ, operation_summAll, operation_type, operation_system, pay_id, out_id, profit_id, buystatus_id, deposit_id, coinmarket_id', 'safe', 'on' => 'search'],
	];
    }

    public function relations()
    {
	return [
            'buystatus' => [self::BELONGS_TO, 'UsersStatus', 'buystatus_id'],
            'deposit' => [self::BELONGS_TO, 'UsersDeposit', 'deposit_id'],
            'out' => [self::BELONGS_TO, 'UsersOuts', 'out_id'],
            'pay' => [self::BELONGS_TO, 'UsersPays', 'pay_id'],
            'profit' => [self::BELONGS_TO, 'UsersProfits', 'profit_id'],
            'market' => [self::BELONGS_TO, 'CoinsMarket', 'coinmarket_id'],
            'user' => [self::BELONGS_TO, 'Users', 'user_id'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => Yii::t('models', 'attr_id'),
            'user_id' => Yii::t('models', 'attr_user_id'),
            'operation_number' => Yii::t('models', 'attr_operation_number'),
            'operation_date' => Yii::t('models', 'attr_date'),
            'operation_summ' => Yii::t('models', 'attr_summ'),
            'operation_summAll' => Yii::t('models', 'Balance_operation_summAll'),
            'operation_type' => Yii::t('models', 'attr_operation'),
            'operation_system' => Yii::t('models', 'attr_system'),
            'pay_id' => 'Pay',
            'out_id' => 'Out',
            'profit_id' => 'Profit',
            'buystatus_id' => 'Buystatus',
            'deposit_id' => 'Deposit',
	];
    }
    
    public function scopes()
    {
        return [
            'order_id_desc' => ['order' => 'id DESC'],
            'order_id_desc_relation' => ['order' => 'rBalance.id DESC']
        ];
    }

    public function search()
    {
	$criteria=new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('user_id', $this->user_id);
	$criteria->compare('operation_number', $this->operation_number, true);
	$criteria->compare('operation_date', $this->operation_date, true);
	$criteria->compare('operation_summ', $this->operation_summ);
	$criteria->compare('operation_summAll', $this->operation_summAll);
	$criteria->compare('operation_type', $this->operation_type);
	$criteria->compare('operation_system', $this->operation_system);
	$criteria->compare('pay_id', $this->pay_id);
	$criteria->compare('out_id', $this->out_id);
	$criteria->compare('profit_id', $this->profit_id);
	$criteria->compare('buystatus_id', $this->buystatus_id);
	$criteria->compare('deposit_id', $this->deposit_id);
        $criteria->compare('coinmarket_id', $this->coinmarket_id);

	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
	]);
    }

    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
    
    public static function operationType($code = null)
    {
        $_items = [
            self::TYPE_INVEST => Yii::t('models', 'Balance_attr_operation_type_invest'),
            self::TYPE_OUT => Yii::t('models', 'Balance_attr_operation_type_out'),
            self::TYPE_PROFIT => Yii::t('models', 'Balance_attr_operation_type_profit'),
            self::TYPE_BUYSTATUS => Yii::t('models', 'Balance_attr_operation_type_buystatus'),
            self::TYPE_BUYCOIN => Yii::t('models', 'Balance_attr_operation_type_buycoin'),
            self::TYPE_SALECOIN => Yii::t('models', 'Balance_attr_operation_type_salecoin'),
            #self::TYPE_ONDEP => Yii::t('models', 'Balance_attr_operation_type_ondep'),
            #self::TYPE_OFDEP => Yii::t('models', 'Balance_attr_operation_type_ofdep'),
            self::TYPE_PROFITCOIN => Yii::t('models', 'Balance_attr_operation_type_profitcoin'),
            self::TYPE_PROFITSTAT => Yii::t('models', 'Balance_attr_operation_type_profitstatus'),
            self::TYPE_PROFITCOINSELL => Yii::t('models', 'Balance_attr_operation_type_profitcoinsell'),
            self::TYPE_PROCENTEX => Yii::t('models', 'Balance_attr_operation_type_procentex'),
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
    
    public static function operationTypeWithColor($code = null)
    {
        $_items = [
            self::TYPE_INVEST => '<span class="text-success">'.Yii::t('models', 'Balance_attr_operation_type_invest').'</span>',
            self::TYPE_OUT => Yii::t('models', 'Balance_attr_operation_type_out'),
            self::TYPE_PROFIT => '<span class="text-success">'.Yii::t('models', 'Balance_attr_operation_type_profit').'</span>',
            self::TYPE_BUYSTATUS => '<span class="text-primary">'.Yii::t('models', 'Balance_attr_operation_type_buystatus').'</span>',
            self::TYPE_BUYCOIN => '<span class="text-warning">'.Yii::t('models', 'Balance_attr_operation_type_buycoin').'</span>',
            self::TYPE_SALECOIN => Yii::t('models', 'Balance_attr_operation_type_salecoin'),
            #self::TYPE_ONDEP => '<span class="text-info">'.Yii::t('models', 'Balance_attr_operation_type_ondep').'</span>',
            #self::TYPE_OFDEP => Yii::t('models', 'Balance_attr_operation_type_ofdep'),
            self::TYPE_PROFITCOIN => '<span class="text-success">'.Yii::t('models', 'Balance_attr_operation_type_profitcoin').'</span>',
            self::TYPE_PROFITSTAT => '<span class="text-success">'.Yii::t('models', 'Balance_attr_operation_type_profitstatus').'</span>',
            self::TYPE_PROFITCOINSELL => '<span class="text-success">'.Yii::t('models', 'Balance_attr_operation_type_profitcoinsell'.'</span>'),
            self::TYPE_PROCENTEX => '<span class="text-primary">'.Yii::t('models', 'Balance_attr_operation_type_procentex').'</span>',
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
    }
    
    public static function formRecord($data, $type)
    {
        $balance = self::model()->order_id_desc()->findByAttributes(['user_id' => $data->user_id])->operation_summAll;
        
        $model = new UsersBalance;
        $model->user_id = $data->user_id;
        $model->operation_number = $data->operation_number;
        $model->operation_date = $data->operation_date;
        $model->operation_summ = $type == self::TYPE_OUT ? $data->operation_allSumm : $data->operation_summ;
        $model->operation_summAll = number_format(in_array($type, [self::TYPE_INVEST, self::TYPE_OFDEP, self::TYPE_PROFIT, self::TYPE_SALECOIN], self::TYPE_PROFITCOINSELL) ? $balance + ($type == self::TYPE_OUT ? $data->operation_allSumm : $data->operation_summ) : $balance - ($type == self::TYPE_OUT ? $data->operation_allSumm : $data->operation_summ),2,'.','');
        $model->operation_type = $type;
        $model->operation_system = isset($data->operation_system) ? $data->operation_system : self::FIN_INNER;
        
        switch ($type) {
            case self::TYPE_INVEST:
                $model->pay_id = $data->id;
                break;
            case self::TYPE_OUT:
                $model->out_id = $data->id;
                break;
            case self::TYPE_PROFIT:
                $model->profit_id = $data->id;
                break;
            case self::TYPE_BUYSTATUS:
                $model->buystatus_id = $data->id;
                break;
            case self::TYPE_ONDEP:
                $model->deposit_id = $data->id;
                break;
            case self::TYPE_OFDEP:
                $model->deposit_id = $data->id;
                break;
            case self::TYPE_PROFITCOIN:
                $model->coinmarket_id = $data->id;
                break;
            case self::TYPE_BUYCOIN:
                $model->coinmarket_id = $data->id;
                break;
            case self::TYPE_SALECOIN:
                $model->coinmarket_id = $data->id;
                break;
            case self::TYPE_PROFITSTAT:
                $model->profit_id = $data->id;
                break;
        }
        
        $model->save();
        Users::model()->updateByPk($model->user_id, ['now_balance' => $model->operation_summAll]);
    }
    
    public static function getValueOnHistory($type, $value)
    {
        switch($type):
            case self::TYPE_INVEST:
                $res = ['sign' => '+', 'color' => 'success', 'value' => $value.'$'];
                break;
            case self::TYPE_OUT:
                $res = ['sign' => '-', 'color' => 'dark', 'value' => $value.'$'];
                break;
            case self::TYPE_PROFIT:
                $res = ['sign' => '+', 'color' => 'success', 'value' => $value.'$'];
                break;
            case self::TYPE_BUYSTATUS:
                $res = ['sign' => '-', 'color' => 'dark', 'value' => $value.'$'];
                break;
            case self::TYPE_ONDEP:
                $res = ['sign' => '-', 'color' => 'dark', 'value' => $value.'$'];
                break;
            case self::TYPE_OFDEP:
                $res = ['sign' => '+', 'color' => 'success', 'value' => $value.'$'];
                break;
            case self::TYPE_PROFITCOIN:
                $res = ['sign' => '+', 'color' => 'success', 'value' => $value.'CP'];
                break;
            case self::TYPE_BUYCOIN:
                $res = ['sign' => '-', 'color' => 'dark', 'value' => $value.'$'];
                break;
            case self::TYPE_SALECOIN:
                $res = ['sign' => '+', 'color' => 'success', 'value' => $value.'$'];
                break;
            case self::TYPE_PROFITSTAT:
                $res = ['sign' => '+', 'color' => 'success', 'value' => $value.'$'];
                break;
            case self::TYPE_PROFITCOINSELL:
                $res = ['sign' => '+', 'color' => 'success', 'value' => $value.'$'];
                break;
            case self::TYPE_PROCENTEX:
                $res = ['sign' => '-', 'color' => 'dark', 'value' => $value.'$'];
                break;
        endswitch;
        
        return '<span class="text-'.$res['color'].'">'.$res['sign'].$res['value'].'</span>';
    }
}
