<?php $this->pageTitle = Admin::t('controllers', 'finance_view_title'); ?>

<div class="container">
    <div class="row bg-white p-3">
        <div class="col-md-12 text-center">
            <h4><?= Admin::t('controllers', 'finance_view_lbl_head', ['#operation_number' => $model->operation_number]); ?></h4>
            <?= Chtml::link(Yii::t('core', 'btn_back'), '#', ['onclick' => 'history.back();', 'class' => 'text-muted small']);?>
        </div>
    </div>
    <div class="row bg-white p-3">
        <div class="col-md-12">
            <?php $this->widget('bootstrap.widgets.TbDetailView', [
                'type' => '',
                'id' => 'payInfo',
                'data' => $model,
                'attributes' => [
                    ['name' => 'operation_date', 'label' => Yii::t('models', 'attr_date'), 'type' => 'html', 'value' => date("d.m.Y H:i:s", strtotime($model->operation_date))],
                    ['name' => 'operation_number', 'label' => Yii::t('models', 'referralPays_attr_operation_number')],
                    ['name' => 'operation_system', 'label' => Yii::t('models', 'base_attr_finType'), 'type' => 'html', 'value' => $model->getPayMode($model->operation_system)],
                    ['name' => 'operation_summ', 'label' => Yii::t('models', 'attr_summ'), 'type' => 'html', 'value' => $model->operation_summ.'$'],
                    ['name' => 'operation_status', 'label' => Yii::t('models', 'attr_status'), 'type' => 'html', 'value' => $model->getPayStatusesToGrid($model->operation_status)],
                    ['name' => 'user_id', 'label' => Yii::t('models', 'attr_user_id'), 'type' => 'raw', 'value' => CHtml::link($model->user->referral_id, "#", ["onclick" => 'getReferralShortInfo("'.$model->user->referral_id.'", true); return false;'])],
                    ['name' => 'user_ip', 'label' => Yii::t('models', 'attr_user_ip')],
                ]
            ]); ?>
        </div>
    </div>
    <?php if($model->operation_status == $model::PSTATUS_WAIT): ?>
        <div class="row bg-white p-3">
            <div class="col-md-12">
                <?php $this->widget('bootstrap.widgets.TbButton', [
                    'context' => 'warning',
                    'block' => true,
                    'label' => Admin::t('core', 'btn_confirmPay'),
                    'buttonType' => 'link',
                    'url' => $this->createUrl('/admin/finance/confirmPay', ['operation_id' => $model->id])
                ]); ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="row bg-white mt-3 p-3">
        <div class="col-md-12 text-center">
            <h5><?= Admin::t('controllers', 'finance_view_lbl_headDetail', ['#operation_system' => $model->getPayMode($model->operation_system)]); ?></h5>
        </div>
    </div>
    <div class="row bg-white p-3">
        <div class="col-md-12">
            <?php if($model->operation_system == $model::FIN_BITCOIN): ?>
                <table class="table" style="width: 100%">
                <tr>
                    <td style="width: 50%; vertical-align: inherit;"><?= Yii::t('controllers', 'finance_operation_bitcoinInvoiceAmount'); ?></td>
                    <td style="width: 50%; vertical-align: inherit;" class="text-right font-weight-bold"><?= $data->price_in_usd.'$ ('. MHelper::formatCurrency($data->price_in_btc).'BTC)'; ?></td>
                </tr>
                <tr>
                    <td style="width: 50%; vertical-align: inherit;"><?= Yii::t('controllers', 'finance_operation_bitcoinInvoicePending'); ?></td>
                    <td style="width: 50%; vertical-align: inherit;" class="text-right font-weight-bold"><?= $amount_pending_btc ?></td>
                </tr>
                <tr>
                    <td style="width: 50%; vertical-align: inherit;"><?= Yii::t('controllers', 'finance_operation_bitcoinInvoiceConfirmed'); ?></td>
                    <td style="width: 50%; vertical-align: inherit;" class="text-right font-weight-bold"><?= $amount_paid_btc; ?></td>
                </tr>
                <tr>
                    <td  colspan="2"style="vertical-align: inherit; text-align: center" class="p-0 pt-2">
                        <h4>
                        <?php
                            if($amount_paid_btc  == 0 && $amount_pending_btc == 0)
                                echo '<span class="badge badge-warning" style="width: 100%">'.Yii::t('controllers', 'finance_operation_bitcoinInvoicePay_notRecieved').'</span>';
                            elseif($amount_paid_btc < $data->price_in_btc)
                                echo '<span class="badge badge-info" style="width: 100%">'.Yii::t('controllers', 'finance_operation_bitcoinInvoicePay_waiting').'</span>';
                            else
                                echo '<span class="badge badge-success" style="width: 100%">'.Yii::t('controllers', 'finance_operation_bitcoinInvoicePay_success').'</span>';
                        ?>
                        </h4>
                    </td>
                </tr>
                </table>
            <?php 
                elseif($model->operation_system == $model::FIN_PAYEER):
                    $this->widget('bootstrap.widgets.TbDetailView', [
                        'type' => '',
                        'id' => 'payInfo',
                        'data' => $data,
                        'attributes' => [
                            ['name' => 'sign', 'label' => Yii::t('models', 'referralPaysPayeer_attr_sign'),],
                            ['name' => 'payeer_id', 'label' => Yii::t('models', 'referralPaysPayeer_attr_payeer_id')],
                            ['name' => 'payeer_status', 'label' => Yii::t('models', 'attr_status')],
                            ['name' => 'payeer_ps', 'label' => Yii::t('models', 'referralPaysPayeer_attr_payeer_ps')],
                        ]
                    ]);
                elseif($model->operation_system == $model::FIN_PRIZM):
                    if(!$data) {
                        echo '<h6 class="mt-4 text-danger text-center">'.Yii::t('controllers', 'finance_operation_prizmStatusNotComplete').'</h6>';
                    } else {
                        echo '<h6 class="mt-4 text-success text-center">'.Yii::t('controllers', 'finance_operation_prizmStatusComplete', ['#date' => MHelper::formBeautyDate($data->tr_date), '#summ' => $model->operation_summConvert]).'</h6>';
                    }
                endif;
            ?>
        </div>
    </div>
</div>    
