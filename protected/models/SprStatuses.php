<?php

class SprStatuses extends MBaseModel
{
    const PERWEEK = 0;
    const PERDAY = 1;
    
    public function tableName()
    {
    	return '{{spr_statuses}}';
    }

    public function rules()
    {
	return [
            ['id, name_ru, name_en, price, max_coin_buy_summ, max_levels, out_count, out_count_period, out_max_summ', 'required'],
            ['id, price, max_coin_buy_summ, max_levels, out_count, out_count_period, out_max_summ', 'numerical', 'integerOnly' => true],
            ['name_ru, name_en', 'length', 'max' => 50],
            ['id, name_ru, name_en, price, max_coin_buy_summ, max_levels, out_count, out_count_period, out_max_summ', 'safe', 'on' => 'search'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => Yii::t('models', 'attr_id'),
            'name_ru' => Yii::t('models', 'sprStatuses_attr_name_ru'),
            'name_en' => Yii::t('models', 'sprStatuses_attr_name_en'),
            'price' => Yii::t('models', 'sprStatuses_attr_price'),
            'max_coin_buy_summ' => Yii::t('models', 'sprStatuses_attr_max_coin_buy_summ'),
            'max_levels' => Yii::t('models', 'sprStatuses_attr_max_levels'),
            'out_count' => Yii::t('models', 'sprStatuses_attr_out_count'),
            'out_count_period' => Yii::t('models', 'sprStatuses_attr_out_count_period'),
            'out_max_summ' => Yii::t('models', 'sprStatuses_attr_out_max_summ'),
	];
    }

    public function search()
    {
	$criteria=new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('name_ru', $this->name_ru, true);
	$criteria->compare('name_en', $this->name_en, true);
	$criteria->compare('price', $this->price);
	$criteria->compare('max_coin_buy_summ', $this->max_coin_buy_summ);
	$criteria->compare('max_levels', $this->max_levels);
	$criteria->compare('out_count', $this->out_count);
	$criteria->compare('out_count_period', $this->out_count_period);
	$criteria->compare('out_max_summ', $this->out_max_summ);

	return new CActiveDataProvider($this, [
		'criteria' => $criteria,
	]);
    }

    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
        
    public function rowExpression()
    {
        if($this->id == Yii::app()->user->model()->status_account) {
            return 'text-primary font-weight-bold';
        }
    }
    
    public static function getOutPeriodType($code = false, $beauty = false)
    {
        $_items = [
            self::PERWEEK => Yii::t('models', !$beauty ? 'sprStatuses_attr_out_count_period_week' : 'sprStatuses_attr_out_count_period_week_beauty'),
            self::PERDAY => Yii::t('models', !$beauty ? 'sprStatuses_attr_out_count_period_day' : 'sprStatuses_attr_out_count_period_day_beauty'),
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
    }
    
    public static function getListForUpgrade()
    {
        $userData = Yii::app()->user->model();
        $mainStatusPrice = self::model()->findByPk($userData->status_account)->price;
        $model = self::model()->findAll(['condition' => 'id > :main_id', 'params' => [':main_id' => $userData->status_account]]);
        if($model){
            foreach($model as $status)
                $res[$status->id] = Yii::t('models', 'Status_attr_status_id_summToUpgrade', ['#name' => $status->{'name_'.Yii::app()->language}, '#price' => $status->price - $mainStatusPrice]);
        }
        
        return $res;
    }
    
    public static function getListForUpgradeAdmin($user_id)
    {
        $model = self::model()->findAll(['condition' => 'id != :main_status', 'params' => [':main_status' => Users::model()->findByPk($user_id)->status_account]]);
        if($model){
            foreach($model as $status)
                $res[$status->id] = $status->{'name_'.Yii::app()->language};
        }
        
        return $res;
    }
    
    public static function getListStatuses()
    {
        $model = self::model()->findAll();
        foreach($model as $status)
            $res[$status->id] = $status->{'name_'.Yii::app()->language};
            
        return $res;
    }
}
