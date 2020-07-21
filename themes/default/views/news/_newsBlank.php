<div class="card">
  <img src="https://www.bootstrapdash.com/demo/purple/jquery/template/assets/images/samples/300x300/5.jpg" alt="" class="card-img-top">
  <div class="card-body">
      <?= ($data->{'title_'.Yii::app()->language}) ? '<h4 class="card-title">'.$data->{'title_'.Yii::app()->language}.'</h4>' : ''; ?>
    <p class="card-text"></p>
      <?php
      $dbTag = 'news_text_'.Yii::app()->language;
      echo '<div id="news_text_'.$data->id.'" class="card-text">'.CHtml::decode($data->{$dbTag}).'</div>';
      ?>
  </div>
</div>