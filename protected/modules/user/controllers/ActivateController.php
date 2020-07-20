<?php

class ActivateController extends MController
{
    public $defaultAction = 'activate';
    
    protected function beforeAction($action)
    {
        if(Yii::app()->getModule('user')->activeAfterRegister) {
            if(!Yii::app()->user->isGuest)
                $this->redirect(Yii::app()->getModule('user')->returnUrl);
        }
        
        return true;
        parent::beforeAction($action);
    }
    
    public function allowedActions()
    {
        return 'activate';
    }

    public function actionActivate () {
        $email = $_GET['email'];
	$activkey = $_GET['activkey'];
	if($email && $activkey) {
            $find = Users::model()->notsafe()->findByAttributes(['email' => $email]);
            if(isset($find->activkey) && ($find->activkey == $activkey)) {
		$find->activkey = $this->encrypting(microtime());
		$find->status = $find::USTATUS_ORD;
                $find->emailConfirm = $find::STEMAIL_CONF;
                $find->save(false);
		Yii::app()->user->setFlash('success', Yii::t('controllers', 'user_activate_success_withoutLogin'));
                $this->redirect($this->createAbsoluteUrl(Yii::app()->getModule('user')->returnUrl));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('controllers', 'user_activate_Fail'));
                $this->redirect($this->createAbsoluteUrl(Yii::app()->getModule('user')->returnUrl));
            }
        } else {
            Yii::app()->user->setFlash('error', Yii::t('controllers', 'user_activate_Fail'));
            $this->redirect($this->createAbsoluteUrl(Yii::app()->getModule('user')->returnUrl));
        }
    }
}

