<?php

class SprLevels extends MBaseModel
{
    public function tableName()
    {
        return '{{spr_levels}}';
    }

    public function rules()
    {
	return [
            ['name_ru, level_percente, level_percente_status, status, create_at, create_uid', 'required'],
            ['name_ru, name_en', 'length', 'max' => 50],
            ['level_percente, level_percente_status', 'length', 'max' => 5],
            ['level_percente, level_percente_status', 'numerical'],
            ['update_at', 'safe'],
            ['id, name_ru, name_en, level_percente, level_percente_status, status', 'safe', 'on' => 'search'],
	];
    }

    public function relations()
    {
	return [
            'referalRelations' => [self::HAS_MANY, 'UsersRelation', 'level'],
            'createU' => [self::BELONGS_TO, 'Users', 'create_uid'],
            'updateU' => [self::BELONGS_TO, 'Users', 'update_uid'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => Yii::t('models', 'attr_id'),
            'name_ru' => Yii::t('models', 'sprLevels_attr_name_ru'),
            'name_en' => Yii::t('models', 'sprLevels_attr_name_en'),
            'level_percente' =>  Yii::t('models', 'sprLevels_attr_level_percente'),
            'level_percente_status' => Yii::t('models', 'sprLevels_attr_level_percente_status'),
            'status' =>  Yii::t('models', 'attr_status'),
	];
    }
    
    public function defaultScope()
    {
        return [
            'order' => 'id ASC'
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

    public function search($size = null)
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('name_ru', $this->name_ru, true);
        $criteria->compare('name_en', $this->name_en, true);
        $criteria->compare('level_percente', $this->level_percente, true);
        $criteria->compare('status', $this->status);
	
	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => $size ? $size : 50
            ]
	]);
    }

    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
    
    public function rowExpression()
    {
        if($this->id == Yii::app()->user->model()->referral_level) {
            return 'text-primary font-weight-bold';
        }
    }
    
    public static function getStatusName($status)
    {
        return SprStatuses::model()->findByPk($status)->{'name_'.Yii::app()->language};
    }
}
