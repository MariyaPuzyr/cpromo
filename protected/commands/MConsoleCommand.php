<?php

class MConsoleCommand extends CConsoleCommand
{
    public function actionCompletesellorder()
    {
            $order = CoinsOrder::model()->findByAttributes(['operation_status' => CoinsOrder::OSTAT_WAIT, 'operation_type' => CoinsOrder::OTYPE_SELL]);
            $now_course = Coins::model()->findByPk(1)->price;
        
            if($order) {
                print_r('Нашел заявку '.$order->id.' #### ');
                if ($order->operation_type == CoinsOrder::OTYPE_SELL && $order->count_now > 0) {
                    //Определяем курс для просроченных заявок
                    $course = ($order->price_perOne < $now_course) ? $order->price_perOne : $now_course;
                
                    //Получаем список всех заявок на продажу СР
                    $sell_buys = CoinsOrder::model()->findAllByAttributes(['operation_type' => CoinsOrder::OTYPE_BUY, 'operation_status' => CoinsOrder::OSTAT_WAIT]);
                    if ($sell_buys) {
                        foreach($sell_buys as $key => $val){
                            $orderToWork[$key] = ['buy_summ' => $val->buy_summ, 'id' => $val->id, 'user_id' => $val->user_id];
                            $orderToSave[$val->id] = ['buy_summ' => $val->buy_summ, 'user_id' => $val->user_id];
                        }
                
                        $i = 0; //Ставим счетчик на первую запись массива
                        $allSumm = $order->count_now; //Требуемая количество
                        $buySumm = $order->count_now; //Постоянная запись для требуемого количества
                        //Проходим по очереди массив ордеров закрывая каждый и проверяя остаток по счету до момента пока нужная сумма не исчерпается
                        while ($allSumm > 0) {
                            //Формируем массив с суммой потрачено/остаток по каждому ордеру
                            $closeOrder[$orderToWork[$i]['id']] = [
                                'ostatok' => floor($allSumm - ($orderToWork[$i]['buy_summ']/$course)),
                                'potracheno' => floor($orderToWork[$i]['buy_summ']/$course),
                                'buy_summ' => $orderToWork[$i]['buy_summ'],
                            ];
                            $allSumm -= floor($orderToWork[$i]['buy_summ']/$course);
                            
                            if(isset($orderToWork[$i+1]))
                                $i++;
                            else break;
                        }
                        
                        $notEnough = 0;
                        $fullCount = 0;
                        $fullNalog = 0;
                        $fullProcent = 0;
                        foreach($closeOrder as $key => $val){
                            if($val['ostatok'] >= 0) {
                                $fullCount += $val['potracheno'];
                                $notEnough = $val['potracheno'];
                                $procentSumm = number_format((number_format($val['buy_summ'], 2,'.','')*0.01), 2, '.', '');
                                $nalogSumm = number_format((number_format($val['buy_summ'], 2,'.','')*0.2), 2, '.', '');
                                $fullNalog += $nalogSumm;
                                $fullProcent += $procentSumm;
                                
                                print_r('Закрыл '.$val['potracheno'].' по заявке продажа '.$key.' на сумму '.number_format($val['buy_summ'], 2,'.','').' c курсом '.$course.' ### ');
                                
                                $modelBuy = new CoinsMarket;
                                $modelBuy->setScenario('buyCoin');
                                $modelBuy->operation_type = $modelBuy::TYPE_BUY;
                                $modelBuy->coinorder_id = $order->id;
                                $modelBuy->from_exchange = true;
                                $modelBuy->user_id = $orderToSave[$key]['user_id'];
                                $modelBuy->to_user = $order->user_id;
                                $modelBuy->operation_date = date('Y-m-d H:i:s');
                                $modelBuy->count = $val['potracheno'];
                                $modelBuy->operation_summ = number_format($val['buy_summ'], 2,'.','');
                                $modelBuy->price_perOne = $course;
                        
                                if ($modelBuy->save()) {
                                    $modelSell = new CoinsMarket;
                                    $modelSell->setScenario('sellCoin');
                                    $modelSell->operation_type = $modelSell::TYPE_SELL;
                                    $modelSell->coinorder_id = $key;
                                    $modelSell->from_exchange = true;
                                    $modelSell->user_id = $order->user_id;
                                    $modelSell->to_user = $orderToSave[$key]['user_id'];
                                    $modelSell->operation_date = date('Y-m-d H:i:s');
                                    $modelSell->count = $val['potracheno'];
                                    $modelSell->operation_summ = number_format((number_format($val['buy_summ'], 2,'.','')), 2,'.','');
                                    $modelSell->price_perOne = $course;
                                    if($modelSell->save()) {
                                        $downOrder = CoinsOrder::model()->findByPk($key);
                                        $downOrder->count += $val['potracheno'];
                                        $downOrder->operation_status = CoinsOrder::OSTAT_COMPL;
                                        $downOrder->sell_summ += $modelSell->operation_summ;
                                        $downOrder->price_perOne = $course;
                                        $downOrder->save(false);
                                
                                        self::formProcentFromExchangeSell($procentSumm, $order->id, $order->user_id);
                                    } else {
                                        print_r('1-').print_r($modelSell->getErrors());
                                    } 
                                } else {
                                    print_r('2-').print_r($modelBuy->getErrors());
                                }
                            } elseif(number_format($val['potracheno']*$course, 2, '.', '') == number_format(($val['buy_summ'] - number_format($val['buy_summ'], 2,'.','')*0.01), 2,'.','')) {
                                print_r('Закрыл '.$val['potracheno'].' по заявке продажа '.$key.' на сумму '.number_format($val['buy_summ'], 2,'.','').' c курсом '.$course.' ### ');
                                $val_summ = number_format($val['buy_summ'], 2,'.','');
                                $procentSumm = number_format(($val_summ*0.01), 2, '.', '');
                                $nalogSumm = number_format(($val_summ*0.2), 2, '.', '');
                                $fullNalog += $nalogSumm;
                                $fullProcent += $procentSumm;
                                
                                $modelBuy = new CoinsMarket;
                                $modelBuy->setScenario('buyCoin');
                                $modelBuy->operation_type = $modelBuy::TYPE_BUY;
                                $modelBuy->coinorder_id = $order->id;
                                $modelBuy->from_exchange = true;
                                $modelBuy->user_id = $orderToSave[$key]['user_id'];
                                $modelBuy->to_user = $order->user_id;
                                $modelBuy->operation_date = date('Y-m-d H:i:s');
                                $modelBuy->count = $val['potracheno'];
                                $modelBuy->operation_summ = number_format($val['buy_summ'], 2,'.','');
                                $modelBuy->price_perOne = $course;
                                
                                if ($modelBuy->save()) {
                                    $modelSell = new CoinsMarket;
                                    $modelSell->setScenario('sellCoin');
                                    $modelSell->operation_type = $modelSell::TYPE_SELL;
                                    $modelSell->coinorder_id = $key;
                                    $modelSell->from_exchange = true;
                                    $modelSell->user_id = $order->user_id;
                                    $modelSell->to_user = $orderToSave[$key]['user_id'];
                                    $modelSell->operation_date = date('Y-m-d H:i:s');
                                    $modelSell->count = $val['potracheno'];
                                    $modelSell->operation_summ = number_format(($val_summ), 2,'.','');
                                    $modelSell->price_perOne = $course;
                                    if($modelSell->save()) {
                                        $downOrder = CoinsOrder::model()->findByPk($key);
                                        $downOrder->count += $val['potracheno'];
                                        $downOrder->operation_status = CoinsOrder::OSTAT_COMPL;
                                        $downOrder->sell_summ += $modelSell->operation_summ;
                                        $downOrder->price_perOne = $course;
                                        $downOrder->save(false);
                                
                                        self::formProcentFromExchangeSell($procentSumm, $order->id, $order->user_id);
                                   } else {
                                        print_r('3-').print_r($modelSell->getErrors());
                                    } 
                                } else {
                                       print_r('4-').print_r($modelBuy->getErrors());
                                    }
                            } else {
                                $changeCount = $fullCount ? $order->count_now - $fullCount : $order->count_now;
                                $changeSumm = number_format($changeCount * $course, 2, '.', '');
                                $procentSumm = number_format(($changeSumm*0.01), 2, '.', '');
                                $nalogSumm = number_format(($changeSumm*0.2), 2, '.', '');
                                $fullNalog += $nalogSumm;
                                $fullProcent += $procentSumm;
                                
                                $modelBuy = new CoinsMarket;
                                $modelBuy->setScenario('buyCoin');
                                $modelBuy->operation_type = $modelBuy::TYPE_BUY;
                                $modelBuy->coinorder_id = $order->id;
                                $modelBuy->from_exchange = true;
                                $modelBuy->user_id = $orderToSave[$key]['user_id'];
                                $modelBuy->to_user = $order->user_id;
                                $modelBuy->operation_date = date('Y-m-d H:i:s');
                                $modelBuy->count = $changeCount;
                                $modelBuy->operation_summ = number_format($changeSumm, 2,'.','');
                                $modelBuy->price_perOne = $course;
                                
                                if ($modelBuy->save()) {
                                    $modelSell = new CoinsMarket;
                                    $modelSell->setScenario('sellCoin');
                                    $modelSell->operation_type = $modelSell::TYPE_SELL;
                                    $modelSell->coinorder_id = $key;
                                    $modelSell->from_exchange = true;
                                    $modelSell->user_id = $order->user_id;
                                    $modelSell->to_user = $orderToSave[$key]['user_id'];
                                    $modelSell->operation_date = date('Y-m-d H:i:s');
                                    $modelSell->count = $changeCount;
                                    $modelSell->operation_summ = number_format(($changeSumm), 2,'.','');
                                    $modelSell->price_perOne = $course;
                                    
                                    if($modelSell->save()) {
                                        $downOrder = CoinsOrder::model()->findByPk($key);
                                        $downOrder->count += $order->count_now;
                                        $downOrder->buy_summ -= $modelBuy->operation_summ;
                                        $downOrder->sell_summ += $modelBuy->operation_summ;
                                        $downOrder->price_perOne = $course;
                                        $downOrder->save(false);
                                    
                                        self::formProcentFromExchangeSell($procentSumm, $order->id, $order->user_id);
                                    } else {
                                        print_r('-5-').print_r($modelSell->getErrors());
                                    } 
                                } else {
                                        print_r('-6-').print_r($modelBuy->getErrors());
                                    }
                        
                                print_r('Потратил '.$changeCount.' по заявке продажа '.$key.' на сумму '.number_format($changeSumm, 2,'.','').' c курсом '.$course.' ### ');
                                       
                                $fullCount += $val['potracheno'];
                                $notEnough = $val['ostatok']-$fullCount;
                        
                            }
                        }
                
                        if ($notEnough <= 0) {
                            $order->count_now = 0;
                            $order->sell_summ = number_format($order->count*$course,2,'.','');
                            $order->operation_status = CoinsOrder::OSTAT_COMPL;
                            if ($order->save()) {
                                $nBuyOrder = new CoinsOrder;
                                $nBuyOrder->user_id = $order->user_id;
                                $nBuyOrder->operation_date = date('Y-m-d H:i:s');
                                $nBuyOrder->operation_type = CoinsOrder::OTYPE_BUY;
                                $nBuyOrder->count = 0;
                                $nBuyOrder->count_now = 0;
                                $nBuyOrder->buy_summ = $fullNalog;
                                $nBuyOrder->price_perOne = $course;
                                $nBuyOrder->operation_status = 0;
                                $nBuyOrder->busy = 1;
                                $nBuyOrder->save(false);
                            }
                        } elseif ($notEnough > 0) {
                            $order->count_now -= $fullCount;
                            $order->sell_summ = number_format($fullCount*$course,2,'.','');
                            if ($order->save()) {
                                $nBuyOrder = new CoinsOrder;
                                $nBuyOrder->user_id = $order->user_id;
                                $nBuyOrder->operation_date = date('Y-m-d H:i:s');
                                $nBuyOrder->operation_type = CoinsOrder::OTYPE_BUY;
                                $nBuyOrder->count = 0;
                                $nBuyOrder->count_now = 0;
                                $nBuyOrder->buy_summ = $fullNalog;
                                $nBuyOrder->price_perOne = $course;
                                $nBuyOrder->operation_status = 0;
                                $nBuyOrder->busy = 1;
                                $nBuyOrder->save(false);
                            }
                        }

                        return ($notEnough <= 0) ? 'full' : 'part';
                    } else {
                        print_r('none');
                    }
                } elseif ($order->operation_type == CoinsOrder::OTYPE_SELL && $order->now_count <= 0) {
                    $order->operation_status = CoinsOrder::OSTAT_COMPL;
                    $order->save();
                }
            } else 
                print_r(' ### Заявок нет ### ');
    }
    
    
    public static function formProcentFromExchangeSell($summ, $co_id, $user_id)
    {
        
        //Начисляем процент на Cosicomsa
        $mainSumm = UsersBalance::model()->order_id_desc()->findByAttributes(['user_id' => 3032])->operation_summAll;
        $balance = new UsersBalance;
        $balance->user_id = 3032;
        $balance->operation_number = MHelper::getOperationNumberForProcent('UsersBalance', 'PCS');
        $balance->operation_date = date('Y-m-d H:i:s');
        $balance->operation_summ = number_format($summ, 2,'.','');
        $balance->operation_summAll = number_format(($summ + $mainSumm),2,'.','');
        $balance->operation_type = UsersBalance::TYPE_PROFITCOINSELL;
        $balance->operation_system = UsersBalance::FIN_INNER;
        $balance->coinorder_id = $co_id;
        $balance->save(false);
        
        //Убираем этот процент с пользователя
        $mainSumm = UsersBalance::model()->order_id_desc()->findByAttributes(['user_id' => $user_id])->operation_summAll;
        $balance = new UsersBalance;
        $balance->user_id = $user_id;
        $balance->operation_number = MHelper::getOperationNumberForProcent('UsersBalance', 'PCO');
        $balance->operation_date = date('Y-m-d H:i:s');
        $balance->operation_summ = number_format($summ, 2,'.','');
        $balance->operation_summAll = number_format(($mainSumm - $summ), 2,'.','');
        $balance->operation_type = UsersBalance::TYPE_PROCENTEX;
        $balance->operation_system = UsersBalance::FIN_INNER;
        $balance->coinorder_id = $co_id;
        $balance->save(false);
    }
    
