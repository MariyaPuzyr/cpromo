index.php


<div class="row gutters justify-content-md-center">
    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 text-center border rounded border-red">
        <h6 id="personal_referral_link_network_head" class="mb-1"><?= Yii::t('controllers', 'rnetwork_index_lbl_personalLink');?></h6>
        <h5><span id="rLink" message="<?=Yii::t('controllers', 'rnetwork_index_ntf_linkCopied');?>"><?= $this->createAbsoluteUrl('/register?referral_id='.Yii::app()->user->model()->referral_id); ?></span></h5>
    </div>
    <span class="far fa-copy text-primary ml-2 fa-3x" id="rCopyLink" style="cursor: pointer"></span>
</div>

<div class="col-xl-<?= $userData->status_account >= 4 ? 6 : 12;?> col-lg-<?= $userData->status_account >= 4 ? 6 : 12;?> col-md-<?= $userData->status_account >= 4 ? 6 : 12;?> col-sm-12">
        <?php $this->widget('bootstrap.widgets.TbButton', [
            'block' => true,
            'context' => 'primary',
            'label' => Yii::t('controllers', 'rnetwork_index_btn_invite'),
            'htmlOptions' => [
                'class' => 'big-btn text-center',
                'id' => 'btn_invite'
            ]
        ]); ?>
    </div>
