<?php

class UserModule extends MBaseModule
{
    public $registrationUrl = ["/register"];
    public $recoveryUrl = ["/recovery"];
    public $logoutUrl = ["/logout"];
    public $returnUrl = ["/dashboard"];
    public $returnLogoutUrl = ["/"];
    public $activeAfterRegister = false;
    
    static private $_users = [];
    static private $_userByName = [ ];
    
    public function init()
    {
    	$this->setImport([
            'user.models.*',
            'user.components.*',
            'user.controllers.*'
	]);
    }
    
    public function users()
    {
	return Users;
    }
    
    public static function user($id = 0, $clearCache = false)
    {
        if(!$id&&!Yii::app()->user->isGuest)
            $id = Yii::app()->user->id;
        
        if($id) {
            if (!isset(self::$_users[$id]) || $clearCache)
                self::$_users[$id] = Users::model()->with(['statusAccount'])->findbyPk($id);
            
            return self::$_users[$id];
        } else 
            return false;
    }
	
    public static function getUserByName($username)
    {
        if(!isset(self::$_userByName[$username])) {
            $_userByName[$username] = Users::model()->findByAttributes(['username' => $username]);
        }
	
        return $_userByName[$username];
    }
    
    public static function encrypting($string = "") 
    {
	return md5($string);
    }
    
    public function getBalanceNow($id)
    {
        $user_id = $id;
        $finance = [
            'balance' => 0,
            'coins' => 0,
            'coins_buy' => 0,
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
        $outs = UsersOuts::model()->findAllByAttributes(['user_id' => $user_id]);
        if($outs) {
            foreach($outs as $out)
                if($out->operation_status == UsersOuts::OSTATUS_COMPL)
                    $finance['outs'] += $out->operation_summ;
                elseif($out->operation_status == UsersOuts::OSTATUS_WAIT)
                    $finance['outs_freeze'] += $out->operation_allSumm;
        }
        
        
        $profits = UsersProfits::model()->findAllByAttributes(['user_id' => $user_id]);
        $balanceByType = UsersBalance::model()->findAll(['select' => 'SUM(operation_summ) as operation_summ, operation_type', 'condition' => 'user_id =:user_id', 'params' => [':user_id' => $user_id], 'group' => 'operation_type']);
        
        $sellOrder = CoinsOrder::model()->findAllByAttributes(['user_id' => $user_id, 'operation_type' => CoinsOrder::OTYPE_SELL, 'operation_status' => CoinsOrder::OSTAT_WAIT]);
        if($sellOrder)
            foreach ($sellOrder as $order) {
                $finance['coins_freeze'] += $order->count_now;
            }
            
        $buyOrder = CoinsOrder::model()->findAllByAttributes(['user_id' => $user_id, 'operation_type' => CoinsOrder::OTYPE_BUY, 'operation_status' => CoinsOrder::OSTAT_WAIT]);
        if($buyOrder)
            foreach ($buyOrder as $order) {
                $finance['buy_freeze'] += $order->buy_summ;
            }
        
        $finance['balance'] = UsersBalance::model()->order_id_desc()->findByAttributes(['user_id' => $user_id])->operation_summAll;
        $finance['coins'] = CoinsMarket::model()->order_id_desc()->findByAttributes(['user_id' => $user_id])->countAll;
        $finance['coinsProfit'] = CoinsMarket::model()->find(['select' => 'SUM(count) as count', 'condition' => 'operation_type = '.CoinsMarket::TYPE_PROF.' AND user_id='.$user_id])->count;
        $finance['pays'] = MHelper::formSumm($pays, 'operation_summ');
        $finance['profits'] = MHelper::formSumm($profits, 'operation_summ');
        $finance['coins_all'] = $finance['coins']-$finance['coins_freeze'];
        
        $finance['coins_buy'] = CoinsMarket::model()->find(['select' => 'SUM(count) as count', 'condition' => 'user_id='.$user_id.' AND operation_type='.CoinsMarket::TYPE_BUY])->count;
        
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
        
        return (object)$finance;
    }
}