    public function actionGetPrizmHistory()
    {
        $model = UsersPaysPrizm::model()->order_id()->find();
        $server = '';
        $url = $model->tr_id ? 'http://'.$server.'/history?fromid='.$model->tr_id : 'http://'.$server.'/history';
        $page = '';
	
        $result = $this->getPrizmPage($url);
	if(($result['errno'] != 0) || ($result['http_code'] != 200)) {
            $error = $result['errmsg'];
	} else {
            $page = $result['content'];
	}
	
        $array_new = [];
	$xcmorewrite = explode("\n", str_replace("\r", '', $page));
	foreach($xcmorewrite as $value) {
            if($value) {
            	$array_new[] = explode(";", $value);
            }
	}
	
        foreach ($array_new as $item) {
            if($item['0'] != "No transactions!") {
                $query[] = [
                    'pay_id' => '',
                    'tr_id' => $item['0'],
                    'tr_date' => date('Y-m-d H:i:s', strtotime($item['1'])),
                    'tr_timestamp' => $item['2'],
                    'pzm' => $item['3'],
                    'pay_summ' => $item['4'],
                    'pay_message' => $item['5'],
                    'pay_status' => 0
                ];
                
            }
        }
        
        $builder = Yii::app()->db->schema->commandBuilder;
        if($query) {
            $command = $builder->createMultipleInsertCommand('{{referral_pays_prizm}}', $query);
            return $command->execute() ? 0 : 1;
        } else
            return 0;
    }
    
