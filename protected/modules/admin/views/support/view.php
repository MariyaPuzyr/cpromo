<?php $this->pageTitle = Admin::t('controllers', 'support_view_title'); ?>

<div class="row bg-white p-3">
    <div class="col-md-12 text-center">
        <h4><?= Admin::t('controllers', 'support_view_lbl_headMessage', ['#message_number' => $model->msg_number]); ?></h4>
        <?= Chtml::link(Yii::t('core', 'btn_back'), '#', ['onclick' => 'history.back();', 'class' => 'text-muted small']);?>
    </div>
</div>
<div class="row bg-white p-3">
    <div class="col-md-4">
        <?php $this->widget('bootstrap.widgets.TbButton', [
            'context' => 'info',
            'block' => true,
            'label' => Admin::t('core', 'btn_workMessage'),
            'buttonType' => 'link',
            'url' => $this->createUrl('/admin/support/workStatus', ['id' => $model->id, 'status' => $model::MSTATUS_WORK]),
            'disabled' => $model->msg_status != $model::MSTATUS_SEND,
        ]); ?>
    </div>
    <div class="col-md-4">
        <?php $this->widget('bootstrap.widgets.TbButton', [
            'context' => 'success',
            'block' => true,
            'label' => Admin::t('core', 'btn_closeMessage'),
            'buttonType' => 'link',
            'url' => $this->createUrl('/admin/support/workStatus', ['id' => $model->id, 'status' => $model::MSTATUS_COMPL]),
            'disabled' => $model->msg_status != $model::MSTATUS_WORK,
        ]); ?>
    </div>
    <div class="col-md-4">
        <?php $this->widget('bootstrap.widgets.TbButton', [
            'context' => 'danger',
            'block' => true,
            'label' => Admin::t('core', 'btn_disagreeMessage'),
            'buttonType' => 'link',
            'url' => $this->createUrl('/admin/support/workStatus', ['id' => $model->id, 'status' => $model::MSTATUS_DISA]),
            'disabled' => $model->msg_status != $model::MSTATUS_WORK,
        ]); ?>
    </div>
</div>
<div class="row bg-white p-3">
    <div class="col-md-12">
        <?php $this->widget('bootstrap.widgets.TbDetailView', [
            'type' => '',
            'id' => 'payInfo',
            'data' => $model,
            'attributes' => [
                ['name' => 'create_at', 'label' => Yii::t('models', 'attr_date'), 'type' => 'html', 'value' => date("d.m.Y H:i:s", strtotime($model->create_at))],
                ['name' => 'user_id', 'label' => Yii::t('models', 'attr_user_id'), 'type' => 'raw', 'value' => CHtml::link($model->user->referral_id, "#", ["onclick" => 'getReferralShortInfo("'.$model->user->referral_id.'", true); return false;'])],
                ['name' => 'msg_cat', 'type' => 'html', 'value' => $model->typeCategory($model->msg_cat)],
                ['name' => 'msg_status', 'type' => 'html', 'value' => $model->statusMessageGrid($model->msg_status)],
                ['name' => 'msg_text'],
            ]
        ]); ?>
    </div>
</div>
<div class="row bg-white p-3">
    <?php if($messages) {
        foreach($messages as $wm) {
            if($wm->answer)
                echo '<div class="col-md-12 text-left text-muted"><i class="fas fa-angle-double-right"></i> '.$wm->text.'</div>';
            else
                echo '<div class="col-md-12 text-right"><i class="fas fa-angle-double-left"></i> '.$wm->text.'</div>';
        }
    } ?>
</div>
<?php if($model->msg_status == $model::MSTATUS_WORK): ?>
<div class="row bg-white p-3 mt-2">
    <div class="col-md-12">
        <?php 
            $formModel = new FeedbackWork;
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
                'id' => get_class($formModel),
                'enableClientValidation' => false,
                'enableAjaxValidation' => true,
                'clientOptions' => [
                    'validateOnSubmit' => true,
                    'validateOnChange' => false,
                     'hideErrorMessage' => false,
                ]
            ]);
            
            echo $form->textareaGroup($formModel, 'text', ['label' => false]);
            echo CHtml::hiddenField(get_class($formModel).'[message_id]', $model->id);
            
            $this->widget('bootstrap.widgets.TbButton', [
                'context' => 'warning',
                'block' => true,
                'label' => Yii::t('core', 'btn_send'),
                'buttonType' => 'submit',
            ]);
            
            $this->endWidget(); unset($form);
        ?>
    </div>
</div>
<?php endif; ?>