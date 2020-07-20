<div>
    <div class="row">
        <div class="col-md-12">
            <h4 class="font-weight-bold"><?= Yii::t('controllers', 'feedback_messageInfo_title', ['#number' => $model->msg_number]); ?></h4>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12">
            <?php 
                $this->widget('bootstrap.widgets.TbDetailView', [
                    'type' => '',
                    'data' => [
                        'cat' => Feedback::model()->typeCategory($model->msg_cat),
                        'date' => date('d.m.Y', strtotime($model->create_at)),
                        'status' => Feedback::model()->statusMessage($model->msg_status),
                    ],
                    'attributes' => [
                        ['name' => 'cat', 'label' => Yii::t('models', 'feedback_attr_msg_cat')],
                        ['name' => 'date', 'label' => Yii::t('models', 'attr_date')],
                        ['name' => 'status', 'label' => Yii::t('models', 'attr_status')],
                    ]
                ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 small" style="text-align: justify">
            <?= $model->msg_text; ?>
        </div>
    </div>
    <hr />
    <div class="row" id="messagesToView">
        <?php 
            if($work)
                foreach($work as $wm)
                    if($wm->answer)
                        echo '<div class="col-md-12 text-left text-muted"><i class="fas fa-angle-double-right"></i> '.$wm->text.'</div>';
                    else
                        echo '<div class="col-md-12 text-right"><i class="fas fa-angle-double-left"></i> '.$wm->text.'</div>';
        ?>
    </div>
    <?php if(in_array($model->msg_status, [$model::MSTATUS_SEND, $model::MSTATUS_WORK])): ?>
    <div class="row mt-2">
        <div class="col-md-12">
            <?php 
                $formModel = new FeedbackWork;
                $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
                    'id' => get_class($formModel),
                    'action' => $this->createUrl('answerToMessage', ['message' => $model->id]),
                    'enableClientValidation' => false,
                    'enableAjaxValidation' => true,
                    'clientOptions' => [
                        'validateOnSubmit' => true,
                        'validateOnChange' => false,
                        'hideErrorMessage' => false,
                    ]
                ]);
            
                $umodel = Yii::app()->user->model();
                    echo $form->textareaGroup($formModel, 'text', ['label' => false]);
            
                $this->widget('bootstrap.widgets.TbButton', [
                    'context' => 'warning',
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
    <?php endif; ?>
</div>