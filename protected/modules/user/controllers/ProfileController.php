<?php

class ProfileController extends MController
{
    public function filters()
    {
    	return [
            'rights',
            [
                'application.filters.YXssFilter',
                'clean'   => '*',
                'tags'    => 'strict',
                'actions' => 'index, getShortInfo, changePassword, confirmEmail, setFinanceAcc'
            ]
        ];
    }
    
    public function allowedActions()
    {
        return 'confirmEmail, changeEmail';
    }
    
    public function actionIndex()
    {
        $model = Users::model()->forProfile()->findByPk(Yii::app()->user->id);
        $model->birthday = $model->birthday ? date('d.m.Y', strtotime($model->birthday)) : false;
        if(!file_exists(Yii::getPathOfAlias('webroot.uploads').'/user_photo/'.$model->photo))
            $model->photo = '';
        
        $historyLogin = new UserHistoryLogin;
        $historyLogin->unsetAttributes();
        $historyLogin->user_id = Yii::app()->user->id;
        $historyLogin->order_id();
        
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($model)) {
            $validate = CActiveForm::validate($model);
            if(!$validate || $validate == '[]') {
                if($_POST[get_class($model)]['birthday'])
                    $model->birthday = date('Y-m-d', strtotime($_POST[get_class($model)]['birthday']));
                
                $model->save();
            } else
                echo $validate;
            
            Yii::app()->end();
        }
        
