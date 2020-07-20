<div class="row screenWidth">
    <div class="col-md-12">
        <h4><?=Yii::t('controllers', 'finance_pay_prizmStatus', ['#invoice_id' => $pay->pay_number]);?> </h4>
        <?php if(!$prizm_pay): ?>
            <h5 class="mt-4 text-danger text-center"><?= Yii::t('controllers', 'finance_pay_prizmStatusNotComplete');?></h5>
            <?php if(date('Y-m-d H:i:s', strtotime('-30min')) <= $pay->pay_date): ?>
                <hr />
                <div class="row text-center">
                    <div class="col-md-12">
                        <h5><?= Yii::t('controllers', 'finance_pay_plsSendPricePrizm', ['#price' => $pay->pay_summConvert]);?></h5>
                        <h4><?= $address; ?></h4>
                        <h6><?= Yii::t('models', 'referralPays_attr_pay_ident', ['#pub_key' => $pub_key,'#pay_number' => $pay->pay_number]);?></h6>
                        <img src="data:image/png;base64,<?=$file;?>" />
                        <span class="mt-2 block font-small"><?= CHtml::link(Yii::t('core', 'btn_forward'), $link, ['target' => '_blank']);?></span>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <h6 class="mt-4 text-success text-center"><?=Yii::t('controllers', 'finance_pay_prizmStatusComplete', ['#date' => MHelper::formBeautyDate($prizm_pay->tr_date), '#summ' => $pay->pay_summConvert]);?></h6>
        <?php endif; ?>
    </div>
</div>