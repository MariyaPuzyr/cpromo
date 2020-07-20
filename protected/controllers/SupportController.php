<?php

class SupportController extends MController
{
    public function actionIndex()
    {
        $model = new Feedback;
        $model->unsetAttributes();
        $model->user_id = Yii::app()->user->id;
        $model->user_email = Yii::app()->user->model()->email;
        $model->order_id_desc();
        
        $faq = SprFaq::model()->findAll();
        
        if(Yii::app()->request->isAjaxRequest){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        
        if(Yii::app()->request->isPostRequest && Yii::app()->request->getPost(get_class($model))) {
            $model->attributes = Yii::app()->request->getPost(get_class($model));
            if($model->save()){
                MHelper::sendEmail(false, false, $model->user_email, Yii::t('core', 'mail_feedback_newMessage_subject'), 'newFeedback', ['number' => $model->msg_number, 'text' => $model->msg_text, 'date' => date('d.m.Y H:i:s', strtotime($model->create_at))]);
                Yii::app()->user->setFlash('success', Yii::t('controllers', 'support_index_lbl_messageAddSuccess'));
                $this->refresh();    
            }
        }
        
        $this->render('index', ['model' => $model, 'faq' => $faq]);
    }
    
    public function actionGetFeedbackMessageInfo($id)
    {
        $model = Feedback::model()->findByAttributes(['msg_number' => $id, 'user_id' => Yii::app()->user->id]);
        $work = $this->getFeedbackWorkMessages($model->id);
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;  
        $this->renderPartial('_viewMessage', ['model' => $model, 'work' => $work], false, true);
    }
    
    public function actionAnswerToMessage($message)
    {
        $model = new FeedbackWork;
        $model->message_id = $message;
        
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($model)) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        
        if(Yii::app()->request->isPostRequest) {
            if(Yii::app()->request->getPost(get_class($model)) !== null) {
                $model->attributes = Yii::app()->request->getPost(get_class($model));
                if($model->save()) {
                    Yii::app()->user->setFlash('success', Yii::t('controllers', 'feedback_messageAnswerSuccess'));
                    $this->redirect('/support');
                }
            } else
                $this->redirect('/support');
        }
    }
    
    public function actionCancelFeedback($id)
    {
        $model = Feedback::model()->findByAttributes(['id' => $id, 'user_id' => Yii::app()->user->id]);
        $model->msg_status = $model::MSTATUS_CANC;
        echo CJSON::encode(['status' => $model->save() ? 'success' : $model->getErrors()]);
        Yii::app()->end();
    }
    
    private function getFeedbackWorkMessages($message)
    {
        return FeedbackWork::model()->findAllByAttributes(['message_id' => $message]);
    }
}