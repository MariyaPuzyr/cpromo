<?php $this->pageTitle = Admin::t('controllers', 'profit_index_title'); ?>

<div class="row bg-white p-3">
    <div class="col-md-12">
        <h6 class="mobile-text-center pt-3 pr-3 pl-3" style="vertical-align: inherit;">
            <?=Admin::t('controllers', 'profit_head_profit');?>
            <span class="ml-2 text-danger font-weight-bold"><?= Admin::t('controllers', 'profit_head_profitAuto');?></span>
        </h6>
        <div class="row mt-3">
            <div class="col-md-12">
                <?php $this->widget('bootstrap.widgets.TbGridView', [
                    'id' => 'accuralList',
                    'dataProvider' => new CArrayDataProvider($profits, ['pagination' => ['pageSize' => 10], 'sort' => ['attributes' => ['profit_date', 'profit_summ']]]),
                    'enableSorting' => true,
                    'template' => '{items}{pager}',
                    'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
                    'pagerCssClass' => 'mt-2 pageNotify',
                    'ajaxUpdate' => true,
                    'columns' => [
                        ['name' => 'profit_date', 'header' => Yii::t('models', 'attr_date'), 'type' => 'raw', 'value' => 'date("d.m.Y H:i:s", strtotime($data->profit_date))'],
                        ['name' => 'profit_summ', 'header' => Yii::t('models', 'attr_summ'), 'type' => 'raw', 'value' => '$data->profit_summ."$"'],
                    ]
                ]);?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3 bg-white p-3">
    <div class="col-md-12">
        <h6 class="mobile-text-center pt-3 pr-3 pl-3" style="vertical-align: inherit;"><?=Admin::t('controllers', 'profit_head_activ');?></h6>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
            'id' => 'LevelChange',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'floating' => true,
            'clientOptions' => [
                'validateOnSubmit' => true,
                'validateOnChange' => false,
                'hideErrorMessage' => false,
            ]
        ]); ?>
        <div class="row mt-3 p-3">
            <div class="col-md-4">
                <?= $form->textFieldGroup($activ, 'income_people'); ?>
            </div>
            <div class="col-md-4">
                <?= $form->textFieldGroup($activ, 'income_auto'); ?>
            </div>
            <div class="col-md-4">
                <?= $form->textFieldGroup($activ, 'income_square'); ?>
            </div>
            <?php $this->widget('bootstrap.widgets.TbButton', [
                'label' => Yii::t('core', 'btn_save'),
                'context' => 'warning',
                'block' => true,
                'buttonType' => 'submit',
                'htmlOptions' => [
                    'class' => 'm-3'
                ]
            ]); ?>
        <?php $this->endWidget(); unset($form); ?>
        </div>
    </div>
</div>

<div class="row mt-3 bg-white p-3">
    <div class="col-md-6 border-bottom-mobile border-right">
        <h6 class="mobile-text-center pt-3 pr-3 pl-3" style="vertical-align: inherit;">
            <?=Admin::t('controllers', 'profit_head_weight');?>
            <?= CHtml::link(Yii::t('core', 'btn_add'), '#', ['id' => 'btn_addWeight', 'class' => 'small ml-2 text-danger font-weight-bold']); ?>
        </h6>
        <div class="row mt-3">
            <div class="col-md-12">
                <?php $this->widget('bootstrap.widgets.TbGridView', [
                    'id' => 'weightList',
                    'dataProvider' => $model->weight()->search(15),
                    'enableSorting' => true,
                    'template' => '{items}{pager}',
                    'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
                    'pagerCssClass' => 'mt-2 pageNotify',
                    'ajaxUpdate' => true,
                    'columns' => [
                        ['name' => 'income_date', 'header' => Yii::t('models', 'attr_date'), 'type' => 'raw', 'value' => 'date("d.m.Y", strtotime($data->income_date))'],
                        ['name' => 'income_weight', 'header' => Yii::t('models', 'attr_unitGR')],
                    ]
                ]);?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <h6 class="mobile-text-center pt-3 pr-3 pl-3" style="vertical-align: inherit;">
            <?=Admin::t('controllers', 'profit_head_summ');?>
            <?= CHtml::link(Yii::t('core', 'btn_add'), '#', ['id' => 'btn_addGold', 'class' => 'small ml-2 text-danger font-weight-bold']); ?>
        </h6>
        <div class="row mt-3">
            <div class="col-md-12">
                <?php $this->widget('bootstrap.widgets.TbGridView', [
                    'id' => 'summList',
                    'dataProvider' => $model2->summ()->search(15),
                    'enableSorting' => true,
                    'template' => '{items}{pager}',
                    'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
                    'pagerCssClass' => 'mt-2 pageNotify',
                    'ajaxUpdate' => true,
                    'columns' => [
                        ['name' => 'income_date', 'header' => Yii::t('models', 'attr_date'), 'type' => 'html', 'value' => 'date("d.m.Y", strtotime($data->income_date))'],
                        ['name' => 'income_summ', 'header' => Yii::t('models', 'attr_summ')],
                    ]
                ]);?>
            </div>
        </div>
    </div>
</div>