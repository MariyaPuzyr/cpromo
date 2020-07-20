<?php

class RnetworkController extends MController
{
    public function actionIndex()
    {
        $mainRelation = UsersRelation::model()->with(['rData'])->findAllByAttributes(['user_id' => Yii::app()->user->id]);
        $mainInvite = UsersInvite::model()->with(['user_ok:onlyRelation.users_head'])->findAllByAttributes(['user_id' => Yii::app()->user->id]);
        $sprStatuses = SprStatuses::model()->getListStatuses();
        $countInviteOut = [];
        $countInvite = [];
        
        if($mainInvite) {
            $countInvite = count($mainInvite);
            foreach($mainInvite as $invite) {
                if(!$invite->user_ok)
                    $countInviteOut[]++;
                
                if(!$invite->user_ok || ($invite->user_ok && $invite->user_ok->users_head->user_id != Yii::app()->user->id))
                    $mainInviteRes[] = $invite;
            }
            if($mainInviteRes)
                $inviteGrid = MHelper::getArrayProvider ($mainInviteRes, 5, ['invite_date', 'invite_email',]);
        }
        
        $fullbalance = 0;
        $fullcoins = 0;
        if($mainRelation) {
            $result = [];
            
            $levels = SprLevels::model()->findAll();
            $maxLevel = Yii::app()->user->model()->referral_level;
            foreach($levels as $level)
                $result['levels'][$level->id] = ['name' => $level->{'name_'.Yii::app()->language}, 'status' => $level->status];
            
            foreach ($mainRelation as $relation) {
                $result['refData'][$relation->level][] = $relation->rData;
                $fullbalance += $relation->rData->now_balance;
                $fullcoins += $relation->rData->now_coins;
            }
            
            $i = 1;
            foreach($result['refData'] as $key => $val) {
                if($i <= $maxLevel) {
                    $result['tabs'][] = [
                        'label' => $result['levels'][$i]['name'].Yii::t('controllers', 'rnetwork_index_lbl_countRefsOnLevel', ['#count' => count($val)]),
                        'content' => $this->renderPartial('_levelStat', ['dataProvider' => MHelper::getArrayProvider($val, 10, ['referral_id', 'email', 'username']), 'id' => 'tabs', 'sprStatuses' => $sprStatuses], true),
                        'active' => !$result['tabs'] ? true : false,
                    ];
                } else {
                    $result['tabsOut'][] = [
                            'label' => $result['levels'][$i]['name'].Yii::t('controllers', 'rnetwork_index_btn_needStatus', ['#status' => SprLevels::getStatusName($result['levels'][$i]['status'])]).Yii::t('controllers', 'rnetwork_index_lbl_countRefsOnLevel2', ['#count' => count($val)]),
                            'content' => $this->renderPartial('_levelStat', ['dataProvider' => MHelper::getArrayProvider($val, 10, ['referral_id', 'email', 'username']), 'id' => 'tabsOut', 'sprStatuses' => $sprStatuses], true),
                            'active' => !$result['tabsOut'] ? true : false
                        ];
                }
                $i++;
            }
        }
        
        $this->render('index', [
            'tabs' => $result['tabs'],
            'tabsOut' => $result['tabsOut'],
            'countInvite' => $countInvite ? $countInvite : 0,
            'countInviteOut' => count($countInviteOut),
            'inviteGrid' => $inviteGrid,
            
            'countRelations' => count($mainRelation) ? count($mainRelation) : 0,
            'countRelationsOut' => $result['allOut'] ? $result['allOut'] : 0,
            'fullbalance' => $fullbalance,
            'fullcoins' => $fullcoins,
            'mobileDetect' => Yii::app()->mobileDetect,
        ]);
    }
    
