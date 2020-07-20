<?php

class UsersStatus extends CActiveRecord
{
    public $levels;
    public $setStatusByAdmin = false;
    
    public function tableName()
    {
    	return '{{users_status}}';
    }

    public function rules()
    {
	return [
            ['user_id, status_id, operation_number, operation_summ, operation_date', 'required'],
            ['user_id, status_id, operation_summ', 'numerical', 'integerOnly' => true],
            ['id, user_id, status_id, operation_number, operation_summ, operation_date', 'safe', 'on' => 'search'],
	];
    }

    public function relations()
    {
    	return [
            'usersBalances' => [self::HAS_MANY, 'UsersBalance', 'buystatus_id'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => Yii::t('models', 'attr_id'),
            'user_id' => Yii::t('models', 'attr_user_id'),
            'status_id' => Yii::t('models', 'attr_status'),
            'operation_number' => Yii::t('models', 'attr_operation_number'),
            'operation_summ' => Yii::t('models', 'attr_summ'),
            'operation_date' => Yii::t('models', 'attr_date'),
	];
    }
    
    public function scopes()
    {
        return [
            'order_id_desc' => ['order' => 'id DESC'],
            'clear_count' => ['condition' => 'visible = 1']
        ];
    }
    
    public function search()
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('user_id', $this->user_id);
	$criteria->compare('status_id', $this->status_id);
	$criteria->compare('operation_number', $this->operation_number, true);
        $criteria->compare('operation_summ', $this->operation_summ);
	$criteria->compare('operation_date', $this->operation_date, true);

	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
    
    public function onBeforeValidate($event) {
        $this->operation_date = date('Y-m-d H:i:s');
        
        if($this->scenario != 'SetVirtualStatus') {
            $this->operation_number = MHelper::getOperationNumber('UsersStatus', 'S');
            $this->user_id = Yii::app()->user->id;
        }
        
        if($this->status_id && $this->scenario != 'SetVirtualStatus'){
            $userData = Yii::app()->user->model();
            $statuses = SprStatuses::model()->findAll();
            foreach($statuses as $stat)
                $rStat[$stat->id] = ['price' => $stat->price, 'levels' => $stat->max_levels];
            $finance = Yii::app()->user->finance;
            $userBalance = number_format($finance->balance - $finance->outs_freeze - $finance->buy_freeze,2,".","");
            $needSumm = $rStat[$this->status_id]['price'] - $rStat[$userData->status_account]['price'];
            
            if($userBalance < $needSumm || $userBalance < 0)
                $this->addError('status_id', Yii::t('models', 'Status_attr_status_id_error_money').$userBalance);
            
            $this->operation_summ = $needSumm;
            $this->levels = $rStat[$this->status_id]['levels'];
        }
        
        parent::onBeforeValidate($event);
    }

    public function afterSave()
    {
        Users::model()->updateByPk($this->user_id, ['status_account' => $this->status_id, 'referral_level' => $this->levels]);
        if($this->scenario != 'SetVirtualStatus') {
            UsersBalance::model()->formRecord($this, UsersBalance::TYPE_BUYSTATUS);
        }
        
        if($this->scenario != 'SetVirtualStatus' && $this->operation_summ > 0) {
            $mainRefs = UsersRelation::model()->with(['users_to'])->findAllByAttributes(['to_user' => Yii::app()->user->id]);
            $levels = SprLevels::model()->findAll();
            foreach($levels as $level)
                $countToLevel[$level->id] = $level->level_percente_status;
            
            $statusNums = UsersStatus::model()->findAll(['select' => 'operation_number']);
            foreach($statusNums as $number)
                $sNum[] = $number->operation_number;
            
            $summ = $this->operation_summ;
            $summBack = $this->operation_summ/2;
            if($mainRefs){
                foreach($mainRefs as $ref){
                    if($countToLevel[$ref->level] && $countToLevel[$ref->level] != 0) {
                        if($ref->level <= $ref->users_to->referral_level) {
                            $summProfit = floor(($summ*$countToLevel[$ref->level])/100);
                            $summBack -= $summProfit;
                        
                            if(!in_array($snID = 'SP'.rand(000000000,999999999), $sNum))
                                $sNum[] = $snID;
                    
                            if($summBack > 0 && $summProfit != 0) {
                                $query[] = [
                                    'operation_number' => $snID,
                                    'operation_date' => date('Y-m-d H:i:s'),
                                    'operation_type' => UsersProfits::TYPE_BUYSTAT,
                                    'operation_percent' => $countToLevel[$ref->level],
                                    'user_id' => $ref->user_id,
                                    'from_user' => Yii::app()->user->id,
                                    'from_summ' => $this->operation_summ,
                                    'from_level' => $ref->level,
                                    'operation_summ' => ($summProfit > $summBack) ? $summBack : $summProfit,
                                ];
                            } else {
                                $summBackQuant = $summProfit - abs($summBack);
                                $query[] = [
                                    'operation_number' => $snID,
                                    'operation_date' => date('Y-m-d H:i:s'),
                                    'operation_type' => UsersProfits::TYPE_BUYSTAT,
                                    'operation_percent' => $countToLevel[$ref->level],
                                    'user_id' => $ref->user_id,
                                    'from_user' => Yii::app()->user->id,
                                    'from_summ' => $this->operation_summ,
                                    'from_level' => $ref->level,
                                    'operation_summ' => $summBackQuant,
                                ];
                                break;
                            }
                        }
                    }
                }
            }
            
            if($summBack > 0){
                if(!in_array($snID = 'SP'.rand(000000000,999999999), $sNum))
                    $sNum[] = $snID;
                
                $query[] = [
                    'operation_number' => $snID,
                    'operation_date' => date('Y-m-d H:i:s'),
                    'operation_type' => UsersProfits::TYPE_BUYSTAT,
                    'user_id' => 3,
                    'from_user' => Yii::app()->user->id,
                    'from_summ' => $this->operation_summ,
                    'operation_summ' => $summBack,
                ];
            }
            
            if($query){
                $builder = Yii::app()->db->schema->commandBuilder;
                $command = $builder->createMultipleInsertCommand('{{users_profits}}', $query);
                $command->execute();
            }
            
            $usersBalances = UsersBalance::model()->findAll(['select' => 'operation_number']);
            foreach($usersBalances as $key)
                $balance_operations[] = $key->operation_number;
            
            $allUserBalances = UsersBalance::model()->findAll(['select' => '*', 'condition' => 'id IN (SELECT MAX(id) FROM {{users_balance}} GROUP BY user_id)']);
            foreach($allUserBalances as $uBalance)
                $uBal[$uBalance->user_id] = $uBalance->operation_summAll;
            
            
            $statusProfits = UsersProfits::model()->findAll(['condition' => 'operation_number LIKE :number AND operation_date BETWEEN :date and :date1', 'params' => [':number' => 'SP%', ':date' => date('Y-m-d H:i:s', strtotime('-1 hours')), ':date1' => date('Y-m-d H:i:s')]]);
            foreach($statusProfits as $key){
                if(!in_array($key->operation_number, $balance_operations)){
                    $queryBalance[]=[
                        'user_id' => $key->user_id,
                        'operation_number' => $key->operation_number,
                        'operation_date' => $key->operation_date,
                        'operation_summ' => $key->operation_summ,
                        'operation_summAll' => ($uBal[$key->user_id]+$key->operation_summ),
                        'operation_type' => UsersBalance::TYPE_PROFITSTAT,
                        'operation_system' => MBaseModel::FIN_INNER,
                        'profit_id' => $key->id,
                    ];
                    
                    Users::model()->updateByPk($key->user_id, ['now_balance' => ($uBal[$key->user_id]+$key->operation_summ)]);
                }
            }
            
            if($queryBalance){
                $builderBalance = Yii::app()->db->schema->commandBuilder;
                $commandBalance = $builderBalance->createMultipleInsertCommand('{{users_balance}}', $queryBalance);
                $commandBalance->execute();
            }
        }
        
        parent::afterSave();
    }
}
