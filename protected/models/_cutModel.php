<?php
class CutModel {
/* CoinsMarket */
public function onBeforeValidate($event) {
        $coins = Coins::model()->findByPk(1);
        $mainCoins = self::model()->order_id_desc()->findByAttributes(['user_id' => $this->user_id])->countAll;
        $coinsOrder = CoinsOrder::model()->findAllByAttributes(['operation_status' => 0]);
        
        $this->operation_number = MHelper::getOperationNumber('CoinsMarket', 'C');
        $this->operation_date = date('Y-m-d H:i:s');
        $this->price_perOne = $coins->price;
        $this->operation_type = $this->scenario == 'buyCoins' ? self::TYPE_BUY : self::TYPE_SELL;
        
        if($this->operation_summ && $this->scenario == 'buyCoins') {
            $count_to_system = Yii::app()->settings->get('system', 'cp_percent_to_system');
        
            if($coinsOrder) {
                foreach($coinsOrder as $order){
                    $orderToWork[$order->id] = $order->count_now;
                }
            }
            
            if($orderToWork)
                foreach($orderToWork as $key => $val)
                    $this->arSumm += $val;
        
            $this->count = ceil(($this->operation_summ/$this->price_perOne) - (($this->operation_summ/$this->price_perOne)*$count_to_system)/100);
            $this->countOnPay = ceil(($this->operation_summ/$this->price_perOne));
            $this->countToSystem = ceil((($this->operation_summ/$this->price_perOne)*$count_to_system)/100);
            $this->countAll = $mainCoins + $this->count;
            
            if($this->arSumm  == 0)
                if($this->countOnPay > $coins->nowLimit)
                    $this->addError('operation_summ', Yii::t('models', 'CoinsMarket_attr_count_error_bigCount'));
            #if($this->countOnPay > $coins->nowLimit)
            #    $this->addError('operation_summ', Yii::t('models', 'CoinsMarket_attr_count_error_big'));
        }
        
        parent::onBeforeValidate($event);
    }
    
