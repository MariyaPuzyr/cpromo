<?php $this->pageTitle = Yii::t('controllers', 'support_index_title'); ?>

<div class="row justify-content-md-center">
    <div class="col-lg-5">
        <h4 class="text-center mb-4"><?= Yii::t('controllers', 'support_index_btn_addMessage'); ?></h4>
        <?php 
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
                'id' => get_class($model),
                'action' => $this->createUrl('index'),
                'enableClientValidation' => false,
                'enableAjaxValidation' => true,
                'clientOptions' => [
                    'validateOnSubmit' => true,
                    'validateOnChange' => false,
                    'hideErrorMessage' => false,
                ]
            ]);
            
            $umodel = Yii::app()->user->model();
            echo $form->dropdownListGroup($model, 'msg_cat', ['widgetOptions' => ['data' => $model->typeCategory()], 'label' => false]);
            echo $form->textareaGroup($model, 'msg_text', ['label' => false]);
            
            $this->widget('bootstrap.widgets.TbButton', [
                'context' => 'primary',
                'block' => true,
                'label' => Yii::t('core', 'btn_send'),
                'buttonType' => 'submit',
                'htmlOptions' => [
                    'class' => 'big-btn'
                ]
            ]);
            
            $this->endWidget(); unset($form);
        ?>
    </div>
</div>
<hr />
<div class="row gutters">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header"><strong><?= Yii::t('controllers', 'support_index_btn_listMessages'); ?></strong></div>
            <div class="card-body bg-white">
                <?php 
                    $this->widget('bootstrap.widgets.TbGridView', [
                        'id' => 'messageList',
                        'type' => 'stripped',
                        'dataProvider' => $model->search(5),
                        'enableSorting' => true,
                        'template' => '{items}{pager}',
                        'htmlOptions' => ['class' => 'table-responsive tableNotify'],
                        'pagerCssClass' => 'mt-2 pagerNew',
                        'ajaxUpdate' => true,
                        'rowHtmlOptionsExpression' => '["data-number" => $data->msg_number]',
                        'ajaxUpdate' => true,
                        'columns' => [
                            ['name' => 'msg_number', 'type' => 'raw'],
                            ['name' => 'msg_cat', 'type' => 'raw', 'value' => '$data->typeCategory($data->msg_cat)'],
                            ['name' => 'msg_text', 'type' => 'raw', 'value' => 'substr($data->msg_text, 0, 25)."..."'],
                            ['name' => 'msg_status', 'type' => 'raw', 'value' => '$data->statusMessageGrid($data->msg_status)'],
                            ['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{open}&nbsp;{cancel}', 'buttons' => [
                                'open' => [
                                    'icon' => 'fas fa-search',
                                    'url' => 'Yii::app()->controller->createUrl("/support/getFeedbackMessageInfo", ["id" => $data->msg_number])',
                                    'options' => [
                                        'data-toggle' => 'tooltip',
                                        'title' => Yii::t('controllers', 'feedback_btn_openMessage'),
                                        'ajax' => [
                                            'url' => 'js:$(this).attr("href")',
                                            'type' => 'get',
                                            'cache' => 'false',
                                            'beforeSend' => 'function(){$("#hidescreen, #loadingData").fadeIn(10);}',
                                            'success' => 'function(html){
                                                $("#modalData").html(html);
                                                $("#hidescreen, #loadingData").fadeOut(10);
                                                $.fancybox.open($("#modalWindow"));
                                            }'    
                                        ], 
                                    ],
                                ],
                                'cancel' => [
                                    'icon' => 'far fa-window-close',
                                    'url' => 'Yii::app()->controller->createUrl("/support/cancelFeedback", ["id" => $data->id])',
                                    'options' => [
                                        'data-toggle' => 'tooltip',
                                        'title' => Yii::t('controllers', 'feedback_btn_cancelMessage'),
                                        'confirm' => Yii::t('controllers', 'feedback_btn_cancelMessageConfirm'),
                                        'ajax' => [
                                            'url' => 'js:$(this).attr("href")',
                                            'beforeSend' => 'function(){$("#hidescreen, #loadingData").fadeOut(10);}',
                                            'success' => 'function(data){
                                                var obj = JSON.parse(data);
                                                if(obj.status == "success") {
                                                    $("#hidescreen, #loadingData").fadeOut(10);
                                                    location.reload();
                                                }
                                            }'    
                                        ], 
                                    ],
                                    'visible' => 'in_array($data->msg_status, [$data::MSTATUS_SEND, $data::MSTATUS_WORK])'
                                ],
                            ]]
                        ]
                    ]); ?>
            </div>
        </div>
    </div>
</div>
<hr />
<div class="row gutters">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <h5><?= Yii::t('controllers', 'support_index_lbl_faq'); ?></h5>
        <div class="row mt-3 p-3">
        <?php if(!$faq): ?>
            <h6 class="text-muted text-center"><?= Yii::t('controllers', 'support_index_faqEmpty');?></h6>
        <?php else: ?>
            <div class="accordion" id="accordionFaq" style="width: 100%">
                <?php foreach($faq as $quest): ?>
                    <div class="card">
                        <div class="card-header" id="heading<?= $quest->id; ?>">
                            <h5 class="mb-0">
                                <button class="btn btn-link text-primary" style="text-decoration: none!important;" type="button" data-toggle="collapse" data-target="#collapse<?= $quest->id; ?>" aria-expanded="false" aria-controls="collapse<?= $quest->id; ?>">
                                   <?php
                                        $qst = 'question_'.Yii::app()->language;
                                        $qst_def = 'question_'.Yii::app()->params->defaultLanguage;
                                        echo ($quest->{$qst}) ? $quest->{$qst} : $quest->{$qst_def};
                                    ?>
                                </button>
                            </h5>
                        </div>
                        <div id="collapse<?= $quest->id; ?>" class="collapse" aria-labelledby="heading<?= $quest->id; ?>" data-parent="#accordionFaq">
                            <div class="card-body text-justify bg-white">
                                <?php
                                    $ans = 'answer_'.Yii::app()->language;
                                    $ans_def = 'answer_'.Yii::app()->params->defaultLanguage;
                                    echo ($quest->{$ans}) ? $quest->{$ans} : $quest->{$ans_def};
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        </div>
    </div>
</div>