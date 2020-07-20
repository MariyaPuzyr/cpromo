<?php
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/landing/css/animate.css');
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/landing/css/ionicons.min.css');
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/landing/css/circle.css');
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/landing/owlcarousel/css/owl.carousel.min.css');
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/landing/owlcarousel/css/owl.theme.default.min.css');
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/landing/css/magnific-popup.css');
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/landing/css/spop.min.css');
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/landing/css/style.css');
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/landing/css/responsive.css');
    Yii::app()->clientScript->registerCssFile($this->assetsBase.'/landing/color/theme.css');

    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/landing/owlcarousel/js/owl.carousel.min.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/landing/js/magnific-popup.min.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/landing/js/waypoints.min.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/landing/js/parallax.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/landing/js/jquery.countdown.min.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/landing/js/particles.min.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/landing/js/jquery.dd.min.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/landing/js/jquery.counterup.min.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/landing/js/spop.min.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/landing/js/scripts.js', CClientScript::POS_END);
    
    Yii::app()->clientScript->registerCssFile(YII_DEBUG ? $this->assetsBase.'/vendor/fancybox/jquery.fancybox.css' : $this->assetsBase.'/vendor/fancybox/jquery.fancybox.min.css');
    Yii::app()->clientScript->registerScriptFile(YII_DEBUG ? $this->assetsBase.'/vendor/fancybox/jquery.fancybox.js' : $this->assetsBase.'/vendor/fancybox/jquery.fancybox.min.js', CClientScript::POS_END);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Circle Project">
        <title><?php echo $this->pageTitle; ?></title>
        <link rel="shortcut icon" type="image/x-icon" href="<?=$this->assetsBase.'/landing/img/favicon.png';?>">
        <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet">
        <?php if(!YII_DEBUG): ?>
            <!-- Yandex.Metrika counter -->
            <script>
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
    <body class="v_dark" data-spy="scroll" data-offset="110">
        <?= $content ?>
    </body>
</html>