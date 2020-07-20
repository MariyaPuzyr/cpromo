<?php

class LangSelect extends CWidget
{
    const TYPE_LIST = 'list';
    const TYPE_DROP = 'drop';
    const TYPE_DROP2 = 'drop2';
    const TYPE_PROMO = 'promo';
    
    public $type = self::TYPE_LIST;
    
    public function run()
    {
        $currentLang = Yii::app()->language;
        $languages = Yii::app()->params->languages;
        $this->render('LangSelect', ['currentLang' => $currentLang, 'languages' => $languages, 'type' => $this->type]);
    }
}
