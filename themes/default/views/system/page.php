<?php 
    $this->pageTitle = Yii::t('core', 'page_'.$model->id);
    $text_field = 'text_'.Yii::app()->language;
?>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body with-list-arrow">
          <h4 class="card-title"><?= Yii::t('core', 'page_'.$model->id); ?></h4>
          <?= $model->{$text_field ? $text_field : 'text_ru'}; ?>
      </div>
    </div>
  </div>
</div>
