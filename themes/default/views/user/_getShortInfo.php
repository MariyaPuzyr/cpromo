<h5 class="mb-3"><?= Yii::t('core', 'modal_shortInfo_title', ['#referral_id' => $model->referral_id]); ?></h5>
<div class="row">
    <div class="col-md-12">
        <?php 
            $this->widget('bootstrap.widgets.TbDetailView', [
                'type' => '',
                'id' => 'referralShortInfo',
                'data' => [
                    'status_account' => $model->status_account,
                    'firstname' => $model->firstname,
                    'email' => $model->email,
                    'phone' => $model->phone,
                    'finance_payeer' => $model->finance_payeer,
                    'finance_card' => $model->finance_card,
                    'finance_prfmoney' => $model->finance_prfmoney,
                    'finance_usdtrc' => $model->finance_usdtrc,
                ],
                'attributes' => [
                    ['name' => 'status_account', 'label' => Yii::t('models', 'attr_status')],
                    ['name' => 'firstname', 'label' => Yii::t('models', 'user_attr_firstname')],
                    ['name' => 'email', 'label' => Yii::t('models', 'user_attr_email')],
                    ['name' => 'phone', 'label' => Yii::t('models', 'user_attr_phone')],
                    ['name' => 'finance_payeer', 'label' => Yii::t('models', 'base_attr_finType_payeer'), 'visible' => Yii::app()->user->getIsSuperuser() ? true : false],
                    ['name' => 'finance_card', 'label' => Yii::t('models', 'base_attr_finType_card'), 'visible' => Yii::app()->user->getIsSuperuser() ? true : false],
                    ['name' => 'finance_prfmoney', 'label' => Yii::t('models', 'base_attr_finType_prfmoney'), 'visible' => Yii::app()->user->getIsSuperuser() ? true : false],
                    ['name' => 'finance_usdtrc', 'label' => Yii::t('models', 'base_attr_finType_coinspay_out_usdtrc20'), 'visible' => Yii::app()->user->getIsSuperuser() ? true : false],
                ]
            ]);
        ?>
    </div>
</div>
<div class="row mt-30">
    <div class="col-md-4">
        <div class="block border rounded">
            <div class="block-content block-content-full text-center p-2">
                <div class="mb-10"><?=Yii::t('models', 'user_attr_balance');?></div>
                <div class="font-size-h4 font-w600"><?= $model->now_balance.'$';?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="block border rounded">
            <div class="block-content block-content-full text-center p-2">
                <div class="mb-10"><?=Yii::t('models', 'user_attr_coins');?></div>
                <div class="font-size-h4 font-w600"><?=$model->now_coins.'CP';?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 ">
        <div class="block border rounded">
            <div class="block-content block-content-full text-center p-2">
                <div class="mb-10"><?=Yii::t('models', 'user_attr_profits');?></div>
                <div class="font-size-h4 font-w600"><?=$model->now_profit.'0$';?></div>
            </div>
        </div>
    </div>
</div>
<?php if($by_ref) { ?>
    <div class="row mt-3">
        <div class="col-md-12 text-center" id="refRelation">
            <?php
                arsort($by_ref);
                $last = end($by_ref);
                foreach($by_ref as $key => $val) {
                    echo CHtml::link($val['referral_id'], '#', ['onclick' => 'getReferralShortInfo($(this))', 'class' => 'text-primary font-weight-bold']);
                    
                    if($val['level'] != $last['level'])
                        echo ' <i class="fas fa-angle-double-left" style="vertical-align: text-top"></i> ';
                }
            ?>
        </div>
    </div>
<?php } ?>