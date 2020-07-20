<div class="row mt-2">
<?php if($type == 'count'): ?>
    <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
        <span class="fas fa-users"></span> <?= $res['countUsers']; ?><br />
        <span class="text-muted small"><?= Admin::t('controllers', 'dashboard_index_lbl_countUsers'); ?></span>
    </div>
    <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
        <span class="fas fa-money-bill"></span> <?= $res['countPays']; ?><br />
        <span class="text-muted small"><?= Admin::t('controllers', 'dashboard_index_lbl_countPays'); ?></span>
    </div>
    <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
        <span class="fas fa-plus"></span> <?= $res['countProfit']; ?><br />
        <span class="text-muted small"><?= Admin::t('controllers', 'dashboard_index_lbl_countProfits'); ?></span>
    </div>
    <div class="col-md-3 p-3 text-center text-danger font-large">
        <span class="fas fa-minus"></span> <?= $res['countOuts']; ?><br />
        <span class="text-muted small"><?= Admin::t('controllers', 'dashboard_index_lbl_countOuts'); ?></span>
    </div>
<?php else: ?>
    <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
        <span class="fas fa-gem"></span> <?= $res['balance'].'$'; ?><br />
        <span class="text-muted small"><?= Admin::t('controllers', 'dashboard_index_lbl_countBalance'); ?></span>
    </div>
    <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
            <span class="fas fa-money-bill"></span> <?= $res['paySumm'].'$'; ?><br />
        <span class="text-muted small"><?= Admin::t('controllers', 'dashboard_index_lbl_countPaySumm'); ?></span>
    </div>
    <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
        <span class="fas fa-plus"></span> <?= $res['profitSumm'].'$'; ?><br />
        <span class="text-muted small"><?= Admin::t('controllers', 'dashboard_index_lbl_countProfitSumm'); ?></span>
    </div>
    <div class="col-md-3 p-3 text-center text-danger font-large">
        <span class="fas fa-minus"></span> <?= $res['outSumm'].'$'; ?><br />
        <span class="text-muted small"><?= Admin::t('controllers', 'dashboard_index_lbl_countOutSumm'); ?></span>
    </div>
<?php endif; ?>
</div>