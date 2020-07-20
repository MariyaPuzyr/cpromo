<?php

class SystemController extends MController
{
    public function allowedActions()
    {
        return 'index, maintenance, getInfoPage, error';
    }
    
    public function init() {
        $this->layout = Yii::app()->user->isGuest ? '//layouts/guest' : '//layouts/main';
    
        parent::init();
    }
    
    public function actionIndex()
    {
        if(!Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->getModule('user')->returnUrl);
        
        $cntUsers = Users::model()->count();
        $cp = CoinsMarket::model()->findAll(['select' => 'SUM(count) as count, operation_type', 'group'=>'operation_type']);
        $cntCP = 0;
        foreach ($cp as $coins) {
            if($coins->operation_type == 0) {
                $cntCP += $coins->count;
            } else {
                $cntCP -= $coins->count;
            }
        }
        
        $summCP = UsersBalance::model()->find(['select' => 'SUM(operation_summ) as operation_summ', 'condition' => 'operation_type='.UsersBalance::TYPE_SALECOIN]);
        $profitSumm = UsersProfits::model()->find(['select' => 'SUM(operation_summ) as operation_summ'])->operation_summ;
        $exchangeBonus = UsersBalance::model()->findAll(['select' => 'SUM(operation_summ) as operation_summ, operation_type', 'condition' => 'operation_type IN ('.UsersBalance::TYPE_SALECOIN.', '.UsersBalance::TYPE_BUYCOIN.')']);
        $bonus = 0;
        foreach($exchangeBonus as $bonus) {
            if($bonus->operation_type == UsersBalance::TYPE_SALECOIN)
                $bonus -= $bonus->operation_summ;
            else
                $bonus += $bonus->operation_summ;
        }
            
        $this->layout = '//layouts/landing';
        $this->render(!Yii::app()->settings->get('system')['offSite'] ? 'index' : 'maintenance', [
            'cntUsers' => $cntUsers,
            'cntCP' => $cntCP,
            'course' => Coins::model()->findByPk(1)->price,
            'cntOrders' => CoinsOrder::model()->count(),
            'summCP' => $summCP->operation_summ,
            'profit' => $profitSumm+$bonus
        ]);
    }
    
    public function actionMaintenance()
    {
        $this->layout = '//layouts/landing';
        $this->render('maintenance');
    }

    public function actionGetInfoPage($name, $partial = false)
    {
        $model = SprPages::model()->findByPk($name);
        
        if(!$partial)
            $this->render('page', ['model' => $model]);
        else
            $this->renderPartial('_page', ['model' => $model], false, false);
    }
    
    public function actionError()
    {
	if($error = Yii::app()->errorHandler->error) {
            if(Yii::app()->request->isAjaxRequest)
		echo $error['message'];
            else
		$this->render('error', $error);
	}
    }
}