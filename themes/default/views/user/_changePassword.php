<div class="row">
    <div class="col-md-12">
        <h2 class="h3 text-center"><?= Yii::t('core', 'modal_changePassword_title');?></h2>
        <?php
            $formPass = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
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
                            setTimeout(function(){location.reload();}, 3000);
                        }
                    }'
                ]
            ]);
            echo $formPass->passwordFieldGroup($model, 'password');
            echo $formPass->passwordFieldGroup($model, 'verifyPassword');
            
            $this->widget('bootstrap.widgets.TbButton', [
                'context' => 'primary',
                'block' => true,
                'buttonType' => 'submit',
                'label' => Yii::t('core', 'btn_changePassword'),
                'url' => $this->createUrl('/admin/users/changePassword', ['id' => $id]),
                'htmlOptions' => [
                    'class' => 'big-btn',
                    'id' => 'changeP'
                ]
            ]);
            
            $this->endWidget(); unset($formPass);
        ?>
    </div>
</div>