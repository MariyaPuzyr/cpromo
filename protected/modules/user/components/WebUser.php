<?php

class WebUser extends RWebUser
{
    
    
    public function getId()
    {
        return $this->getState('__id') ? $this->getState('__id') : 0;
    }
    
    public function model($id = 0)
    {
        return Yii::app()->getModule('user')->user($id);
    }
    
    public function getFinance()
    {
        $user_id = $this->getId();
        $finance = [
            'balance' => 0,
            'coins' => 0,
            'coins_buy' => 0,
            'coins_sell' => 0,
            'coins_freeze' => 0,
            'coinsProfit' => 0,
            'pays' => 0,
            'outs' => 0,
            'outs_freeze' => 0,
            'buy_freeze' => 0,
            'profits' => 0,
            'invest_ondep' => 0,
            'invest_status' => 0,
            'invest_coin' => 0,
            'profit_coin' => 0,
            'profit_refs' => 0,
        ];
        
        
        $pays = UsersPays::model()->findAllByAttributes(['user_id' => $user_id, 'operation_status' => UsersPays::PSTATUS_COMPL]);
        if ($pays)
            $finance['pays'] = MHelper::formSumm($pays, 'operation_summ');
        
        $outs = UsersOuts::model()->findAllByAttributes(['user_id' => $user_id]);
        if ($outs) {
            foreach($outs as $out)
                if($out->operation_status == UsersOuts::OSTATUS_COMPL)
                    $finance['outs'] += $out->operation_summ;
                elseif(in_array($out->operation_status, [UsersOuts::OSTATUS_WAIT, UsersOuts::OSTATUS_WCONFIRM]))
                    $finance['outs_freeze'] += $out->operation_allSumm;
        }
        
        
        $profits = UsersProfits::model()->findAllByAttributes(['user_id' => $user_id]);
        if ($profits)
            $finance['profits'] = MHelper::formSumm($profits, 'operation_summ');
            
        $sellOrder = CoinsOrder::model()->findAllByAttributes(['user_id' => $user_id, 'operation_type' => CoinsOrder::OTYPE_SELL, 'operation_status' => CoinsOrder::OSTAT_WAIT]);
        if ($sellOrder) {
            foreach ($sellOrder as $order)
                $finance['coins_freeze'] += $order->count_now;
        }    
            
        $buyOrder = CoinsOrder::model()->findAllByAttributes(['user_id' => $user_id, 'operation_type' => CoinsOrder::OTYPE_BUY, 'operation_status' => CoinsOrder::OSTAT_WAIT]);
        if ($buyOrder) {
            foreach ($buyOrder as $order)
                $finance['buy_freeze'] += $order->buy_summ;
        }
        
        $balance = UsersBalance::model()->order_id_desc()->findByAttributes(['user_id' => $user_id])->operation_summAll;
        if ($balance)
            $finance['balance'] = $balance;
        
        $coins = CoinsMarket::model()->findAllByAttributes(['user_id' => $user_id]);
        if ($coins) {
            foreach ($coins as $coin) {
                if(in_array($coin->operation_type, [CoinsMarket::TYPE_BUY, CoinsMarket::TYPE_PROF]))
                    $finance['coins'] += $coin->count;
                else
                    $finance['coins'] -= $coin->count;
                
                if($coin->operation_type == CoinsMarket::TYPE_PROF)
                    $finance['coinsProfit'] += $coin->count;
                
                if($coin->operation_type == CoinsMarket::TYPE_BUY)
                    $finance['coins_buy'] += $coin->count;
                
                if($coin->operation_type == CoinsMarket::TYPE_SELL)
                    $finance['coins_sell'] += $coin->count;
            }
            
            $finance['coins_all'] = $finance['coins']-$finance['coins_freeze'];
        }
        
        $balanceByType = UsersBalance::model()->findAll(['select' => 'SUM(operation_summ) as operation_summ, operation_type', 'condition' => 'user_id =:user_id', 'params' => [':user_id' => $user_id], 'group' => 'operation_type']);
        if($balanceByType){
            foreach($balanceByType as $btype){
                switch($btype->operation_type):
                    case UsersBalance::TYPE_ONDEP:
                        $finance['invest_ondep'] = $btype->operation_summ;
                    break;
                    case UsersBalance::TYPE_BUYSTATUS:
                        $finance['invest_status'] = $btype->operation_summ;
                    break;
                    case UsersBalance::TYPE_BUYCOIN:
                        $finance['invest_coin'] = $btype->operation_summ;
                    break;
                    case UsersBalance::TYPE_SALECOIN:
                        $finance['profit_coin'] = $btype->operation_summ;
                    break;
                    case UsersBalance::TYPE_PROFITSTAT:
                        $finance['profit_refs'] = $btype->operation_summ;
                    break;
                endswitch;
            }
        }
        
        /*$finance['deposit'] = UsersDeposit::model()->order_id_desc()->findByAttributes(['user_id' => $user_id])->operation_summAll;
        $allDepositPays = UsersDeposit::model()->findAllByAttributes(['user_id' => $user_id, 'operation_type' => UsersDeposit::TYPE_ONDEP]);
        if($allDepositPays) {
            foreach($allDepositPays as $dep){
                if(MHelper::diffDate(date('Y-m-d'), $dep->operation_date) <= Yii::app()->settings->get('system', 'deposit_pay_freeze_period'))
                    $finance['freezeDeposit'] += $dep->operation_summ;
            }
        }*/
        
        return (object)$finance;
    }
    
    public function user($id = 0)
    {
        return $this->model($id);
    }

    public function getUserByName($username) {
        return Yii::app()->getModule('user')->getUserByName($username);
    }   
}