    public function afterSave() {
        UsersBalance::model()->formRecord($this, $this->scenario == 'buyCoins' ? UsersBalance::TYPE_BUYCOIN : UsersBalance::TYPE_SALECOIN);
        $uInfo = Users::model()->findByPk($this->user_id);
        $uInfo->now_coins = self::model()->order_id_desc()->findByAttributes(['user_id' => $this->user_id])->countAll;
        $uInfo->save(false);
        
        if($this->scenario == 'buyCoins'){
            $coins = Coins::model()->findByPk(1);
            if($this->arSumm == 0 && $coins->nowLimit > 0){
                $coins->nowLimit -= $this->countOnPay;
                $coins->save();
            } else {
                $coinsOrder = CoinsOrder::model()->findAll(['condition' => 'operation_status='.CoinsOrder::OSTAT_WAIT.' AND user_id !='.Yii::app()->user->id]);
                if($coinsOrder) {
                    foreach($coinsOrder as $key => $val){
                        $orderToWork[$key] = ['count_now' => $val->count_now, 'id' => $val->id];
                        $orderToSave[$val->id] = ['count_now' => $val->count_now, 'user_id' => $val->user_id];
                    }
           
                    $summCount = $this->countOnPay;
                    $summCountTotal = $this->countOnPay;
                    $i = 0;
                
                    while($summCount > 0){
                        $closeOrder[$orderToWork[$i]['id']] = $summCount - $orderToWork[$i]['count_now'];
                        $summCount -= $orderToWork[$i]['count_now'];
                        if($orderToWork[$i+1])
                            $i++;
                        else break;
                    }
            
                    $notEnough = 0;
                    foreach($closeOrder as $key => $val){
                        if($val >= 0){
                            CoinsOrder::model()->updateByPk($key, ['count_now' => 0, 'operation_status' => CoinsOrder::OSTAT_COMPL]);
                            $notEnough = $val;
                            $changeSumm = $orderToSave[$key]['count_now'] - $val;
                        } else {
                            CoinsOrder::model()->updateByPk($key, ['count_now' => ($notEnough ? $orderToSave[$key]['count_now'] - $notEnough : abs($val))]);
                            $changeSumm = ($notEnough ? $orderToSave[$key]['count_now'] - $notEnough : $summCountTotal);
                        }
                        $mainCoins = self::model()->order_id_desc()->findByAttributes(['user_id' => $orderToSave[$key]['user_id']])->countAll;
                        $model = self::model();
                        $model->isNewRecord = true;
                        $model->setScenario('sellCoins');
                        $model->user_id = $orderToSave[$key]['user_id'];
                        $model->operation_number = MHelper::getOperationNumber('CoinsMarket', 'C');
                        $model->operation_date = date('Y-m-d H:i:s');
                        $model->price_perOne = $this->price_perOne;
                        $model->count = $changeSumm;
                        $model->countToSystem = 0;
                        $model->countAll = ($mainCoins - $changeSumm);
                        $model->operation_summ = ($summCountTotal * $model->price_perOne);
                        $model->operation_type = self::TYPE_SELL;
                        $model->to_user = Yii::app()->user->id;
                        $model->save(false);
                    }
                }
            }
            
            
            $mainRefs = UsersRelation::model()->findAllByAttributes(['to_user' => $this->user_id]);
            $levels = SprLevels::model()->findAll();
            foreach($levels as $level)
                $countToLevel[$level->id] = $level->level_percente;
            
            $coinNums = CoinsMarket::model()->findAll(['select' => 'operation_number']);
            foreach($coinNums as $number)
                $cNum[] = $number->operation_number;
            
            $coinsBalances = CoinsMarket::model()->findAll(['select' => '*', 'condition' => 'id IN (SELECT MAX(id) FROM {{coins_market}} GROUP BY user_id)']);
            foreach($coinsBalances as $cBalance)
                $cBal[$cBalance->user_id] = $cBalance->countAll;
            
            $count = $this->countOnPay;
            $countBack = $this->countToSystem;
            if($mainRefs){
                foreach($mainRefs as $ref){
                    $coinSumm = ceil(($count*$countToLevel[$ref->level])/100);
                    $countBack -= $coinSumm;
                    
                    if(!in_array($cnID = 'CP'.rand(000000000,999999999), $cNum))
                        $cNum[] = $cnID;
                    
                    $query[] = [
                        'operation_number' => $cnID,
                        'operation_date' => date('Y-m-d H:i:s'),
                        'operation_type' => self::TYPE_PROF,
                        'user_id' => $ref->user_id,
                        'from_count' => $this->countOnPay,
                        'from_user' => $this->user_id,
                        'from_level' => $ref->level,
                        'count' => $coinSumm,
                        'countToSystem' => 0,
                        'countAll' => $cBal[$ref->user_id]+$coinSumm,
                        'operation_summ' => 0,
                        'price_perOne' => $coins->price
                    ];
                    
                    $uInfo = Users::model()->findByPk($ref->user_id);
                    $uInfo->now_coins = $cBal[$ref->user_id]+$coinSumm;
                    $uInfo->save(false);
                }
            }
            
            if($countBack > 0){
                if(!in_array($cnID = 'CP'.rand(000000000,999999999), $cNum))
                    $cNum[] = $cnID;
                
                $query[] = [
                    'operation_number' => $cnID,
                    'operation_date' => date('Y-m-d H:i:s'),
                    'operation_type' => self::TYPE_PROF,
                    'user_id' => 3,
                    'from_count' => $this->countOnPay,
                    'from_user' => $this->user_id,
                    'count' => $countBack,
                    'countToSystem' => 0,
                    'countAll' => $cBal[3] ? $cBal[3]+$countBack : $countBack,
                    'operation_summ' => 0,
                    'price_perOne' => $coins->price
                ];
                
                $uInfo = Users::model()->findByPk(3);
                $uInfo->now_coins = $cBal[3] ? $cBal[3]+$countBack : $countBack;
                $uInfo->save(false);
            }
            
            if($query){
                $builder = Yii::app()->db->schema->commandBuilder;
                $command = $builder->createMultipleInsertCommand('{{coins_market}}', $query);
                $command->execute();
            }
            
            $usersBalances = UsersBalance::model()->findAll(['select' => 'operation_number']);
            foreach($usersBalances as $key)
                $balance_operations[] = $key->operation_number;
            
            $allUserBalances = UsersBalance::model()->findAll(['select' => '*', 'condition' => 'id IN (SELECT MAX(id) FROM {{users_balance}} GROUP BY user_id)']);
            foreach($allUserBalances as $uBalance)
                $uBal[$uBalance->user_id] = $uBalance->operation_summAll;
            
            
            $coinsProfits = CoinsMarket::model()->findAll(['condition' => 'operation_number LIKE :number AND operation_date BETWEEN :date and :date1', 'params' => [':number' => 'CP%', ':date' => date('Y-m-d H:i:s', strtotime('-1 hours')), ':date1' => date('Y-m-d H:i:s')]]);
            foreach($coinsProfits as $key){
                if(!in_array($key->operation_number, $balance_operations)){
                    $queryBalance[]=[
                        'user_id' => $key->user_id,
                        'operation_number' => $key->operation_number,
                        'operation_date' => $key->operation_date,
                        'operation_summ' => 0,
                        'operation_summAll' => $uBal[$key->user_id],
                        'operation_type' => UsersBalance::TYPE_PROFITCOIN,
                        'operation_system' => MBaseModel::FIN_INNER,
                        'coinmarket_id' => $key->id,
                    ];
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
    
    public function checkOperationSumm()
    {
        if($this->operation_summ) {
            $status = SprStatuses::model()->findByPk(Yii::app()->user->model()->status_account);
            $buy = self::model()->findAllByAttributes(['user_id' => $this->user_id, 'operation_type' => self::TYPE_BUY]);
            
            if($buy){
                foreach($buy as $coinSumm)
                    $summ += $coinSumm->operation_summ;
                
                if($summ >= $status->max_coin_buy_summ)
                    $this->addError ('operation_summ', Yii::t('models', 'CoinsMarket_attr_operation_summ_error_limit'));
            }
            
            if($this->operation_summ > $status->max_coin_buy_summ)
                $this->addError ('operation_summ', Yii::t('models', 'CoinsMarket_attr_operation_summ_error_big'));
            
            if($this->operation_summ > Yii::app()->user->finance->balance)
                $this->addError ('operation_summ', Yii::t('models', 'CoinsMarket_attr_operation_summ_error_balance'));
        }
    }
}    
    
    
    
    
    
    
    
    
    