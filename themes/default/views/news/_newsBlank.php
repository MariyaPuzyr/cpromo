<div class="row" style="display: contents!important">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 text-muted small text-left">
                <span class="fas fa-fw fa-calendar"></span><?= date('d.m.Y', strtotime($data->news_date)); ?>
                <?= ($data->{'title_'.Yii::app()->language}) ? '<h4 style="color: #000!important;">'.$data->{'title_'.Yii::app()->language}.'</h4>' : ''; ?>
            </div>
        </div>
        <div class="row mt-2 mb-4">
            <div class="col-md-12 text-muted" style="text-align: justify!important;">
                <?php
                    $dbTag = 'news_text_'.Yii::app()->language;
                    echo '<div style="color: #000!important;" id="news_text_'.$data->id.'" class="hide">'.CHtml::decode($data->{$dbTag}).'</div>';
                    echo '<div class="text-right"><span class="text-primary text-right" id="newsToggle_'.$data->id.'" onclick="toggleNews('.$data->id.')">'.Yii::t('core', 'btn_more').'</span></div>';
                ?>
            </div>
        </div>
    </div>
</div>
<hr style="width: 75%"/>

