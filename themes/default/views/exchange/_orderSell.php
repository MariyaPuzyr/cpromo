<?php 
    Yii::app()->clientScript->registerScript('selectWork', '
        var activ = '.$coinNow.';
        $("[id^=\'key_\']").on("click", function(){
            $("#CoinsOrder_count_percent").val($(this).attr("attr_key"));
            var price = Math.floor(activ*$(this).attr("attr_key")/100);
            $("#countToBuy").html(price);
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
            'beforeValidate' => 'js: function(){
                $("#hidescreen, #loadingData").fadeIn(10);
                return true;
            }',
            'afterValidate' => 'js:function(form, data, hasError) {
                $("#hidescreen, #loadingData").fadeOut(10);
                if(hasError == "") {
                    showNoty(data.message, "success");
                    $.fn.yiiGridView.update("orderSell");
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
            <h4 class="font-weight-bold"><?= Yii::t('core', 'modal_headFormOrderSell'); ?></h4>
        </div>
    </div>
    <div id="orderProcess">
        <div class="row" id="paySummRow">
            <div class="col-md-12">
                <p class="mb-1"><?= Yii::t('controllers', 'exchange_buy_lbl_countActivAll', ['#count' => $coinNow]); ?></p>
                <p class="mb-1"><?= Yii::t('controllers', 'exchange_buy_lbl_countActivAllToBuy'); ?><strong><span id="countToBuy"><?= floor(($coinNow*5)/100); ?></span></strong></p>
                <p class="mb-1 text-danger"><?= Yii::t('controllers', 'exchange_buy_lbl_noCancOrder'); ?></p>
                <hr />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <label><?= Yii::t('controllers', 'exchange_buy_lbl_percent_count'); ?></label><br />
                <?php 
                    foreach ($model::PERCENT_TO_SELL_COUNT as $key => $val) {
                        $this->widget('bootstrap.widgets.TbButton', [
                            'label' => $val,
                            'context' => 'info',
                            'htmlOptions' => [
                                'id' => 'key_'.$key,
                                'class' => 'mb-3 mr-3',
                                'attr_key' => $key
                            ]
                        ]);
                    }    
                    echo $form->error($model,'count_percent');
                    echo $form->hiddenField($model, 'count_percent', ['value' => 10]);
                ?>
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
                        'id' => 'sellOrder_btn',
                        'confirm' => Yii::t('controllers', 'exchange_buy_btn_order_confirm')
                    ]
                ]);  ?>
            </div>
        </div>
        <?php $this->endWidget(); unset($form); ?>
    </div>
</div>