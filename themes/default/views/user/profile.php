<?php 
    $this->pageTitle = Yii::t('controllers', 'user_profile_index_title');
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/fileupload/vendor/jquery.ui.widget.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/fileupload/jquery.iframe-transport.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/fileupload/jquery.fileupload.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/vendor/fileupload/jquery.fileupload.css');
    Yii::app()->clientScript->registerScript('uploadProfilePhoto', "$('#profilePhoto').fileupload({
        url: '/user/profile/uploadPhoto',
        dataType: 'json',
        success: function (data) {
            if(data.status == 'success'){
                location.reload();
            }
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress').show();
            $('#progress .progress-bar').css('width', progress + '%');
        },
    });");
    
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
        'id' => get_class($model),
        'withOutPlaceholder' => true,
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'floating' => true,
        'clientOptions' => [
            'validateOnSubmit' => true,
            'validateOnChange' => true,
            'hideErrorMessage' => false,
        ]
    ]);
?>

<div class="container">
    <div class="row bg-white p-3">
        <div class="col-md-12 text-center">
            <h4><?= Yii::t('controllers', 'user_profile_index_titleHead', ['referral_id' => $model->referral_id]); ?></h4>
        </div>
    </div>
    <?php if($model->status == $model::USTATUS_BANNED): ?>
        <div class="row bg-white p-3">
            <div class="col-md-12 text-center">
                <div class="alert alert-danger" role="alert"><?= Yii::t('controllers', 'user_profile_index_lbl_statusBlock'); ?></div>
            </div>
        </div>
    <?php endif; ?>   
    <div class="row bg-white p-3">
        <div class="col-md-3 offset-md-2 text-center mb-2-mobile">
            <?= CHtml::image($model->photo ? 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::getPathOfAlias('webroot.uploads').'/user_photo/'.$model->photo)) : $this->assetsBase.'/img/no_photo.jpg', '', ['class' => 'profilePhoto']); ?>
            <?php /*
            <span class="btn btn-block fileinput-button btn-primary mt-2" id="fileTitleBut">
                <span><?php echo Yii::t('controllers', 'user_profile_index_btnUploadPhoto'); ?></span>
                <?php echo CHtml::activeFileField($model, 'loadFile', ['id' => 'profilePhoto', 'accept'=>'.jpg, .png']); ?>
            </span>
            <div id="progress" class="progress progress-bar progress-bar-striped active mt-1" style="display: none; margin-bottom: 0px!important;">
                <div id="progressBar" class="progress-bar progress-bar-info"></div>
            </div>
             */ ?>
        </div>
        <div class="col-md-6 offset-md-1">
            <?php
                echo $form->textFieldGroup($model, 'lastname');
                echo $form->textFieldGroup($model, 'firstname');
                echo $form->textFieldGroup($model, 'middlename');
            ?>
        </div>
    </div>
    <div class="row bg-white mt-3 p-3">
        <div class="col-md-12"><h6><?= Yii::t('controllers', 'user_profile_index_lbl_contact'); ?></h6><hr /></div>
        <div class="col-md-6">
            <?php
                echo $form->datePickerGroup($model, 'birthday', [
                    'widgetOptions' => [
                        'options' => [
                            'language' => Yii::app()->language,
                            'format' => 'dd.mm.yyyy',
                            'autoclose' => true,
                            'orientation' => 'top',
                            'endDate' => date('d.m.Y', strtotime('-18 year'))
                        ],
                    ]
                ]);
                echo $form->textFieldGroup($model, 'phone');
                echo $form->textFieldGroup($model, 'passport');
            ?>
        </div>
        <div class="col-md-6">
            <?php
                echo $form->dropdownListGroup($model, 'country', ['widgetOptions' => ['htmlOptions' => ['readonly' => true]]]);
                echo $form->dropdownListGroup($model, 'region', ['widgetOptions' => ['htmlOptions' => ['readonly' => true]]]);
                echo $form->dropdownListGroup($model, 'city', ['widgetOptions' => ['htmlOptions' => ['readonly' => true]]]);
            ?>
        </div>
    </div>
    <div class="row bg-white mt-3 p-3">
        <div class="col-md-12">
            <?php $this->widget('bootstrap.widgets.TbButton', [
                'context' => 'primary',
                'label' => Yii::t('controllers', 'user_profile_index_lbl_finance'),
                'block' => true,
                'htmlOptions' => [
                    'id' => 'fin_acc'
                ]
            ]); ?>
        </div>
    </div>
    <div class="row bg-white mt-3 p-3">
        <div class="col-md-12"><h6><?= Yii::t('controllers', 'user_profile_index_lbl_other'); ?></h6><hr /></div>
        <div class="col-md-6">
            <?php
                echo $form->checkboxGroup($model, 'subscribe_news');
                echo $form->checkboxGroup($model, 'subscribe_admin');
            ?>
        </div>
        <div class="col-md-6">
            <?php echo $form->checkboxGroup($model, 'subscribe_login'); ?>
            <?php $this->widget('bootstrap.widgets.TbButton', [
                'context' => $model->googleAuth ? 'success' : 'danger',
                'label' => Yii::t('models', $model->googleAuth ? 'user_attr_googleAuth_enable' : 'user_attr_googleAuth_disable'),
                'disabled' => $model->googleAuth ? true : false,
                'htmlOptions' => [
                    'id' => 'googleAuth_button',
                    'onclick' => '
                        $.ajax({
                            url: "/user/profile/googleAuth",
                            type: "get",
                            cache: false,
                            success: function(html){  
                                $("#modalData").html(html);
                                $.fancybox.open($("#modalWindow"), {touch: false, toolbar: false, hash: false, clickSlide: false});
                            } 
                        });
                    '
                ]
            ]); ?>
        </div>
        <div class="col-md-12">
            <span class="text-muted"><?= Yii::t('models', 'user_attr_language'); ?></span>
            <?= $form->dropdownListGroup($model, 'language', ['widgetOptions' => ['data' => Yii::app()->params->languages],'label' => false]); ?>
        </div>
    </div>
    <div class="row bg-white mt-3 p-3">
        <div class="col-md-12"><h6><?= Yii::t('controllers', 'user_profile_index_lbl_historyLogin'); ?></h6><hr /></div>
        <div class="col-md-12">
            <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'historyLoginList',
            'dataProvider' => $historyLogin->search(5),
            'enableSorting' => true,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive pagerNew font-small'],
            'pagerCssClass' => 'mt-2 pagerNew',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'login_time', 'header' => Yii::t('models', 'attr_date'), 'type' => 'html', 'value' => 'date("d.m.Y H:i:s", strtotime($data->login_time))'],
                ['name' => 'login_ip'],
                ['name' => 'login_client'],
            ]
        ]); ?>
        </div>
    </div>
    <div class="row bg-white mt-3 p-3">
        <div class="col-md-6">
            <?php $this->widget('bootstrap.widgets.TbButton', [
                'context' => 'danger',
                'label' => Yii::t('core', 'btn_cemail'),
                'block' => true,
                'htmlOptions' => [
                    'onclick' => 'cemail(false)'
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?php $this->widget('bootstrap.widgets.TbButton', [
                'context' => 'primary',
                'label' => Yii::t('core', 'btn_cpass'),
                'block' => true,
                'htmlOptions' => [
                    'onclick' => 'cpassword(false)'
                ]
            ]); ?>
        </div>
    </div>
</div>




<?php $this->endWidget(); unset($form); ?>
