<?php

class LogoutController extends MController
{
    public $defaultAction = 'logout';
    
    public function allowedActions()
    {
        return 'logout';
    }
    
    public function actionLogout()
    {
        Yii::app()->user->logout();
	$this->redirect(Yii::app()->user->loginUrl);
    }
}


