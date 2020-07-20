<?php 
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
        'id' => get_class($model),
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'floating' => true,
        'withOutPlaceholder' => true,
        'clientOptions' => [
            'validateOnSubmit' => true,
            'validateOnChange' => false,
            'hideErrorMessage' => false,
            'afterValidate' => 'js:function(form, data, hasError) {
                if(hasError == "") {
                    showNoty(data.message, "success");
                    $.fancybox.close($("#modalWindow"));
                    setTimeout(function(){location.reload();}, 2000);
                }
            }'
        ]
    ]); 
?>

<div class="screenWidth" style="min-width: 350px">
    <div class="row mb-2">
        <div class="col-md-12">
            <h4 class="font-weight-bold"><?= Yii::t('core', 'modal_headFormOrderBuy'); ?></h4>
        </div>
    </div>
    <div class="row" id="paySummRow">
        <div class="col-md-12">
            <?= $form->textFieldGroup($model, 'count'); ?>
        </div>
    </div>
    <hr class="mt-0"/>
    <div class="row">
        <div class="col-md-12">
            <?php $this->widget('bootstrap.widgets.TbButton', [
                'label' => Yii::t('core', 'btn_order'),
                'buttonType' => 'submit',
                'context' => 'primary',
                'block' => true,
            ]);  ?>
        </div>
    </div>
    <?php $this->endWidget(); unset($form); ?>
</div>