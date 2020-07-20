<?php 
    $this->pageTitle = Yii::t('controllers', 'page_'.$model->id.'_title');
    $text_field = 'text_'.Yii::app()->language;
    echo $model->{$text_field ? $text_field : 'text_ru'};