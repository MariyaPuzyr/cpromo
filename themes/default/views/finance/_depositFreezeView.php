<div class="screenWidth">
    <div class="row mb-2">
        <div class="col-md-12">
            <h4 class="font-weight-bold"><?= Yii::t('core', 'modal_headGridDepositFreeze'); ?></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
                if($model){
                    $this->widget('bootstrap.widgets.TbGridView', [
                        'type' => 'stripped',
                        'dataProvider' => MHelper::getArrayProvider($model, 10),
                        'template' => '{items}{pager}',
                        'htmlOptions' => ['class' => 'table-responsive mt-0'],
                        'pagerCssClass' => 'mt-2 pageHistory',
                        'ajaxUpdate' => true,
                        'columns' => [
                            ['name' => 'operation_date', 'header' => Yii::t('models', 'attr_date'), 'type' => 'html', 'value' => 'date("d.m.Y H:i:s", strtotime($data->operation_date))'],
                            ['name' => 'operation_type', 'header' => Yii::t('models', 'attr_operation'), 'type' => 'html', 'value' => '$data->getOperationType($data->operation_type)'],
                            ['name' => 'operation_summ', 'header' => Yii::t('models', 'attr_summ'), 'type' => 'html', 'value' => '$data->operation_summ."$"'],
                        ]
                    ]);
                }
            ?>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12 text-center">
            <?= CHtml::link(Yii::t('controllers', 'finance_depositFreezeView_btn_why'), '#', ['id' => 'whyDepFreeze']); ?>
        </div>
    </div>
</div>