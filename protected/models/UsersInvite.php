<?php

class UsersInvite extends MBaseModel
{
    public function tableName()
    {
	return '{{users_invite}}';
    }

    public function rules()
    {
	return [
            ['user_id, invite_email, invite_date', 'required'],
            ['user_id', 'numerical', 'integerOnly' => true],
            ['invite_email', 'length', 'max' => 128],
            ['invite_email', 'email'],
            ['invite_email', 'unique'],
            ['invite_email', 'checkEmail'],
            ['id, user_id, invite_email, invite_date', 'safe', 'on' => 'search'],
	];
    }

    public function relations()
    {
	return [
            'user' => [self::BELONGS_TO, 'Users', 'user_id'],
            'user_ok' => [self::BELONGS_TO, 'Users', '', 'on' => 't.invite_email = user_ok.email'],
	];
    }

    public function attributeLabels()
    {
	return [
            'id' => Yii::t('models', 'attr_id'),
            'user_id' => Yii::t('models', 'attr_user_id'),
            'invite_email' => Yii::t('models', 'Invite_attr_invite_email'),
            'invite_date' => Yii::t('models', 'Invite_attr_invite_date'),
	];
    }

    public function search($size = null)
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
	$criteria->compare('user_id', $this->user_id);
	$criteria->compare('invite_email', $this->invite_email, true);
	$criteria->compare('invite_date', $this->invite_date, true);

	return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => $size ? $size : 10
            ]
	]);
    }
    
    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
    
    public function onBeforeValidate($event) {
        if($this->isNewRecord) {
            $this->user_id = Yii::app()->user->id;
            $this->invite_date = date('Y-m-d H:i:s');
        }
        
        parent::onBeforeValidate($event);
    }
    
    public function checkEmail()
    {
        $user_email = Users::model()->email_select()->findAllByAttributes(['email' => $this->invite_email]);
        if($this->invite_email && $user_email)
            $this->addError('invite_email', Yii::t('models', 'Invite_attr_invite_email_error_exist'));
    }
    
    public static function addInviteFromMainReg($user_id, $invite_email, $invite_date)
    {
        $model = self::model();
        $model->isNewRecord = true;
        $model->user_id = $user_id;
        $model->invite_email = $invite_email;
        $model->invite_date = $invite_date;
        $model->save(false);
    }
    
    public static function getReferralId($email)
    {
        return self::model()->findByAttributes(['invite_email' => $email])->user_id;
    }
    
    public static function inviteStatusGrid($data)
    {
        $classes[] = 'badge py-1';
        
        if($data) {
            if($data->users_head->user_id == Yii::app()->user->id) {
                $classes[] = 'badge-primary';
                $message = Yii::t('models', 'Invite_status_reg');
            } else {
                $classes[] = 'badge-danger';
                $message = Yii::t('models', 'Invite_status_otherRef');
            }
        } else {
            $classes[] = 'badge-info';
            $message = Yii::t('models', 'Invite_status_wait');
        }
        
        return '<span class="'.implode(' ', $classes).'">'.$message.'</span>';
    }
}
