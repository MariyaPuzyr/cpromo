<?php $this->pageTitle = Admin::t('controllers', 'news_index_title'); ?>

<div class="row bg-white p-3">
    <div class="col-md-12">
        <h6 class="mobile-text-center  pt-3 pr-3 pl-3" style="vertical-align: inherit;"><?= Admin::t('controllers', 'news_index_lbl_headList'); ?></h6>
    </div>
</div> 
<div class="row bg-white p-3">
    <div class="col-md-12">
        <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'newsList',
            'dataProvider' => $model->search(5),
            'enableSorting' => false,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
            'pagerCssClass' => 'mt-2 pageNotify',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'news_date', 'header' => Yii::t('models', 'attr_date'), 'type' => 'html', 'value' => 'date("d.m.Y", strtotime($data->news_date))'],
                ['name' => 'news_text_ru', 'type' => 'html', 'value' => 'mb_substr(strip_tags($data->news_text_ru), 0, 150, "UTF-8")'],
                ['name' => 'news_text_en', 'type' => 'html', 'value' => 'mb_substr(strip_tags($data->news_text_en), 0, 150, "UTF-8")'],
                ['name' => 'sendStatus', 'header' => false, 'type' => 'html', 'value' => '$data->statusMessageGrid($data->sendStatus)'],
                ['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{editNews}', 'buttons' => [
                    'editNews' => [
                        'icon' => 'fas fa-pencil-alt',
                        'label' => Yii::t('core', 'btn_edit'),
                        'url' => 'Yii::app()->createUrl("/admin/news/workNews", ["type" => "edit", "id" => $data->id])',
                        'options' => [
                            'data-toggle' => false,
                            'class' => 'text-dark'
                        ]
                    ]
                ]]
            ]
        ]);?>
    </div>
</div>
<div class="row bg-white p-3">
    <div class="col-md-12">
        <?php $this->widget('bootstrap.widgets.TbButton', [
            'context' => 'warning',
            'block' => true,
            'label' => Yii::t('core', 'btn_add'),
            'buttonType' => 'link',
            'url' => $this->createUrl('workNews', ['type' => 'add'])
        ]); ?>
    </div>
</div>