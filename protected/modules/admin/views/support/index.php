<?php $this->pageTitle = Admin::t('controllers', 'support_index_title'); ?>

<div class="row bg-white p-3">
    <div class="col-md-12">
        <h6 class="mobile-text-center  pt-3 pr-3 pl-3" style="vertical-align: inherit;"><?= Admin::t('controllers', 'support_index_lbl_headList'); ?></h6>
    </div>
</div>    
<?php echo CHtml::beginForm('', 'get', ['id' => 'filterFeedbackForm']); ?>
<div class="row bg-white p-3">
    <div class="col-md-3">
        <?php echo CHtml::textField('number', '', ['class' => 'form-control form-control-sm', 'placeholder' => Yii::t('models', 'feedback_attr_msg_number')]); ?>
    </div>
    <div class="col-md-4">
        <?php $this->widget('bootstrap.widgets.TbDateRangePicker', [
            'name' => 'feedback_date',
            'options' => [
                'showDropdowns' => true,
                'drops' => 'down',
                'autoApply' => true,
                'autoUpdateInput' => true,
                'locale' => [
                    'format'=>'DD.MM.YYYY',
                    'customRangeLabel' => Yii::t('controllers', 'dashboard_historyOperation_lbl_mainPeriod')
                ],
                'linkedCalendars' => false,
                'language' => Yii::app()->language,
                'minDate' => '01.01.2020',
                'maxDate' => date('d.m.Y'),
                'startDate' => '01.01.2020',
                'endDate' => date('d.m.Y'),
                'ranges' => [
                    Yii::t('controllers', 'dashboard_historyOperation_lbl_today') => [date('d.m.Y'), date('d.m.Y')],
                    Yii::t('controllers', 'dashboard_historyOperation_lbl_yesterday') => [date('d.m.Y' ,strtotime('-1 day')), date('d.m.Y')],
                    Yii::t('controllers', 'dashboard_historyOperation_lbl_last7day') => [date('d.m.Y' ,strtotime('-7 day')), date('d.m.Y')],
                    Yii::t('controllers', 'dashboard_historyOperation_lbl_last30day') => [date('d.m.Y' ,strtotime('-30 day')), date('d.m.Y')],
                    Yii::t('controllers', 'dashboard_historyOperation_lbl_thisMonth') => [date('01.m.Y'), date('d.m.Y')],
                    Yii::t('controllers', 'dashboard_historyOperation_lbl_thisYear') => [date('01.01.Y'), date('d.m.Y')],
                ]
            ],
            'htmlOptions' => [
                'style' => 'min-width: 100%',
                'class' => 'form-control form-control-sm'
            ]
        ]); ?>
    </div>
    <div class="col-md-3">
        <?php echo CHtml::dropDownList('type_category', $type_category, $model->typeCategory(), ['class' => 'form-control form-control-sm', 'empty' => Yii::t('models', 'feedback_attr_msg_cat')]); ?>
    </div>
    <div class="col-md-2">
        <?php echo CHtml::dropDownList('status', $status, $model->statusMessage(), ['class' => 'form-control form-control-sm', 'empty' => Yii::t('models', 'attr_status')]); ?>
    </div>
</div>
<?php echo CHtml::endForm(); ?>
<div class="row bg-white p-3">
    <div class="col-md-12">
        <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'feedbackList',
            'dataProvider' => $model->search(10),
            'enableSorting' => true,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
            'pagerCssClass' => 'mt-2 pageNotify',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'referral_id', 'header' => Yii::t('models', 'attr_user_id'), 'type' => 'raw', 'value' => 'CHtml::link($data->user->referral_id, "#", ["onclick" => "getReferralShortInfo(\"{$data->user->referral_id}\", true); return false;"])'],
                ['name' => 'create_at', 'header' => Yii::t('models', 'attr_date'), 'type' => 'raw', 'value' => 'date("d.m.Y", strtotime($data->create_at))'],
                ['name' => 'msg_number', ],
                ['name' => 'msg_cat', 'type' => 'raw', 'value' => '$data->typeCategory($data->msg_cat)'],
                ['name' => 'msg_text'], 
                ['name' => 'msg_status', 'type' => 'html', 'value' => '$data->statusMessageGrid($data->msg_status)'],
                ['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{viewBtn}', 'buttons' => [
                    'viewBtn' => [
                        'icon' => 'fas fa-search',
                        'label' => Yii::t('core', 'btn_view'),
                        'url' => 'Yii::app()->createUrl("/admin/support/viewMessage", ["id" => $data->id])', 
                        'options' => [
                            'data-toggle' => false,
                            'class' => 'text-dark'
                        ]
                    ]
                ]]
            ]
        ]);?>
    </div>
</div>
<div class="row bg-white p-3 mt-3">
    <div class="col-md-12">
        <h6 class="mobile-text-center  pt-3 pr-3 pl-3" style="vertical-align: inherit;"><?= Admin::t('controllers', 'support_index_lbl_headFaq'); ?></h6>
    </div>
</div> 
<div class="row bg-white p-3">
    <div class="col-md-12">
        <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'faqList',
            'dataProvider' => new CArrayDataProvider($faqs, ['pagination' => ['pageSize' => 10]]),
            'enableSorting' => false,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
            'pagerCssClass' => 'mt-2 pageNotify',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'question_ru', 'header' => Yii::t('models', 'sprFaq_attr_question_ru')],
                ['name' => 'question_en', 'header' => Yii::t('models', 'sprFaq_attr_question_en')],
                ['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{editFaq}', 'buttons' => [
                    'editFaq' => [
                        'icon' => 'fas fa-pencil-alt',
                        'label' => Yii::t('core', 'btn_edit'),
                        'url' => 'Yii::app()->createUrl("/admin/support/workFaq", ["type" => "edit", "id" => $data->id])',
                        'options' => [
                            'data-toggle' => false,
                            'class' => 'text-dark'
                        ]
                    ]
                ]]
            ]
        ]);?>
    </div>
</div>
<div class="row bg-white p-3">
    <div class="col-md-12">
        <?php $this->widget('bootstrap.widgets.TbButton', [
            'context' => 'warning',
            'block' => true,
            'label' => Yii::t('core', 'btn_add'),
            'buttonType' => 'link',
            'url' => $this->createUrl('workFaq', ['type' => 'add'])
        ]); ?>
    </div>
</div>