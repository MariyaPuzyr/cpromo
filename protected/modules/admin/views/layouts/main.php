 <?php
    Yii::app()->clientScript->registerCssFile($this->adminAssetsBase.'/css/common.css');
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/css/common.css');
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/vendor/fancybox/jquery.fancybox.min.css');
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/vendor/theme/icomoon/icomoon.css');
    
    Yii::app()->clientScript->registerScriptFile($this->adminAssetsBase.'/js/common.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/fancybox/jquery.fancybox.min.js', CClientScript::POS_END);
?>

<!doctype html> 
<html>
    <head>
	<title><?= $this->pageTitle?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>
    <body>
        <div id="loadingData">
            <div class="spinner-border text-light" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div id="hidescreen"></div>
        <?php
            $this->renderPartial('application.modules.admin.views.layouts._header');
            echo '<div class="container"><div class="row mt-3"><div class="col-md-12">'.$content.'</div></div></div>';
            echo '<div class="wrapper mt-4 footer-white-space"></wrapper>';
            echo '<div id="modalWindow"><div id="modalData"></div></div>';
            foreach(Yii::app()->user->getFlashes() as $type => $message)
                echo "<script>showNoty('{$message}', '{$type}');</script>";
        ?>
    </body>
</html>