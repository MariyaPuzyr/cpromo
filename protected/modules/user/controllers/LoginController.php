<?php

class LoginController extends MController
{
    public $layout = '//layouts/guest';
    public $defaultAction = 'login';
    
    public function allowedActions()
    {
        return 'login, captcha, checkVerify';
    }
    
    public function actions()
    {
	return [
            'captcha' => [
		'class' => 'ExtCaptcha',
                'testLimit' => 3,
		'backColor' => 0xFFFFFF,
                'foreColor' => 0x0063BF,
            ],
	];
    }
    
    public function actionLogin($google = false)
    {
        if(Yii::app()->user->isGuest) {

            $model = new UserLoginForm;
            Yii::app()->session->remove('udata');
            
            if(Yii::app()->request->isAjaxRequest) {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        
            if(Yii::app()->request->isPostRequest) {
                if(Yii::app()->request->getPost(get_class($model)) !== null) {
                    $model->attributes = Yii::app()->request->getPost(get_class($model));
                    if(strpos($model->username, "@"))
                        $user = Users::model()->notsafe()->findByAttributes(['email' => $model->username]);
                    else 
                        $user = Users::model()->notsafe()->findByAttributes(['username' => $model->username]);
                   
                    /*if($user) {
                        if(!$user->googleAuth) {
                            Yii::import('vendor.googleauth.GoogleAuthenticator');
                            $ga = new GoogleAuthenticator();
                            $code = $ga->getCode('OAYWQBBQFTMWF2I5');
                            
                            $codeSave = new UsersVerify;
                            $codeSave->email = $user->email;
                            $codeSave->code = $code;
                            $codeSave->verify_date = date('Y-m-d H:i:s');
                            $codeSave->save(false);
                            
                            MHelper::sendEmail(false, false, $user->email, Yii::t('core', 'mail_verifyLoginEmail_subject'), 'verifyLogin', ['code' => $code]);
                        }
                        
                        Yii::app()->session->add('udata', base64_encode(serialize(['google' => $user->googleAuth ? 'true' : '', 'login' => $model->username, 'password' => Yii::app()->getModule('user')->encrypting($model->password)])));
                        $this->redirect('/login/checkVerify');
                    }*/
                    
                    $model->authenticate();
                    $this->lastVisit();
                    $this->setDefaultLang();
                    Yii::app()->session->remove('udata');
                    $this->redirect(Yii::app()->getModule('user')->returnUrl);
                }
            }
            $this->render('//user/login', ['model' => $model]);
        } else
            $this->redirect(Yii::app()->getModule('user')->returnUrl);
    }
    
    public function actionCheckGoogle()
    {
        if(Yii::app()->user->isGuest){
            $data = unserialize(base64_decode(Yii::app()->session->get('udata')));
            $model = new UserLoginForm;
            $model->setScenario('checkGoogle');
            $model->username = $data['login'];
            $model->password = $data['password'];
            
            if(Yii::app()->request->isAjaxRequest) {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            
            if(Yii::app()->request->isPostRequest) {
                if(Yii::app()->request->getPost(get_class($model)) !== null) {
                    $model->attributes = Yii::app()->request->getPost(get_class($model));
                    $model->authenticate();
                    if(Yii::app()->user->model()->subscribe_login)
                        $this->sendLoginNotify();
                    $this->lastVisit();
                    $this->setDefaultLang();
                    Yii::app()->session->remove('udata');
                    $this->redirect(Yii::app()->getModule('user')->returnUrl);   
                }
            }
            
            $this->render('//user/checkVerify', ['model' => $model, 'google' => $data['google']]);
        } else
            $this->redirect(Yii::app()->getModule('user')->returnUrl);   
    }
    
    public function actionCheckVerify()
    {
        if(Yii::app()->user->isGuest){
            $data = unserialize(base64_decode(Yii::app()->session->get('udata')));
            $model = new UserLoginForm;
            $model->setScenario('checkVerify');
            $model->username = $data['login'];
            $model->password = $data['password'];
            
            if(Yii::app()->request->isAjaxRequest) {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            
            if(Yii::app()->request->isPostRequest) {
                if(Yii::app()->request->getPost(get_class($model)) !== null) {
                    $model->attributes = Yii::app()->request->getPost(get_class($model));
                    $model->authenticate();
                    if(Yii::app()->user->model()->subscribe_login)
                        $this->sendLoginNotify();
                    $this->lastVisit();
                    $this->setDefaultLang();
                    Yii::app()->session->remove('udata');
                    $this->redirect(Yii::app()->getModule('user')->returnUrl);   
                }
            }
            
            $this->render('//user/checkVerify', ['model' => $model, 'google' => $data['google']]);
        } else
            $this->redirect(Yii::app()->getModule('user')->returnUrl);   
    }
    
    private function sendLoginNotify()
    {
        MHelper::sendEmail(false, false, Yii::app()->user->model()->email, Yii::t('core', 'mail_loginNoty_subject'), 'loginNotify', ['time' => date('d.m.Y H:i:s')]);
        return true;
    }
    
    private static function lastVisit()
    {
	$model = new UserHistoryLogin();
        $model->user_id = Yii::app()->user->id;
        $model->login_time = date('Y-m-d H:i:s');
        $model->login_ip = Yii::app()->request->getUserHostAddress();
        $model->login_client = Yii::app()->request->getUserAgent();
        $model->save();
    }
    
    private static function setDefaultLang()
    {
        $defaultLang = Yii::app()->user->model()->language;
        if($defaultLang && Yii::app()->language != $defaultLang) {
            $cookie = new CHttpCookie('language', $defaultLang);
            $cookie->expire = time() + (60*60*24*365); #1 год
            Yii::app()->request->cookies['language'] = $cookie; 
        }
    }
}

