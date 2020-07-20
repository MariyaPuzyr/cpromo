<?php

class News extends MBaseModel
{
    const ST_NSEND = 0;
    const ST_SEND = 1;
    
    public $loadFile;
    
    public function tableName()
    {
	return '{{news}}';
    }

    public function rules()
    {
	return [
            ['news_date, sendStatus, create_at, create_uid', 'required'],
            ['sendStatus, create_uid, update_uid', 'numerical', 'integerOnly' => true],
            ['news_text_ru, news_text_en', 'checkText'],
            ['update_at, gallery_id, sendStatus, sendDate', 'safe'],
            ['title_ru, title_en', 'length', 'max' => 50],
            ['news_text_ru, news_text_en', 'length', 'max' => 3500],
            ['id, news_date, title_ru, title_en, news_text_ru, news_text_en, sendStatus, sendDate, create_at, create_uid, update_at, update_uid', 'safe', 'on' => 'search'],
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
            'news_date' => 'News Date',
            'title_ru' => Yii::t('models', 'news_attr_title_ru'),
            'title_en' => Yii::t('models', 'news_attr_title_en'),
            'news_text_ru' => Yii::t('models', 'news_attr_text_ru'),
            'news_text_en' => Yii::t('models', 'news_attr_text_en'),
            'sendStatus' => Yii::t('models', 'news_attr_sendStatus'),
            'sendDate' => Yii::t('models', 'news_attr_sendDate'),
            'create_at' => 'Create At',
            'create_uid' => 'Create Uid',
            'update_at' => 'Update At',
            'update_uid' => 'Update Uid',
	];
    }
    
    public function scopes()
    {
        return [
            'lang_ru' => ['condition' => 'news_text_ru IS NOT NULL'],
            'lang_en' => ['condition' => 'news_text_en IS NOT NULL'],
            'order_id_desc' => ['order' => 'id DESC']
        ];
    }

    public function search($size = false)
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('news_date', $this->news_date, true);
	$criteria->compare('title_ru', $this->title_ru, true);
        $criteria->compare('title_en', $this->title_en, true);
        $criteria->compare('news_text_ru', $this->news_text_ru, true);
        $criteria->compare('news_text_en', $this->news_text_en, true);
        $criteria->compare('sendStatus', $this->sendStatus);
        $criteria->compare('sendDate', $this->sendDate, true);
	$criteria->compare('create_at', $this->create_at, true);
	$criteria->compare('create_uid', $this->create_uid);
	$criteria->compare('update_at', $this->update_at, true);
	$criteria->compare('update_uid', $this->update_uid);

	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => $size ? $size : 10
            ]
	]);
    }
    
    public function onBeforeValidate($event) {
        if($this->isNewRecord) {
            $this->news_date = date('Y-m-d H:i:s');
            $this->sendStatus = self::ST_NSEND;
            $this->create_at = date('Y-m-d H:i:s');
            $this->create_uid = Yii::app()->user->id;
        } else {
            $this->update_at = date('Y-m-d H:i:s');
            $this->update_uid = Yii::app()->user->id;
        }
        
        parent::onBeforeValidate($event);
    }
    
    public function checkText()
    {
        foreach(Yii::app()->params->languages as $key => $val) {
            $field = 'news_text_'.$key;
            if($this->{$field})
                $notEmpty = true;
        }
        
        if(!$notEmpty)
            $this->addError('news_text_ru', AdminModule::t('models', 'news_empty'));
    }

    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
    
    public static function statusMessage($code = null) 
    {
        $_items = [
            self::ST_SEND => Yii::t('models', 'news_attr_sendStatus_send'),
            self::ST_NSEND => Yii::t('models', 'news_attr_sendStatus_notsend'),
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
    
    public function statusMessageGrid($sendStatus)
    {
        $classes[] = 'badge py-1';
        if($sendStatus == self::ST_NSEND)
            $classes[] = 'badge-warning';
        if($sendStatus == self::ST_SEND)
            $classes[] = 'badge-success';
        return '<span class="'.implode(' ', $classes).'">'.$this->statusMessage($sendStatus).'</span>';
    }
}
