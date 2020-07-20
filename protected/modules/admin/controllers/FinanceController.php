<?php

class FinanceController extends MAdminController
{
    public function actionIndex(
            $pay_number = false, $pay_date = false, $pay_summ_min = false, $pay_summ_max = false, $pay_status = false, $pay_system = false, 
            $order_date = false, $out_summ_min = false, $out_summ_max = false, $order_status = false,
            $profit_date = false, $profit_summ_min = false, $profit_summ_max = false, $profit_type = false, $sellOrderType = false, $out_number = false, $order_system = false
    )
    {
        $pays = new UsersPays;
        $pays->with(['user']);
        $inCr = new CDbCriteria();
        $inCr->order = 'id DESC';
        $inCr->addColumnCondition(['visible' => 1], 'AND');
        if($pay_number)
            $pays->operation_number = $pay_number;
        if(isset($pay_status))
            $pays->operation_status = $pay_status;
        if($pay_date)
            $inCr->addCondition('DATE(operation_date) BETWEEN "'.date('Y-m-d', strtotime(explode(' - ', $pay_date)[0])).'" and "'.date('Y-m-d', strtotime(explode(' - ', $pay_date)[1])).'"', 'AND');
        if($pay_summ_min)
            $inCr->addCondition('operation_summ > '.$pay_summ_min, 'AND');
        if($pay_summ_max)
            $inCr->addCondition('operation_summ < '.$pay_summ_max, 'AND');
        if(isset($pay_system))
            $pays->operation_system = $pay_system;
        $pays->setDbCriteria($inCr);
        
        
        $finance_control = UsersPays::model()->findAllByAttributes(['operation_status' => UsersPays::PSTATUS_COMPL, 'visible' => 1]);
        $rFin = ['virtual' => '0', 'real' => 0, 'real_payeer' => 0, 'real_bitcoin' => 0, 'real_prfmoney' => 0, 'real_coinspay' => 0, 'coinbuy' => 0];
        
        if($finance_control){
            foreach($finance_control as $fin){
                if(strpos($fin->operation_number, 'VIRTUAL') !== false)
                    $rFin['virtual'] += $fin->operation_summ;
                else
                    $rFin['real'] += $fin->operation_summ;
                
                if($fin->operation_system == MBaseModel::FIN_PAYEER)
                    $rFin['real_payeer'] += $fin->operation_summ;
                if($fin->operation_system == MBaseModel::FIN_BITCOIN)
                    $rFin['real_bitcoin'] += $fin->operation_summ;
                if($fin->operation_system == MBaseModel::FIN_PRFMONEY)
                    $rFin['real_prfmoney'] += $fin->operation_summ;
                if($fin->operation_system == MBaseModel::FIN_COINSPAY)
                    $rFin['real_coinspay'] += $fin->operation_summ;
            }
        }
        
        $rFin['coinbuy'] = CoinsMarket::model()->find(['select' => 'SUM(count) as count', 'condition' => 'operation_type = '.CoinsMarket::TYPE_BUY])->count;
        
        $outs = new UsersOuts;
        $outs->unsetAttributes();
        $outs->with(['user']);
        $outCr = new CDbCriteria();
        $outCr->order = 'id DESC';
        if($out_number)
            $outs->operation_number = $out_number;
        if(isset($order_system))
            $outs->operation_system = $order_system;
        if(isset($order_status))
            $outs->operation_status = $order_status;
        if($order_date)
            $outCr->addCondition('DATE(operation_date) BETWEEN "'.date('Y-m-d', strtotime(explode(' - ', $order_date)[0])).'" and "'.date('Y-m-d', strtotime(explode(' - ', $order_date)[1])).'"', 'AND');
        if($out_summ_min)
            $outCr->addCondition('operation_summ > '.$out_summ_min, 'AND');
        if($out_summ_max)
            $outCr->addCondition('operation_summ < '.$out_summ_max, 'AND');
        $outs->setDbCriteria($outCr);
        
        $profits = new UsersProfits;
        $profits->unsetAttributes();
        $profits->with(['user', 'from_user']);
        $profitCr = new CDbCriteria();
        $profitCr->order = 'id DESC';
        if(isset($profit_type))
            $profits->operation_type = $profit_type;
        if($profit_date)
            $profitCr->addCondition('DATE(operation_date) BETWEEN "'.date('Y-m-d', strtotime(explode(' - ', $profit_date)[0])).'" and "'.date('Y-m-d', strtotime(explode(' - ', $profit_date)[1])).'"', 'AND');
        if($profit_summ_min)
            $profitCr->addCondition('operation_summ > '.$profit_summ_min, 'AND');
        if($profit_summ_max)
            $profitCr->addCondition('operation_summ < '.$profit_summ_max, 'AND');
        $profits->setDbCriteria($profitCr);
        
        $coinOrders = new CoinsOrder;
        $coinOrders->unsetAttributes();
        $coinOrders->with(['user']);
        $coinOrders->order_record();
        $coinOrders->operation_status = (isset($sellOrderType)) ? $sellOrderType : 0;
            
        
        $allOrders = CoinsOrder::model()->findAll();
        if($allOrders){
            foreach($allOrders as $order){
                if($order->operation_status == CoinsOrder::OSTAT_WAIT) {
                    $rOrder['count']++;
                    $rOrder['countCP'] += $order->count_now;
                } else {
                    $rOrder['countOut']++;
                    $rOrder['countOutCP'] += $order->count;
                }
            }
            
            $rOrder['countSumm'] = $rOrder['countCP']*Coins::model()->findByPk(1)->price;
            $rOrder['countOutSumm'] = $rOrder['countOutCP']*Coins::model()->findByPk(1)->price;
        }
        
        $outsDis = UsersOutsDisabled::model()->findByPk(1);
        $rOut = [
            'count' => 0,
            'countCompl' => 0,
            'countCanc' => 0,
            'countSummNow' => 0,
            'countSummAll' => 0,
            'countComplReal' => 0,
            'countProcent' => 0,
        ];
        
        $rMOuts = UsersOuts::model()->findAll();
        if($rMOuts){
            foreach($rMOuts as $out) {
                if($out->operation_status == UsersOuts::OSTATUS_WAIT){
                    $rOut['count']++;
                    $rOut['countSummNow'] += $out->operation_allSumm;
                }
                if($out->operation_status == UsersOuts::OSTATUS_COMPL)
                    $rOut['countCompl']++;
                    
                if(in_array($out->operation_status, [UsersOuts::OSTATUS_CANC, UsersOuts::OSTATUS_DISAGREE]))
                    $rOut['countCanc']++;
                
                $rOut['countSummAll'] += $out->operation_allSumm;
                $rOut['countComplReal'] += $out->operation_summ;
                $rOut['countProcent'] += $out->operation_procentFreeze;
            }
        }
        
        
        $sSumm = UsersBalance::model()->find(['select' => 'SUM(operation_summ) as operation_summ', 'condition' => 'visible = 1 AND operation_type = '.UsersBalance::TYPE_BUYSTATUS])->operation_summ;
        $nowStatus = UsersStatus::model()->findAll(['select' => 'count(id) as id, status_id', 'condition' => 'visible = 1 AND operation_summ != 0', 'group' => 'status_id']);
        $sCountU = UsersBalance::model()->find(['select' => 'COUNT(DISTINCT(user_id)) as user_id', 'condition' => 'visible = 1 AND operation_summ !=0 AND operation_type = '.UsersBalance::TYPE_BUYSTATUS])->user_id;
        foreach($nowStatus as $stat) {
            switch($stat->status_id){
                case 1:
                    $rStat['c1'] = $stat->id;
                    continue 2;
                case 2:
                    $rStat['c2'] = $stat->id;
                    continue 2;
                case 3:
                    $rStat['c3'] = $stat->id;
                    continue 2;
                case 4:
                    $rStat['c4'] = $stat->id;
                    continue 2;
                case 5:
                    $rStat['c5'] = $stat->id;
                    continue 2;
            }
        }
        
        $nowStatusSumm = UsersStatus::model()->findAll(['select' => 'SUM(operation_summ) as operation_summ, status_id', 'condition' => 'visible=1','group' => 'status_id']);
        foreach($nowStatusSumm as $stat) {
            switch($stat->status_id){
                case 1:
                    $rStat['c1_p'] = $stat->operation_summ;
                    continue 2;
                case 2:
                    $rStat['c2_p'] = $stat->operation_summ;
                    continue 2;
                case 3:
                    $rStat['c3_p'] = $stat->operation_summ;
                    continue 2;
                case 4:
                    $rStat['c4_p'] = $stat->operation_summ;
                    continue 2;
                case 5:
                    $rStat['c5_p'] = $stat->operation_summ;
                    continue 2;
            }
        }
        
        $this->render('index', [
            'pays' => $pays,
            'outs' => $outs,
            'profits' => $profits,
            'rFin' => $rFin,
            'coinsOrder' => $coinOrders,
            'rOrder' => $rOrder,
            'rOut' => $rOut,
            'rStat' => $rStat,
            'sSumm' => $sSumm,
            'sSummProfit' => $sSummProfit,
            'sCountU' => $sCountU,
            'outsDis' => $outsDis
        ]);
    }
    
