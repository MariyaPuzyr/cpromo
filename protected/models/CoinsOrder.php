<?php

class CoinsOrder extends MBaseModel
{
    const OSTAT_WAIT = 0;
    const OSTAT_COMPL = 1;
    const OSTAT_CANC = 2;
    const OSTAT_ERROR = 3;
    
    const OTYPE_SELL = 0;
    const OTYPE_BUY = 1;
    
    const PERCENT_TO_SELL_COUNT = [5 => '5%', 10 => '10%', 25 => '25%', 50 => '50%', 75 => '75%', 100 => '100%'];
    
    public $can_sell;
    public $can_buy;
    public $count_percent;
    
    
    public function tableName()
    {
	return '{{coins_order}}';
    }

    public function rules()
    {
	return [
            ['user_id, operation_date, operation_type, count, count_now, price_perOne', 'required'],
            ['count', 'checkCount'],
            ['sell_summ, buy_summ, price_perOne', 'numerical'],
            ['user_id, operation_type, count, count_now, operation_status, busy,', 'numerical', 'integerOnly' => true],
            ['buy_summ', 'numerical', 'min' => 10],
            ['id, user_id, operation_date, operation_type, count, count_now, sell_summ, price_perOne, update_at, update_uid, operation_status, busy', 'safe', 'on' => 'search'],
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
            'operation_date' => Yii::t('models', 'attr_date'),
            'operation_type' => Yii::t('models', 'attr_operation_type'),
            'count' => Yii::t('models', 'attr_count'),
            'count_percent' => Yii::t('models', 'attr_count'),
            'count_now' => Yii::t('models', 'coinsOrder_attr_count_now'),
            'sell_summ' => Yii::t('models', 'coinsOrder_attr_sell_summ'),
            'buy_summ' => Yii::t('models', 'coinsOrder_attr_buy_summ'),
            'update_at' => Yii::t('models', 'coinsOrder_attr_update_at'),
            'operation_status' => Yii::t('models', 'attr_status'),
            'operation_type' => Yii::t('models', 'attr_operation'),
            'price_perOne' => Yii::t('models', 'coinsOrder_attr_price_perOne'),
	];
    }
    
    public function scopes()
    {
        return [
            'order_record' => ['order' => 't.operation_status ASC, t.id ASC'],
            'order_id_desc' => ['order' => 'id DESC'],
        ];
    }

