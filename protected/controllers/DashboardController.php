<?php

class DashboardController extends MController
{
    public function actionIndex($operation_type = false, $summ_min = false, $summ_max = false, $balance_period = false)
    {
        $user_id = Yii::app()->user->id;
        $rFU = [];
        $rFUO = [];
        $rFUOC = [];
        $main = [];
        $chartPrice = 0;
        $cnt = 0;
        
        
        $balance = new UsersBalance;
        $balance->unsetAttributes();
        $balance->user_id = $user_id;
        $balance->with(['market']);
        $criteria = new CDbCriteria();
        
        if(isset($operation_type))
            $balance->operation_type = $operation_type;
        
        $criteria->addCondition('operation_summ >= 0', 'AND');
        if($summ_min)
            $criteria->addCondition('operation_summ >= '.$summ_min, 'AND');
        if($summ_max)
            $criteria->addCondition('operation_summ <= '.$summ_max, 'AND');
        if($balance_period)
            $criteria->addBetweenCondition('DATE(operation_date)', date('Y-m-d', strtotime(explode(' - ', $balance_period)[0])), date('Y-m-d', strtotime(explode(' - ', $balance_period)[1])));
        $balance->setDbCriteria($criteria);
        $balance->order_id_desc();
        
        $inPays = new UsersPays;
        $inPays->unsetAttributes();
        $inPays->user_id = $user_id;
        $inPays->operation_status = UsersPays::PSTATUS_WAIT;
        $inPays->show_vis();
        
        $inOuts = UsersOuts::model()->findAllByAttributes(['user_id' => $user_id, 'operation_status' => UsersOuts::OSTATUS_WAIT]);
        $coins = Coins::model()->findByPk(1);
        
        $referrals = UsersRelation::model()->with(['rData'])->findAllByAttributes(['user_id' => $user_id]);
        $countOfDay = 0;
        $countAll = 0;
        if($referrals) {
            foreach($referrals as $referral) {
                $countAll++;
                if(date('Y-m-d') == date('Y-m-d', strtotime($referral->rData->create_at)))
                    $countOfDay++;
            }
        }
        
        $profits = UsersProfits::model()->findAllByAttributes(['user_id' => $user_id]);
        $pRes = ['system' => 0, 'referral' => 0];
        if($profits) {
            foreach($profits as $profit){
                if($profit->operation_type == UsersProfits::TYPE_SYSTEM)
                    $pResFor['system'] += $profit->operation_summ;
                if($profit->operation_type == UsersProfits::TYPE_USER)
                    $pResFor['referral'] += $profit->operation_summ;
            }
            #array_merge($pRes, $pResFor);
        }
        
        $nowChartPrice = CoinsOrder::model()->findAll(['condition' => 'operation_type ='.CoinsOrder::OTYPE_SELL.' AND DATE(update_at) = "'.date('Y-m-d').'" AND operation_status='.CoinsOrder::OSTAT_COMPL]);
        if ($nowChartPrice) {
            $firstOrder = $nowChartPrice[0]->price_perOne;
            $endOrder = end($nowChartPrice)->price_perOne;
            $chartPrice = round(((($endOrder-$firstOrder)/$firstOrder)*100),2);
        } else {
            /*$nowChartPrice_old = CoinsOrder::model()->findAll(['condition' => 'operation_type ='.CoinsOrder::OTYPE_SELL.' AND DATE(operation_date) = "'.date('Y-m-d', strtotime('-1 day')).'" AND operation_status='.CoinsOrder::OSTAT_COMPL]);
            if($nowChartPrice_old) {
                $firstOrder = $nowChartPrice_old[0]->price_perOne;
                $endOrder = end($nowChartPrice_old)->price_perOne;
                $chartPrice = round(((($endOrder-$firstOrder)/$firstOrder)*100),2);
            }*/
            $chartPrice = 0;
        }
        
        $firstWeekUsers = CoinsMarket::model()->findAll(['select' => 'DISTINCT(user_id) as user_id', 'condition' => 'price_perOne = "0.015" AND operation_type='.CoinsMarket::TYPE_BUY]);
        $firstWeekUsersOrders = CoinsOrder::model()->findAll();
        
        
        if($firstWeekUsers)
            foreach($firstWeekUsers as $first)
                $rFU[] = $first->user_id;
        
        if($firstWeekUsersOrders)
            foreach($firstWeekUsersOrders as $first){
                $rFUO[] = $first->user_id;
                if($first->operation_status == 1)
                    $rFUOC[] = $first->user_id;
                
                if(!$first->operation_status){
                    $cnt++;
                    if($first->user_id == Yii::app()->user->id){
                        $main = [
                            'number' => $first->id,
                            'count' => $first->count,
                            'count_now' => $first->count_now
                        ];
                    }
                }
                    
            }
        
        $this->render('index', [
            'balance' => $balance,
            'inPays' => $inPays,
            'inOuts' => $inOuts,
            'profits' => $pRes,
            'coins' => $coins,
            'refs' => ['count' => $countAll, 'countOfDay' => $countOfDay],
            'chartPrice' => $chartPrice,
            'rFU' => $rFU,
            'rFUO' => $rFUO,
            'rFUOC' => $rFUOC,
            'cnt' => $cnt,
            'main' => $main
        ]);
    }
    
    
    public function actiontest()
    {
        $this->render('test');
    }
}