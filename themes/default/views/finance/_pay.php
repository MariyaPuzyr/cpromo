<?php 
    Yii::app()->clientScript->registerScript('coinpays_warning', '
        $("#UsersPays_operation_system").on("change", function(){
            
        });
    ');


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
                    $.fn.yiiGridView.update("incompletePays");
                    $("#payProcess").load("'.$this->createUrl('/finance/payProcess', ['pay_id' => '']).'"+data.pay_id, function(){
                        $("#UsersPays_pay_summ").attr("readonly", true);
                        $("#btn_pay, #UsersPays_operation_system, #paySummRow").attr({"disabled": true, "style": "display: none"});
                    });
                }
            }'
        ]
    ]); 
?>

<div class="screenWidth">
    <div class="row mb-2">
        <div class="col-md-12">
            <h4 class="font-weight-bold"><?= Yii::t('core', 'modal_headFormPaySumm'); ?></h4>
        </div>
    </div>
    <div class="row" id="paySummRow">
        <div class="col-md-12">
            <?= $form->textFieldGroup($model, 'operation_summ', ['widgetOptions' => ['htmlOptions' => ['class' => 'form-control-sm']], 'hint' => Yii::t('models', 'Pays_attr_operation_summ_hint'), 'label' => Yii::t('models', 'attr_summ_in_usd')]); ?>
        </div>
    </div>
    <hr class="mt-0"/>
    <div class="row">
        <div class="col-md-12">
            <?= $form->radioButtonListGroup($model, 'operation_system', ['widgetOptions' => ['data' => $model->getPayMode()], 'block' => true, 'label' => false]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php $this->widget('bootstrap.widgets.TbButton', [
                'label' => Yii::t('core', 'btn_pay'),
                'buttonType' => 'submit',
                'context' => 'primary',
                'block' => true,
                'htmlOptions' => [
                    'id' => 'btn_pay',
                    'confirm' => Yii::t('controllers', 'finance_confirm_invest')
                ]
            ]);  ?>
        </div>
    </div>
    <?php $this->endWidget(); unset($form); ?>
    <div id="payProcess"></div>
</div>