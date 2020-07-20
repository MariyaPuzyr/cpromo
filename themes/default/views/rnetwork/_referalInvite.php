<div>
    <div class="row">
        <div class="col-md-12">
            <h4 class="font-weight-bold mobile-text-center"><?= Yii::t('controllers', 'rnetwork_invite_title'); ?></h4>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12">
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
                
                echo $form->emailFieldGroup($model, 'invite_email');
                $this->widget('bootstrap.widgets.TbButton', [
                    'label' => Yii::t('controllers', 'rnetwork_invite_btn_invite'),
                    'block' => true,
                    'context' => 'primary',
                    'buttonType' => 'submit',
                    'htmlOptions' => [
                        'class' => 'big-btn'
                    ]
                ]);
                
                $this->endWidget();
                unset($form);
            ?>
        </div>
    </div>
</div>