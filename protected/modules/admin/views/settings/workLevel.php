<?php 
    $this->pageTitle = Admin::t('controllers', 'settings_workLevel_title');
    $colSize = 12/count(Yii::app()->params->languages);
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
        'id' => get_class($model),
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'floating' => true,
        'clientOptions' => [
            'validateOnSubmit' => true,
            'validateOnChange' => false,
            'hideErrorMessage' => false,
        ]
    ]);
?>
<div class="row bg-white p-3">
    <div class="col-md-12 text-center">
        <h4><?= Admin::t('controllers', 'settings_workLevel_title'); ?></h4>
        <?= Chtml::link(Yii::t('core', 'btn_back'), '#', ['onclick' => 'history.back();', 'class' => 'text-muted small']);?>
    </div>
</div>
<div class="row bg-white p-3">
    <?php foreach(Yii::app()->params->languages as $lang => $name): ?>
        <div class="col-md-<?= $colSize; ?>">
            <?= $form->textFieldGroup($model, 'name_'.$lang); ?>
        </div>
    <?php endforeach; ?>
</div>
<div class="row bg-white p-3">
    <div class="col-md-6">
        <?= $form->textFieldGroup($model, 'level_percente'); ?>
    </div>
</div>
<div class="row bg-white p-3">
    <div class="col-md-12">
        <?php $this->widget('bootstrap.widgets.TbButton', [
            'context' => 'warning',
            'block' => true,
            'label' => Yii::t('core', 'btn_save'),
            'buttonType' => 'submit',
        ]); ?>
    </div>
</div>
<?php $this->endWidget(); unset($form); ?>

