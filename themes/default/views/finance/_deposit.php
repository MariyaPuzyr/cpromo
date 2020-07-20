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
        ]
    ]);
?>

<div class="screenWidth" style="min-width: 350px">
    <div class="row mb-2">
        <div class="col-md-12">
            <h4 class="font-weight-bold"><?= Yii::t('core', 'modal_headFormDeposit', ['#type' => $model->getOperationType($type)]); ?></h4>
        </div>
    </div>
    <div class="row" id="paySummRow">
        <div class="col-md-12">
            <?php
                echo $form->textFieldGroup($model, 'operation_summ', ['widgetOptions' => ['htmlOptions' => ['class' => 'form-control-sm']], 'label' => Yii::t('models', 'attr_summ_in_usd')]); 
                echo $form->hiddenField($model, 'operation_type', ['value' => $type]);
            ?>
        </div>
    </div>
    <hr class="mt-0"/>
    <div class="row">
        <div class="col-md-12">
            <?php $this->widget('bootstrap.widgets.TbButton', [
                'label' => Yii::t('core', 'btn_investShort'),
                'buttonType' => 'submit',
                'context' => 'primary',
                'block' => true,
            ]);  ?>
        </div>
    </div>
    <?php $this->endWidget(); unset($form); ?>
</div>