    public function search($user_id = false, $operation_type = false, $operation_status = false, $order = false, $size = false)
    {
	$criteria = new CDbCriteria;

	$criteria->compare('user_id', isset($user_id) ? $user_id : $this->user_id);
	$criteria->compare('operation_date', $this->operation_date,true);
        
        if (is_array($operation_type)) {
            $criteria->addInCondition('operation_type', $operation_type);
        } else {
            $criteria->compare('operation_type', isset($operation_type) ? $operation_type : $this->operation_type);
        }
        
	$criteria->compare('count', $this->count);
	$criteria->compare('count_now', $this->count_now);
        $criteria->compare('price_perOne', $this->price_perOne);
	$criteria->compare('operation_status', isset($operation_status) ? $operation_status : $this->operation_status);
        
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
        if(!$this->isNewRecord){
            $this->update_at = date('Y-m-d H:i:s');
            $this->update_uid = (get_class(Yii::app())=='CConsoleApplication' || (get_class(Yii::app())!='CConsoleApplication')) ? 1 : Yii::app()->user->id;
            
            if ($this->operation_type == self::OTYPE_SELL && $this->operation_status == self::OSTAT_COMPL) {
                if($this->price_perOne > Coins::model()->findByPk(1)->price)
                    Coins::model()->updateByPk(1, ['price' => $this->price_perOne]);
            }
        } else {
            //Проверяем наличие уже поданной в текущие сутки заявки
            if ($this->operation_type == self::OTYPE_SELL) {
                /*$last_main_order = self::model()->order_id_desc()->find(['condition' => 'user_id = '.$this->user_id.' AND operation_status IN ('.self::OSTAT_COMPL.','.self::OSTAT_WAIT.')']);
                if ($last_main_order) {
                    if(date('Y-m-d', strtotime($last_main_order->operation_date)) == date('Y-m-d')) {
                        $this->addError('buy_summ', Yii::t('models', 'coinsOrder_attr_id_error_unique'));
                        $this->addError('count_percent', Yii::t('models', 'coinsOrder_attr_id_error_unique'));
                    }
                }
                
                $nowOrder = self::model()->find(['condition' => 'user_id = '.$this->user_id.' AND operation_type = '.self::OTYPE_SELL.' AND operation_status = '.self::OSTAT_WAIT]);
                if ($nowOrder) {
                    $this->addError('buy_summ', Yii::t('models', 'coinsOrder_attr_id_error_have'));
                    $this->addError('count_percent', Yii::t('models', 'coinsOrder_attr_id_error_have'));
                }*/
            }
            
            //Проверяем валидность указываемой суммы для ордера по покупке СР
            if ($this->operation_type == self::OTYPE_BUY) {
                if(!$this->buy_summ) {
                    $this->addError('buy_summ', Yii::t('models', 'coinsOrder_attr_buy_summ_error_empty'));
                } else {
                    $uModel = Users::model()->onlyRelation()->findByPk($this->user_id);
                    $status = SprStatuses::model()->findByPk($uModel->status_account);
                    $opers = CoinsMarket::model()->findAll(['condition' => 'user_id='.$this->user_id.' AND operation_type IN ('.CoinsMarket::TYPE_BUY.', '.CoinsMarket::TYPE_SELL.')']);
                    $summBuy = 0;
                    $summSell = 0;
                
                    if ($opers){
                        foreach ($opers as $coinSumm){
                            if ($coinSumm->operation_type == CoinsMarket::TYPE_BUY) {
                                $summBuy += $coinSumm->count;
                                $summBuy += $coinSumm->countToSystem;
                            } elseif ($coinSumm->operation_type == CoinsMarket::TYPE_SELL)
                                $summSell += $coinSumm->count;
                        }
                
                
                        if (((($summBuy - $summSell) * $this->price_perOne) + $this->buy_summ) > $status->max_coin_buy_summ)
                            $this->addError('buy_summ', Yii::t('models', 'coinsMarket_attr_operation_summ_error_limit'));
                        
                    }
                    
                    if ($this->buy_summ > $status->max_coin_buy_summ)
                        $this->addError('buy_summ', Yii::t('models', 'CoinsMarket_attr_operation_summ_error_big'));
                   
                    if($this->buy_summ > $this->can_buy) {
                        $this->addError('buy_summ', Yii::t('models', 'coinsOrder_attr_buy_summ_error_big'));
                    }
                }
                
                //Формируем курс единицы СР
                $this->price_perOne = Coins::model()->findByPk(1)->price;
            } else {
                //Формируем курс единицы СР
                $last_order = self::model()->order_id_desc()->findByAttributes(['operation_type' => self::OTYPE_SELL]);
                if ($this->operation_type == self::OTYPE_SELL) {
                    if ($last_order->price_perOne)
                        $this->price_perOne = number_format($last_order->price_perOne * 1.001, 6);
                    else
                        $this->price_perOne = number_format(Coins::model()->findByPk(1)->price * 1.001, 6);
                }
            }
        }
        
        parent::onBeforeValidate($event);
    }
    
