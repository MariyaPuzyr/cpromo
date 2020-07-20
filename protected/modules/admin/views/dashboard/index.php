<?php $this->pageTitle = Admin::t('controllers', 'dashboard_index_title'); ?>

<div class="row bg-white p-3">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 text-left mobile-text-center">
                <h6><?= Admin::t('controllers', 'dashboard_index_lbl_countActiv'); ?></h6>    
            </div>
            <div class="col-md-6 text-right mobile-text-center">
                <?php $this->widget('bootstrap.widgets.TbButtonGroup', [
                    'htmlOptions' => ['id' => 'countActiv'],
                    'buttons' => [
                        [
                            'label' => Admin::t('controllers', 'dashboard_index_btn_count'),
                            'active' => true,
                            'htmlOptions' => ['type' => 'count'],
                        ],
                        [
                            'label' => Admin::t('controllers', 'dashboard_index_btn_finance'),
                            'htmlOptions' => ['type' => 'price'],
                        ],
                    ]
                ]); ?>    
            </div>
        </div>
        <div id="countData" class="mt-3">
            <?php $this->renderPartial('application.modules.admin.views.dashboard._countActiv', ['res' => $res, 'type' => 'count'], false, false); ?>
        </div>
    </div>
</div>

<div class="row mt-3 p-3 bg-white">
    <div class="col-md-12 mobile-text-center">
         <h6><?= Admin::t('controllers', 'dashboard_header_lastPays'); ?></h6>
    </div>
    <div class="col-md-12 mt-2">
        <?php $this->widget('bootstrap.widgets.TbGridView', [
            'dataProvider' => new CArrayDataProvider($pays, ['pagination' => ['pageSize' => 5]]),
            'enableSorting' => false,
            'template' => '{items}',
            'type' => 'stripped',
            'htmlOptions' => ['class' => 'table-responsive'],
            'pagerCssClass' => 'mt-2 pagerNew',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'operation_date', 'header' => Admin::t('core', 'attr_date'), 'type' => 'raw', 'value' => 'date("d.m.Y", strtotime($data->operation_date))'],
                ['name' => 'operation_summ', 'header' => Admin::t('core', 'attr_summ'), 'type' => 'raw', 'value' => '$data->operation_summ."$"'],
                ['name' => 'user_id', 'header' => Yii::t('models', 'user_attr_referral_id'), 'type' => 'raw', 'value' => 'CHtml::link($data->user->referral_id, "#", ["onclick" => "getReferralShortInfo(\"{$data->user->referral_id}\", true); return false;"])'],
            ]
        ]); ?>
    </div>
</div>

<div class="row mt-3 p-3 bg-white">
    <div class="col-md-12 mobile-text-center">
         <h6><?= Admin::t('controllers', 'dashboard_header_lastOuts'); ?></h6>
    </div>
    <div class="col-md-12 mt-2">
        <?php $this->widget('bootstrap.widgets.TbGridView', [
            'dataProvider' => new CArrayDataProvider($outs, ['pagination' => ['pageSize' => 5]]),
            'enableSorting' => false,
            'template' => '{items}',
            'type' => 'stripped',
            'htmlOptions' => ['class' => 'table-responsive'],
            'pagerCssClass' => 'mt-2 pagerNew',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'operation_date', 'header' => Admin::t('core', 'attr_date'), 'type' => 'raw', 'value' => 'date("d.m.Y", strtotime($data->operation_date))'],
                ['name' => 'operation_summ', 'header' => Admin::t('core', 'attr_summ'), 'type' => 'raw', 'value' => '$data->operation_summ."$"'],
                ['name' => 'update_at', 'header' => Admin::t('core', 'attr_update'), 'type' => 'raw', 'value' => '$data->update_at ? date("d.m.Y", strtotime($data->update_at)) : ""'],
                ['name' => 'user_id', 'header' => Yii::t('models', 'user_attr_referral_id'), 'type' => 'raw', 'value' => 'CHtml::link($data->user->referral_id, "#", ["onclick" => "getReferralShortInfo(\"{$data->user->referral_id}\", true); return false;"])'],
            ]
        ]); ?>
    </div>
</div>