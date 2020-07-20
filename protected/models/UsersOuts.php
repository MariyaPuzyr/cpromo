<?php

class UsersOuts extends MBaseModel
{
    const OSTATUS_WAIT = 0;
    const OSTATUS_COMPL = 1;
    const OSTATUS_CANC = 2;
    const OSTATUS_DISAGREE = 3;
    const OSTATUS_WCONFIRM = 4;
    
    const OUT_PROCENT = [self::FIN_PAYEER => 2, self::FIN_PRFMONEY => 2, self::FIN_COINSPAY => 2, self::FIN_CARD => 5];
    
    public function tableName()
    {
    	return '{{users_outs}}';
    }

    public function rules()
    {
	$settings = Yii::app()->settings->get('system');
        
        return [
            ['user_id, user_ip, operation_date, operation_number, operation_system, operation_summ, operation_allSumm, operation_maxSumm', 'required'],
            ['user_id, operation_system, operation_status, update_uid', 'numerical', 'integerOnly' => true],
            ['user_ip', 'length', 'max' => 50],
            ['operation_number', 'length', 'max' => 11],
            ['operation_summ, operation_maxSumm, operation_allSumm', 'length', 'max' => 20],
            ['update_at', 'safe'],
            ['operation_allSumm', 'numerical', 'min' => $settings['out_summ_use'] ? $settings['out_summ_min'] : 10],
            ['operation_status', 'in', 'range' => [self::OSTATUS_WAIT, self::OSTATUS_COMPL, self::OSTATUS_CANC, self::OSTATUS_DISAGREE, self::OSTATUS_WCONFIRM]],
            ['operation_summ', 'checkOperationSumm'],
            ['operation_allSumm, operation_procentFreeze', 'numerical'],
            ['id, user_id, user_ip, operation_date, operation_number, operation_system, operation_summ, operation_maxSumm, operation_status, update_at, update_uid, operation_allSumm, operation_procentFreeze', 'safe', 'on'=>'search'],
	];
    }

    public function relations()
    {
	return [
            'update_user' => [self::BELONGS_TO, 'Users', 'update_uid'],
            'user' => [self::BELONGS_TO, 'Users', 'user_id'],
	];
    }
	
    public function attributeLabels()
    {
	return [
            'id' => Yii::t('models', 'attr_id'),
            'user_id' => Yii::t('models', 'attr_user_id'),
            'user_ip' => Yii::t('models', 'attr_user_ip'),
            'operation_date' => Yii::t('models', 'attr_date'),
            'operation_number' => Yii::t('models', 'attr_operation_number'),
            'operation_system' => Yii::t('models', 'attr_system'),
            'operation_summ' => Yii::t('models', 'attr_summ'),
            'operation_maxSumm' => Yii::t('models', 'Outs_attr_operation_maxSumm'),
            'operation_allSumm' => Yii::t('models', 'Outs_attr_operation_allSumm'),
            'operation_procentFreeze' => Yii::t('models', 'Outs_attr_operation_procentFreeze'),
            'operation_status' => Yii::t('models', 'attr_status'),
            'update_at' => 'Update At',
            'update_uid' => 'Update Uid',
	];
    }

    public function scopes()
    {
        return [
            'order_id_desc' => ['order' => 'id DESC'],
            'order_id_desc_find' => ['order' => 't.id DESC'],
            'complete' => ['condition' => 'operation_status = '.self::OSTATUS_COMPL],
            'complete_find' => ['condition' => 'outs.operation_status = '.self::OSTATUS_COMPL],
        ];
    }
    