    public function actionSetPayPrizmStatus()
    {
        $pays = UsersPays::model()->findAllByAttributes(['pay_system' => GBaseModel::FIN_PRIZM, 'pay_status' => GBaseModel::PSTATUS_WAIT]);
        $prizmHistory = UsersPaysPrizm::model()->findAll();
        if($prizmHistory) {
            foreach($prizmHistory as $prizm) {
                $resPaysPrizm[$prizm->pay_message] = [
                    'pay_id' => $prizm->pay_id,
                    'pay_summ' => $prizm->pay_summ
                ];
            }
        }
        
        if($pays && $resPaysPrizm) {
            foreach($pays as $pay){
                if($resPaysPrizm[$pay->pay_number]) {
                    print_r($resPaysPrizm[$pay->pay_number]['pay_id']);
                    if(!$resPaysPrizm[$pay->pay_number]['pay_id']) {
                        if($resPaysPrizm[$pay->pay_number]['pay_summ'] >= $pay->pay_summConvert) {
                            UsersPays::model()->updateByPk($pay->id, ['pay_status' => GBaseModel::PSTATUS_COMPL]);
                            $prizmModel = UsersPaysPrizm::model()->findByAttributes(['pay_message' => $pay->pay_number]);
                            $prizmModel->pay_id = $pay->id;
                            $prizmModel->save(false);
                            UsersBalance::model()->savePayRecord(UsersPays::model()->findByPk($pay->id), false);
                        }
                    }
                }
            }
        }
        
        return 0;
    }
    
    private static function getPrizmPage($url)
    {
	$uagent = "Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.14";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_ENCODING, "");
	curl_setopt($ch, CURLOPT_USERAGENT, $uagent);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 2);

	$content = curl_exec($ch);
	$err = curl_errno($ch);
	$errmsg = curl_error($ch);
	$header = curl_getinfo($ch);
	curl_close($ch);

	$header['errno'] = $err;
	$header['errmsg'] = $errmsg;
	$header['content'] = $content;
	
        return $header;
    }
}

