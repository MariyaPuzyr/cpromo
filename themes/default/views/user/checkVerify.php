<?php $this->pageTitle = Yii::t('controllers', 'user_login_checkGoogle_title'); ?>

<div class="row justify-content-md-center pt-120">
    <div class="col-lg-5">
        <div class="login-container">
            <div class="login-box">
                <div class="login-logo text-center">
                    <?=CHtml::image($this->assetsBase.DIRECTORY_SEPARATOR.'img/logo_full_grey.png'); ?>
                </div><br><br>
                <h2 class="text-center mb-4"><?= Yii::t('controllers', 'user_login_checkGoogle_title'); ?></h2>
                <?php
                    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
                        'id' => get_class($model),
                        'enableClientValidation' => false,
                        'enableAjaxValidation' => true,
                        'htmlOptions' => ['class' => 'form-signin'],
                        'floating' => true,
                        'withOutPlaceholder' => true,
                        'clientOptions' => [
                            'validateOnSubmit' => true,
                            'validateOnChange' => false,
                            'hideErrorMessage' => false,
                        ]
                    ]);
        
                    echo $form->textFieldGroup($model, 'code', ['hint' => Yii::t('models', $google ? 'users_attr_code_hint_google' : 'users_attr_code_hint_email')]);
                    $this->widget('bootstrap.widgets.TbButton', [
                        'context' => 'primary',
                        'block' => true,
                        'buttonType' => 'submit',
                        'label' => Yii::t('controllers', 'user_login_btn_confirm'),
                        'htmlOptions' => [
                            'class' => 'mb-3 big-btn'
                        ],
                    ]);
                    $this->endWidget(); unset($form);
                ?>
                <hr style="width: 30%" />
                <center><?php $this->widget('LangSelect', ['type' => 'list']); ?></center>
            </div>
        </div>
    </div>
</div>