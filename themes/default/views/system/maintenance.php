<!-- START LOADER -->
<div id="loader-wrapper">
    <div id="loading-center-absolute">
        <div class="object" id="object_four"></div>
        <div class="object" id="object_three"></div>
        <div class="object" id="object_two"></div>
        <div class="object" id="object_one"></div>
    </div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>
<!-- END LOADER -->

<!-- START HEADER -->
<header class="header_wrap fixed-top">
    <div class="container-fluid">
	<nav class="navbar navbar-expand-lg">
            <a class="navbar-brand page-scroll animation" href="index-dark-particle.html#home_section" data-animation="fadeInDown" data-animation-delay="1s">
            	<img class="logo_light" src="<?= $this->assetsBase.'/landing/img';?>/logo.png" alt="logo" />
                <img class="logo_dark" src="<?= $this->assetsBase.'/landing/img';?>/logo_dark.png" alt="logo" />
            </a>
            <button class="navbar-toggler animation" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" data-animation="fadeInDown" data-animation-delay="1.1s">
                <span class="ion-android-menu"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav m-auto">
                </ul>
                <ul class="navbar-nav nav_btn align-items-center">
                    <li class="animation" data-animation="fadeInDown" data-animation-delay="1.9s">
                        <?php $this->widget('LangSelect', ['type' => 'promo']);?>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<!-- END HEADER -->

<!-- START SECTION BANNER -->
<section id="home_section" class="section_banner bg_black_dark" data-z-index="1" data-parallax="scroll" data-image-src="<?= $this->assetsBase.'/landing/img';?>/banner_bg2.png">
    <div id="banner_bg_effect" class="banner_effect"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 col-sm-12 order-lg-first">
                <div class="banner_text_s2 text_md_center">
                    <h1 class="animation text-white" data-animation="fadeInUp" data-animation-delay="1.1s"><?= Yii::t('controllers', 'maintenance_offText');?></h1>
                    <h5 class="animation presale_txt text-white" data-animation="fadeInUp" data-animation-delay="1.3s"><?= Yii::t('controllers', 'maintenance_offText1');?></h5>
                    <div class="transparent_bg tk_counter_inner m-lg-0 banner_token text-center px-0 animation" data-animation="fadeIn" data-animation-delay="1.4s">
                        <a href="https://www.youtube.com/watch?v=II7e7xuaS3A" data-fancybox>
                            <img src="<?= $this->assetsBase.'/img/video_img.png'; ?>" style="border-radius: 9px; border: 1px solid #dee2e6 !important;"/>
                        </a>
                    </div>
                    <div class="btn_group pt-2 pb-3 animation" data-animation="fadeInUp" data-animation-delay="1.4s">
                    </div>
                    <span class="text-white icon_title animation" data-animation="fadeInUp" data-animation-delay="1.4s"><?= Yii::t('controllers', 'maintenance_features');?></span>
                    <ul class="list_none currency_icon">
                        <li class="animation" data-animation="fadeInUp" data-animation-delay="1.6s"><span><?= Yii::t('controllers', 'maintenance_features_2');?> </span></li>
                        <li class="animation" data-animation="fadeInUp" data-animation-delay="1.8s"><span><?= Yii::t('controllers', 'maintenance_features_4');?></span></li><br>
                    </ul>
                    <div id="whitepaper" class="team_pop mfp-hide">
                        <div class="row m-0">
                            <div class="col-md-7">
                                <div class="pt-3 pb-3">
                                    <div class="title_dark title_border">
                                        <h4><?= Yii::t('controllers', 'landing_modReg_title');?></h4>
                                        <p><?= Yii::t('controllers', 'landing_modReg_text');?></p>
                                        <a href="<?= $this->createUrl(Yii::app()->user->loginUrl);?>" class="btn btn-default btn-radius"><?= Yii::t('controllers', 'landing_btn_signin');?> <i class="ion-ios-arrow-thin-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <img class="pt-3 pb-3" src="<?= $this->assetsBase.'/landing/img';?>/whitepaper.png" alt="whitepaper"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 order-first">
                <div class="banner_image_right res_md_mb_50 res_xs_mb_30 animation" data-animation-delay="1.5s" data-animation="fadeInRight">
                    <img alt="banner_vector2" src="<?= $this->assetsBase.'/landing/img';?>/banner_img2.png">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END SECTION BANNER -->

<!-- START FOOTER SECTION -->
<footer>
    <div class="bottom_footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="copyright">Copyright &copy; <?=date('Y'); ?> <strong>Circle.promo</strong> <?= Yii::t('controllers', 'landing_rights');?></p>
                </div>
                <div class="col-md-6">
                    <ul class="list_none footer_menu">
                        <li><a href="<?= $this->createUrl('/info/privacy');?>"><?= Yii::t('core', 'page_privacy_full'); ?></a></li>
                        <li><a href="<?= $this->createUrl('/info/terms');?>"><?= Yii::t('core', 'page_terms_full'); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- END FOOTER SECTION -->

<a href="index-dark-particle.html#" class="scrollup btn-default"><i class="ion-ios-arrow-up"></i></a>