<div style="min-width: 350px;">    
    <div class="row">
        <div class="col-md-12 text-muted">
            <?= Yii::t('controllers', 'dashboard_operatrionInfo_lbl_timeOperation', ['#date' => date('d.m.Y', strtotime($model->operation_date)), '#time' => date('H:i', strtotime($model->operation_date))]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-uppercase font-weight-bold">
            <h2><?= $model->operationTypeWithColor($model->operation_type); ?></h2>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-4">
            <h4><?= $model->getValueOnHistory($model->operation_type, $model->operation_type == $model::TYPE_PROFITCOIN ? $model->market->count : $model->operation_summ); ?></h4>
        </div><div class="col-md-8">
            <h4 class="text-primary"><?= Yii::t('controllers', $model->operation_type != $model::TYPE_PROFITCOIN ?'dashboard_operatrionInfo_lbl_sumAll' : 'dashboard_operatrionInfo_lbl_countAll', ['#summ' => $model->operation_summAll, '#count' => $model->market->countAll]); ?></h4>
        </div>
    </div>
    <hr />
    <span class="text-muted">
        <?= Yii::t('controllers', 'dashboard_operatrionInfo_lbl_number', ['#number' => $model->operation_number]); ?><br />
        <?= Yii::t('controllers', 'dashboard_operatrionInfo_lbl_system', ['#system' => MBaseModel::getFinType($model->operation_system)]); ?><br />
    </span>
</div>