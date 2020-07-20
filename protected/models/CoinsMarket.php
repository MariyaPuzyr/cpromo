<?php

class CoinsMarket extends MBaseModel
{
    const TYPE_BUY = 0;
    const TYPE_SELL = 1;
    const TYPE_PROF = 2;
    
    public $countOnPay;
    public $arSumm = 0;
    public $from_exchange = false;
    public $count_buy;
    
    public function tableName()
    {
    	return '{{coins_market}}';
    }

    public function rules()
    {
	return [
            ['user_id, count, countToSystem, countAll, operation_number, operation_summ, price_perOne, operation_type, operation_date, coinorder_id', 'required'],
            ['user_id, to_user, from_count, from_user, from_level, count, countToSystem, countAll, operation_type', 'numerical', 'integerOnly' => true],
            ['price_perOne', 'numerical'],
            ['operation_summ', 'numerical'],
            ['operation_summ', 'checkOperationSumm'],
            ['id, user_id, to_user, from_count, from_user, from_level, count, countToSystem, countAll, operation_number, operation_summ, price_perOne, operation_type, operation_date, coinorder_id', 'safe', 'on' => 'search'],
	];
    }

    public function relations()
    {
        return [
            'toUser' => [self::BELONGS_TO, 'Users', 'to_user'],
            'user' => [self::BELONGS_TO, 'Users', 'user_id'],
            'fromUser' => [self::BELONGS_TO, 'Users', 'from_user'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => Yii::t('models', 'attr_id'),
            'user_id' => Yii::t('models', 'attr_user_id'),
            'to_user' => Yii::t('models', 'CoinsMarket_attr_to_user'),
            'from_user' => Yii::t('models', 'CoinsMarket_attr_from_user'),
            'from_level' => Yii::t('models', 'CoinsMarket_attr_from_level'),
            'from_count' => Yii::t('models', 'CoinsMarket_attr_from_count'),
            'count' => Yii::t('models', 'attr_count'),
            'count_buy' => Yii::t('models', 'CoinsMarket_attr_count_buy'),
            'countToSystem' => Yii::t('models', 'CoinsMarket_attr_countToSystem'),
            'countAll' => Yii::t('models', 'CoinsMarket_attr_countAll'),
            'operation_summ' => Yii::t('models', 'attr_summ'),
            'price_perOne' => Yii::t('models', 'CoinsMarket_attr_price_perOne'),
            'operation_type' => Yii::t('models', 'attr_operation'),
            'operation_date' => Yii::t('models', 'attr_date'),
	];
    }
    
    public function scopes()
    {
        return [
            'order_id_desc' => ['order' => 'id DESC'],
            'order_id_desc_find' => ['order' => 't.id DESC'],
            'order_id_desc_relation' => ['order' => 'rCoins.id DESC', 'limit' => 1],
        ];
    }

    public function search($size = null, $operation_type = false, $order = false)
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('user_id', $this->user_id);
	$criteria->compare('to_user', $this->to_user);
	$criteria->compare('count', $this->count);
        $criteria->compare('countToSystem', $this->countToSystem);
        $criteria->compare('countAll', $this->countAll);
	$criteria->compare('operation_summ', $this->operation_summ);
	$criteria->compare('price_perOne', $this->price_perOne);
        
        if (is_array($operation_type)) {
            $criteria->addInCondition('operation_type', $operation_type);
        } else {
            $criteria->compare('operation_type', isset($operation_type) ? $operation_type : $this->operation_type);
        }

        $criteria->compare('operation_date', $this->operation_date, true);
        
        if ($order) {
            $criteria->order = $order;
        }

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
    
    public function onBeforeValidate($event) {
        //Поднимаем сведения по ордеру, с которым будем работать
        $order = CoinsOrder::model()->findByPk($this->coinorder_id);
        
        //Получаем текущее количество монет пользователя
        $mCoins = self::model()->order_id_desc()->findByAttributes(['user_id' => $this->user_id]);
        $mainCoins = $mCoins ? $mCoins->countAll : 0;
        $this->countAll = $this->operation_type == self::TYPE_BUY ? $mainCoins + $this->count : $mainCoins - $this->count;
        
        $this->operation_number = MHelper::getOperationNumber('CoinsMarket', 'C');
        $this->operation_date = date('Y-m-d H:i:s');
        
        if ($this->operation_type == self::TYPE_BUY) {
            //Если пользователь покупает монеты
            $count_to_system = Yii::app()->settings->get('system', 'cp_percent_to_system');
            
            //Подставляе количество монет к покупке по текущему курсу и формируем числовые показатели
            if ($this->count_buy && !$this->from_exchange) { //При формировании заявки
                $this->countToSystem = ceil(($this->count_buy*$count_to_system)/100);
                $this->countOnPay = $this->count_buy;
                $this->count = ceil($this->count_buy - $this->countToSystem);
                $this->operation_summ = $this->countOnPay * $this->price_perOne;
            } elseif($this->from_exchange) { //При схлопывании заявки
                $this->countOnPay = $this->count;
                $this->countToSystem = ceil(($this->count*$count_to_system)/100);
                $this->count = ceil($this->countOnPay - $this->countToSystem);
            } else {
                $this->addError('count_buy', Yii::t('models', 'CoinsMarket_attr_count_error_empty'));
            }
            
            //Проверяем наличие актива в покупаемой заявке
            if ($this->countOnPay > $order->count_now && !$this->from_exchange) {
                $this->addError('count_buy', Yii::t('models', 'CoinsMarket_attr_count_error_order_big'));
            } elseif ($this->count > $order->count_now && $this->from_exchange) {
                $this->addError('count_buy', Yii::t('models', 'CoinsMarket_attr_count_error_order_big').' --- '.$this->count.' ---- '.$order->count_now);
            }
        } elseif ($this->operation_type == self::TYPE_SELL) {
            if (!$this->from_exchange) {
                $this->count = ceil($this->operation_summ * $this->price_perOne);
                $this->countOnPay = $this->count;
                $this->countToSystem = 0;
            } else {
                $this->countOnPay = $this->count;
                $this->countToSystem = 0;
            }
        }
        
        //Поднимаем сведения по ордеру, с которым будем работать
        /*$order = CoinsOrder::model()->findByPk($this->coinorder_id);
       
        //Генерируем обязательные поля: номер операции, дата
        
        
        //Формируем правила валидации
        if ($this->operation_type == self::TYPE_BUY) {
            //Если пользователь покупает монеты
            $count_to_system = Yii::app()->settings->get('system', 'cp_percent_to_system');
            
            //Подставляе количество монет к покупке по текущему курсу и формируем числовые показатели
            if ($this->count_buy && !$this->from_exchange) { //При формировании заявки
                $this->countToSystem = ceil(($this->count_buy*$count_to_system)/100);
                $this->countOnPay = $this->count_buy;
                $this->count = ceil($this->count_buy - $this->countToSystem);
                $this->operation_summ = $this->countOnPay * $this->price_perOne;
            } elseif($this->from_exchange) { //При схлопывании заявки
                $this->countOnPay = $this->count;
                $this->countToSystem = ceil(($this->count*$count_to_system)/100);
            } else {
                $this->addError('count_buy', Yii::t('models', 'CoinsMarket_attr_count_error_empty'));
            }
            
            //Проверяем наличие актива в покупаемой заявке
            if ($this->countOnPay > $order->count_now && !$this->from_exchange) {
                $this->addError('count_buy', Yii::t('models', 'CoinsMarket_attr_count_error_order_big'));
            } elseif ($this->count > $order->count_now && $this->from_exchange) {
                $this->addError('count_buy', Yii::t('models', 'CoinsMarket_attr_count_error_order_big'));
            }
        } elseif ($this->operation_type == self::TYPE_SELL) {
            if (!$this->from_exchange) {
                $this->count = ceil($this->operation_summ * $this->price_perOne);
                $this->countOnPay = $this->count;
                $this->countToSystem = 0;
            } else {
                $this->countOnPay = $this->count;
                $this->countToSystem = 0;
            }
        }*/
        //Получаем общее количество монет для пользователя
        
        
        parent::onBeforeValidate($event);
    }
    
    public function checkOperationSumm()
    {
        /*if($this->operation_summ) {
            $uModel = Users::model()->onlyRelation()->findByPk($this->user_id);
            $finance = Yii::app()->getModule('user')->getBalanceNow($this->user_id);
            $status = SprStatuses::model()->findByPk($uModel->status_account);
            $opers = self::model()->findAll(['condition' => 'user_id='.$this->user_id.' AND operation_type IN ('.CoinsMarket::TYPE_BUY.', '.CoinsMarket::TYPE_SELL.')']);
            $summBuy = 0;
            $summSell = 0;
                
            if($opers){
                foreach($opers as $coinSumm){
                    if($coinSumm->operation_type == self::TYPE_BUY) {
                        $summBuy += $coinSumm->count;
                        $summBuy += $coinSumm->countToSystem;
                    } elseif($coinSumm->operation_type == self::TYPE_SELL)
                        $summSell += $coinSumm->count;
                }
                
                
                if ($this->operation_type == self::TYPE_BUY)
                    if (((($summBuy-$summSell) * $this->price_perOne) + $this->operation_summ) > $status->max_coin_buy_summ)
                        $this->addError('count_buy', Yii::t('models', 'coinsMarket_attr_operation_summ_error_limit'));
            }
            
            if ($this->operation_type == self::TYPE_BUY) {
                if ($this->operation_summ > $status->max_coin_buy_summ)
                    $this->addError('count_buy', Yii::t('models', 'CoinsMarket_attr_operation_summ_error_big'));
                
                if ($this->operation_summ > ($finance->balance - $finance->outs_freeze - $finance->buy_freeze))
                    $this->addError('count_buy', Yii::t('models', 'CoinsMarket_attr_operation_summ_error_balance'));
            }
                
            if($this->operation_type == self::TYPE_SELL) {
                $countCoins = 0;
                $countCoins = self::model()->order_id_desc()->findByAttributes(['user_id' => Yii::app()->user->id])->countAll;
                
                if ($this->count > $countCoins) {
                    $this->addError('operation_summ', Yii::t('models', 'CoinsMarket_attr_count_error_big'));
                }
            }
        }*/
    }
    
    public function afterSave() {
        UsersBalance::model()->formRecord($this, $this->operation_type == self::TYPE_BUY ? UsersBalance::TYPE_BUYCOIN : UsersBalance::TYPE_SALECOIN);
        $uInfo = Users::model()->findByPk($this->user_id);
        $uInfo->now_coins = $this->countAll;
        $uInfo->save(false);
        
        if($this->operation_type == self::TYPE_BUY){
            $mainRefs = UsersRelation::model()->with(['users_to'])->findAllByAttributes(['to_user' => $this->user_id]);
            $levels = SprLevels::model()->findAll();
            foreach($levels as $level)
                $countToLevel[$level->id] = $level->level_percente;
            
            $coinNums = CoinsMarket::model()->findAll(['select' => 'operation_number']);
            foreach($coinNums as $number)
                $cNum[] = $number->operation_number;
            
            $coinsBalances = CoinsMarket::model()->findAll(['select' => '*', 'condition' => 'id IN (SELECT MAX(id) FROM {{coins_market}} GROUP BY user_id)']);
            foreach($coinsBalances as $cBalance)
                $cBal[$cBalance->user_id] = $cBalance->countAll ? $cBalance->countAll : 0;
            
            $count = $this->countOnPay;
            $countBack = $this->countToSystem;
            if($mainRefs){
                foreach($mainRefs as $ref){
                    if(isset($countToLevel[$ref->level]) && $countToLevel[$ref->level] != 0) {
                        if($ref->level <= $ref->users_to->referral_level) {
                            $coinSumm = ceil(($count*$countToLevel[$ref->level])/100);
                            $countBack -= $coinSumm;
                            
                            if(!in_array($cnID = 'CP'.rand(000000000,999999999), $cNum))
                                $cNum[] = $cnID;
                            
                            if($countBack > 0 && $coinSumm > 0) {
                                $query[] = [
                                    'operation_number' => $cnID,
                                    'coinorder_id' => $this->coinorder_id,
                                    'operation_date' => date('Y-m-d H:i:s'),
                                    'operation_type' => self::TYPE_PROF,
                                    'user_id' => $ref->user_id,
                                    'from_count' => $this->countOnPay,
                                    'from_user' => $this->user_id,
                                    'from_level' => $ref->level,
                                    'count' => ($coinSumm > $countBack) ? $countBack : $coinSumm,
                                    'countToSystem' => 0,
                                    'countAll' => isset($cBal[$ref->user_id]) ? $cBal[$ref->user_id] + $coinSumm : $coinSumm,
                                    'operation_summ' => 0,
                                    'price_perOne' => $this->price_perOne
                                ];
                    
                                $uInfo = Users::model()->findByPk($ref->user_id);
                                $uInfo->now_coins = isset($cBal[$ref->user_id]) ? $cBal[$ref->user_id] + $coinSumm : $coinSumm;
                                $uInfo->save(false);
                            } else {
                                $coinBackQuant = $coinSumm - abs($countBack);
                                $query[] = [
                                    'operation_number' => $cnID,
                                    'coinorder_id' => $this->coinorder_id,
                                    'operation_date' => date('Y-m-d H:i:s'),
                                    'operation_type' => self::TYPE_PROF,
                                    'user_id' => $ref->user_id,
                                    'from_count' => $this->countOnPay,
                                    'from_user' => $this->user_id,
                                    'from_level' => $ref->level,
                                    'count' => $coinBackQuant,
                                    'countToSystem' => 0,
                                    'countAll' => $cBal[$ref->user_id]+$coinSumm,
                                    'operation_summ' => 0,
                                    'price_perOne' => $this->price_perOne
                                ];
                    
                                $uInfo = Users::model()->findByPk($ref->user_id);
                                $uInfo->now_coins = $cBal[$ref->user_id]+$coinSumm;
                                $uInfo->save(false);
                                break;
                            }
                        }
                    }
                }
            }
            
            if($countBack > 0){
                if(!in_array($cnID = 'CP'.rand(000000000,999999999), $cNum))
                    $cNum[] = $cnID;
                
                $query[] = [
                    'operation_number' => $cnID,
                    'coinorder_id' => $this->coinorder_id,
                    'operation_date' => date('Y-m-d H:i:s'),
                    'operation_type' => self::TYPE_PROF,
                    'user_id' => 3,
                    'from_count' => $this->countOnPay,
                    'from_user' => $this->user_id,
                    'count' => $countBack,
                    'countToSystem' => 0,
                    'countAll' => $cBal[3] ? $cBal[3]+$countBack : $countBack,
                    'operation_summ' => 0,
                    'price_perOne' => $this->price_perOne
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
                        'operation_summAll' => number_format(isset($uBal[$key->user_id]) ? $uBal[$key->user_id] : 0, 2, '.', ''),
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
    
    public static function getOperationType($code = null)
    {
        $_items = [
            self::TYPE_BUY => Yii::t('models', 'CoinsMarket_attr_operation_type_buy'),
            self::TYPE_SELL => Yii::t('models', 'CoinsMarket_attr_operation_type_sale'),
            self::TYPE_PROF => Yii::t('models', 'CoinsMarket_attr_operation_type_profit'),
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
    
    public static function getOperationTypeGrid($code)
    {
        $classes[] = 'badge py-1';
        return '<span class="badge py-1 badge-'.self::getOperationTypeClass($code).'">'.self::getOperationType($code).'</span>';
    }
    
    private static function getOperationTypeClass($code)
    {
        $_items = [
            self::TYPE_BUY => 'success',
            self::TYPE_SELL => 'warning',
            self::TYPE_PROF => 'primary',
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;  
    }
}
