<?php

class MAdminController extends MBaseController
{
    public $layout = 'application.modules.admin.views.layouts.main';
    
    public function init()
    {
        if(!Yii::app()->user->getIsSuperuser()) {
            $this->redirect(Yii::app()->getModule('user')->returnUrl);
        } else {
            Yii::app()->user->loginUrl = '/admin/auth';
            $this->module->init();
        }
    }
    
    public function beforeAction($action)
    {
	if(Yii::app()->user->isGuest && get_class($this) !== 'AuthController')
            Yii::app()->request->redirect($this->createUrl('/admin/auth'));

	Yii::app()->errorHandler->errorAction = '/admin/errors/error';

	return true;
    }

    public function render($view, $data = null, $return = false)
    {
	if (Yii::app()->request->isAjaxRequest === true)
            parent::renderPartial($view, $data, $return, false);
	else
            parent::render($view, $data, $return);
    }
}