    public function checkCount()
    {
        if ($this->isNewRecord) {
            if ($this->scenario == 'sellCoin') {
                if ($this->count){
                    //Если количество СР меньше выставляемого
                    if ($this->can_sell < $this->count)
                        $this->addError('count_percent', Yii::t('models', 'coinsOrder_attr_count_error_big'));
                
                    //Если количество которое приходит из формы не равно правильно посчитанному количеству
                    if ($this->count != floor(($this->can_sell*self::PERCENT_TO_SELL_COUNT[$this->count_percent])/100))
                        $this->addError('count_percent', 'ERROR');
                    
                    if (!array_key_exists($this->count_percent, self::PERCENT_TO_SELL_COUNT)) {
                        $this->addError('count_percent', 'ERROR_PERCENT_FAIL');
                    }
                    
                    //Если количество монет, выставляемых на продажу в сумме по текущему курсе менее 10 долларов
                    if ($this->count*$this->price_perOne < 10) {
                        $this->addError('count_percent', Yii::t('models', 'coinsOrder_attr_count_error_priceFloor'));
                    }
                }
                
                if (!$this->count_percent) {
                    $this->addError('count_percent', Yii::t('models', 'coinsOrder_attr_count_percent_required'));
                }
            }
        }
    }
    
    public static function getOperationStatus($code = null)
    {
        $_items = [
            self::OSTAT_WAIT => Yii::t('models', 'coinsOrder_attr_operation_status_wait'),
            self::OSTAT_COMPL => Yii::t('models', 'coinsOrder_attr_operation_status_compl'),
            self::OSTAT_CANC => Yii::t('models', 'coinsOrder_attr_operation_status_canc'),
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
    
    public static function getOperationType($code = null)
    {
        $_items = [
            self::OTYPE_BUY => Yii::t('models', 'coinsOrder_attr_operation_type_buy'),
            self::OTYPE_SELL => Yii::t('models', 'coinsOrder_attr_operation_type_sell'),
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
    
    public static function getOperationStatusToGrid($code)
    {
        $classes[] = 'badge py-1';
        $_items = [
            self::OSTAT_WAIT => 'badge-info',
            self::OSTAT_COMPL => 'badge-success',
            self::OSTAT_CANC => 'badge-danger',
        ];
        
        if(isset($code)) {
            array_push($classes, $_items[$code]);
            return '<span class="'.implode(' ', $classes).'">'.self::getOperationStatus($code).'</span>';
        }
            return false;
    }
    
    public static function closeBuyOrder($id)
    {
        $order = self::model()->findByPk($id);
        $course = Coins::model()->findByPk(1)->price;
        if ($order->operation_type == self::OTYPE_BUY) {
            //Получаем список всех заявок на продажу СР
            $sell_orders = self::model()->findAllByAttributes(['operation_type' => self::OTYPE_SELL, 'operation_status' => self::OSTAT_WAIT,]);
            if ($sell_orders) {
                foreach($sell_orders as $key => $val){
                    $orderToWork[$key] = ['count_now' => $val->count_now, 'id' => $val->id, 'price_perOne' => $course];
                    $orderToSave[$val->id] = ['count_now' => $val->count_now, 'user_id' => $val->user_id, 'price_perOne' => $course];
                }
                
                $i = 0; //Ставим счетчик на первую запись массива
                $allSumm = $order->buy_summ; //Требуемая сумма
                $buySumm = $order->buy_summ; //Постоянная запись для требуемой суммы
                //Проходим по очереди массив ордеров закрывая каждый и проверяя остаток по счету до момента пока нужная сумма не исчерпается
                while ($allSumm > 0) {
                    //Формируем массив с суммой потрачено/остаток по каждому ордеру
                    $closeOrder[$orderToWork[$i]['id']] = [
                        'ostatok' => number_format($allSumm - ($orderToWork[$i]['count_now']*$orderToWork[$i]['price_perOne']), 2,'.',''),
                        'potracheno' => number_format(($orderToWork[$i]['count_now']*$orderToWork[$i]['price_perOne']), 2,'.','')
                    ];
                    $allSumm -= number_format(($orderToWork[$i]['count_now']*$orderToWork[$i]['price_perOne']), 2,'.','');
        
                    if($orderToWork[$i+1])
                        $i++;
                    else break;
                }

                $notEnough = 0;
                $fullCount = 0;
                
                foreach($closeOrder as $key => $val){
                    if($val['ostatok'] >= 0){
                        $modelBuy = new CoinsMarket;
                        $modelBuy->setScenario('buyCoin');
                        $modelBuy->operation_type = $modelBuy::TYPE_BUY;
                        $modelBuy->coinorder_id = $key;
                        $modelBuy->from_exchange = true;
                        $modelBuy->user_id = Yii::app()->user->id;
                        $modelBuy->to_user = $orderToSave[$key]['user_id'];
                        $modelBuy->operation_date = date('Y-m-d H:i:s');
                        $modelBuy->count = $orderToSave[$key]['count_now'];
                        $modelBuy->operation_summ = number_format($val['potracheno'], 2,'.','');
                        $modelBuy->price_perOne = $course;
                        if ($modelBuy->save()) {
                            $procentSumm = $val['potracheno']*0.01;
                            
                            $modelSell = new CoinsMarket;
                            $modelSell->setScenario('sellCoin');
                            $modelSell->operation_type = $modelSell::TYPE_SELL;
                            $modelSell->coinorder_id = $order->id;
                            $modelSell->from_exchange = true;
                            $modelSell->user_id = $orderToSave[$key]['user_id'];
                            $modelSell->to_user = Yii::app()->user->id;
                            $modelSell->operation_date = date('Y-m-d H:i:s');
                            $modelSell->count = $orderToSave[$key]['count_now'];
                            $modelSell->operation_summ = number_format(($val['potracheno']-$procentSumm), 2,'.','');
                            $modelSell->price_perOne = $course;
                            if($modelSell->save()) {
                                $downOrder = self::model()->findByPk($key);
                                $downOrder->count_now = 0;
                                $downOrder->sell_summ = $modelSell->operation_summ;
                                $downOrder->price_perOne = $course;
                                $downOrder->operation_status = self::OSTAT_COMPL;
                                $downOrder->save();
                                
                                $this->formProcentFromExchangeSell($procentSumm, $order->id);
                            } else return $modelSell->getErrors();
                        } else return $modelBuy->getErrors();

                        $fullCount += $orderToSave[$key]['count_now'];
                        $notEnough = $val['ostatok'];
                    } else {
                        $changeSumm = ($notEnough ? $notEnough : $buySumm);
                        
                        $modelBuy = new CoinsMarket;
                        $modelBuy->setScenario('buyCoin');
                        $modelBuy->operation_type = $modelBuy::TYPE_BUY;
                        $modelBuy->coinorder_id = $key;
                        $modelBuy->from_exchange = true;
                        $modelBuy->user_id = Yii::app()->user->id;
                        $modelBuy->to_user = $orderToSave[$key]['user_id'];
                        $modelBuy->operation_date = date('Y-m-d H:i:s');
                        $modelBuy->count = floor(($changeSumm/$orderToSave[$key]['price_perOne']));
                        $modelBuy->operation_summ = number_format($changeSumm, 2,'.','');
                        $modelBuy->price_perOne = $course;
                        if ($modelBuy->save()) {
                            $procentSumm = $changeSumm*0.01;
                            
                            $modelSell = new CoinsMarket;
                            $modelSell->setScenario('sellCoin');
                            $modelSell->operation_type = $modelSell::TYPE_SELL;
                            $modelSell->coinorder_id = $order->id;
                            $modelSell->from_exchange = true;
                            $modelSell->user_id = $orderToSave[$key]['user_id'];
                            $modelSell->to_user = Yii::app()->user->id;
                            $modelSell->operation_date = date('Y-m-d H:i:s');
                            $modelSell->count = floor(($changeSumm/$orderToSave[$key]['price_perOne']));
                            $modelSell->countToSystem = 0;
                            $modelSell->operation_summ = number_format($changeSumm - $procentSumm);
                            $modelSell->price_perOne = $course;
                            if ($modelSell->save()) {
                                $downOrder = self::model()->findByPk($key);
                                $downOrder->count_now -= $modelSell->count;
                                $downOrder->sell_summ = $modelSell->operation_summ;
                                $downOrder->price_perOne = $course;
                                $downOrder->save();
                                
                                self::formProcentFromExchangeSell($procentSumm, $order->id);
                                
                            } else return $modelSell->getErrors();
                        } else return $modelBuy->getErrors();
                        
                        $fullCount += floor(($changeSumm/$orderToSave[$key]['price_perOne']));
                        $notEnough = $val['ostatok'];
                    }
                }
                
                if ($notEnough <= 0) {
                    $order->count = $fullCount;
                    $order->operation_status = self::OSTAT_COMPL;
                    $order->save();
                } elseif ($notEnough > 0) {
                    $order->count = $fullCount;
                    $order->buy_summ = number_format($notEnough, 2,'.','');
                    $order->save();
                }

                return ($notEnough <= 0) ? 'full' : 'part';
            } else {
                return 'none';
            }
            return 'none';
        }
    }
    
    public static function closeSellOrder($id)
    {
        $order = self::model()->findByPk($id);
        if ($order->operation_type == self::OTYPE_SELL) {
            //Получаем список всех заявок на продажу СР
            $sell_buys = self::model()->findAllByAttributes(['operation_type' => self::OTYPE_BUY, 'operation_status' => self::OSTAT_WAIT, 'busy' => 1]);
            if ($sell_buys) {
                foreach($sell_buys as $key => $val){
                    $orderToWork[$key] = ['buy_summ' => $val->buy_summ, 'id' => $val->id];
                    $orderToSave[$val->id] = ['buy_summ' => $val->buy_summ, 'user_id' => $val->user_id];
                }
                
                $i = 0; //Ставим счетчик на первую запись массива
                $allSumm = $order->count_now; //Требуемая количество
                $buySumm = $order->count_now; //Постоянная запись для требуемого количества
                //Проходим по очереди массив ордеров закрывая каждый и проверяя остаток по счету до момента пока нужная сумма не исчерпается
                while ($allSumm > 0) {
                    //Формируем массив с суммой потрачено/остаток по каждому ордеру
                    $closeOrder[$orderToWork[$i]['id']] = [
                        'ostatok' => floor($allSumm - ($orderToWork[$i]['buy_summ']/$order->price_perOne)),
                        'potracheno' => floor($orderToWork[$i]['buy_summ']/$order->price_perOne),
                        'buy_summ' => $orderToWork[$i]['buy_summ'],
                    ];
                    $allSumm -= floor($orderToWork[$i]['buy_summ']/$order->price_perOne);
        
                    if($orderToWork[$i+1])
                        $i++;
                    else break;
                }

                $notEnough = 0;
                $fullCount = 0;
                
                foreach($closeOrder as $key => $val){
                    if($val['ostatok'] < 0){
                        $changeCount = $notEnough ? $notEnough : $order->count_now;
                        $changeSumm = $notEnough ? $notEnough*$order->price_perOne : $order->count_now * $order->price_perOne;
                        
                        $modelBuy = new CoinsMarket;
                        $modelBuy->setScenario('buyCoin');
                        $modelBuy->operation_type = $modelBuy::TYPE_BUY;
                        $modelBuy->coinorder_id = $order->id;
                        $modelBuy->from_exchange = true;
                        $modelBuy->user_id = $orderToSave[$key]['user_id'];
                        $modelBuy->to_user = Yii::app()->user->id;
                        $modelBuy->operation_date = date('Y-m-d H:i:s');
                        $modelBuy->count = $changeCount;
                        $modelBuy->operation_summ = $changeSumm;
                        $modelBuy->price_perOne = $order->price_perOne;
                        if ($modelBuy->save()) {
                            $procentSumm = $changeSumm*0.01;
                            
                            $modelSell = new CoinsMarket;
                            $modelSell->setScenario('sellCoin');
                            $modelSell->operation_type = $modelSell::TYPE_SELL;
                            $modelSell->coinorder_id = $key;
                            $modelSell->from_exchange = true;
                            $modelSell->user_id = Yii::app()->user->id;
                            $modelSell->to_user = $orderToSave[$key]['user_id'];
                            $modelSell->operation_date = date('Y-m-d H:i:s');
                            $modelSell->count = $changeCount;
                            $modelSell->operation_summ = $changeSumm - $procentSumm;
                            $modelSell->price_perOne = $order->price_perOne;
                            if($modelSell->save()) {
                                $downOrder = self::model()->findByPk($key);
                                $downOrder->count += $order->count_now;
                                $downOrder->buy_summ -= $modelBuy->operation_summ;
                                $downOrder->save();
                                
                                self::formProcentFromExchangeSell($procentSumm, $key);
                                
                            } else print_r($modelSell->getErrors());
                        } else print_r($modelBuy->getErrors());
                        
                        $fullCount += $val['potracheno'];
                        $notEnough = $val['ostatok']-$fullCount;
                        
                    } else {
                        $fullCount += $val['potracheno'];
                        $notEnough = $val['potracheno'];
                        
                        $modelBuy = new CoinsMarket;
                        $modelBuy->setScenario('buyCoin');
                        $modelBuy->operation_type = $modelBuy::TYPE_BUY;
                        $modelBuy->coinorder_id = $order->id;
                        $modelBuy->from_exchange = true;
                        $modelBuy->user_id = $orderToSave[$key]['user_id'];
                        $modelBuy->to_user = Yii::app()->user->id;
                        $modelBuy->operation_date = date('Y-m-d H:i:s');
                        $modelBuy->count = $val['potracheno'];
                        $modelBuy->operation_summ = $val['buy_summ'];
                        $modelBuy->price_perOne = $order->price_perOne;
                        if ($modelBuy->save()) {
                            $procentSumm = $val['buy_summ']*0.01;
                            
                            $modelSell = new CoinsMarket;
                            $modelSell->setScenario('sellCoin');
                            $modelSell->operation_type = $modelSell::TYPE_SELL;
                            $modelSell->coinorder_id = $key;
                            $modelSell->from_exchange = true;
                            $modelSell->user_id = Yii::app()->user->id;
                            $modelSell->to_user = $orderToSave[$key]['user_id'];
                            $modelSell->operation_date = date('Y-m-d H:i:s');
                            $modelSell->count = $val['potracheno'];
                            $modelSell->operation_summ = $val['buy_summ'] - $procentSumm;
                            $modelSell->price_perOne = $order->price_perOne;
                            if($modelSell->save()) {
                                $downOrder = self::model()->findByPk($key);
                                $downOrder->count += $val['potracheno'];
                                $downOrder->operation_status = self::OSTAT_COMPL;
                                $downOrder->save();
                                
                                self::formProcentFromExchangeSell($procentSumm, $key);
                                
                            } else print_r($modelSell->getErrors());
                        } else print_r($modelBuy->getErrors());
                    }
                }
                
                if ($notEnough <= 0) {
                    $order->count_now = 0;
                    $order->operation_status = self::OSTAT_COMPL;
                    $order->save();
                } elseif ($notEnough > 0) {
                    $order->count -= $fullCount;
                    $order->buy_summ = $fullCount*$order->price_perOne;
                    $order->save();
                }

                return ($notEnough <= 0) ? 'full' : 'part';
            } else {
                return 'none';
            }
            return 'none';
        }
    }
    
    public static function formProcentFromExchangeSell($summ, $co_id)
    {
        $mainSumm = UsersBalance::model()->order_id_desc()->findByAttributes(['user_id' => 3032])->operation_summAll;
        
        $balance = new UsersBalance;
        $balance->user_id = 3032;
        $balance->operation_number = 'PCSMAIN'.rand(0000,9999);
        $balance->operation_date = date('Y-m-d H:i:s');
        $balance->operation_summ = number_format($summ, 2,'.','');
        $balance->operation_summAll = $summ + $mainSumm;
        $balance->operation_type = UsersBalance::TYPE_PROFITCOINSELL;
        $balance->operation_system = UsersBalance::FIN_INNER;
        $balance->coinorder_id = $co_id;
        $balance->save(false);
        
    }
}
