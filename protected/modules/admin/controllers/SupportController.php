<?php

class SupportController extends MAdminController
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
    
    public function actionIndex($feedback_date = false, $type_category = false, $status = false, $number = false)
    {
        $model = new Feedback;
        $model->unsetAttributes();
        $model->with(['user']);
        $mCr = new CDbCriteria();
        if($number)
            $model->msg_number = $number;
        if($type_category)
            $model->msg_cat = $type_category;
        if($status)
            $model->msg_status = $status;
        if($feedback_date)
            $mCr->addCondition('DATE(create_at) BETWEEN "'.date('Y-m-d', strtotime(explode(' - ', $feedback_date)[0])).'" and "'.date('Y-m-d', strtotime(explode(' - ', $feedback_date)[1])).'"', 'AND');
        $model->setDbCriteria($mCr);
        $model->order_id_desc();
        
        $this->render('index', ['model' => $model, 'faqs' => SprFaq::model()->findAll()]);
    }
    
    public function actionViewMessage($id)
    {
        $model = Feedback::model()->with(['user'])->findByPk($id);
        $messages = FeedbackWork::model()->findAllByAttributes(['message_id' => $model->id]);
        
        if(Yii::app()->request->isPostRequest) {
            $fwork = new FeedbackWork;
            $fwork->create_at = date('Y-m-d H:i:s');
            $fwork->create_uid = Yii::app()->user->id;
            $fwork->answer = 1;
            
            if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($fwork)) {
                echo CActiveForm::validate($fwork);
                Yii::app()->end();
            }
            
            if(Yii::app()->request->getPost(get_class($fwork)) !== null) {
                $fwork->attributes = Yii::app()->request->getPost(get_class($fwork));
                if($fwork->save()) {
                    $model->update_at = date('Y-m-d H:i:s');
                    $model->update_uid = Yii::app()->user->id;
                    $model->save();      
                    
                    if($model->user->subscribe_admin)
                        MHelper::sendEmail(false, false, $model->user->email, Yii::t('core', 'mail_feedback_newMessage_subject'), 'messageFeedback', ['msg_number' => $model->msg_number, 'text' => $fwork->text]);
                    
                    $this->redirect($this->createUrl('/admin/support/viewMessage', ['id' => $model->id]));
                }
            }
        }
        
        $this->render('view', ['model' => $model, 'messages' => $messages]);
    }
    
    public function actionWorkStatus($id, $status)
    {
        $model = Feedback::model()->with(['user'])->findByPk($id);
        $model->msg_status = $status;
        $model->update_at = date('Y-m-d H:i:s');
        $model->update_uid = Yii::app()->user->id;
        $model->save();
        
        if(Yii::app()->user->model()->subscribe_admin)
            MHelper::sendEmail(false, false, $model->user->email, Yii::t('core', 'mail_feedback_changeStatus_subject'), 'statusFeedback', ['msg_number' => $model->msg_number, 'msg_status' => $model->statusMessage($model->msg_status)]);
        
        $this->redirect($this->createUrl('/admin/support/viewMessage', ['id' => $id]));
    }
    
    public function actionWorkFaq($type, $id = false)
    {
        $model = ($type == 'add') ? new SprFaq : SprFaq::model()->findByPk($id);
        
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($model)) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        
        if(Yii::app()->request->getPost(get_class($model)) !== null) {
            $model->attributes = Yii::app()->request->getPost(get_class($model));
            $model->save();
        }
        
        $this->render('workFaq', ['model' => $model]);
    }
    
    public function deleteFaq($id)
    {
        SprFaq::model()->deleteByPk($id);
        $this->redirect($this->createUrl('/admin/support'));
    }
}