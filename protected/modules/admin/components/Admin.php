<?php

class Admin
{
    public static function t($category, $message, $params = [], $source = null, $language = null)
    {
	return Yii::t('AdminModule.'.$category, $message, $params, $source, $language);
    }
}