<div style="min-width: 600px;">
    <div class="row">
        <div class="col-md-12">
            <h4 class="font-weight-bold"><?= Yii::t('controllers', 'rnetwork_statusInfo_title'); ?></h4>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12">
            <?php $this->widget('bootstrap.widgets.TbGridView', [
                'id' => 'statusListInfo',
                'dataProvider' => $model->search(),
                'type' => 'stripped',
                'enableSorting' => false,
                'template' => '{items}',
                'htmlOptions' => ['class' => 'table-responsive'],
                'rowCssClassExpression' => '$data->rowExpression()',
                'columns' => [
                    ['name' => 'name_'.Yii::app()->language, 'header' => false, 'htmlOptions' => ['class' => 'font-weight-bold']],
                    ['name' => 'price', 'type' => 'raw', 'value' => '$data->price."$"'],
                    ['name' => 'max_coin_buy_summ', 'type' => 'raw', 'value' => '$data->max_coin_buy_summ."$"'],
                    ['name' => 'max_levels'],
                    ['name' => 'out_count'],
                    ['name' => 'out_count_period', 'type' => 'raw', 'value' => '$data->getOutPeriodType($data->out_count_period)'],
                    ['name' => 'out_max_summ', 'type' => 'raw', 'value' => '$data->out_max_summ."$"'],
                ]
            ]); ?>
        </div>
    </div>
</div>