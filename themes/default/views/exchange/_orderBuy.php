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
                    $.fn.yiiGridView.update("orderBuy");
                    $.fn.yiiGridView.update("orders");
                    $.fn.yiiGridView.update("orders_buy_list");
                    $.fn.yiiGridView.update("coinsTable_buy");
                    $.fancybox.close($("#modalWindow"));
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
    <div class="row">
            <div class="col-md-12">
                <p class="mb-1 text-danger"><?= Yii::t('controllers', 'exchange_buy_lbl_noCancOrder'); ?></p>
                <hr />
            </div>
        </div>
    <div class="row" id="paySummRow">
        <div class="col-md-12">
            <?= $form->numberFieldGroup($model, 'buy_summ', ['widgetOptions' => ['htmlOptions' => ['class' => 'form-control-sm', 'step' => '0.01', 'min' => 10]], 'label' => Yii::t('models', 'attr_summ_in_usd')]); ?>
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
                'htmlOptions' => [
                    'id' => 'btnBuy'
                ]
            ]);  ?>
        </div>
    </div>
    <?php $this->endWidget(); unset($form); ?>
</div>