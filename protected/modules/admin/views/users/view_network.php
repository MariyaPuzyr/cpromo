<?php $this->pageTitle = Admin::t('controllers', 'user_viewNetwork_title', ['#user' => $model->username.'('.$model->email.', '.$model->referral_id.')']); ?>
<div class="row bg-white p-3">
    <div class="col-md-12 text-center">
        <h4><?= Admin::t('controllers', 'user_viewNetwork_title', ['#user' => $model->username.'('.$model->email.', '.$model->referral_id.')']); ?></h4>
        <?= Chtml::link(Yii::t('core', 'btn_back'), '#', ['onclick' => 'history.back();', 'class' => 'text-muted small']);?>
    </div>
</div>
<div class="row bg-white p-3 mt-4">
    <div class="col-md-12">
        <div class="row gutters mt-3">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-center">
            <?php if($tabs): ?>
                <div class="card bg-white">
                    <div class="card-header"><strong><?= Yii::t('controllers', 'rnetwork_index_lbl_relationList'); ?></strong></div>
                    <div class="card-body">
                        <?php $this->widget('ExtPillsTabs', [
                            'type' => 'pills',
                            'tabs' => $tabs,
                        ]); ?>  
                    </div>
                </div>
            <?php else: ?>
                <h5><?= Yii::t('controllers', 'rnetwork_index_lbl_relationList_empty'); ?></h5>
            <?php endif; ?>
            </div>
        </div>
        <?php if($tabsOut): ?>
        <div class="row gutters mt-3">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-center">
                <div class="card bg-white">
                    <div class="card-header"><strong><?= Yii::t('controllers', 'rnetwork_index_lbl_relationOutList', ['#help' => false]); ?></strong></div>
                    <div class="card-body">
                        <?php $this->widget('ExtPillsTabs', [
                            'type' => 'pills',
                            'tabs' => $tabsOut
                        ]);?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

