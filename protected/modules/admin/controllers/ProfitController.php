<?php

class ProfitController extends MAdminController
{
    public function actionIndex()
    {
        $model = new Income;
        $model2 = new Income;
        $model->unsetAttributes();
        $model->order_id();
        $model2->unsetAttributes();
        $model2->order_id();
        
        $profits = UsersProfit::model()->order_date()->findAll(['select' => 'SUM(profit_summ) as profit_summ, profit_date', 'group' => 'profit_date']);
        
        $activ = Income::model()->findByPk(1);
        if(Yii::app()->request->isAjaxRequest) {
            echo CActiveForm::validate($activ);
            Yii::app()->end();
        }
        
        if(Yii::app()->request->isPostRequest) {
            if(Yii::app()->request->getPost('Income') !== null) {
                $activ->attributes = Yii::app()->request->getPost('Income');
                if($activ->save()) {
                    Yii::app()->user->setFlash('success', Admin::t('controllers', 'profit_activSaveSuccess'));
                    $this->refresh();
                }
            }
        }
        
        $this->render('index', ['model' => $model, 'activ' => $activ, 'profits' => $profits, 'model2' => $model2]);
    }
    
    public function actionWorkProfit($type)
    {
        $model = new Income;
        $model->setScenario('addIncome');
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($model)) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        
        if(Yii::app()->request->isPostRequest) {
            if(Yii::app()->request->getPost(get_class($model)) !== null) {
                $model->attributes = Yii::app()->request->getPost(get_class($model));
                $model->income_date = date('Y-m-d H:i:s');
                $model->create_at = date('Y-m-d H:i:s');
                $model->create_uid = Yii::app()->user->id;
                if($model->save(false)) {
                    Yii::app()->user->setFlash('success', Admin::t('controllers', 'profit_incomeAddSuccess'));
                    $this->redirect($this->createUrl('/admin/profit'));
                }
            }
        }
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;  
        $this->renderPartial('application.modules.admin.views.profit._work', ['model' => $model, 'type' => $type], false, true);
    }
}