<?php

class OnAfterSaveBehavior extends CActiveRecordBehavior{
    function afterSave($event){
        if(get_class(Yii::app())!='CConsoleApplication' || (get_class(Yii::app())!='CConsoleApplication')) {
            $assignmentTable = Yii::app()->getAuthManager()->assignmentTable;
            $attr = $event->sender->getAttributes();
            
            if($event->sender->getIsNewRecord()) {
                $defRole = 'referral';
                Yii::app()->db->createCommand("INSERT INTO {$assignmentTable} (`itemname`,`userid`,`bizrule`,`data`) VALUES ('{$defRole}','{$attr['id']}',NULL,'N;')")->execute();
                Yii::app()->db->createCommand("INSERT INTO `users_status` (`user_id`,`status_id`,`operation_number`,`operation_summ`,`operation_date`) VALUES ('{$attr['id']}',1,'START".rand(00000,99999)."',0,'".date('Y-m-d H:i:s')."')")->execute();
            }
        }
    }
}