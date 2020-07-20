<?php 
    Yii::app()->clientScript->registerScript('changeCountCP', '
        $("input").on("input keyup", function(e){
            var procent_arr = '.CJSON::encode(UsersOuts::OUT_PROCENT).';
            var checked = [];
            $("input:radio:checked").each(function() {
                checked.push($(this).val());
            });
            
            if(checked.length != 0 ){
                var price = $("#UsersOuts_operation_summ").val() - ($("#UsersOuts_operation_summ").val()*procent_arr[checked[0]])/100;
                $("#CPCalc2").html(procent_arr[checked[0]]);
                $("#CPCalc").html(price);
            }
        });
    ');

    if(!$resFields) {
?>
<div class="row">
    <div class="col-md-12">
        <h2 class="h3 text-center"><?= Yii::t('core', 'modal_warningOut_title');?></h2>
        <?= Yii::t('core', 'modal_watringOut_text_noWallet'); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', [
            'label' => Yii::t('core', 'menu_head_btn_profile'),
            'context' => 'primary',
            'block' => true,
            'buttonType' => 'link',
            'htmlOptions' => ['class' => 'mt-3'],
            'url' => $this->createUrl('/user/profile/'),
        ]); ?>
    </div>
</div>
        
<?php } else {
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
        ]); ?>
<div class="screenWidth">
    <div class="row mb-2">
        <div class="col-md-12">
            <h4 class="font-weight-bold"><?= Yii::t('core', 'modal_headFormOutSumm'); ?></h4>
        </div>
    </div>
    <?php 
        #echo $form->errorSummary($model);
        $data = Yii::app()->user->model();
        if(!$data->finance_payeer && !$data->finance_card && !$data->finance_prfmoney && !$data->finance_usdtrc):
    ?>
    <div class="row mt-3">
        <div class="col-md-12">
            <h5><?= Yii::t('models', 'user_attr_finance_error_empty'); ?></h5>
            <?php $this->widget('bootstrap.widgets.TbButton', [
                'label' => Yii::t('core', 'menu_head_btn_profile'),
                'buttonType' => 'link',
                'url' => $this->createUrl('/profile'),
                'context' => 'primary',
                'block' => true,
                'htmlOptions' => ['class' => 'mt-4']
            ]);  ?>
        </div>
    </div>
    <?php else: ?>
        <div class="row" id="paySummRow">
            <div class="col-md-12">
                <?= $form->numberFieldGroup($model, 'operation_summ', ['widgetOptions' => ['htmlOptions' => ['class' => 'form-control-sm', 'step' => '0.01', 'min' => 10]], 'label' => Yii::t('models', 'attr_summ_in_usd')]); ?>
            </div>
            <div class="col-md-12">
                <p class="mb-1"><?= Yii::t('models', 'Outs_attr_operation_summ_all'); ?><span class="font-bold" id="CPCalc">0</span>$ (<?= Yii::t('models', 'Outs_attr_operation_summ_all_procent'); ?> <span class="font-bold" id="CPCalc2">0</span>%)</p>
            </div>
        </div>
        <hr class="mt-0"/>
        <div class="row">
            <div class="col-md-12">
                <?= $form->radioButtonListGroup($model, 'operation_system', ['widgetOptions' => ['data' => $resFields], 'block' => true, 'label' => false]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php $this->widget('bootstrap.widgets.TbButton', [
                    'label' => Yii::t('core', 'btn_confirmOut'),
                    'buttonType' => 'submit',
                    'context' => 'primary',
                    'block' => true,
                ]);  ?>
            </div>
        </div>
    <?php endif; ?>
    <?php $this->endWidget(); unset($form); ?>
</div>
<?php } ?>