    public function actionViewPay($id)
    {
        $model = UsersPays::model()->with(['user'])->findByPk($id);
        
        switch ($model->operation_system) {
            case $model::FIN_PAYEER:
                $data = UsersPaysPayeer::model()->findByPk($model->id);
                break;
            case $model::FIN_BITCOIN:
                $data = UsersPaysBit::model()->findByPk($model->id);
                $pendings = UsersPaysBitPending::model()->findAllByAttributes(['pay_id' => $model->id]);
                foreach($pendings as $pend)
                    $amount_pending_btc += $pend->value;
                $payments = UsersPaysBitPayments::model()->findAllByAttributes(['pay_id' => $model->id]);
                foreach($payments as $payment)
                    $amount_paid_btc += $payment->value;
                break;
            case $model::FIN_PRIZM:
                $data = UsersPaysPrizm::model()->findByAttributes(['pay_id' => $model->id]);
        }
        
        $this->render('view', ['model' => $model, 'data' => $data, 'amount_pending_btc' => $amount_pending_btc, 'amount_paid_btc' => $amount_paid_btc]);
    }
    
    public function actionConfirmPay($pay_id)
    {
        $model = UsersPays::model()->findByPk($pay_id);
        $model->operation_status = $model::PSTATUS_COMPL;
        $model->save(false);
        $this->redirect($this->createUrl('/admin/finance/viewPay', ['id' => $pay_id]));
    }
    
