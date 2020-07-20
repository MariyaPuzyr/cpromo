<?php 
    $this->pageTitle = Admin::t('controllers', 'user_profile_edit_title', ['#referral_id' => $model->referral_id]);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/fileupload/vendor/jquery.ui.widget.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/fileupload/jquery.iframe-transport.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/fileupload/jquery.fileupload.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/vendor/fileupload/jquery.fileupload.css');
    Yii::app()->clientScript->registerScript('uploadProfilePhoto', "$('#profilePhoto').fileupload({
        url: '/admin/users/uploadPhoto?id=".$model->id."',
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
?>

<div class="container">
    <div class="row bg-white p-3">
        <div class="col-md-12 text-center">
            <h4><?= Admin::t('controllers', 'user_profile_edit_lbl_head', ['referral_id' => $model->referral_id]); ?></h4>
            <?= Chtml::link(Yii::t('core', 'btn_back'), '#', ['onclick' => 'history.back();', 'class' => 'text-muted small']);?>
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
        <?php $userBalance = Yii::app()->getModule('user')->getBalanceNow($model->id); ?>
        <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
            <?= $userBalance->pays ? $userBalance->pays.'$' : 0; ?><br />
            <span class="text-muted small"><?= Admin::t('controllers', 'user_profile_edit_lbl_pays'); ?></span>
        </div>
        <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
            <?= $userBalance->profits ? $userBalance->profits.'$' : 0; ?><br />
            <span class="text-muted small"><?= Admin::t('controllers', 'user_profile_edit_lbl_profits'); ?></span>
        </div>
        <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
            <?= $userBalance->outs ? $userBalance->outs.'$' : 0; ?><br />
            <span class="text-muted small"><?= Admin::t('controllers', 'user_profile_edit_lbl_outs'); ?></span>
        </div>
        <div class="col-md-3 p-3 text-center text-primary font-large">
            <?= $userBalance->balance ? $userBalance->balance.'$' : 0; ?><br />
            <span class="text-muted small"><?= Admin::t('controllers', 'user_profile_edit_lbl_now'); ?></span>
        </div>
    </div>
    <div class="row bg-white p-3">
        <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
            <?= $userBalance->deposit ? $userBalance->deposit.'$' : 0; ?><br />
            <span class="text-muted small"><?= Admin::t('controllers', 'user_profile_edit_lbl_deposit'); ?></span>
        </div>
        <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
            <?= $userBalance->coins ? $userBalance->coins.'CP' : 0; ?><br />
            <span class="text-muted small"><?= Admin::t('controllers', 'user_profile_edit_lbl_coins'); ?></span>
        </div>
        <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
            <?= $model->statusAccount->{'name_'.Yii::app()->language}; ?><br />
            <span class="text-muted small"><?= Yii::t('models', 'user_attr_status_account'); ?></span>
        </div>
        <div class="col-md-3 p-3 text-center text-primary font-large">
            <?php
                if(!$model->emailConfirm){
                    echo '<span class="badge badge-danger">'
                            .CHtml::ajaxLink('<span class="icon-check" style="vertical-align: revert;"></span>&nbsp;&nbsp;'.Yii::t('core', 'btn_confirmEmail_short'), 
                                $this->createUrl('/admin/users/confirmEmail', ['user_id' => $model->id]), 
                                ['success' => 'function(e){location.reload();}'], 
                                ['style' => 'color: #fff;']).'</span>';
                } else
                    echo $model->statusUserToBage('emailConfirm', $model->emailConfirm).'<br />';
            ?>
            <span class="text-muted small block"><?= Admin::t('controllers', 'user_profile_edit_lbl_emailConfirm'); ?></span>
        </div>
    </div>
    <hr />
    <div class="row bg-white p-3">
        <div class="col-md-4">
            <?php 
                $nPay = new UsersPays;
                $payForm = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
                    'id' => get_class($nPay),
                    'enableClientValidation' => false,
                    'enableAjaxValidation' => true,
                    'floating' => true,
                    'clientOptions' => [
                        'validateOnSubmit' => true,
                        'validateOnChange' => false,
                        'hideErrorMessage' => false,
                    ]
                ]);
                
                echo $payForm->textFieldGroup($nPay, 'operation_summ');
                $this->widget('bootstrap.widgets.TbButton', [
                    'label' => Admin::t('core', 'btn_pay'),
                    'context' => 'primary',
                    'block' => true,
                    'url' => $this->createUrl('/admin/users/addVirtualPay', ['user_id' => $model->id]),
                    'buttonType' => 'ajaxSubmit',
                    'ajaxOptions' => [
                        'success' => 'js: function(obj){
                            var data = JSON.parse(obj);
                            if(data.status == "success")
                               location.reload();
                            else {
                                $.each(data, function(key, val){
                                    var text_error = val+"";
                                    var rx = /\s*,\s*/;
                                    var error = text_error.split(rx);
                                    showNoty(error[0], "error");
                                    $("#"+key).addClass("alert-danger");
                                });
                            }
                        }'
                    ]
                ]);
                $this->endWidget(); unset($payForm);
            ?>
        </div>
        <div class="col-md-4">
            <?php 
                $nOut = new UsersOuts;
                $outForm = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
                    'id' => get_class($nPay),
                    'enableClientValidation' => false,
                    'enableAjaxValidation' => true,
                    'floating' => true,
                    'clientOptions' => [
                        'validateOnSubmit' => true,
                        'validateOnChange' => false,
                        'hideErrorMessage' => false,
                    ]
                ]);
                
                echo $outForm->textFieldGroup($nOut, 'operation_summ');
                $this->widget('bootstrap.widgets.TbButton', [
                    'label' => Admin::t('core', 'btn_out'),
                    'context' => 'primary',
                    'block' => true,
                    'url' => $this->createUrl('addVirtualOut', ['user_id' => $model->id]),
                    'buttonType' => 'ajaxSubmit',
                    'ajaxOptions' => [
                        'success' => 'js: function(obj){
                            var data = JSON.parse(obj);
                            if(data.status == "success"){
                                location.reload();
                            }else {
                                $.each(data, function(key, val){
                                    var text_error = val+"";
                                    var rx = /\s*,\s*/;
                                    var error = text_error.split(rx);
                                    showNoty(error[0], "error");
                                    $("#"+key).addClass("alert-danger");
                                });
                            }
                        }'
                    ]
                ]);
                $this->endWidget(); unset($outForm);
            ?>
        </div>
        <div class="col-md-4">
            <?php 
                $lStatuses = SprStatuses::getListForUpgradeAdmin($model->id);
                if($lStatuses) {
                    echo CHtml::form();
                        echo '<div class="form-label-group">';
                            echo CHtml::dropDownList('status_account', $status_account, $lStatuses, ['class' => 'form-control', 'id' => 'statusSelect']);
                        echo '</div>';
                        $this->widget('bootstrap.widgets.TbButton', [
                            'label' => Admin::t('core', 'btn_setStatus'),
                            'context' => 'primary',
                            'block' => true,
                            'url' => $this->createUrl('setAccountStatus', ['user_id' => $model->id]),
                            'buttonType' => 'ajaxSubmit',
                            'ajaxOptions' => [
                                'success' => 'js: function(obj){
                                    var data = JSON.parse(obj);
                                    if(data.status == "success")
                                        location.reload();
                                    else {
                                        $.each(data, function(key, val){
                                            var text_error = val+"";
                                            var rx = /\s*,\s*/;
                                            var error = text_error.split(rx);
                                            showNoty(error[0], "error");
                                            $("#"+key).addClass("alert-danger");
                                        });
                                    }
                                }'
                            ]
                        ]);
                    echo CHtml::endForm();
                }
            ?>
        </div>
    </div>
    <hr />
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
        'id' => get_class($model),
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'floating' => true,
        'clientOptions' => [
            'validateOnSubmit' => true,
            'validateOnChange' => true,
            'hideErrorMessage' => false,
        ]
    ]); ?>
    <div class="row bg-white p-3">
        <div class="col-md-3 offset-md-2 text-center mb-2-mobile">
            <?= CHtml::image($model->photo ? 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::getPathOfAlias('webroot.uploads').'/user_photo/'.$model->photo)) : $this->assetsBase.'/img/no_photo.jpg', '', ['class' => 'profilePhoto']); ?>
            <span class="btn btn-block fileinput-button btn-warning mt-2" id="fileTitleBut">
                <span><?php echo Yii::t('controllers', 'user_profile_index_btnUploadPhoto'); ?></span>
                <?php echo CHtml::activeFileField($model, 'loadFile', ['id' => 'profilePhoto', 'accept'=>'.jpg, .png']); ?>
            </span>
            <div id="progress" class="progress progress-bar progress-bar-striped active mt-1" style="display: none; margin-bottom: 0px!important;">
                <div id="progressBar" class="progress-bar progress-bar-info"></div>
            </div>
        </div>
        <div class="col-md-6 offset-md-1">
            <?php
                echo $form->textFieldGroup($model, 'email');
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
        <div class="col-md-12"><h6><?= Yii::t('controllers', 'user_profile_index_lbl_finance'); ?></h6><hr /></div>
        <div class="col-md-6">
            <?php
                echo $form->textFieldGroup($model, 'finance_payeer');
                echo $form->textFieldGroup($model, 'finance_prfmoney');
                echo $form->textFieldGroup($model, 'finance_usdtrc');
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->textFieldGroup($model, 'finance_bitcoin'); ?>
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
            <?= $form->checkboxGroup($model, 'subscribe_login'); ?>
        </div>
        <div class="col-md-12">
            <span class="text-muted"><?= Yii::t('models', 'user_attr_language'); ?></span>
            <?= $form->dropdownListGroup($model, 'language', ['widgetOptions' => ['data' => Yii::app()->params->languages],'label' => false]); ?>
        </div>
    </div>
    
    <?php if($dataPays): ?>
    <div class="row bg-white mt-3 p-3">
        <div class="col-md-12"><h6><?= Admin::t('controllers', 'user_profile_edit_lbl_historyPays'); ?></h6><hr /></div>
        <div class="col-md-12">
            <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'historyPays',
            'dataProvider' => $dataPays,
            'enableSorting' => true,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
            'pagerCssClass' => 'mt-2 pageNotify',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'operation_date', 'header' => Yii::t('models', 'attr_date'), 'type' => 'html', 'value' => 'date("d.m.Y H:i:s", strtotime($data->operation_date))'],
                ['name' => 'operation_number', 'header' => Yii::t('models', 'attr_operation')],
                ['name' => 'operation_system', 'header' => Yii::t('models', 'attr_system'), 'type' => 'raw', 'value' => '$data->getFinType($data->operation_system)'],
                ['name' => 'operation_summ', 'header' => Yii::t('models', 'attr_summ'), 'type' => 'html', 'value' => '$data->operation_summ."$"'],
                ['name' => 'operation_status', 'header' => Yii::t('models', 'attr_status'), 'type' => 'raw', 'value' => '$data->getPayStatusesToGrid($data->operation_status)'],
            ]
        ]); ?>
        </div>
    </div>
    <?php 
        endif;
        if($dataProfits):
    ?>
    <div class="row bg-white mt-3 p-3">
        <div class="col-md-12"><h6><?= Admin::t('controllers', 'user_profile_edit_lbl_historyProfits'); ?></h6><hr /></div>
        <div class="col-md-12">
            <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'historyProfits',
            'dataProvider' => $dataProfits,
            'enableSorting' => true,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
            'type' => 'stripped',
            'pagerCssClass' => 'mt-2 pagerNew',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'operation_date', 'header' => Yii::t('models', 'attr_date'), 'type' => 'html', 'value' => 'date("d.m.Y H:i:s", strtotime($data->operation_date))'],
                ['name' => 'operation_summ', 'header' => Yii::t('models', 'attr_summ'), 'type' => 'html', 'value' => '$data->operation_summ."$"'],
                ['name' => 'operation_type', 'header' => Yii::t('models', 'attr_status'), 'type' => 'raw', 'value' => '$data->profitTypeGrid($data->operation_type)'],
            ]
        ]); ?>
        </div>
    </div>
    <?php 
        endif;
        if($dataOuts):
    ?>
    <div class="row bg-white mt-3 p-3">
        <div class="col-md-12"><h6><?= Admin::t('controllers', 'user_profile_edit_lbl_historyOuts'); ?></h6><hr /></div>
        <div class="col-md-12">
            <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'historyOuts',
            'dataProvider' => $dataOuts,
            'enableSorting' => true,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
            'type' => 'stripped',
            'pagerCssClass' => 'mt-2 pagerNew',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'operation_date', 'header' => Yii::t('models', 'attr_date'), 'type' => 'html', 'value' => 'date("d.m.Y H:i:s", strtotime($data->operation_date))'],
                ['name' => 'operation_summ', 'header' => Yii::t('models', 'attr_summ'), 'type' => 'html', 'value' => '$data->operation_summ."$"'],
                ['name' => 'operation_status', 'header' => Yii::t('models', 'attr_status'), 'type' => 'raw', 'value' => '$data->getOutStatusesToGrid($data->operation_status)'],
                ['name' => 'update_at', 'header' => Yii::t('models', 'referralOut_attr_update_at'), 'type' => 'html', 'value' => '$data->update_at ? date("d.m.Y H:i:s", strtotime($data->update_at)) : ""'],
            ]
        ]); ?>
        </div>
    </div>
    <?php 
        endif; 
        if($dataHistory):
    ?>
    <div class="row bg-white mt-3 p-3">
        <div class="col-md-12"><h6><?= Yii::t('controllers', 'user_profile_index_lbl_historyLogin'); ?></h6><hr /></div>
        <div class="col-md-12">
            <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'historyLoginList',
            'dataProvider' => $dataHistory,
            'enableSorting' => true,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
            'type' => 'stripped',
            'pagerCssClass' => 'mt-2 pagerNew',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'login_time', 'header' => Yii::t('models', 'user_history_login_login_time'), 'type' => 'html', 'value' => 'date("d.m.Y H:i:s", strtotime($data->login_time))'],
                ['name' => 'login_ip', 'header' => Yii::t('models', 'user_history_login_login_ip')],
                ['name' => 'login_client', 'header' => Yii::t('models', 'user_history_login_login_client')],
            ]
        ]); ?>
        </div>
    </div>
    <?php 
        endif;
        if($dataStatus):
    ?>
    <div class="row bg-white mt-3 p-3">
        <div class="col-md-12"><h6><?= Admin::t('controllers', 'user_profile_edit_lbl_dataStatus'); ?></h6><hr /></div>
        <div class="col-md-12">
            <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'dataStatus',
            'dataProvider' => $dataStatus,
            'enableSorting' => true,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
            'type' => 'stripped',
            'pagerCssClass' => 'mt-2 pagerNew',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'operation_number', 'header' => Yii::t('models', 'attr_operation_number')],
                ['name' => 'operation_date', 'header' => Yii::t('models', 'attr_date'), 'type' => 'html', 'value' => 'date("d.m.Y H:i:s", strtotime($data->operation_date))'],
                ['name' => 'operation_summ', 'header' => Yii::t('models', 'attr_summ'), 'type' => 'html', 'value' => '$data->operation_summ."$"'],
                ['name' => 'status_id', 'header' => Admin::t('controllers', 'user_profile_edit_lbl_dataStatus_status_id')],
            ]
        ]); ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="row bg-white mt-3 p-3">
        <div class="col-md-3">
            <?php $this->widget('bootstrap.widgets.TbButton', [
                'context' => 'light',
                'label' => Yii::t('core', 'btn_cpass'),
                'block' => true,
                'disabled' => $model->status == $model::USTATUS_BANNED,
                'htmlOptions' => [
                    'onclick' => 'cpassword("'.$model->id.'")'
                ]
            ]); ?>
        </div>
        <div class="col-md-3">
            <?php 
                $this->widget('bootstrap.widgets.TbButton', [
                    'context' => 'warning',
                    'label' => Admin::t('core', 'btn_block'),
                    'block' => true,
                    'buttonType' => 'link',
                    'visible' => $model->status == $model::USTATUS_ACTIVE,
                    'url' => $this->createUrl('/admin/users/blockUser', ['id' => $model->id]),
                ]);
                $this->widget('bootstrap.widgets.TbButton', [
                    'context' => 'success',
                    'label' => Admin::t('core', 'btn_unblock'),
                    'block' => true,
                    'buttonType' => 'link',
                    'visible' => $model->status == $model::USTATUS_BANNED,
                    'url' => $this->createUrl('/admin/users/unblockUser', ['id' => $model->id]),
                ]);
            ?>
        </div>
        <div class="col-md-3">
            <?php $this->widget('bootstrap.widgets.TbButton', [
                'context' => 'danger',
                'label' => Yii::t('core', 'btn_delete'),
                'block' => true,
                'buttonType' => 'link',
                'url' => $this->createUrl('/admin/users/deleteFormProfile', ['id' => $model->id]),
                'htmlOptions' => [
                    'confirm' => Admin::t('controllers', 'user_deleteConfirm')
                ]
            ]); ?>
        </div>
        <div class="col-md-3">
            <?php $this->widget('bootstrap.widgets.TbButton', [
                'context' => 'danger',
                'label' => Admin::t('core', 'btn_offGoogle'),
                'block' => true,
                'buttonType' => 'ajaxLink',
                'url' => $this->createUrl('/admin/users/offGoogle', ['id' => $model->id]),
                'disabled' => $model->googleAuth ? false : true,
                'htmlOptions' => [
                    'id' => 'btn_offGoogle'
                ],
                'ajaxOptions' => [
                    'success' => 'function(response){
                        var data = JSON.parse(response);
                        if(data.status == "success"){
                            showNoty("'.Admin::t('core', 'ntf_googleOff_success').'", "success");
                            $("#btn_offGoogle").addClass("disabled");
                        }
                    }'
                ]
            ]); ?>
        </div>
    </div>
</div>




<?php $this->endWidget(); unset($form); ?>
    
    
    