    public function search($size = false)
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('user_id', $this->user_id);
	$criteria->compare('user_ip', $this->user_ip, true);
	$criteria->compare('operation_date', $this->operation_date, true);
	$criteria->compare('operation_number', $this->operation_number, true);
	$criteria->compare('operation_system', $this->operation_system);
	$criteria->compare('operation_summ', $this->operation_summ, true);
	$criteria->compare('operation_maxSumm', $this->operation_maxSumm, true);
        $criteria->compare('operation_allSumm', $this->operation_allSumm, true);
        $criteria->compare('operation_procentFreeze', $this->operation_procentFreeze, true);
	$criteria->compare('operation_status', $this->operation_status);
	$criteria->compare('update_at', $this->update_at, true);
	$criteria->compare('update_uid', $this->update_uid);

	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => $size ? $size : 25
            ]
	]);
    }
    
    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
        
    public static function getOutStatuses($code = null)
    {
        $_items = [
            self::OSTATUS_WAIT => Yii::t('models', 'Outs_attr_operation_status_wait'),
            self::OSTATUS_COMPL => Yii::t('models', 'Outs_attr_operation_status_compl'),
            self::OSTATUS_CANC => Yii::t('models', 'Outs_attr_operation_status_canc'),
            self::OSTATUS_DISAGREE => Yii::t('models', 'Outs_attr_operation_status_disagree'),
            self::OSTATUS_WCONFIRM => Yii::t('models', 'Outs_attr_operation_status_wconfirm'),
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
    
    public static function getOutStatusesToGrid($code)
    {
        $classes[] = 'badge py-1';
        $_items = [
            self::OSTATUS_WAIT => 'badge-warning',
            self::OSTATUS_COMPL => 'badge-success',
            self::OSTATUS_CANC => 'badge-info',
            self::OSTATUS_DISAGREE => 'badge-danger',
            self::OSTATUS_WCONFIRM => 'badge-danger'
        ];
        
        if(isset($code)) {
            array_push($classes, $_items[$code]);
            return '<span class="'.implode(' ', $classes).'">'.self::getOutStatuses($code).'</span>';
        } else
            return false;
    }
    
    public function onBeforeValidate($event) {
        if($this->isNewRecord) {
            $this->user_id = Yii::app()->user->id;
            $this->user_ip = Yii::app()->request->getUserHostAddress();
            $this->operation_date = date('Y-m-d H:i:s');
            $this->operation_status = self::OSTATUS_WCONFIRM;
            $this->operation_maxSumm = Yii::app()->user->finance->balance;
            $this->operation_number = MHelper::getOperationNumber('UsersOuts', 'O');
            
            if($this->operation_summ) {
                $this->operation_allSumm = $this->operation_summ;
                
                if($this->operation_system || $this->operation_system == 0) {
                    $this->operation_procentFreeze = number_format(($this->operation_summ*self::OUT_PROCENT[$this->operation_system])/100, 2);
                    $this->operation_summ = $this->operation_summ - $this->operation_procentFreeze;
                }
            }
        } else {
            $this->update_at = date('Y-m-d H:i:s');
            $this->update_uid = Yii::app()->user->id;
        }
        
        parent::onBeforeValidate($event);
    }
    
    public function checkOperationSumm()
    {
        $settings = Yii::app()->settings->get('system');
        $finance = Yii::app()->user->finance;
        if($settings['out_summ_use']) {
            if($this->operation_allSumm < $settings['out_summ_min'])
                $this->addError('operation_summ', Yii::t('models', 'Outs_attr_order_summ_error_min', ['#summ' => $settings['out_summ_min']]));
        
            if($this->operation_allSumm % $settings['out_summ_crat'] != 0)
                $this->addError('operation_summ', Yii::t('models', 'Outs_attr_order_summ_error_crat', ['#summ' => $settings['out_summ_crat']]));    
        }
        
        if($this->operation_summ) {
            if($this->operation_allSumm > ($finance->balance - $finance->outs_freeze))
                $this->addError('operation_summ', Yii::t('models', 'Outs_attr_order_summ_error_big'));  
        }
        
        $status = SprStatuses::model()->findByPk(Yii::app()->user->model()->status_account);
        $criteria = new CDbCriteria();
        $criteria->compare('user_id', $this->user_id);
        $criteria->addNotInCondition('operation_status', [self::OSTATUS_CANC, self::OSTATUS_DISAGREE]);
        if($status->out_count_period == SprStatuses::PERWEEK)
            $criteria->addCondition('operation_date BETWEEN "'.date('Y-m-d H:i:s', strtotime('-7 day')).'" and "'.date('Y-m-d H:i:s').'"', 'AND');
        else
            $criteria->addCondition('DATE(operation_date) = "'.date('Y-m-d').'"', 'AND');
        
        $outs = self::model()->findAll($criteria);
        if($outs) {
            foreach ($outs as $out) {
                $allOutSumm += $out->operation_allSumm;
            }
            
            $allOutSumm += $this->operation_allSumm;
            if($allOutSumm > $status->out_max_summ)
                $this->addError('operation_summ', Yii::t('models', 'Outs_attr_order_summ_error_bigStatus'));
            
            if(count($outs) >= $status->out_count)
                $this->addError('operation_summ', Yii::t('models', 'Outs_attr_order_summ_error_period'));  
            else
                if($this->operation_allSumm > $status->out_max_summ)
                    $this->addError('operation_summ', Yii::t('models', 'Outs_attr_order_summ_error_bigStatus'));
        } else {
            if($this->operation_allSumm > $status->out_max_summ)
                $this->addError('operation_summ', Yii::t('models', 'Outs_attr_order_summ_error_bigStatus'));
        }
    }
    
    public function afterSave() {
        if((!$this->isNewRecord || $this->scenario == 'addVirtualOut') && !in_array($this->operation_status, [self::OSTATUS_WAIT, self::OSTATUS_DISAGREE, self::OSTATUS_CANC]))
            UsersBalance::model()->formRecord($this, UsersBalance::TYPE_OUT);
        
        parent::afterSave();
    }
}