    public function actionInvite()
    {
        $model = new UsersInvite;
        
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($model)) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        
        if(Yii::app()->request->isPostRequest) {
            if(Yii::app()->request->getPost(get_class($model)) !== null) {
                $model->attributes = Yii::app()->request->getPost(get_class($model));
                if($model->save()) {
                    $mainData = Yii::app()->user->model();
                    MHelper::sendEmail($mainData->email, $mainData->firstname.' '.$mainData->referral_id, $model->invite_email, Yii::t('core', 'mail_invite_subject'), 'invite', ['referral_id' => $mainData->referral_id, 'referral_email' => $mainData->email, 'referral_name' => $mainData->firstname,'email' => $model->invite_email]);
                    Yii::app()->user->setFlash('success', Yii::t('controllers', 'rnetwork_inviteSuccess'));
                    $this->redirect($this->createUrl('/rnetwork'));
                }
            } else
                $this->redirect(str_replace(Yii::app()->request->getHostInfo(), '', Yii::app()->request->getUrlReferrer()));
        }
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;  
        $this->renderPartial('_referalInvite', ['model' => $model], false, true);
    }
    
    public function actionRepeatInvite($invite_email)
    {   
        $mainData = Yii::app()->user->model();
        MHelper::sendEmail($mainData->email, $mainData->firstname.' '.$mainData->referral_id, $invite_email, Yii::t('core', 'mail_invite_subject'), 'invite', ['referral_id' => $mainData->referral_id, 'referral_email' => $mainData->email, 'referral_name' => $mainData->firstname, 'email' => $invite_email]);
        echo CJSON::encode(['status' => 'success']);            
        Yii::app()->end();
    }
    
    public function actionMainRegister()
    {
        $model = new UserRegisterForm;
        $model->setScenario('mainRegister');
        
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($model)) {
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
                $model->create_uid = Yii::app()->user->id;
                
                if($model->save(false)) {
                    UsersRelation::model()->addUserRelation(Yii::app()->user->id, $model->id, 1);
                    UsersInvite::model()->addInviteFromMainReg(Yii::app()->user->id, $model->email, date('Y-m-d H:i:s'));
                    
                    $mainData = Yii::app()->user->model();
                    MHelper::sendEmail(false, false, $model->email, Yii::t('core', 'mail_mainRegister_subject'), 'mainRegister', ['login' => $model->username, 'pass' => $soucePassword, 'email' => $model->email, 'activkey' => $model->activkey, 'referral_id' => $model->referral_id, 'ref' => $mainData->referral_id, 'refName' => $mainData->firstname, 'refEmail' => $mainData->email, 'activeAfter' => Yii::app()->getModule('user')->activeAfterRegister ? true : false]);
                    Yii::app()->user->setFlash('success', Yii::t('controllers', 'rnetwork_mainRegister_success'));
                    $this->redirect($this->createUrl('/rnetwork'));
                }
            } else
                $this->redirect(str_replace(Yii::app()->request->getHostInfo(), '', Yii::app()->request->getUrlReferrer()));
        }
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;  
        $this->renderPartial('_referalRegister', ['model' => $model, 'error' => $error], false, true);
    }
    
    public function actionGetLevelInfo()
    {
        $model = new SprLevels;
        $this->renderPartial('_levelInfo', ['model' => $model], false, false);
    }
    
    public function actionGetStatusInfo()
    {
        $model = new SprStatuses;
        $this->renderPartial('_statusInfo', ['model' => $model], false, false);
    }
    
    public function listTree() {
        $firstUser = Yii::app()->user->id;
        $user = Yii::app()->user->model();
        $userInfo = "{$user->firstname} <span class='text-muted small'>({$user->email}, {$user->referral_id})</span>. ".Yii::t('controllers', 'rnetwork_index_lbl_userStat', ['#balance' => $user->now_balance, '#coins' => $user->now_coins, '#profit' => $user->now_profit]);
        $nodes = self::userNodes($firstUser);
        $data = [
            'text' => $userInfo,
            'children' => $nodes
        ];
        return [$data];
    }
    
    private static function userNodes($userId) {
        $nodes = [];
        $downlineUsers = UsersRelation::model()->with(['rData.statusAccount'])->findAll(['condition' => 'user_id=:user_id AND level=1', 'params' => [':user_id' => $userId], 'order' => 't.id ASC']);
        if(!empty($downlineUsers)) {
            foreach ($downlineUsers as $dnLn) {
                $user = $dnLn->rData;
                $nodes[] = [
                    'text' => "{$user->firstname} # {$user->statusAccount->{'name_'.Yii::app()->language}} <span class='text-muted small'>({$user->username}, {$user->email}, {$user->referral_id})</span>. ".Yii::t('controllers', 'rnetwork_index_lbl_userStat', ['#balance' => $user->now_balance, '#coins' => $user->now_coins, '#profit' => $user->now_profit]),
                    'children' => self::userNodes($user->id)
                ];
            }
        }
        return $nodes;
    }
}