<div class="row">
    <div class="col-md-12">
        <h2 class="h3 text-center"><?= Yii::t('core', 'modal_changeEmail_title');?></h2>
        <?php
            $formEmail = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
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
                        }
                    }'
                ]
            ]);
            echo $formEmail->emailFieldGroup($model, 'email');
            
            $this->widget('bootstrap.widgets.TbButton', [
                'context' => 'primary',
                'block' => true,
                'buttonType' => 'submit',
                'label' => Yii::t('core', 'btn_changeEmail_short'),
                'htmlOptions' => [
                    'class' => 'big-btn',
                    'id' => 'changeE'
                ]
            ]);
            
            $this->endWidget(); unset($formEmail);
        ?>
    </div>
</div>