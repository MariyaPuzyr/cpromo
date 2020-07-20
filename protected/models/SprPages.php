<?php

class SprPages extends MBaseModel
{
    public function tableName()
    {
	return '{{spr_pages}}';
    }

    public function rules()
    {
	return [
            ['id', 'required'],
            ['id', 'length', 'max' => 50],
            ['text_ru, text_en', 'safe'],
            ['text_ru, text_en', 'checkText'],
            ['id, text_ru, text_en', 'safe', 'on' => 'search'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => Yii::t('models', 'attr_id'),
            'text_ru' => Yii::t('models', 'sprPages_attr_text_ru'),
            'text_en' => Yii::t('models', 'sprPages_attr_text_en'),
	];
    }

    public function search()
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id, true);
	$criteria->compare('text_ru', $this->text_ru, true);
        $criteria->compare('text_en', $this->text_en, true);

	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
	]);
    }
    
    public function checkText()
    {
        foreach(Yii::app()->params->languages as $key => $val) {
            $field = 'text_'.$key;
            if($this->{$field})
                $notEmpty = true;
        }
        
        if(!$notEmpty)
            $this->addError('text_ru', Yii::t('models', 'sprPages_attr_text_empty'));
    }

    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
}
