<?php

class UsersPays extends MBaseModel
{
    const PSTATUS_WAIT = 0;
    const PSTATUS_COMPL = 1;
    const PSTATUS_CANC = 2;
    const PSTATUS_ERROR = 3;
    
    public function tableName()
    {
    	return '{{users_pays}}';
    }

    public function rules()
    {
	$settings = Yii::app()->settings->get('system');
        
        return [
            ['user_id, user_ip, operation_number, operation_date, operation_summ', 'required'],
            ['user_id, operation_system, operation_status', 'numerical', 'integerOnly' => true],
            ['operation_summConvert', 'numerical'],
            ['user_ip', 'length', 'max' => 50],
            ['operation_number', 'length', 'max' => 11],
            ['operation_summ', 'length', 'max' => 10],
            ['operation_number', 'unique'],
            ['operation_summ', 'checkOperationSumm'],
            ['operation_summ', 'numerical', 'min' => $settings['enter_summ_use'] ? $settings['enter_summ_min'] : 1],
            ['operation_status', 'in', 'range' => [self::PSTATUS_WAIT, self::PSTATUS_COMPL, self::PSTATUS_CANC, self::PSTATUS_ERROR]],
            ['id, user_id, user_ip, operation_number, operation_system, operation_date, operation_summ, operation_summConvert, operation_status', 'safe', 'on' => 'search'],
	];
    }

    public function relations()
    {
	return [
            'user' => [self::BELONGS_TO, 'Users', 'user_id'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => Yii::t('models', 'attr_id'),
            'user_id' => Yii::t('models', 'attr_user_id'),
            'user_ip' => Yii::t('models', 'attr_user_ip'),
            'operation_number' => Yii::t('models', 'attr_operation_number'),
            'operation_system' => Yii::t('models', 'attr_system'),
            'operation_date' => Yii::t('models', 'attr_date'),
            'operation_summ' => Yii::t('models', 'attr_summ'),
            'operation_summConvert' => Yii::t('models', 'Pays_attr_operation_summConvert'),
            'operation_status' => Yii::t('models', 'attr_status'),
	];
    }
    
    public function scopes()
    {
        return [
            'approved' => ['condition' => 'operation_status='.self::PSTATUS_COMPL],
            'approved_onlyRelation' => [
                'condition' => 'operation_status='.self::PSTATUS_COMPL,
                'select' => 'operation_summ'
            ],
            'approved_main' => ['condition' => 'main_pays.operation_status='.self::PSTATUS_COMPL],
            'approved_referral' => ['condition' => 'referral_pay.operation_status='.self::PSTATUS_COMPL],
            'order_date' => ['order' => 'DATE(operation_date) DESC'],
            'notCompleted' => ['condition' => 'operation_status='.self::PSTATUS_WAIT],
            'order_id_desc' => ['order' => 'id DESC'],
            'order_id_desc_find' => ['order' => 't.id DESC'],
            'show_vis' => ['condition' => 'record_status='.self::RSTAT_VIS],
            'show_invis' => ['condition' => 'record_status='.self::RSTAT_INVIS],
            'clear_count' => ['condition' => 'visible = 1']
        ];
    }
        
    public function search($size = false)
    {
    	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('user_id', $this->user_id);
	$criteria->compare('user_ip', $this->user_ip, true);
	$criteria->compare('operation_number', $this->operation_number, true);
	$criteria->compare('operation_system', $this->operation_system);
	$criteria->compare('operation_date', $this->operation_date, true);
	$criteria->compare('operation_summ', $this->operation_summ, true);
	$criteria->compare('operation_summConvert', $this->operation_summConvert);
	$criteria->compare('operation_status', $this->operation_status);

	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => $size ? $size : 10
            ]
	]);
    }
    
    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
    
    public function onBeforeValidate($event) {
        if($this->isNewRecord) {
            $this->user_id = Yii::app()->user->id;
            $this->user_ip = Yii::app()->request->getUserHostAddress();
            $this->operation_date = date('Y-m-d H:i:s');
            $this->operation_status = self::PSTATUS_WAIT;
            $this->operation_number = MHelper::getOperationNumber('UsersPays', 'I');
        }
        
        parent::onBeforeValidate($event);
    }
    
    public function afterSave() {
        if((!$this->isNewRecord || $this->scenario == 'addVirtualPay') && $this->operation_status == self::PSTATUS_COMPL)
            UsersBalance::model()->formRecord($this, UsersBalance::TYPE_INVEST);
        
        parent::afterSave();
    }
    
    public function checkOperationSumm()
    {
        if($this->operation_summ) {
            $settings = Yii::app()->settings->get('system');
            if($settings['enter_summ_use']) {
                if($this->operation_summ % $settings['enter_summ_crat'] != 0)
                    $this->addError('operation_summ', Yii::t('models', 'Pays_attr_operation_summ_error_crat', ['#summ' => $settings['enter_summ_crat']]));    
            
                if($this->operation_summ < $settings['enter_summ_min'])
                    $this->addError('operation_summ', Yii::t('models', 'Pays_attr_operation_summ_error_min', ['#summ' => $settings['enter_summ_min']]));
            }
            
            if($this->operation_system == MBaseModel::FIN_COINSPAY && $this->operation_summ < $settings['coinpays_min_summ'])
                $this->addError('operation_summ', Yii::t('models', 'Pays_attr_operation_summ_error_coinpaymin', ['#summ' => $settings['coinpays_min_summ']]));
        }
    }
    
    public static function getPayStatuses($code = null)
    {
        $_items = [
            self::PSTATUS_WAIT => Yii::t('models', 'Pays_attr_operation_status_wait'),
            self::PSTATUS_COMPL => Yii::t('models', 'Pays_attr_operation_status_compl'),
            self::PSTATUS_CANC => Yii::t('models', 'Pays_attr_operation_status_canc'),
            self::PSTATUS_ERROR => Yii::t('models', 'Pays_attr_operation_status_err'),
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
    
    public static function getPayStatusesToGrid($code)
    {
        $classes[] = 'badge py-1';
        $_items = [
            self::PSTATUS_WAIT => 'badge-warning',
            self::PSTATUS_COMPL => 'badge-success',
            self::PSTATUS_CANC => 'badge-info',
            self::PSTATUS_ERROR => 'badge-danger'
        ];
        
        if(isset($code)) {
            array_push($classes, $_items[$code]);
            return '<span class="'.implode(' ', $classes).'">'.self::getPayStatuses($code).'</span>';
        }
            return false;
    }
    
    public static function changeStatus($operation_id, $status)
    {
        $model = self::model()->findByPk($operation_id);
        if($model->operation_status != self::PSTATUS_COMPL) {
            $model->operation_status = $status;
            $model->save(false);
        }
    }
}
