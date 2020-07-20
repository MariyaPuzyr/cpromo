<?php

class UsersRelation extends CActiveRecord
{
    public function tableName()
    {
	return '{{users_relation}}';
    }

    public function rules()
    {
    	return [
            ['user_id, to_user, level', 'required'],
            ['id, user_id, to_user, level', 'numerical', 'integerOnly' => true],
            ['id, user_id, to_user, level', 'safe', 'on' => 'search'],
	];
    }

    public function relations()
    {
    	return [
            'rData' => [self::BELONGS_TO, 'Users', 'to_user', 'joinType' => 'LEFT JOIN', 'scopes' => 'onlyRelation'],
            #'rBalance' => [self::HAS_MANY, 'UsersBalance', '', 'on' => 't.to_user = rBalance.user_id', 'scopes' => 'order_id_desc_relation'],
            #'rDeposit' => [self::BELONGS_TO, 'UsersDeposit', '', 'on' => 't.to_user = rDeposit.user_id', 'joinType' => 'LEFT JOIN', 'scopes' => 'order_id_desc_relation'],
            #'rCoins' => [self::HAS_MANY, 'CoinsMarket', '', 'on' => 't.to_user = rCoins.user_id', 'scopes' => 'order_id_desc_relation'],
            'users_to' => [self::BELONGS_TO, 'Users', 'user_id'],
            
            
            'user_level' => [self::BELONGS_TO, 'SprLevels', 'level'],
            'users_pay' => [self::HAS_MANY, 'UsersPays', '', 'on' => 't.to_user=users_pay.user_id', 'joinType'=>'LEFT JOIN'],
            'users_out' => [self::HAS_MANY, 'UsersOutS', '', 'on' => 't.to_user=users_out.user_id', 'joinType'=>'LEFT JOIN'],
            'users_profit' => [self::HAS_MANY, 'UsersProfitS', '', 'on' => 't.to_user=users_profit.user_id', 'joinType'=>'LEFT JOIN'],
            'main_data' => [self::BELONGS_TO, 'User', 'user_id', 'joinType'=>'LEFT JOIN'],
            'main_pays' => [self::HAS_MANY, 'UsersPays', '', 'on' => 't.user_id=main_pays.user_id', 'joinType'=>'LEFT JOIN'],
            'main_outs' => [self::HAS_MANY, 'UsersOuts', '', 'on' => 't.user_id=main_outs.user_id', 'joinType'=>'LEFT JOIN'],
            'main_profits' => [self::HAS_MANY, 'UsersProfits', '', 'on' => 't.user_id=main_profits.user_id', 'joinType'=>'LEFT JOIN'],
            'user' => [self::BELONGS_TO, 'Users', 'user_id'],
	];
    }

    public function attributeLabels()
    {
	return [
            'user_id' => 'User',
            'to_user' => 'To User',
            'level' => 'Level',
	];
    }

    public function search()
    {
	$criteria = new CDbCriteria;

	$criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
	$criteria->compare('to_user', $this->to_user);
	$criteria->compare('level', $this->level);

        return new CActiveDataProvider($this, [
            'criteria'=>$criteria,
	]);
    }
    
    public function defaultScope()
    {
        return [
            'order' => 'level ASC'
        ];
    }
    
    public function inviteRelation($id, $email, $ref = false)
    {
        if($ref) {
            $invite_user = Users::model()->userByRef($ref);
            $this->addUserRelation($invite_user, $id, 1);
        } else {
            $invite = UsersInvite::model()->findByAttributes(['invite_email' => $email]);
            if($invite)
                $this->addUserRelation($invite->user_id, $id, 1);
            else
                $this->addUserRelation(1, $id, 1);
        }
    }
    
    public function addUserRelation($id, $to_id, $level = false)
    {
        $rData = Users::model()->onlyRelation()->findByPk($id);
        $relations = $this->with(['user:onlyRelation'])->findAllByAttributes(['to_user' => $id]);
        foreach($relations as $relation) {
            $relationLevels[$relation->level] = [
                'id' => $relation->user_id,
                'status' => $relation->user->status
            ];
        }
        
        $this->isNewRecord = true;
        $this->user_id = $id;
        $this->to_user = $to_id;
        $this->level = $level;
        $this->save();
        
        $maxLevel = SprLevels::model()->order_id()->find()->id;
        if($relationLevels) {
            foreach($relationLevels as $key => $value) {
                for($i = 1; $i <= 99; $i++)
                    $level_arrays[] = $i;
                
                if($level_arrays) {
                    $values[] = [
                        'user_id' => $value['id'],
                        'to_user' => $to_id,
                        'level' => $key+1
                    ];
                }
            }
            
            if($values) {
                $builder = Yii::app()->db->schema->commandBuilder;
                $command = $builder->createMultipleInsertCommand('{{users_relation}}', $values);
                $command->execute();
            }
        }
    }
    
    public static function model($className=__CLASS__)
    {
	return parent::model($className);
    }
}
