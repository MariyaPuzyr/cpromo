<?php 
    $this->pageTitle = Yii::t('controllers', 'news_index_title');
    Yii::app()->clientScript->registerScript('toggleNews', 'function toggleNews(id){
        $("#news_text_"+id).toggleClass("hide", ["duration"]);
        if($("#news_text_"+id).hasClass("hide"))
            $("#newsToggle_"+id).html("'.Yii::t('core', 'btn_more').'");
        else
            $("#newsToggle_"+id).html("'.Yii::t('core', 'btn_less').'");
	
        return false;
    }', CClientScript::POS_END);
?>

<div class="row gutters justify-content-md-center">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 card-columns">
        <?php 
            if($dataProvider->getData()) {
                $this->widget('bootstrap.widgets.TbThumbnails', [
                    'dataProvider' => $dataProvider,
                    'template' => '{items}{pager}',
                    'pagerCssClass' => 'mt-2 pagerNew',
                    'itemView' => '_newsBlank',
                ]);
            } else
                echo Yii::t('controllers', 'news_index_lbl_emptyNews');
        ?>    
    </div>
</div>