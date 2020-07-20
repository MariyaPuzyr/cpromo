<?php 
    $this->pageTitle = Admin::t('controllers', 'settings_workPage_title');
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
        <h4><?= Admin::t('controllers', 'settings_workPage_titleHead', ['#name' => $model->id]); ?></h4>
        <?= Chtml::link(Yii::t('core', 'btn_back'), '#', ['onclick' => 'history.back();', 'class' => 'text-muted small']);?>
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
<div class="row bg-white p-3">
    <div class="col-md-12">
        <?php /*$form->textFieldGroup($model, 'id');*/ ?>
    </div>
</div>
<div class="row bg-white p-3">
    <div class="col-md-2">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <?php foreach(Yii::app()->params->languages as $lang => $name): ?>
            <a class="nav-link <?= array_keys(Yii::app()->params->languages)[0] == $lang ? 'active' : ''; ?>" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-<?= $lang; ?>" role="tab" aria-controls="v-pills-<?= $lang; ?>" aria-selected="<?= array_keys(Yii::app()->params->languages)[0] == $lang ? 'true' : 'false'; ?>"><?= Yii::t('models', 'sprPages_attr_text_'.$lang); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-md-10">
        <div class="tab-content" id="v-pills-tabContent">
            <?php foreach(Yii::app()->params->languages as $lang => $name): ?>
                <div class="tab-pane fade show <?= array_keys(Yii::app()->params->languages)[0] == $lang ? 'active' : ''; ?>" id="v-pills-<?= $lang; ?>" role="tabpanel" aria-labelledby="v-pills-<?= $lang; ?>-tab"><?= $form->redactorGroup($model, 'text_'.$lang, ['label' => false, 'widgetOptions' => ['editorOptions' => ['plugins' => ['alignment']]]]); ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); unset($form); ?>

