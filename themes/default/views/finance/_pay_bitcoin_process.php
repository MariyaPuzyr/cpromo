<div class="row">
    <div class="col-md-12 text-center">
        <h4><?=Yii::t('controllers', 'finance_pay_bitcoinInvoiceStatus', ['#invoice_id' => $invoice->operation_number]);?> </h4>
        <table class="table table-responsive">
            <tr>
                <td style="vertical-align: inherit;" class="text-left"><?= Yii::t('controllers', 'finance_pay_bitcoinInvoiceAmount'); ?></td>
                <td style="vertical-align: inherit;" class="text-right font-weight-bold"><?= $model->price_in_usd.'$ ('. $model->price_in_btc.'BTC)'; ?></td>
            </tr>
            <tr>
                <td style="vertical-align: inherit;" class="text-left"><?= Yii::t('controllers', 'finance_pay_bitcoinInvoicePending'); ?></td>
                <td style="vertical-align: inherit;" class="text-right font-weight-bold"><?= $amount_pending_btc ?></td>
            </tr>
            <tr>
                <td style="vertical-align: inherit;" class="text-left"><?= Yii::t('controllers', 'finance_pay_bitcoinInvoiceConfirmed'); ?></td>
                <td style="vertical-align: inherit;" class="text-right font-weight-bold"><?= $amount_paid_btc; ?></td>
            </tr>
            <tr>
                <td  colspan="2"style="vertical-align: inherit; text-align: center" class="p-0 pt-2">
                    <h4>
                    <?php
                        if($amount_paid_btc  == 0 && $amount_pending_btc == 0)
                            echo '<span class="badge badge-warning" style="width: 100%">'.Yii::t('controllers', 'finance_pay_bitcoinInvoicePay_notRecieved').'</span>';
                        elseif($amount_paid_btc < $model->price_in_btc)
                            echo '<span class="badge badge-info" style="width: 100%">'.Yii::t('controllers', 'finance_pay_bitcoinInvoicePay_waiting').'</span>';
                        else
                            echo '<span class="badge badge-success" style="width: 100%">'.Yii::t('controllers', 'finance_pay_bitcoinInvoicePay_success').'</span>';
                    ?>
                    </h4>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php if((date('Y-m-d H:i:s', strtotime('-30min')) <= $invoice->operation_date) || $amount_paid_btc): ?>
<hr />
<div class="row">
    <div class="col-md-12 text-center">
        <h6><?= $model->address; ?></h6>
        <?= CHtml::image('data:image/png;base64,'.$file);?>
    </div>
</div>
<?php endif; ?>