    public function actionConfirmCoinOrder($id)
    {
        $order = CoinsOrder::model()->with('user')->findByPk($id);
        $order->operation_status = $order::OSTAT_COMPL;
        $order->count_now = 0;
        $order->sell_summ = ($order->count * Coins::model()->findByPk(1)->price);
        $order->update_at = date('Y-m-d H:i:s');
        $order->update_uid = Yii::app()->user->id;
        if($order->save(false)){
            $sellCoin = new CoinsMarket;
            $sellCoin->setScenario('sellCoins');
            $sellCoin->user_id = $order->user_id;
            $sellCoin->count = $order->count;
            $sellCoin->countToSystem = 0;
            if($sellCoin->save()){
                MHelper::sendEmail(false, false, $order->user->email, Yii::t('core', 'mail_sellOrder_subject'), 'sellOrder', ['number' => $order->id, 'count' => $order->count, 'summ' => $sellCoin->operation_summ]);
                echo CJSON::encode(['status' => 'success']);
            }else
                print_r($sellCoin->getErrors());
        } else
            print_r($order->getErrors());
        
        Yii::app()->end();
    }
    
    public function actionDeletePay($id)
    {
        $model = UsersPays::model()->findByAttributes(['pay_id' => $id]);
        echo CJSON::encode(['status' => $model->delete() ? 'success' : 'error']);
        Yii::app()->end();
    }
    
    public function actionConfirmOrder($id)
    {
        $model = UsersOuts::model()->findByPk($id);
        $model->operation_status = $model::OSTATUS_COMPL;
        $model->update_at = date('Y-m-d H:i:s');
        $model->update_uid = Yii::app()->user->id;
        
        echo CJSON::encode(['status' => $model->save(false) ? 'success' : 'error']);
        Yii::app()->end();
    }
    
    public function actionReturnOrder($id)
    {
        $model = UsersOuts::model()->findByPk($id);
        $model->operation_status = $model::OSTATUS_WAIT;
        $model->update_at = date('Y-m-d H:i:s');
        $model->update_uid = Yii::app()->user->id;
        
        echo CJSON::encode(['status' => $model->save(false) ? 'success' : 'error']);
        Yii::app()->end();
    }
    
    public function actionDisOrder($id)
    {
        $model = UsersOuts::model()->findByPk($id);
        $model->operation_status = $model::OSTATUS_DISAGREE;
        $model->update_at = date('Y-m-d H:i:s');
        $model->update_uid = Yii::app()->user->id;
        
        echo CJSON::encode(['status' => $model->save(false) ? 'success' : 'error']);
        Yii::app()->end();
    }
    
    public function actionChangeOuts($name, $type)
    {
        $model = UsersOutsDisabled::model()->findByPk(1);
        $model->{$name} = $type;
        $model->save();
        $this->redirect($this->createUrl('/admin/finance'));
    }
}

