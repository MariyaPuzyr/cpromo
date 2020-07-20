<div class="row">
    <div class="col-md-12 text-center">
        <h4><?= Yii::t('core', 'modal_showGoogle_title');?></h4>
        <?= CHtml::image($img); ?><br />
        <span class="text-muted text-center small"><?= $key; ?></span>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-12 text-center">
        <?php 
            $formCheck = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
                'id' => 'checkCode',
                'enableClientValidation' => true,
                'enableAjaxValidation' => true,
                'floating' => true,
                'withOutPlaceholder' => true,
                'clientOptions' => [
                    'validateOnSubmit' => true,
                    'validateOnChange' => false,
                    'hideErrorMessage' => false,
                ]
            ]); 
                echo CHtml::textField('code', '', ['min' => 6, 'max' => 6, 'required' => true,
                    'class' => 'form-control inline mr-2 pt-1',
                    'placeholder' => Yii::t('controllers', 'user_profile_index_lbl_enterGoogleCode')]);
                $this->widget('bootstrap.widgets.TbButton', [
                    'icon' => 'fas fa-check',
                    'context' => 'primary',
                    'url' => $this->createUrl('googleAuth', ['state' => true]),
                    'buttonType' => 'ajaxSubmit',
                    'ajaxOptions' => [
                        'dataType' => 'json',
                        'success' => 'function(data) {
                            if(data.status == "error")
                                alert("'.Yii::t('controllers', 'user_profile_index_lbl_errorCheckCode').'");
                            else {
                                $.fancybox.close($("#modalWindow"));
                                location.reload();
                            }
                        }'
                    ]
                ]);
            $this->endWidget();
            unset($formCheck);
        ?>
    </div>
</div>