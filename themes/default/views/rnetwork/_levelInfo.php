<div style="min-width: 600px;">
    <div class="row">
        <div class="col-md-12">
            <h4 class="font-weight-bold"><?= Yii::t('controllers', 'rnetwork_levelInfo_title'); ?></h4>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12">
            <?php $this->widget('bootstrap.widgets.TbGridView', [
                'id' => 'levelListInfo',
                'dataProvider' => $model->search(),
                'type' => 'stripped',
                'enableSorting' => false,
                'template' => '{items}',
                'htmlOptions' => ['class' => 'table-responsive'],
                'rowCssClassExpression' => '$data->rowExpression()',
                'columns' => [
                    ['name' => 'name_'.Yii::app()->language, 'header' => false, 'htmlOptions' => ['class' => 'font-weight-bold']],
                    ['name' => 'status', 'type' => 'raw', 'value' => '$data->getStatusName($data->status)'],
                    ['name' => 'level_percente', 'type' => 'html', 'value' => '$data->level_percente."%"'],
                ]
            ]); ?>
        </div>
    </div>
</div>