        $this->render('//user/profile', [
            'model' => $model,
            'historyLogin' => $historyLogin
        ]);
    }
    
    public function actionGetShortInfo($referral_id)
    {
        $rID = Users::model()->forProfile()->findByAttributes(['referral_id' => $referral_id]);
        $by_ref = UsersRelation::model()->with(['users_to'])->findAllByAttributes(['to_user' => $rID->id]);
        
        if(Yii::app()->user->getIsSuperuser())
            $main_ref = true;
        else
            $main_ref = UsersRelation::model()->findByAttributes(['user_id' => Yii::app()->user->id, 'to_user' => $rID->id]);
        
        if($main_ref) {
            if($by_ref) {
                foreach($by_ref as $ref) {
                    if(Yii::app()->user->id != $ref->user_id) {
                        $bR[] = [
                            'level' => $ref->level,
                            'referral_id' => $ref->users_to->referral_id
                        ];
                    }
                }
            }
        }
        
        $this->renderPartial('//user/_getShortInfo', [
            'model' => $main_ref ? $rID : false,
            'by_ref' => $bR
        ], false, false);
    }
    
    public function actionChangePassword($return = true, $post = false)
    {
        $model = new UserChangePasswordForm();
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($model)) {
            $validate = CActiveForm::validate($model);
            if($validate == '[]' || !$validate) {
                $user = Users::model()->findByPk(Yii::app()->user->id);
                $user->password = Yii::app()->getModule('user')->encrypting($model->password);
                $user->cpassword = 0;
                if($user->save(false))
                    echo CJSON::encode(['message' => Yii::t('models', 'user_cpassword_lbl_success')]);
            } else
                echo $validate;
            
            Yii::app()->end();
        }
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;
        $this->renderPartial('//user/_changePassword', ['model' => $model], false, true);
    }
    
    public function actionSetFinanceAcc($close = false)
    {
        $model = Users::model()->forProfile()->findByPk(Yii::app()->user->id);
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === 'finACC') {
            if(!$model->finance_payeer && $_POST['User']['finance_payeer'])
                $model->finance_payeer = $_POST['User']['finance_payeer'];
            
            if(!$model->finance_prfmoney && $_POST['User']['finance_prfmoney'])
                $model->finance_prfmoney = $_POST['User']['finance_prfmoney'];
            
            if(!$model->finance_usdtrc && $_POST['User']['finance_usdtrc'])
                $model->finance_usdtrc = $_POST['User']['finance_usdtrc'];
            
            $validate = CActiveForm::validate($model);
            if($validate == '[]' || !$validate) {
                $model->save(false);
                echo CJSON::encode(['status' => 'success']);
            } else
                echo $validate;
            
            Yii::app()->end();
        }
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;
        $this->renderPartial('//user/_fin_acc', ['model' => $model], false, true);
    }
    
    public function actionChangeEmail($change = false)
    {
        if(!$change) {
            $model = new UserChangeEmailForm();
            if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($model)) {
                $validate = CActiveForm::validate($model);
                if($validate == '[]' || !$validate) {
                    $user = Users::model()->email_select()->findByPk(Yii::app()->user->id);
                    MHelper::sendEmail(false, false, $user->email, Yii::t('core', 'mail_changeEmail_subject'), 'emailChange', ['old_email' => $user->email, 'new_email' => $model->email,'key' => $user->activkey]);
                    echo CJSON::encode(['message' => Yii::t('models', 'user_cemail_lbl_success')]);
                } else
                    echo $validate;
            
                Yii::app()->end();
            }
        
            Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
            Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
            Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;
            $this->renderPartial('//user/_changeEmail', ['model' => $model], false, true);
        } else {
            if(isset($_GET['old_email'])&& isset($_GET['new_email']) && isset($_GET['key'])) {
                $user = Users::model()->email_select()->findByAttributes(['email' => $_GET['old_email']]);
                if($user && $user->activkey == $_GET['key']){
                    $user->email = $_GET['new_email'];
                    $user->activkey = Yii::app()->getModule('user')->encrypting(microtime().$_GET['new_email']);
                    $user->save(false);
                    Yii::app()->user->setFlash('success', Yii::t('core', 'noty_emailChangeMessage_success'));
                    $this->redirect(Yii::app()->getModule('user')->returnUrl);
                }
            } else{
                Yii::app()->user->setFlash('error', Yii::t('core', 'noty_emailChangeMessage_error'));
                $this->redirect(Yii::app()->getModule('user')->returnUrl);
            }
        }
    }
    
    public function actionConfirmEmail()
    {
        if(isset($_GET['email']) && isset($_GET['activkey'])) {
            $user = Users::model()->notsafe()->findByAttributes(['email' => $_GET['email']]);
            if($user->activkey == $_GET['activkey']) {
                $user->emailConfirm = Users::STEMAIL_CONF;
                $user->activkey = Yii::app()->getModule('user')->encrypting(microtime().$_GET['email']);
                if($user->save()) {
                    Yii::app()->user->setFlash('success', Yii::t('core', 'noty_emailConfirmMessage_confirmSuccess'));
                } else
                    Yii::app()->user->setFlash('error', Yii::t('core', 'noty_emailConfirmMessage_confirmError'));
            } else
                Yii::app()->user->setFlash('error', Yii::t('core', 'noty_emailConfirmMessage_confirmError'));
            
            $this->redirect(Yii::app()->getModule('user')->returnUrl);
        } else {
            $model = Users::model()->notsafe()->findByPk(Yii::app()->user->id);
            if(MHelper::sendEmail(false, false, $model->email, Yii::t('core', 'mail_confirmEmail_subject'), 'emailConfirm', ['email' => $model->email, 'activkey' => $model->activkey]))
                echo CJSON::encode([
                    'status' => 'success',
                    'message' => Yii::t('core', 'noty_emailConfirmMessage_sendSuccess'),
                    'html' => '<span class="badge badge-info"><span class="icon-check" style="vertical-align: revert;"></span>&nbsp;&nbsp;'.Yii::t('core', 'lbl_emailConfirmMessage_sendSuccess_short').'</span></span>'
                ]);
            else
                echo CJSON::encode([
                'status' => 'error',
                    'message' => Yii::t('core', 'noty_emailConfirmMessage_sendError'),
                    'html' => '<span class="badge badge-danger"><span class="icon-close" style="vertical-align: revert;"></span>&nbsp;&nbsp;'.Yii::t('core', 'lbl_emailConfirmMessage_sendError_short').'</span></span>'
                ]);
            
            Yii::app()->end();
        }
    }
    
    public function actionUploadPhoto()
    {
        $model = Yii::app()->user->model();
                
        $file = CUploadedFile::getInstance($model, 'loadFile');
        $extension = strtolower($file->extensionName);
        $realname = $file->name;
        $filename = MHelper::getRandomFileName(Yii::getPathOfAlias('webroot.uploads.user_photo'), $extension);
        $basename = $filename.'.'.$extension;
            
        $model->loadFile = $basename;
        $valid = $model->validate(['loadFile']);
        
        if($valid){
            if($file->saveAs(Yii::getPathOfAlias('webroot.uploads.user_photo').DIRECTORY_SEPARATOR.$basename)){
                $thumb = MHelper::getRandomFileName(Yii::getPathOfAlias('webroot.uploads.user_photo'), $extension);
                $thumbName = $thumb.'.'.$extension;
                $ih = new MImageHandler();
                Yii::app()->ih
                        ->load(Yii::getPathOfAlias('webroot.uploads.user_photo').DIRECTORY_SEPARATOR.$basename)
                        ->thumb('200', false)
                        ->save(Yii::getPathOfAlias('webroot.uploads.user_photo').DIRECTORY_SEPARATOR.$thumbName);
                
                unlink(Yii::getPathOfAlias('webroot.uploads.user_photo').DIRECTORY_SEPARATOR.$basename);
                if($model->photo)
                    if(file_exists(Yii::getPathOfAlias('webroot.uploads.user_photo').DIRECTORY_SEPARATOR.$model->photo))
                        unlink(Yii::getPathOfAlias('webroot.uploads.user_photo').DIRECTORY_SEPARATOR.$model->photo);
                    
                $model->photo = $thumbName;
                $model->save(false);
                
                
            }
            echo CJSON::encode(['status' => 'success']);
            Yii::app()->end();
        }
    }
    
    public function actionGoogleAuth()
    {
        $model = Yii::app()->user->model();
        
        if(!$model->googleAuth) {
            Yii::import('vendor.googleauth.GoogleAuthenticator');
            include_once Yii::getPathOfAlias('application.vendor.phpqrcode').'/qrlib.php';
            
            $ga = new GoogleAuthenticator();
            
            if(Yii::app()->request->isPostRequest) {
                $code = $ga->getCode($model->googleAuth_key);
                if($code == $_POST['code']) {
                    $model->googleAuth = 1;
                    $model->save();
                }
                
                echo CJSON::encode(['status' => ($code != $_POST['code'] || $_POST['code'] == null || !$_POST['code']) ? 'error' : 'success']);
                Yii::app()->end();
            } else {
                $model->googleAuth_key = $ga->generateSecret();
                $model->save(false);
                $url =  sprintf("otpauth://totp/%s?secret=%s", $this->createAbsoluteUrl('/'), $model->googleAuth_key);
                QRcode::png($url, Yii::getPathOfAlias('application.runtime').'/qr_google_temp.png', 'L', '4', 2);
                $file = base64_encode(file_get_contents(Yii::getPathOfAlias('application.runtime').'/qr_google_temp.png'));
            
                Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
                Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
                Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;
                Yii::app()->clientscript->scriptMap['jquery.ba-bbq.js'] = false;
                Yii::app()->clientscript->scriptMap['jquery.yiiactiveform.js'] = false;
                $this->renderPartial('//user/_showGoogleKey', ['img' => 'data:image/png;base64,'.$file, 'key' => $model->googleAuth_key], false, true);
            }
        }
    }
    
    public function actionWarning($not_remind = false)
    {
        if($not_remind){
            Users::model()->forProfile()->updateByPk(Yii::app()->user->id, ['readwarning' => 0]);
            Yii::app()->user->setFlash('success', Yii::t('controllers', 'you_dont_remind'));
            $this->redirect(Yii::app()->getModule('user')->returnUrl);
        }
        
        $this->renderPartial('//user/_warning', [], false, false);
    }
}