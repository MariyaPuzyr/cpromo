<?php 
    $this->pageTitle = Admin::t('controllers', 'user_index_title');
    $dProvider = $model->search();
?>
<div class="row bg-white p-3">
    <div class="col-md-12">
        <h6 class="mobile-text-center  pt-3 pr-3 pl-3" style="vertical-align: inherit;"><?= Admin::t('controllers', 'user_index_title2', ['#count' => $dProvider->getTotalItemCount(), '#upays' => $uPays, '#ucbuys' => $uCBuys]); ?></h6>
        <?php echo CHtml::beginForm('', 'get', ['id' => 'filterUserForm', 'class' => 'form-row', 'style' => 'width: 100%']); ?>
        <div class="row p-3">
            <div class="col-md-2">
                <?= CHtml::textField('referral_id', '', ['class' => 'form-control form-control-sm', 'placeholder' => Yii::t('models', 'user_attr_referral_id')]); ?>
            </div>
            <div class="col-md-2">
                <?= CHtml::textField('username', '', ['class' => 'form-control form-control-sm', 'placeholder' => Yii::t('models', 'user_attr_username')]); ?>
            </div>
            <div class="col-md-2">
                <?= CHtml::textField('email', '', ['class' => 'form-control form-control-sm', 'placeholder' => Yii::t('models', 'user_attr_email')]); ?>
            </div>
            <div class="col-md-2">
                <?= CHtml::textField('firstname', '', ['class' => 'form-control form-control-sm', 'placeholder' => Yii::t('models', 'user_attr_firstname')]); ?>
            </div>
            <div class="col-md-2">
                <?= CHtml::dropDownList('user_status', $user_status, $model->statusUser('status'), ['class' => 'form-control form-control-sm', 'empty' => Yii::t('models', 'attr_status')]); ?>
            </div>
            <div class="col-md-2">
                <?= CHtml::dropDownList('status_account', $status_account, SprStatuses::getListStatuses(), ['class' => 'form-control form-control-sm', 'empty' => Yii::t('models', 'user_attr_status_account')]); ?>
            </div>
        <?php echo CHtml::endForm(); ?>
        </div>
    </div>
    <div class="col-md-12">
        <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'userList',
            'type' => 'condensed bordered stripped',
            'dataProvider' => $dProvider,
            'enableSorting' => true,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
            'pagerCssClass' => 'mt-2 pagerNew',
            'ajaxUpdate' => true,
            'extraParams' => ['uRefs' => $uRefs, 'rRel' => $rRel],
            'columns' => [
                ['header' => Admin::t('controllers', 'users_index_lbl_invited'), 'type' => 'raw', 'value' => 'CHtml::link($this->grid->extraParams["rRel"]["$data->id"]["username"], "#", ["onclick" => "getReferralShortInfo(\"{$this->grid->extraParams["rRel"]["$data->id"]["referral_id"]}\", true); return false;", "class" => "text-primary"])'],
                ['name' => 'referral_id'],
                #['name' => 'status', 'type' => 'raw', 'value' => '$data->statusUser("status", $data->status)'],
                ['name' => 'status_account', 'header' => Admin::t('controllers', 'user_index_lbl_status_account'), 'type' => 'raw', 'value' => '$data->statusAccount->{"name_".Yii::app()->language}'],
                ['name' => 'username'],
                ['name' => 'email'],
                ['name' => 'firstname'],
                ['header' => Yii::t('models', 'user_attr_balance'), 'type' => 'html', 'value' => '$data->rBalance ? end($data->rBalance)->operation_summAll."$" : ""'],
                ['header' => Yii::t('models', 'user_attr_coins'), 'type' => 'html', 'value' => '$data->rCoins ? end($data->rCoins)->countAll."CP" : ""'],
                ['header' => Admin::t('controllers', 'user_index_lbl_countRefs'), 'type' => 'raw', 'value' => '$this->grid->extraParams["uRefs"]["$data->id"]'],
                ['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{viewNetwork}&nbsp;&nbsp;{editUser}&nbsp;&nbsp;{deleteUser}', 'buttons' => [
                    'viewNetwork' => [
                        'icon' => 'icon-flow-tree',
                        'label' => Admin::t('core', 'btn_userRNetwork'),
                        'url' => 'Yii::app()->controller->createUrl("viewNetwork", ["user_id" => $data->id])', 
                        'options' => [
                            'data-toggle' => false,
                            'class' => 'text-dark'
                        ],
                    ],
                    'editUser' => [
                        'icon' => 'fas fa-pencil-alt',
                        'label' => Yii::t('core', 'btn_edit'),
                        'url' => 'Yii::app()->controller->createUrl("edit", ["id" => $data->id])', 
                        'options' => [
                            'data-toggle' => false,
                            'class' => 'text-dark'
                        ],
                    ],
                    'deleteUser' => [
                        'icon' => 'fas fa-trash',
                        'label' => Admin::t('core', 'btn_delete'),
                        'url' => 'Yii::app()->controller->createUrl("delete", ["id" => $data->id])', 
                        'options' => [
                            'data-toggle' => false,
                            'class' => 'text-dark',
                            'confirm' => Admin::t('core', 'btn_deleteUserConfirm'),
                            'ajax' => [
                                'url' => 'js:$(this).attr("href")',
                                'success'=>'function(data){
                                    var obj = JSON.parse(data);
                                    if(obj.status == "success") {
                                        $(".notifyjs-corner").empty();
                                        $.fn.yiiGridView.update("userList");
                                    }
                                }'    
                            ],  
                        ],
                    ],
                ]]
            ]
        ]); ?>
    </div>
</div>
