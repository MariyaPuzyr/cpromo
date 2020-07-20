<div class="row gutters mt-3">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header"><strong><?= Yii::t('controllers', 'finance_index_lbl_profits'); ?></strong></div>
            <div class="card-body bg-white">
                <?php 
                    $this->widget('bootstrap.widgets.TbGridView', [
                        'id' => 'profitsTable',
                        'type' => 'stripped',
                        'dataProvider' => $profits->search(10),
                        'enableSorting' => true,
                        'template' => '{items}{pager}',
                        'pagerCssClass' => 'mt-2 pagerNew',
                        'htmlOptions' => ['class' => 'table-responsive'],
                        'ajaxUpdate' => true,
                        'columns' => [
                            ['name' => 'operation_date', 'type' => 'raw', 'value' => 'date("d.m.Y", strtotime($data->operation_date))'],
                            ['name' => 'operation_summ', 'type' => 'raw', 'value' => '$data->operation_summ."$"'],
                            ['name' => 'operation_type', 'type' => 'raw', 'value' => '$data->profitTypeGrid($data->operation_type)',],
                        ]
                    ]); ?>
            </div>
        </div>
    </div>
</div>