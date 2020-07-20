<?php

class ExchangeController extends MController
{
    public function actionIndex($type_order = false)
    {
        //Создаем первоначальный массив сведений
        $count = [
            'buy' => 0,
            'sell' => 0,
            'closed' => 0,
            'course' => 0,
            'count_buy' => 0,
            'count_sell' => 0,
        ];
        $endOrderRate = CoinsOrder::model()->order_id_desc()->findByAttributes(['operation_status' => CoinsOrder::OSTAT_WAIT, 'operation_type' => CoinsOrder::OTYPE_SELL]);
        
        //Получаем открытые и закрытые заявки
        $counts = CoinsOrder::model()->findAll(['condition' => 'operation_status IN ('.implode(',', [CoinsOrder::OSTAT_WAIT, CoinsOrder::OSTAT_COMPL]).')']);
        if($counts) {
            foreach ($counts as $cnt) {
                //Если, заявка открыта, формируем количество по типу
                if ($cnt->operation_status == CoinsOrder::OSTAT_WAIT) {
                    if ($cnt->operation_type == CoinsOrder::OTYPE_BUY) {
                        //Заявка на покупку
                        $count['buy'] += $cnt->buy_summ;
                        $count['count_buy']++;
                    } else {
                        //Заявка на продажу
                        $count['sell'] += $cnt->count_now;
                        $count['count_sell']++;
                    }
                }
            
                //Считаем количество закрытых заявок
                if($cnt->operation_status == CoinsOrder::OSTAT_COMPL) {
                    $count['closed']++;
                    if ($cnt->operation_type == CoinsOrder::OTYPE_BUY) {
                        //Заявка на покупку
                        $count['count_buy_closed']++;
                    } else {
                        //Заявка на продажу
                        $count['count_sell_closed']++;
                    }
                }
            }
        }
        
        
        //Получаем текущий курс монеты
        $count['course'] = Coins::model()->findByPk(1)->price;
        $count['course_end'] = $endOrderRate->price_perOne;
        
        //Открываем модель с заявками для последующей работы с ней во view через $model->search($user_id, $operation_type)
        $order = new CoinsOrder;
        $order->unsetAttributes();
        $order->with('user');
        
        //Получаем сведения о полученной прибыли и мой список покупки актива
        $coinsMarket = new CoinsMarket;
        $coinsMarket->unsetAttributes();
        $coinsMarket->user_id = Yii::app()->user->id;
        $coinsMarket->with(['fromUser']);
        
        //Проверяем наличие заявки в текущие сутки. Если, есть, делаем кнопки неактивными
        $last_main_order = CoinsOrder::model()->order_id_desc()->find(['condition' => 'user_id = '.Yii::app()->user->id.' AND operation_status IN ('.CoinsOrder::OSTAT_COMPL.','.CoinsOrder::OSTAT_WAIT.')']);
        if ($last_main_order) {
            if (date('Y-m-d', strtotime($last_main_order->operation_date)) == date('Y-m-d')) {
                $button_disabled = true;
            } else {
                $button_disabled = false;
            }
        }
        
        //График роста
        $chart = CoinsOrder::model()->findAll(['select' => 'MAX(price_perOne) as price_perOne, DATE(update_at) as update_at', 'condition' => 'operation_status = '.CoinsOrder::OSTAT_COMPL.' AND operation_type = '.CoinsOrder::OTYPE_SELL.' AND price_perOne != 0', 'group' => 'DATE(update_at)']);
        if ($chart) {
            foreach ($chart as $crt) {
                $crts['label'][] = date('d.m.Y', strtotime($crt['update_at']));
                $crts['value'][] = $crt['price_perOne'];
            }
        }
        
        $this->render('index', [
            'order' => $order,
            'coinsMarket' => $coinsMarket,
            'count' => $count,
            'balance' => Yii::app()->user->finance,
            'button_disabled' => $button_disabled,
            'crts' => $crts
        ]);
    }
    
