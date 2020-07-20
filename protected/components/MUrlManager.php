<?php

class MUrlManager extends CUrlManager
{
    public function createUrl($route, $params = [], $ampersand = '&')
    {
        if(!isset($params['language']) && Yii::app()->user->isGuest) {
            if (Yii::app()->user->hasState('language'))
                Yii::app()->language = Yii::app()->user->getState('language');
            else if(isset(Yii::app()->request->cookies['language']))
                Yii::app()->language = Yii::app()->request->cookies['language']->value;
            #$params['language'] = Yii::app()->language;
        }
        
        return parent::createUrl($route, $params, $ampersand);
    }
}

