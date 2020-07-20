<?php

class FeedbackWork extends MBaseModel
{
    public function tableName()
    {
	return '{{feedback_work}}';
    }

    public function rules()
    {
	return [
            ['message_id, text, create_at, create_uid', 'required'],
            ['message_id, create_uid, answer', 'numerical', 'integerOnly' => true],
            ['id, message_id, text, create_at, create_uid, answer', 'safe', 'on' => 'search'],
	];
    }

    public function relations()
    {
	return [
            'message' => [self::BELONGS_TO, 'Feedback', 'message_id'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => 'ID',
            'message_id' => 'Message',
            'text' => Yii::t('models', 'feedbackWork_attr_text'),
            'create_at' => 'Create At',
            'create_uid' => 'Create Uid',
	];
    }
    
    public function scopes()
    {
        return [
            'order_id' => [
                'order' => 'id DESC'
            ]
        ];
    }

    public function search()
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('message_id', $this->message_id);
	$criteria->compare('text', $this->text, true);
	$criteria->compare('create_at', $this->create_at, true);
	$criteria->compare('create_uid', $this->create_uid);

	return new CActiveDataProvider($this, [
            'criteria'=>$criteria,
	]);
    }
    
    public function onBeforeValidate($event) {
        $this->create_at = date('Y-m-d H:i:s');
        $this->create_uid = Yii::app()->user->id;
        
        parent::onBeforeValidate($event);
    }

    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
}