    public function actionOrderBuy()
    {
        if(in_array(Yii::app()->user->id, [6211,4117,1836,6053,4012,6053,4117,4010,2624,4012,2599,4117,1412,1836,612,1019,]))
            return false;

        //Получаем полное финансовое состояние пользователя
        $allFinance = Yii::app()->user->finance;
        $balanceNow = $allFinance->balance - $allFinance->outs_freeze - $allFinance->buy_freeze; //Получаем текущее количество монет, доступное к продаже
        
        if($balanceNow <= 0)
            return false;
                        
        $model = new CoinsOrder;
        if (Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($model)) {
            $model->setScenario('OrderBuy');
            $model->user_id = Yii::app()->user->id;
            $model->operation_date = date('Y-m-d H:i:s');
            $model->can_buy = $balanceNow;
            $model->buy_summ = $_POST[get_class($model)]['buy_summ'];
            $model->count = 0;
            $model->count_now = 0;
            $model->operation_type = $model::OTYPE_BUY;
            
            $validate = CActiveForm::validate($model);
            if ($validate == '[]' || !$validate) {
                if($model->save(false)) {
                    #$closed = CoinsOrder::closeBuyOrder($model->id);
                    /*switch ($closed) {
                        case 'full':*/
                            echo CJSON::encode(['message' => Yii::t('controllers', 'exchange_orderSell_success', ['#order_number' => $model->id])]);
                    /*        break;
                        case 'part':
                            echo CJSON::encode(['message' => Yii::t('controllers', 'exchange_orderSell_success_part', ['#order_number' => $model->id])]);
                            break;
                        case 'none':
                            echo CJSON::encode(['message' => Yii::t('controllers', 'exchange_orderSell_success_none', ['#order_number' => $model->id])]);
                            break;
                    }*/
                }
                    
            } else
                echo $validate;
            
            Yii::app()->end();
        }
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;  
        $this->renderPartial('//exchange/_orderBuy', ['model' => $model], false, true);
    }

    public function actionSell()
    {
        if(in_array(Yii::app()->user->id, [6211,4117,1836,6053,4012,6053,4117,4010,2624,4012,2599,4117,1412,1836,612,1019,]))
            return false;

        //Получаем полное финансовое состояние пользователя
        $allFinance = Yii::app()->user->finance;
        $coinNow = $allFinance->coins - $allFinance->coins_freeze; //Получаем текущее количество монет, доступное к продаже
        
        if ($coinNow <= 0)
            return false;
        
        $model = new CoinsOrder;
            
        if (Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($model)) {
            $model->user_id = Yii::app()->user->id;
            $model->operation_date = date('Y-m-d H:i:s');
            $model->can_sell = $coinNow;
            $model->count = floor(($coinNow * $_POST[get_class($model)]['count_percent'])/100);
            $model->count_percent = $_POST[get_class($model)]['count_percent'];
            $model->count_now = $model->count;
            $model->operation_type = $model::OTYPE_SELL;
            
            $validate = CActiveForm::validate($model);
            if ($validate == '[]' || !$validate) {
                if ($model->save(false)) {
                    #$closed = CoinsOrder::closeSellOrder($model->id);
                    #switch ($closed) {
                        #case 'full':
                            echo CJSON::encode(['message' => Yii::t('controllers', 'exchange_orderSell_success', ['#order_number' => $model->id])]);
                            #break;
                        /*case 'part':
                            echo CJSON::encode(['message' => Yii::t('controllers', 'exchange_orderSell_success_part', ['#order_number' => $model->id])]);
                            break;
                        case 'none':
                            echo CJSON::encode(['message' => Yii::t('controllers', 'exchange_orderSell_success_none', ['#order_number' => $model->id])]);
                            break;
                    }*/
                } else
                    $model->getErrors();
            } else
                echo $validate;
            
            Yii::app()->end();
        }
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;  
        $this->renderPartial('//exchange/_orderSell', ['model' => $model, 'coinNow' => $coinNow], false, true);
    }
    
    public function actionCancOrder($id)
    {
        return false;
        
        $model = CoinsOrder::model()->findByPk($id);
        if ($model->busy)
            return false;
        else {
            $model->operation_status = CoinsOrder::OSTAT_CANC;
            $model->sell_summ = 0;
            $model->buy_summ = 0;
            $model->count_now = 0;
            $model->update_at = date('Y-m-d H:i:s');
            $model->update_uid = Yii::app()->user->id;
            echo CJSON::encode(['status' => $model->save(false) ? 'success' : 'error']);
        }
        
        Yii::app()->end();
    }
    
    public static function actionGetNowCourse()
    {
        $orders = CoinsOrder::model()->findAll();
        $count_closed = 0;
        $count_buy = 0;
        $count_sell = 0;
        
        if ($order) {
            foreach ($orders as $order) {
                if ($operation_status == CoinsOrder::OSTAT_COMPL) {
                    $count_closed ++;
                } elseif ($operation_status == CoinsOrder::OSTAT_WAIT) {
                    if ($order->operation_type == CoinsOrder::OTYPE_BUY) {
                        $count_buy += $order->buy_summ;
                    } else {
                        $count_sell += $order->count_now;
                    }
                }
                
            }
        }
        
        echo CJSON::encode([
            'course' => Coins::model()->findByPk(1)->price.'$',
            'count_closed' => $count_closed,
            'count_buy' => $count_buy.'$',
            'count_sell' => $count_sell.'CP'
        ]);
        Yii::app()->end();
    }
    
    public function actioncloseOrder()
    {
        shell_exec('php /var/www/fastuser/data/www/cpromo.rinion.ru/protected/yiic mconsole completesellorder');
                    

        $this->redirect('/exchange');
    }
    
}
