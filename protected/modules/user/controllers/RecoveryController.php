<?php

class RecoveryController extends MController
{
    public $layout = '//layouts/guest';
    public $defaultAction = 'recovery';
    
    protected function beforeAction($action)
    {
        if(!Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->getModule('user')->returnUrl);
        
        return true;
        parent::beforeAction($action);
    }
    
    public function allowedActions()
    {
        return 'recovery, captcha';
    }
    
    public function actions()
    {
	return [
            'captcha' => [
		'class' => 'ExtCaptcha',
                'testLimit' => 3,
                'backColor' => 0xFFFFFF,
                'foreColor' => 0x007BFF,
                'transparent' => true,
            ],
	];
    }
    
    public function actionRecovery()
    {
        $email = (isset($_GET['email'])) ? $_GET['email'] : '';
	$activkey = (isset($_GET['activkey'])) ? $_GET['activkey'] : '';
	
        if($email && $activkey) {
            $form = new UserChangePasswordForm;
            $find = Users::model()->notsafe()->findByAttributes(['email' => $email]);
            if($find && $find->activkey == $activkey) {
                if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($form)) {
                    echo CActiveForm::validate($form);
                    Yii::app()->end();
                }
                    
                if(Yii::app()->request->isPostRequest) {
                    if(Yii::app()->request->getPost(get_class($form)) !== null) {
                        $form->attributes = Yii::app()->request->getPost(get_class($form));
                        
                        $find->password = Yii::app()->getModule('user')->encrypting($form->password);
                        $find->activkey = Yii::app()->getModule('user')->encrypting(microtime().$form->password);
                        if($find->status == $find::USTATUS_NOACTIVE)
                            $find->status = $find::USTATUS_ORD;
			
                        $find->save();
                        Yii::app()->user->setFlash('success', Yii::t('controllers', 'user_reovery_passwordChangeSuccess'));
                        $this->redirect(Yii::app()->user->loginUrl);
                    }
                }
                
                $this->render('//user/changePassword', ['model' => $form]);
            } else {
		Yii::app()->user->setFlash('error', Yii::t('controllers', 'user_recovery_incorrectLink'));
		$this->redirect(Yii::app()->user->loginUrl);
            }
	} else {
            $form = new UserRecoveryForm;
            if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($form)) {
                echo CActiveForm::validate($form);
                Yii::app()->end();
            }
            
            if(Yii::app()->request->isPostRequest) {
                if(Yii::app()->request->getPost(get_class($form)) !== null) {
                    $form->attributes = Yii::app()->request->getPost(get_class($form));
                    if($form->validate(['username'])) {
                        $user = Users::model()->notsafe()->findbyPk($form->user_id);
                        $activation_url = Yii::app()->request->getHostInfo().'/recovery?activkey='.$user->activkey.'&email='.$user->email;
                        MHelper::sendEmail(false, false, $user->email, Yii::t('core', 'mail_recovery_subject'), 'recovery', ['url' => $activation_url, 'login' => $user->username, 'activkey' => $user->activkey, 'email' => $user->email]);
                        Yii::app()->user->setFlash('success', Yii::t('controllers', 'user_recovery_successSendLink'));
                        $this->redirect(Yii::app()->user->loginUrl);
                    }
                }
            }
			    	
            $this->render('//user/recovery', ['model' => $form]);
        }
    }
}
