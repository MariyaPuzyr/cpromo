<?php 
    $count_to_system = Yii::app()->settings->get('system', 'cp_percent_to_system');
    Yii::app()->clientScript->registerScript('changeCountCP', '
        $("input").on("input keyup", function(e){
            var price = Math.ceil($("#CoinsMarket_operation_summ").val()/'.$coins->price.' - (($("#CoinsMarket_operation_summ").val()/'.$coins->price.')*'.$count_to_system.')/100);
            $("#CPCalc").html(price);
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
            <h4 class="font-weight-bold"><?= Yii::t('core', 'modal_headFormBuyCoins'); ?></h4>
        </div>
    </div>
    <div class="row" id="paySummRow">
        <div class="col-md-12">
            <p class="mb-1"><?= Yii::t('models', 'CoinsMarket_attr_price_perOne_full', ['#price' => $coins->price]); ?></p>
            <p class="mb-1"><?= Yii::t('models', 'CoinsMarket_attr_count_now', ['#countCP' => $main['count_now']]); ?></p>
            <p class="mb-1"><?= Yii::t('models', 'CoinsMarket_attr_count_now_summ', ['#summ' => $main['count_now_summ']]); ?></p>
            <p class="mb-1"><?= Yii::t('models', 'CoinsMarket_attr_count_now_can', ['#price' => $main['count_now_can']]); ?></p>
            <hr class="mt-0"/>
            <p class="mb-1"><?= Yii::t('models', 'CoinsMarket_attr_count_full'); ?><span class="font-bold" id="CPCalc">0</span>CP</p>
            <p class="text-danger"><?= Yii::t('models', 'CoinsMarket_attr_countProcent', ['#procent' => $count_to_system]); ?></p>
        </div>
    </div>
    <div class="row">    
        <div class="col-md-7">
            <?= $form->textFieldGroup($model, 'operation_summ', ['widgetOptions' => ['htmlOptions' => ['class' => 'form-control-sm']], 'label' => Yii::t('models', 'attr_summ_in_usd')]); ?>
        </div>
        <div class="col-md-5">
            <?php $this->widget('bootstrap.widgets.TbButton', [
                'label' => Yii::t('core', 'btn_buy'),
                'buttonType' => 'submit',
                'context' => 'primary',
                'block' => true,
                'htmlOptions' => [
                    'style' => 'height: 3.125rem',
                    'confirm' => Yii::t('controllers', 'finance_confirm_buyCoins')
                ]
            ]);  ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            
        </div>
    </div>
    <?php $this->endWidget(); unset($form); ?>
</div>