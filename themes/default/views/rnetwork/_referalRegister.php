<div>
    <div class="row">
        <div class="col-md-12">
            <h4 class="font-weight-bold mobile-text-center"><?= Yii::t('controllers', 'rnetwork_mainRegister_title'); ?></h4>
        </div>
    </div>
    <?php if(!$error):
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
        <div class="row mt-2">
            <div class="col-md-5">
                <?= $form->emailFieldGroup($model, 'email', ['hint' => Yii::t('models', 'user_register_attr_email_desc'), 'widgetOptions' => ['htmlOptions' => ['readonly' => $model->email ? true : false]]]); ?>
            </div>
            <div class="col-md-3">
                <?= $form->textFieldGroup($model, 'username'); ?>
            </div>
            
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->passwordFieldGroup($model, 'password'); ?>
            </div>
            <div class="col-md-6">
                <?= $form->passwordFieldGroup($model, 'verifyPassword');?>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-4">
                <?= $form->textFieldGroup($model, 'firstname'); ?>
            </div>
            <div class="col-md-4">
                <?= $form->textFieldGroup($model, 'lastname'); ?>
            </div>
            <div class="col-md-4">
                <?= $form->textFieldGroup($model, 'phone');?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php $this->widget('bootstrap.widgets.TbButton', [
                    'label' => Yii::t('controllers', 'rnetwork_mainRegister_btn_register'),
                    'block' => true,
                    'context' => 'primary',
                    'buttonType' => 'submit',
                    'htmlOptions' => [
                        'class' => 'big-btn'
                    ]
                ]); ?>
            </div>
        </div>
        <?php $this->endWidget(); unset($form);?>
    <?php else: ?>
        <div class="row">
            <div class="col-md-12 text-center text-danger">
                <h3><?= $error; ?></h3>
            </div>
        </div>
    <?php endif; ?>
</div>