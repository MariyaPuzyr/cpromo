<?php

class Settings extends MBaseModel
{
    public function tableName()
    {
    	return '{{settings}}';
    }

    public function rules()
    {
	return [
            ['enter_summ_use, enter_summ_min, enter_summ_crat, out_summ_use, out_summ_min, out_summ_max, out_summ_crat, procent_profit, deposit_pay_freeze_period, deposit_procent_freeze_period, enableLockRegister, cp_percent_to_system, coinpays_min_summ', 'required'],
            ['id, enter_summ_use, enter_summ_min, enter_summ_crat, out_summ_use, out_summ_min, out_summ_max, out_summ_crat, procent_profit, deposit_pay_freeze_period, deposit_procent_freeze_period, enableLockRegister, cp_percent_to_system, coinpays_min_summ, offSite', 'numerical', 'integerOnly' => true],
            ['id, enter_summ_use, enter_summ_min, enter_summ_crat, out_summ_use, out_summ_min, out_summ_max, out_summ_crat, procent_profit, deposit_pay_freeze_period, deposit_procent_freeze_period, enableLockRegister, cp_percent_to_system, coinpays_min_summ, offSite', 'safe', 'on' => 'search'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => Yii::t('models', 'attr_id'),
            'enter_summ_use' => Yii::t('models', 'settings_attr_enter_summ_use'),
            'enter_summ_min' => Yii::t('models', 'settings_attr_enter_summ_min'),
            'enter_summ_crat' => Yii::t('models', 'settings_attr_enter_summ_crat'),
            'out_summ_use' => Yii::t('models', 'settings_attr_out_summ_use'),
            'out_summ_min' => Yii::t('models', 'settings_attr_out_summ_min'),
            'out_summ_max' => Yii::t('models', 'settings_attr_out_summ_max'),
            'out_summ_crat' => Yii::t('models', 'settings_attr_out_summ_crat'),
            'procent_profit' => Yii::t('models', 'settings_attr_procent_profit'),
            'deposit_pay_freeze_period' => Yii::t('models', 'settings_attr_deposit_pay_freeze_period'),
            'deposit_procent_freeze_period' => Yii::t('models', 'settings_attr_deposit_procent_freeze_period'),
            'enableLockRegister' => Yii::t('models', 'settings_attr_enableLockRegister'),
            'cp_percent_to_system' => Yii::t('models', 'settings_attr_cp_percent_to_system'),
            'coinpays_min_summ' => Yii::t('models', 'settings_attr_coinpays_min_summ'),
	];
    }

    public function search() 
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('enter_summ_use', $this->enter_summ_use);
        $criteria->compare('enter_summ_min', $this->enter_summ_min);
        $criteria->compare('enter_summ_crat', $this->enter_summ_crat);
	$criteria->compare('out_summ_use', $this->out_summ_use);
        $criteria->compare('out_summ_min', $this->out_summ_min);
        $criteria->compare('out_summ_max', $this->out_summ_max);
        $criteria->compare('out_summ_crat', $this->out_summ_crat);
        $criteria->compare('procent_profit', $this->procent_profit);
        $criteria->compare('summ_freeze_period', $this->summ_freeze_period);
        $criteria->compare('procent_freeze_Period', $this->procent_freeze_Period);
        $criteria->compare('enableLockRegister', $this->enableLockRegister);
        $criteria->compare('lockSumm', $this->lockSumm);
        $criteria->compare('offSite', $this->offSite);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
	]);
    }
    
    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
}
