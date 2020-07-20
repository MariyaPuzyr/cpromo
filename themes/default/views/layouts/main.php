<?php
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/vendor/theme/css/main.css');
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/vendor/theme/css/custom.css');
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/vendor/theme/icomoon/icomoon.css');
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/css/common.css');
    Yii::app()->clientScript->registerCssFile(YII_DEBUG ? $this->assetsBase.'/vendor/fancybox/jquery.fancybox.css' : $this->assetsBase.'/vendor/fancybox/jquery.fancybox.min.css');
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/js/common.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/theme/js/unifyMenu.js');
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/theme/js/onoffcanvas.js');
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/theme/js/moment.js');
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/theme/slimscroll/slimscroll.min.js');
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/theme/slimscroll/custom-scrollbar.js');
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/theme/js/common.js');
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/theme/js/jquery.easing.1.3.js');
    Yii::app()->clientScript->registerScriptFile(YII_DEBUG ? $this->assetsBase.'/vendor/fancybox/jquery.fancybox.js' : $this->assetsBase.'/vendor/fancybox/jquery.fancybox.min.js', CClientScript::POS_END);
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->pageTitle; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="apple-touch-icon" sizes="180x180" href="<?=$this->assetsBase.'/img/favicons/apple-touch-icon.png';?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?=$this->assetsBase.'/img/favicons/favicon-32x32.png';?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?=$this->assetsBase.'/img/favicons/favicon-16x16.png';?>">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        <?php if(!YII_DEBUG): ?>
            <!-- Yandex.Metrika counter -->
            <script type="text/javascript" >
                (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
                (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

                ym(64517809, "init", {
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            </script>
            <noscript><img src="https://mc.yandex.ru/watch/64517809" class="yandexImage" /></noscript>
            <!-- /Yandex.Metrika counter -->
        <?php endif; ?>
    </head>
    <body>
        <div id="loadingData">
            <div class="spinner-border text-light" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div id="hidescreen"></div>
        
        <div class="app-wrap">
            <?php $this->renderPartial('//layouts/_header'); ?>
            <div class="app-container">
                <?php $this->renderPartial('//layouts/_leftMenu');?>
                <div class="app-main">
                    <div class="main-content">
                        <?= $content ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
            $user = Yii::app()->user->model();
        
            foreach(Yii::app()->user->getFlashes() as $type => $message)
                echo "<script>showNoty('{$message}', '{$type}');</script>";
            
            
            if($user->readwarning)
                Yii::app()->clientScript->registerScript('showWarning', 'showwarning()');
                
            if($user->cpassword)
                Yii::app()->clientScript->registerScript('changePassword', 'cpassword()');
            echo '<div id="modalWindow"><div id="modalData"></div></div>';
        ?>
    </body>
</html>