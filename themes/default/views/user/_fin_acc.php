<div class="row">
    <div class="col-md-12">
        <h2 class="h3 text-center"><?= Yii::t('core', 'modal_setFinAcc_title');?></h2>
        <?php
            $formFin = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
                'id' => 'finACC',
                'enableClientValidation' => false,
                'enableAjaxValidation' => true,
                'floating' => true,
                'withOutPlaceholder' => true,
                'clientOptions' => [
                    'validateOnSubmit' => true,
                    'validateOnChange' => true,
                    'hideErrorMessage' => false,
                ]
            ]);
            
            echo $formFin->errorSummary($model);
            if (!$model->finance_payeer)
                echo $formFin->textFieldGroup($model, 'finance_payeer', ['hint' => Yii::t('models', 'user_attr_finance_procent', ['#procent' => 2])]);
            else
                echo '<p class="mb-3 text-danger">'.Yii::t('models', 'user_attr_finance_payeer_req', ['#wallet' => CHtml::encode($model->finance_payeer)]).'</p>';
            
            if (!$model->finance_prfmoney)
                echo $formFin->textFieldGroup($model, 'finance_prfmoney', ['hint' => Yii::t('models', 'user_attr_finance_procent', ['#procent' => 2])]);
            else
                echo '<p class="mb-3 text-danger">'.Yii::t('models', 'user_attr_finance_prfmoney_req', ['#wallet' => CHtml::encode($model->finance_prfmoney)]).'</p>';
            
            if (!$model->finance_usdtrc)
                echo $formFin->textFieldGroup($model, 'finance_usdtrc', ['hint' => Yii::t('models', 'user_attr_finance_procent', ['#procent' => 2])]);
            else
                echo '<p class="mb-3 text-danger">'.Yii::t('models', 'user_attr_finance_usdtrc_req', ['#wallet' => CHtml::encode($model->finance_usdtrc)]).'</p>';
            
            $this->widget('bootstrap.widgets.TbButton', [
                'context' => 'primary',
                'block' => true,
                'buttonType' => 'ajaxSubmit',
                'label' => Yii::t('core', 'btn_save'),
                'url' => $this->createUrl('/user/profile/SetFinanceAcc'),
                'ajaxOptions' => [
                    'type' => 'post',
                    'dataType' => 'json',
                    'data' => 'js: $("#finACC").serialize()',
                    'success' => 'function(data){
                        if(data.status == "success"){
                            showNoty("'.Yii::t('models', 'user_attr_finance_success').'", "success");
                            $.fancybox.close($("#modalWindow"));   
                        }else{
                            $.each(data, function(key, val){
                                $("#"+key).addClass("is-invalid");
                                var text_error = val+"";
                                var rx = /\s*,\s*/;
                                var error = text_error.split(rx);
                                $.notify(""+error[0], "error");
                            });
                        }
                    }'
                ],
                'htmlOptions' => [
                    'id' => 'saveFin',
                ]
            ]);
            
            $this->endWidget(); unset($formFin);
        ?>
    </div>
</div>