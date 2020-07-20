<?php

class MHelper
{
    public static function formatCurrency($summ)
    {
        return number_format($summ, 4, '.', ' ');
    }
    
    public static function formBeautyDate($date)
    {
        return date('d.m', strtotime($date)).' <span class="text-muted font-small">'.date('H:i', strtotime($date)).'</span>';
    }
    
    public static function diffDate($date, $date2)
    {
        return (strtotime($date) - strtotime($date2)) / 86400;
    }
    
    public static function formSumm($model, $column_name)
    {
        foreach($model as $key) {
            $res += $key->{$column_name};
        }
        
        return $res;
    }
    
    public static function sendEmail($fromEmail = false, $fromName = false, $toEmail, $subject = false, $bodyName, $ext = [])
    {
        $file = Yii::getPathOfAlias("application.emails").DIRECTORY_SEPARATOR.$bodyName.'.php';
        if(file_exists($file)) {
            $mailer = Yii::app()->mail;
            $mailer->Mailer = YII_DEBUG ? 'mail' : 'sendmail';
            $mailer->From = $fromEmail ? $fromEmail : Yii::app()->params->replyToAddress;
            $mailer->FromName = $fromName ? $fromName : Yii::app()->name;
            $mailer->Subject = $subject ? $subject : Yii::t('core', 'mail_defSubject');
            $mailer->Body = Yii::app()->controller->renderFile($file, ['subject' => $subject, 'ext' => $ext], true);;
            $mailer->AddAddress($toEmail);
            $mailer->ClearReplyTos();
            $mailer->addReplyTo(Yii::app()->params->replyToAddress);
            $mailer->isHtml(true);
            
            return $mailer->Send() ? true : false;
            $mailer->ClearAddresses();
        } else
            return false;
    }
    
    public static function getRandomFileName($path, $extension='')
    {
        $extension = $extension ? '.'.$extension : '';
        $path = $path ? $path.'/':'';
        do {
            $name = md5(microtime() . rand(0, 9999));
            $file = $path.$name.$extension;
        } while (file_exists($file));
        return $name;
    }
    
    public static function getArrayProvider($model, $pageSize = false, $sort_attrs = [])
    {
        return new CArrayDataProvider($model, ['pagination' => ['pageSize' => $pageSize ? $pageSize : 25], 'sort' => ['attributes' => $sort_attrs]]);
    }
    
    public static function getOperationNumber($model, $letter)
    {
        $numbers = $model::model()->findAll(['select' => 'operation_number']);
        if($numbers) {
            foreach($numbers as $number)
                $rNums[] = $number->operation_number;
                
            $oNumber = self::generateOperationNumber($rNums, $letter);
        } else
            $oNumber = self::generateOperationNumber([], $letter);
        
        return $oNumber;
    }
    
    private static function generateOperationNumber($nums, $letter)
    {
        if(!in_array($num = $letter.rand(0000000000, 9999999999), $nums))
            return $num;
        else
            self::generateOperationNumber($nums, $letter);
    }
    
    
    public static function getOperationNumberForProcent($model, $letter)
    {
        $numbers = $model::model()->findAll(['select' => 'operation_number']);
        if($numbers) {
            foreach($numbers as $number)
                $rNums[] = $number->operation_number;
                
            $oNumber = self::generateOperationNumberForProcent($rNums, $letter);
        } else
            $oNumber = self::generateOperationNumberForProcent([], $letter);
        
        return $oNumber;
    }
    
    private static function generateOperationNumberForProcent($nums, $letter)
    {
        if(!in_array($num = $letter.rand(00000000, 99999999), $nums))
            return $num;
        else
            self::generateOperationNumber($nums, $letter);
    }
}
