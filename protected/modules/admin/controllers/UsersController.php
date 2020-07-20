<?php

class UsersController extends MAdminController
{
    public function actionIndex($referral_id = null, $firstname = null, $username = null, $email = null, $user_status = false, $status_account = false)
    {
        $model = new Users;
        $model->unsetAttributes();
        $model->order_id_desc_find();
        $model->with(['statusAccount', 'rCoins']);
        if($referral_id)
            $model->referral_id = $referral_id;
        if($username)
            $model->username = $username;
        if($email)
            $model->email = $email;
        if(isset($user_status))
            $model->status = $user_status;
        if($firstname)
            $model->firstname = $firstname;
        if($status_account)
            $model->status_account = $status_account;
        
        
        $refs = Users::model()->with(['referrals'])->findAll();
        if($refs){
            foreach($refs as $ref){
                if($ref->referrals){
                    foreach($ref->referrals as $refData){
                        if($refData->level == 1){
                            $uRefs[$ref->id]++;
                        }
                    }
                }
            }
        }
        
        $relations = UsersRelation::model()->with(['users_to'])->findAllByAttributes(['level' => 1]);
        foreach($relations as $rel){
            $rRel[$rel->to_user] = $rel->users_to;
        }
        
        $uPays = count(UsersPays::model()->findAll(['condition' => 'operation_status = '.UsersPays::PSTATUS_COMPL, 'group' => 'user_id']));        
        $uCBuys = count(CoinsMarket::model()->findAll(['condition' => 'operation_type = '.CoinsMarket::TYPE_BUY, 'group' => 'user_id']));
        
        $this->render('index', [
            'model' => $model,
            'uRefs' => $uRefs,
            'rRel' => $rRel,
            'uPays' => $uPays,
            'uCBuys' => $uCBuys
        ]);
    }
    
    public function actionViewNetwork($user_id)
    {
        $userData = Users::model()->notsafe()->findByPk($user_id);
        
        $mainRelation = UsersRelation::model()->with(['rData'])->findAllByAttributes(['user_id' => $user_id]);
        if($mainRelation) {
            $result = [];
            
            $levels = SprLevels::model()->findAll();
            $maxLevel = $userData->referral_level;
            foreach($levels as $level)
                $result['levels'][$level->id] = ['name' => $level->{'name_'.Yii::app()->language}, 'status' => $level->status];
            
            foreach ($mainRelation as $relation)
                $result['refData'][$relation->level][] = $relation->rData;
            
            foreach($result['refData'] as $key => $val) {
                for($i = $maxLevel; $i != 0; $i--) {
                    if($i == $key) {
                        $result['tabs'][] = [
                            'label' => $result['levels'][$i]['name'].Yii::t('controllers', 'rnetwork_index_lbl_countRefsOnLevel', ['#count' => count($val)]),
                            'content' => $this->renderPartial('_levelStat', ['dataProvider' => MHelper::getArrayProvider($val, 10, ['referral_id', 'email', 'username']), 'id' => 'tabs', 'sprStatuses' => $sprStatuses], true),
                            'active' => !$result['tabs'] ? true : false,
                        ];
                    }
                }
                if(($key > $maxLevel)) {
                    foreach($val as $k) {
                        $tabsOuts[$key][] = $k;
                        if($k->balance > 0)
                            $balanceOut += $k->balance;
                    }
                }
                
                if($tabsOuts) {
                    foreach($tabsOuts as $key => $val) {
                        $result['tabsOut'][] = [
                            'label' => $result['levels'][$key]['name'].Yii::t('controllers', 'rnetwork_index_btn_needStatus', ['#status' => SprLevels::getStatusName($result['levels'][$key]['status'])]).Yii::t('controllers', 'rnetwork_index_lbl_countRefsOnLevel2', ['#count' => count($val)]),
                            'content' => $this->renderPartial('_levelStat', ['dataProvider' => MHelper::getArrayProvider($val, 10, ['referral_id', 'email', 'username']), 'id' => 'tabsOut', 'sprStatuses' => $sprStatuses], true),
                            'active' => !$result['tabsOut'] ? true : false
                        ];
                    }
                }
            }
        }
        
        $this->render('view_network', [
            'tabs' => $result['tabs'],
            'tabsOut' => $result['tabsOut'],
            'model' => $userData
        ]);
    }
    
    public function actionEdit($id)
    {
        $model = Users::model()->findByPk($id);
        $model->birthday = $model->birthday ? date('d.m.Y', strtotime($model->birthday)) : false;
        if(!file_exists(Yii::getPathOfAlias('webroot.uploads').'/user_photo/'.$model->photo))
            $model->photo = '';
        
        $history = UserHistoryLogin::model()->order_id()->findAllByAttributes(['user_id' => $id]);
        $dataHistory = MHelper::getArrayProvider($history, 5, ['login_time', 'login_ip', 'login_client']);
        
        $pays = UsersPays::model()->order_id_desc()->findAllByAttributes(['user_id' => $id]);
        $dataPays = !$pays ? [] : MHelper::getArrayProvider($pays, 10, ['operation_date', 'operation_summ', 'operation_status']);
        
        $outs = UsersOuts::model()->order_id_desc()->findAllByAttributes(['user_id' => $id]);
        $dataOuts = !$outs ? [] : MHelper::getArrayProvider($outs, 10, ['operation_date', 'operation_summ', 'operation_status', 'update_at']);
        
        $profits = UsersProfits::model()->order_id_desc()->findAllByAttributes(['user_id' => $id]);
        $dataProfits = !$profits ? [] : MHelper::getArrayProvider($profits, 10, ['operation_date', 'operation_summ', 'profit_percent', 'from_user']);
        
        $statuses = UsersStatus::model()->findAllByAttributes(['user_id' => $id]);
        $dataStatus = !$statuses ? [] : MHelper::getArrayProvider($statuses, 10);
        
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
        
        $this->render('edit', [
            'model' => $model,
            'dataHistory' => $dataHistory,
            'dataPays' => $dataPays,
            'dataOuts' => $dataOuts,
            'dataProfits' => $dataProfits,
            'dataStatus' => $dataStatus
        ]);
    }
    
