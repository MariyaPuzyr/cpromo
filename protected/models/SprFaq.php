<?php

class SprFaq extends MBaseModel
{
    public function tableName()
    {
	return '{{spr_faq}}';
    }

    public function rules()
    {
	return [
            ['create_at, create_uid', 'required'],
            ['create_uid, update_uid', 'numerical', 'integerOnly' => true],
            ['question_ru, question_en, question_fr, question_ua', 'length', 'max' => 255],
            ['question_ru, question_en, question_fr, question_ua, answer_ru, answer_en, answer_fr, answer_ua, update_at', 'safe'],
            ['question_ru, question_en, question_fr, question_ua', 'checkQuestion'],
            ['answer_ru, answer_en, answer_fr, answer_ua', 'checkAnswer'],
            ['id, question_ru, question_en, question_fr, question_ua, answer_ru, answer_en, answer_fr, answer_ua, create_at, create_uid, update_at, update_uid', 'safe', 'on' => 'search'],
	];
    }

    public function relations()
    {
    	return [
            'createU' => [self::BELONGS_TO, 'Users', 'create_uid'],
            'updateU' => [self::BELONGS_TO, 'Users', 'update_uid'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => 'ID',
            'question_ru' => Yii::t('models', 'sprFaq_attr_question_ru'),
            'question_en' => Yii::t('models', 'sprFaq_attr_question_en'),
            'question_fr' => 'Question Fr',
            'question_ua' => 'Question Ua',
            'answer_ru' => Yii::t('models', 'sprFaq_attr_answer_ru'),
            'answer_en' => Yii::t('models', 'sprFaq_attr_answer_en'),
            'answer_fr' => 'Answer Fr',
            'answer_ua' => 'Answer Ua',
            'create_at' => 'Create At',
            'create_uid' => 'Create Uid',
            'update_at' => 'Update At',
            'update_uid' => 'Update Uid',
	];
    }

    public function search()
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('question_ru', $this->question_ru, true);
	$criteria->compare('question_en', $this->question_en, true);
	$criteria->compare('question_fr', $this->question_fr, true);
	$criteria->compare('question_ua', $this->question_ua, true);
	$criteria->compare('answer_ru', $this->answer_ru, true);
	$criteria->compare('answer_en', $this->answer_en, true);
	$criteria->compare('answer_fr', $this->answer_fr, true);
	$criteria->compare('answer_ua', $this->answer_ua, true);
	$criteria->compare('create_at', $this->create_at, true);
	$criteria->compare('create_uid', $this->create_uid);
	$criteria->compare('update_at', $this->update_at, true);
	$criteria->compare('update_uid', $this->update_uid);

	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
	]);
    }

    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
    
    public function onBeforeValidate($event) {
        if($this->isNewRecord){
            $this->create_at = date('Y-m-d H:i:s');
            $this->create_uid = Yii::app()->user->id;
        } else {
            $this->update_at = date('Y-m-d H:i:s');
            $this->update_uid = Yii::app()->user->id;
        }
        
        parent::onBeforeValidate($event);
    }
    
    public function checkQuestion()
    {
        foreach(Yii::app()->params->languages as $key => $val) {
            $field = 'question_'.$key;
            if($this->{$field})
                $notEmpty = true;
        }
        
        if(!$notEmpty)
            $this->addError('question_ru', Yii::t('models', 'sprFaq_attr_question_empty'));
    }
    
    public function checkAnswer()
    {
        foreach(Yii::app()->params->languages as $key => $val) {
            $field = 'answer_'.$key;
            if($this->{$field})
                $notEmpty = true;
        }
        
        if(!$notEmpty)
            $this->addError('answer_ru', Yii::t('models', 'sprFaq_attr_answer_empty'));
    }
}
