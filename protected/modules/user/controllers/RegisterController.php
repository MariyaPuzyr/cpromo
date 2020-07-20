<?php

class RegisterController extends MController
{
    public $layout = '//layouts/guest';
    public $defaultAction = 'register';
    
    protected function beforeAction($action)
    {
        if(!Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->getModule('user')->returnUrl);
        
        return true;
        parent::beforeAction($action);
    }
    
    public function allowedActions()
    {
        return 'register, validate, showTerms, captcha';
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
    
    public function actionRegister()
    {
        if(Yii::app()->settings->get('system', 'enableLockRegister') && !isset($_GET['referral_id'])) {
            Yii::app()->user->setFlash('error', Yii::t('controllers', 'user_register_refError'));
            $this->redirect(Yii::app()->user->loginUrl);
        }
        
        $uFind = Users::model()->notsafe()->findByAttributes(['referral_id' => $_GET['referral_id']]);
        if($uFind && $uFind->status == 9) {
            Yii::app()->user->setFlash('error', Yii::t('controllers', 'user_register_refError'));
            $this->redirect(Yii::app()->user->loginUrl);
        }
        
        
        $model = new UserRegisterForm;
        $model->ref = isset($_GET['referral_id']) ? $_GET['referral_id'] : '';
        if(isset($_GET['email'])) {
            $model->email = $_GET['email'];
            $model->username = explode('@', $_GET['email'])[0];
        }
        
        if($model->email) {
            if(Users::model()->email_select()->findByAttributes(['email' => $model->email])) {
                Yii::app()->user->setFlash('error', Yii::t('controllers', 'user_register_error_register_on_login'));
                $this->redirect(Yii::app()->user->loginUrl);
            }
        }
        
        if(Yii::app()->request->isAjaxRequest) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        
        if(Yii::app()->request->isPostRequest) {
            if(Yii::app()->request->getPost(get_class($model)) !== null) {
                $model->attributes = Yii::app()->request->getPost(get_class($model));
                
                $soucePassword = $model->password;
                $model->password = Yii::app()->getModule('user')->encrypting($model->password);
                $model->verifyPassword = Yii::app()->getModule('user')->encrypting($model->password);
                $model->referral_id = $model->generateReferralID();
                $model->activkey = Yii::app()->getModule('user')->encrypting(microtime().$model->password);
                $model->status_account = 1;
                $model->referral_level = 1;
                $model->status = Yii::app()->getModule('user')->activeAfterRegister ? $model::USTATUS_NOACTIVE : $model::USTATUS_ACTIVE;
                $model->create_at = date('Y-m-d H:i:s');
                   
                if($model->save(false)) {
                    UsersRelation::model()->inviteRelation($model->id, $model->email, $model->ref);
                    MHelper::sendEmail(false, false, $model->email, Yii::t('core', 'mail_register_subject'), 'register', ['login' => $model->username, 'pass' => $soucePassword, 'email' => $model->email, 'activkey' => $model->activkey, 'referral_id' => $model->referral_id, 'activeAfter' => Yii::app()->getModule('user')->activeAfterRegister ? true : false]);
                        
                    if(Yii::app()->getModule('user')->activeAfterRegister) {
                        Yii::app()->user->setFlash('success', Yii::t('controllers', 'user_register_success'));
                        $this->redirect(Yii::app()->user->loginUrl);
                    } else {
                        $identity = new UserIdentity($model->username, $soucePassword);
			$identity->authenticate();
			Yii::app()->user->login($identity, 0);
                        
                        Yii::app()->user->setFlash('success', Yii::t('controllers', 'user_register_success_withOutActive'));
                        $this->redirect(Yii::app()->getModule('user')->returnUrl);
                    }
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('controllers', 'user_register_error'));
                    $this->redirect(Yii::app()->user->loginUrl);
                }
            }
        }
        
        $this->render('//user/register', ['model' => $model]);
    }
}

