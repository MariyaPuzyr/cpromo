<?php 
    Yii::app()->clientScript->registerScript('statusInfo', '
        $("#UsersStatus_status_id").on("change", function(){
            $.ajax({
                url: "'.$this->createUrl('/finance/getStatusInfo', ['id' => '']).'"+$(this).val(),
                type: "get",
                success: function(html){  
                    $("#statusData").html(html);  
                } 
            });
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
        ]
    ]); 
?>

<div class="screenWidth" style="min-width: 350px">
    <div class="row mb-2">
        <div class="col-md-12">
            <h4 class="font-weight-bold"><?= Yii::t('core', 'modal_headFormBuyStatus'); ?></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->dropdownListGroup($model, 'status_id', ['widgetOptions' => ['data' => $statusList, 'htmlOptions' => ['class' => 'form-control-sm']], 'label' => false]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="statusData">
            <p class="mb-1 mt-2"><?= Yii::t('models', 'sprStatuses_attr_max_levels_full', ['#count' => $statusData->max_levels]); ?></p>
            <p class="mb-1"><?= Yii::t('models', 'sprStatuses_attr_max_coin_buy_summ_full', ['#summ' => $statusData->max_coin_buy_summ]); ?></p>
            <p><?= Yii::t('models', 'sprStatuses_attr_out_full', ['#count' => $statusData->out_count, '#period' => SprStatuses::getOutPeriodType($statusData->out_count_period, true), '#summ' => $statusData->out_max_summ]); ?></p>
            <p class="text-danger"><?= Yii::t('models', 'sprStatuses_attr_warning'); ?></p>
        </div>
    </div>
    <hr class="mt-3"/>
    <div class="row">
        <div class="col-md-12">
            <?php $this->widget('bootstrap.widgets.TbButton', [
                'label' => Yii::t('core', 'btn_upStatus'),
                'buttonType' => 'submit',
                'context' => 'primary',
                'block' => true,
                'htmlOptions' => [
                    'confirm' => Yii::t('controllers', 'finance_confirm_buyStatus')
                ]
            ]);  ?>
        </div>
    </div>
    <?php $this->endWidget(); unset($form); ?>
</div>