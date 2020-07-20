<div id="level_stat">
    <?php $this->widget('bootstrap.widgets.TbGridView', [
        'dataProvider' => $dataProvider,
        'enableSorting' => true,
        'template' => '{items}{pager}',
        'htmlOptions' => ['class' => 'table-responsive tableNotify'],
        'pagerCssClass' => 'mt-2 pageNotify',
        'ajaxUpdate' => true,
        'extraParams' => ['sprStatuses' => $sprStatuses],
        'columns' => [
            ['name' => 'referral_id', 'header' => Yii::t('models', 'user_attr_referral_id'), 'type' => 'raw', 'value' => 'CHtml::link($data->referral_id, "#", ["onclick" => "getReferralShortInfo(\"{$data->referral_id}\", true); return false;", "class" => "text-primary"])'],
            ['name' => 'email', 'header' => Yii::t('models', 'user_attr_email'), 'type' => 'raw'],
            ['name' => 'username', 'header' => Yii::t('models', 'user_attr_username')],
            #['name' => 'balance', 'header' => Yii::t('models', 'user_attr_balance'), 'type' => 'raw', 'value' => '$data->now_balance."$"'],
            ['header' => Yii::t('models', 'user_attr_coins'), 'type' => 'raw', 'value' => '$data->now_coins."CP"'],
            ['header' => Yii::t('models', 'user_attr_status_account'), 'type' => 'raw', 'value' => '$this->grid->extraParams["sprStatuses"][$data->status_account]'],
            #['name' => 'deposit','header' => Yii::t('models', 'user_attr_deposit'), 'type' => 'raw', 'value' => '$data->deposit."$"'],
            #['name' => 'photo', 'header' => false, 'htmlOptions' => ['class' => 'referral_photo'], 'type' => 'raw', 'value' => '$data->photo ? CHtml::image("data:image/png;base64,".base64_encode(file_get_contents(Yii::getPathOfAlias("webroot.uploads.user_photo").DIRECTORY_SEPARATOR.$data->photo)), "", ["class" => "photo_table"]) : ""'],
        ]
    ]); ?>
</div>