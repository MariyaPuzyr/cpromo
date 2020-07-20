<div class="row">
    <div class="col-md-12">
        <h2 class="h3 text-center"><?= Yii::t('core', 'modal_warningOut_title');?></h2>
        <?= Yii::t('core', 'modal_watringOut_text'); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', [
            'label' => Yii::t('core', 'btn_notRemind'),
            'context' => 'primary',
            'block' => true,
            'buttonType' => 'link',
            'htmlOptions' => ['class' => 'mt-3'],
            'url' => $this->createUrl('/user/profile/warning', ['not_remind' => true]),
        ]); ?>
    </div>
</div>