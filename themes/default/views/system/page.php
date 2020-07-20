<?php 
    $this->pageTitle = Yii::t('core', 'page_'.$model->id);
    $text_field = 'text_'.Yii::app()->language;
?>
<div class="container">
    <div class="row bg-white p-3 mt-3">
        <div class="col-md-12 text-center">
            <h4><?= Yii::t('core', 'page_'.$model->id); ?></h4>
        </div>
    </div>
    <div class="row bg-white p-3">
        <div class="col-md-12 text-justify">
            <?= $model->{$text_field ? $text_field : 'text_ru'}; ?>
        </div>
    </div>
</div>