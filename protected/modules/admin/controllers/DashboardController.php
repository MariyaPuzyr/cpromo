<?php

class DashboardController extends MAdminController
{
    public function actionIndex()
    {
        $users = Users::model()->with(['referrals'])->findAll();
        $pays = UsersPays::model()->with(['user'])->approved()->order_id_desc_find()->findAll();
        $outs = UsersOuts::model()->with(['user'])->complete()->order_id_desc_find()->findAll();
        $profits = UsersProfits::model()->findAll();
        $res['countUsers'] = count($users);
        $res['countPays'] = count($pays);
        $res['countOuts'] = count($outs);
        $res['countProfit'] = count($profits);
        
        $this->render('index', [
            'res' => $res,
            'pays' => $pays,
            'outs' => $outs
        ]);
    }
    
    public function actionGetCountActiv($type)
    {
        $users = Users::model()->with(['referrals'])->findAll();
        $pays = UsersPays::model()->with(['user'])->approved()->order_id_desc_find()->findAll();
        $outs = UsersOuts::model()->with(['user'])->complete()->order_id_desc_find()->findAll();
        $profits = UsersProfits::model()->findAll();
        
        if($type == 'count') {
            $res['countUsers'] = count($users);
            $res['countPays'] = count($pays);
            $res['countOuts'] = count($outs);
            $res['countProfit'] = count($profits);
        } else {
            foreach($users as $user)
            if($user->referrals)
                $res['countUsersWithRef']++;
            
            foreach($pays as $pay)
                $res['paySumm'] += $pay->operation_summ;
        
            foreach($outs as $out)
                $res['outSumm'] += $out->operation_summ;
        
            foreach($profits as $profit)
                $res['profitSumm'] += $profit->operation_summ;
        
            $res['balance'] = ($res['paySumm'] - $res['outSumm']) + $res['profitSumm'];
        }
        
        $this->renderPartial('application.modules.admin.views.dashboard._countActiv', ['res' => $res, 'type' => $type], false, false);
    }
}
