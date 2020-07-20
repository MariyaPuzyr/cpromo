<?php $this->pageTitle = Admin::t('controllers', 'settings_index_title'); ?>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
    'id' => get_class($settings),
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
    'floating' => true,
    'clientOptions' => [
        'validateOnSubmit' => true,
        'validateOnChange' => false,
        'hideErrorMessage' => false,
    ]
]);?>
<div class="row bg-white p-3">
    <div class="col-md-3">
        <?= $form->checkboxGroup($settings, 'enableLockRegister'); ?>
    </div>
    <div class="col-md-3">
        <?php echo $form->checkboxGroup($settings, 'enter_summ_use'); ?>
    </div>
    <div class="col-md-3">
        <?php echo $form->checkboxGroup($settings, 'out_summ_use'); ?>
    </div>
    <div class="col-md-3">
        <?php echo $form->checkboxGroup($settings, 'offSite'); ?>
    </div>
</div>
<div class="row bg-white p-3">
    <div class="col-md-3">
        <?php echo $form->textFieldGroup($settings, 'enter_summ_min', ['widgetOptions' => ['htmlOptions' => ['placeholder' => false]]]);?>
    </div>
    <div class="col-md-3">
        <?php echo $form->textFieldGroup($settings, 'enter_summ_crat', ['widgetOptions' => ['htmlOptions' => ['placeholder' => false]]]);?>
    </div>
    <div class="col-md-3">
        <?php echo $form->textFieldGroup($settings, 'out_summ_min', ['widgetOptions' => ['htmlOptions' => ['placeholder' => false]]]);?>
    </div>
    <div class="col-md-3">
        <?php echo $form->textFieldGroup($settings, 'out_summ_max', ['widgetOptions' => ['htmlOptions' => ['placeholder' => false]]]);?>
    </div>
</div>
<div class="row bg-white p-3">
    <div class="col-md-3">
        <?php echo $form->textFieldGroup($settings, 'out_summ_crat', ['widgetOptions' => ['htmlOptions' => ['placeholder' => false]]]);?>
    </div>
    <div class="col-md-3">
        <?php echo $form->textFieldGroup($settings, 'procent_profit', ['widgetOptions' => ['htmlOptions' => ['placeholder' => false]]]);?>
    </div>
</div>
<div class="row bg-white p-3">
    <div class="col-md-4">
        <?php echo $form->textFieldGroup($settings, 'deposit_pay_freeze_period', ['widgetOptions' => ['htmlOptions' => ['placeholder' => false]]]);?>
    </div>
    <div class="col-md-7">
        <?php echo $form->textFieldGroup($settings, 'deposit_procent_freeze_period', ['widgetOptions' => ['htmlOptions' => ['placeholder' => false]]]);?>
    </div>
    <div class="col-md-7">
        <?php echo $form->textFieldGroup($settings, 'cp_percent_to_system', ['widgetOptions' => ['htmlOptions' => ['placeholder' => false]]]);?>
    </div>
</div>
<div class="row bg-white p-3">
    <div class="col-md-12">
        <?php $this->widget('bootstrap.widgets.TbButton', [
            'block' => true,
            'context' => 'warning',
            'label' => Yii::t('core', 'btn_save'),
            'buttonType' => 'submit',
        ]); ?>
    </div>
</div>
<?php $this->endWidget(); unset($form); ?>


<div class="row bg-white p-3 mt-3">
    <div class="col-md-12">
        <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'newsList',
            'dataProvider' => new CArrayDataProvider($levels, ['pagination' => ['pageSize' => 20]]),
            'enableSorting' => false,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
            'pagerCssClass' => 'mt-2 pageNotify',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'id', 'header' => Yii::t('models', 'attr_id')],
                ['name' => 'name_ru', 'header' => Yii::t('models', 'sprLevels_attr_name_ru')],
                ['name' => 'name_en', 'header' => Yii::t('models', 'sprLevels_attr_name_en')],
                ['name' => 'level_percente', 'header' => Yii::t('models', 'sprLevels_attr_level_percente'), 'value' => '$data->level_percente."%"'],
                ['name' => 'status', 'header' => Yii::t('models', 'user_attr_status_account')],
                ['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{edit_level}', 'buttons' => [
                    'edit_level' => [
                        'icon' => 'fas fa-pencil-alt',
                        'label' => Yii::t('core', 'btn_edit'),
                        'url' => 'Yii::app()->createUrl("/admin/settings/workLevel", ["type" => "edit", "id" => $data->id])',
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
            'url' => $this->createUrl('workLevel', ['type' => 'add'])
        ]); ?>
    </div>
</div>

<div class="row bg-white p-3 mt-3">
    <div class="col-md-12">
        <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'pagesList',
            'dataProvider' => new CArrayDataProvider($infoPages, ['pagination' => ['pageSize' => 20]]),
            'enableSorting' => false,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
            'pagerCssClass' => 'mt-2 pageNotify',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'id', 'header' => Yii::t('models', 'attr_id')],
                ['name' => 'text_ru', 'header' => Yii::t('models', 'sprPages_attr_text_ru'), 'type' => 'html', 'value' => 'mb_substr(strip_tags($data->text_ru), 0, 150, "UTF-8")'],
                ['name' => 'text_en', 'header' => Yii::t('models', 'sprPages_attr_text_en'), 'type' => 'html', 'value' => 'mb_substr(strip_tags($data->text_en), 0, 150, "UTF-8")'],
                ['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{edit_page}&nbsp;{delete_page}', 'buttons' => [
                    'edit_page' => [
                        'icon' => 'fas fa-pencil-alt',
                        'label' => Yii::t('core', 'btn_edit'),
                        'url' => 'Yii::app()->createUrl("/admin/settings/workPage", ["id_page" => $data->id,"type" => "edit"])',
                        'options' => [
                            'data-toggle' => false,
                            'class' => 'text-dark'
                        ]
                    ],
                    'delete_page' => [
                        'icon' => 'fas fa-trash',
                        'label' => Yii::t('core', 'btn_delete'),
                        'url' => 'Yii::app()->createUrl("/admin/settings/deletePage", ["id" => $data->id])',
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
            'url' => $this->createUrl('workPage', ['type' => 'add'])
        ]); ?>
    </div>
</div>