    public function actionAddVirtualPay($user_id = null)
    {
        $model = new UsersPays;
        $model->setScenario('addVirtualPay');
        
        if(Yii::app()->request->isAjaxRequest) {
            $validate = CActiveForm::validate($model);
            if(!$validate || $validate == '[]') {
                $model->user_id = $user_id;
                $model->operation_number = 'VIRTUAL'.rand(0000,9999);
                $model->operation_system = $model::FIN_INNER;
                $model->operation_status = $model::PSTATUS_COMPL;
                $model->save(false);
                echo CJSON::encode(['status' => 'success']);
            } else
                echo $validate;
            
            Yii::app()->end();
        }
    }
    
    public function actionAddVirtualOut($user_id = null)
    {
        $model = new UsersOuts();
        $model->setScenario('addVirtualOut');
        
        if(Yii::app()->request->isAjaxRequest) {
            $model->operation_system = $model::FIN_INNER;
            $model->user_id = $user_id;
            $validate = CActiveForm::validate($model);
            if(!$validate || $validate == '[]') {
                $model->user_id = $user_id;
                $model->operation_number = 'VIRTUAL'.rand(0000,9999);
                $model->operation_system = $model::FIN_INNER;
                $model->operation_status = $model::OSTATUS_COMPL;
                $model->operation_maxSumm = Yii::app()->getModule('user')->getBalanceNow($user_id)->balance;
                $model->update_at = date('Y-m-d H:i:s');
                $model->update_uid = Yii::app()->user->id;
                $model->save(false);
                echo CJSON::encode(['status' => 'success']);
            } else
                echo $validate;
            
            Yii::app()->end();
        }
    }
    
    public function actionSetAccountStatus($user_id)
    {
        $model = Users::model()->findByPk($user_id);
        $model->status_account = Yii::app()->request->getParam('status_account');
        $model->referral_level = SprStatuses::model()->findByPk(Yii::app()->request->getParam('status_account'))->max_levels;
        $model->save(false);
        
        $uStatus = new UsersStatus;
        $uStatus->setScenario('SetVirtualStatus');
        $uStatus->user_id = $user_id;
        $uStatus->status_id = $model->status_account;
        $uStatus->operation_number = 'SVIRT'.rand(000000,999999);
        $uStatus->operation_summ = 0;
        $uStatus->operation_date = date('Y-m-d H:i:s');
        $uStatus->save();

        echo CJSON::encode(['status' => 'success']);
        Yii::app()->end();
    }
    
    
    public function actionConfirmEmail($user_id)
    {
        $model = Users::model()->findByPk($user_id);
        $model->emailConfirm = $model::STEMAIL_CONF;
        $model->save(false);
        
        Yii::app()->end();
    }
    
    public function actionUploadPhoto($id)
    {
        $model = Users::model()->notsafe()->findByPk($id);
                
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
                    unlink(Yii::getPathOfAlias('webroot.uploads.user_photo').DIRECTORY_SEPARATOR.$model->photo);
                    
                $model->photo = $thumbName;
                $model->save();
            }
            echo CJSON::encode(['status' => 'success']);
            Yii::app()->end();
        }
    }
    
    public function actionChangePassword($id)
    {
        $model = new UserChangePasswordForm();
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($model)) {
            $validate = CActiveForm::validate($model);
            if($validate == '[]' || !$validate) {
                $user = Users::model()->findByPk($id);
                $user->password = Yii::app()->getModule('user')->encrypting($model->password);
                $user->cpassword = 1;
                if($user->save(false))
                    echo CJSON::encode(['message' => Yii::t('models', 'user_cpassword_lbl_success')]);
            } else
                echo $validate;
            
            Yii::app()->end();
        }
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;
        $this->renderPartial('//user/_changePassword', ['model' => $model, 'id' => $id], false, true);
    }
    
    public function actionBlockUser($id)
    {
        $model = Users::model()->notsafe()->findByPk($id);
        $model->status = $model::USTATUS_BANNED;
        $model->save(false);
        
        $this->redirect($this->createUrl('edit', ['id' => $id]));
    }
    
    public function actionUnBlockUser($id)
    {
        $model = Users::model()->notsafe()->findByPk($id);
        $model->status = $model::USTATUS_ACTIVE;
        $model->save(false);
        
        $this->redirect($this->createUrl('edit', ['id' => $id]));
    }
    
    public function actionDeleteFromProfile($id)
    {
        $model = Users::model()->notsafe()->findByPk($id);
        $model->delete();
        
        Yii::app()->user->setFlash('success', Admin::t('controllers', 'user_deleteSuccess'));
        $this->redirect('/admin/users');
    }
    
    public function actionDelete($id)
    {
        $model = Users::model()->findByAttributes(['id' => $id]);
        
        if($model->delete()) {
            UsersInvite::model()->deleteAllByAttributes(['invite_email' => $model->email]);
            echo CJSON::encode(['status' => 'success']);
        } else {
            echo CJSON::encode(['status' => 'error']);
        }
        
        Yii::app()->end();
    }
    
    public function actionOffGoogle($id)
    {
        $model = Users::model()->findByPk($id);
        if ($model) {
            $model->googleAuth = 0;
            if ($model->save())
                echo CJSON::encode(['status' => 'success']);
        }
        
        Yii::app()->end();
    }
}

