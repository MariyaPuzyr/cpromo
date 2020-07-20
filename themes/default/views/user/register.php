<?php $this->pageTitle = Yii::t('controllers', 'user_register_register_title'); ?>
<?php Yii::app()->clientScript->registerScript('confirm_reg', '
    $("#terms_agree_register").on("click", function(){
        $("#hidescreen, #loadingData").fadeIn(10);
        $("#modalData").load("/info/full_terms/?partial=1", function(){
            $.fancybox.open($("#modalWindow"));
            $("#hidescreen, #loadingData").fadeOut(10);
        });
    });
'); ?>

<?php $this->pageTitle = Yii::t('controllers', 'user_login_login_title'); ?>
<div class="row justify-content-md-center pt-120">
    <div class="col-lg-10">
        <div class="login-container">
            <div class="login-box">
                <div class="login-logo text-center">
                    <?=CHtml::image($this->assetsBase.DIRECTORY_SEPARATOR.'img/logo_new.png'); ?>
                </div><br><br>
                <h2 class="text-center mb-4"><?= Yii::t('controllers', 'user_login_login_title'); ?></h2>
                <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
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
                ]); ?>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <?= $form->emailFieldGroup($model, 'email', ['hint' => Yii::t('models', 'user_register_attr_email_desc'), 'widgetOptions' => ['htmlOptions' => ['readonly' => $model->email ? true : false]]]); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->textFieldGroup($model, 'username'); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->textFieldGroup($model, 'firstname'); ?>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-5">
                        <?= $form->passwordFieldGroup($model, 'password'); ?>
                    </div>
                    <div class="col-md-5">
                        <?= $form->passwordFieldGroup($model, 'verifyPassword'); ?>
                    </div>
                </div>
                <hr />
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <?= $form->textFieldGroup($model, 'ref', ['hint' => Yii::t('models', 'user_register_attr_ref_desc'), 'widgetOptions' => ['htmlOptions' => ['readonly' => $model->ref ? true : false]]]); ?>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <?= $form->textFieldGroup($model, 'verifyCode'); ?>
                    </div>
                    <div class="col-md-3 text-center">
                        <?php $this->widget('CCaptcha', [
                            'showRefreshButton' => true,
                            'buttonLabel' => '<i class="fas fa-sync-alt"></i>',
                            'buttonOptions' => [
                                'style' => 'color: #e4e7ed;!important'
                            ]
                        ]);?>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-8 text-center">
                        <?= $form->checkboxGroup($model, 'agree', ['widgetOptions' => ['htmlOptions' => ['checked' => 'checked', 'style' => 'vertical-align: text-top;']]]); ?>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <?php 
                            $this->widget('bootstrap.widgets.TbButton', [
                                'context' => 'primary',    
                                'block' => true,
                                'buttonType' => 'submit',
                                'label' => Yii::t('core', 'btn_signUp'),
                                'htmlOptions' => [
                                    'class' => 'mb-3 big-btn'
                                ],
                            ]);
            
                            echo '<h6 class="login-extlink">';
                                echo CHtml::link(Yii::t('controllers', 'user_register_btn_login'), Yii::app()->user->loginUrl).'<br />';
                                echo CHtml::link(Yii::t('controllers', 'user_register_btn_recovery'), $this->createUrl('/recovery'));
                            echo '</h6>';
                        ?>
                    </div>
                </div>
                <hr style="width: 30%" />
                <center><?php $this->widget('LangSelect', ['type' => 'list']); ?></center>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); unset($form); ?>