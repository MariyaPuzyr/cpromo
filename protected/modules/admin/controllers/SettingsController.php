<?php

class SettingsController extends MAdminController
{
    public function filters()
    {
    	return [
            'rights',
            [
                'application.filters.YXssFilter',
                'clean'   => '*',
                'tags'    => 'none',
                'actions' => '*'
            ]
        ];
    }
    
    public function actionIndex()
    {
        $settings = Settings::model()->findByPk(1);
        $levels = SprLevels::model()->findAll();
        $infoPages = SprPages::model()->findAll();
        
        if(Yii::app()->request->isAjaxRequest) {
            echo CActiveForm::validate($settings);
            Yii::app()->end();
        }
        
        if(Yii::app()->request->isPostRequest){
            if(Yii::app()->request->getPost('Settings') !== null) {
                $settings->attributes = Yii::app()->request->getPost('Settings');
                if($settings->save()) {
                    Yii::app()->user->setFlash('success', Admin::t('controllers', 'settings_saveSuccess'));
                    $this->refresh();
                }
            }
        }
        
        $this->render('index', ['levels' => $levels, 'settings' => $settings, 'infoPages' => $infoPages]);
    }
    
    public function actionWorkLevel($type, $id = false)
    {
        $model = ($type == 'add') ? new SprLevels : SprLevels::model()->findByPk($id);
        
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($model)) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        
        if(Yii::app()->request->getPost(get_class($model)) !== null) {
            $model->attributes = Yii::app()->request->getPost(get_class($model));
            $model->save();
        }
        
        $this->render('workLevel', ['model' => $model]);
    }
    
    public function actionWorkPage($id_page = false, $type = null)
    {
        $model = ($type == 'add') ? new SprPages : SprPages::model()->findByPk($id_page);
        
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($model)) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        
        if(Yii::app()->request->getPost(get_class($model)) !== null) {
            $model->attributes = Yii::app()->request->getPost(get_class($model));
            $model->save();
        }
        
        $this->render('workPage', ['model' => $model]);
    }
    
    public function deletePage($id)
    {
        SprPages::model()->deleteByPk($id);
        $this->redirect($this->createUrl('/admin/settings'));
    }
}