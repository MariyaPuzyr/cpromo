<div class="row">
    <div class="col-md-12">
        <h2 class="h3 text-center"><?= Admin::t('controllers', 'profit_workModal');?></h2>
        <?php 
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
                'id' => 'Income',
                'enableClientValidation' => false,
                'enableAjaxValidation' => true,
                'floating' => true,
                'clientOptions' => [
                    'validateOnSubmit' => true,
                    'validateOnChange' => false,
                    'hideErrorMessage' => false,
                ]
            ]);
    
            echo $form->textFieldGroup($model, $type == 'weight' ? 'income_weight' : 'income_summ');
            echo CHtml::hiddenField('Income[type]', $type);
    
            $this->widget('bootstrap.widgets.TbButton', [
                'label' => Yii::t('core', 'btn_save'),
                'context' => 'warning',
                'block' => true,
                'buttonType' => 'submit',
            ]); 
            
            $this->endWidget(); unset($form);
        ?>
    </div>
</div>