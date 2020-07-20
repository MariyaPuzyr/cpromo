<?php Yii::app()->clientScript->registerScript('removeDL', "
    $(document).ready(function() {
        $(window).on(\"load\", function() {
            $('a:contains(\"Design Nominees\")').attr('style', 'display:none!important');
            $('a:contains(\"Best CSS Web Gallery\")').attr('style', 'display:none!important');
        });
    });", CClientScript::POS_END); ?>
    

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
            <a class="navbar-brand page-scroll animation" href="/" data-animation="fadeInDown" data-animation-delay="1s"> 
            	<img class="logo_light" src="<?= $this->assetsBase.'/landing/img';?>/logo.png" alt="logo" /> 
                <img class="logo_dark" src="<?= $this->assetsBase.'/landing/img';?>/logo_dark.png" alt="logo" /> 
            </a>
            <button class="navbar-toggler animation" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" data-animation="fadeInDown" data-animation-delay="1.1s"> 
                <span class="ion-android-menu"></span> 
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav m-auto">
                    <li class="animation" data-animation="fadeInDown" data-animation-delay="1.2s"><a class="nav-link active" href="/"><?= Yii::t('controllers', 'landing_head_btn_home'); ?></a></li>
                    <li class="animation" data-animation="fadeInDown" data-animation-delay="1.2s"><a class="nav-link page-scroll nav_item" href="#service"><?= Yii::t('controllers', 'landing_head_btn_services'); ?></a></li>
                    <li class="animation" data-animation="fadeInDown" data-animation-delay="1.3s"><a class="nav-link page-scroll nav_item" href="#about"><?= Yii::t('controllers', 'landing_head_btn_about'); ?></a></li>
                    <li class="animation" data-animation="fadeInDown" data-animation-delay="1.4s"><a class="nav-link page-scroll nav_item" href="#token"><?= Yii::t('controllers', 'landing_head_btn_features'); ?></a></li>
                    <li class="animation" data-animation="fadeInDown" data-animation-delay="1.5s"><a class="nav-link page-scroll nav_item" href="#roadmap"><?= Yii::t('controllers', 'landing_head_btn_map'); ?></a></li>
                    <li class="animation" data-animation="fadeInDown" data-animation-delay="1.8s"><a class="nav-link page-scroll nav_item" href="#contact"><?= Yii::t('controllers', 'landing_head_btn_contact'); ?></a></li>
                </ul>
                <ul class="navbar-nav nav_btn align-items-center">
                    <li class="animation" data-animation="fadeInDown" data-animation-delay="1.9s">
                        <?php $this->widget('LangSelect', ['type' => 'promo']);?>
                    </li>
                    <li class="animation" data-animation="fadeInDown" data-animation-delay="2s"><a class="btn btn-default btn-radius nav_item" href="<?= $this->createUrl(Yii::app()->user->loginUrl);?>"><?= Yii::t('controllers', 'langing_btn_signin_short'); ?></a></li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<!-- END HEADER --> 

<!-- START SECTION BANNER -->
<section id="home_section" class="section_banner bg_black_dark" data-z-index="1" data-parallax="scroll">
    <div id="banner_bg_effect" class="banner_effect"></div>
    <div class="container">
        <div class="row align-items-center mb-5" style="border-radius: 9px; border: 1px solid #dee2e6 !important;">
            <div class="col-lg-2 col-md-2 col-sm-12" >
                <div class="transparent_bg tk_counter_inner m-lg-0 banner_token text-center px-0 animation" data-animation="fadeIn" data-animation-delay="1.4s">
                    <?= Yii::t('controllers', 'landing_headT_users', ['#count' => $cntUsers]); ?>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12" >
                <div class="transparent_bg tk_counter_inner m-lg-0 banner_token text-center px-0 animation" data-animation="fadeIn" data-animation-delay="1.4s">
                    <?= Yii::t('controllers', 'landing_headT_cp', ['#count' => $cntCP]); ?>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12" >
                <div class="transparent_bg tk_counter_inner m-lg-0 banner_token text-center px-0 animation" data-animation="fadeIn" data-animation-delay="1.4s">
                    <?= Yii::t('controllers', 'landing_headT_course', ['#course' => $course.'$']); ?>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-122" >
                <div class="transparent_bg tk_counter_inner m-lg-0 banner_token text-center px-0 animation" data-animation="fadeIn" data-animation-delay="1.4s">
                    <?= Yii::t('controllers', 'landing_headT_orders', ['#count' => $cntOrders]); ?>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12" >
                <div class="transparent_bg tk_counter_inner m-lg-0 banner_token text-center px-0 animation" data-animation="fadeIn" data-animation-delay="1.4s">
                    <?= Yii::t('controllers', 'landing_headT_priceOrders', ['#summ' => number_format($summCP, 2, '.', '').'$']); ?>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12" >
                <div class="transparent_bg tk_counter_inner m-lg-0 banner_token text-center px-0 animation" data-animation="fadeIn" data-animation-delay="1.4s">
                    <?= Yii::t('controllers', 'landing_headT_profit', ['#summ' => number_format($profit, 2, '.', '').'$']); ?>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 col-sm-12 order-lg-first">
                <div class="banner_text_s2 text_md_center">
                    <h1 class="animation text-white" data-animation="fadeInUp" data-animation-delay="1.1s"><?= Yii::t('controllers', 'landing_offText');?></h1>
                    <div class="transparent_bg tk_counter_inner m-lg-0 banner_token text-center px-0 animation" data-animation="fadeIn" data-animation-delay="1.4s">
                        <a href="https://www.youtube.com/watch?v=II7e7xuaS3A" data-fancybox>
                            <img src="<?= $this->assetsBase.'/img/video_img.png'; ?>" style="border-radius: 9px; border: 1px solid #dee2e6 !important;"/>
                        </a>
                    </div>
                    <div class="btn_group pt-2 pb-3 animation" data-animation="fadeInUp" data-animation-delay="1.4s"> 
                        <a href="<?= $this->createUrl(Yii::app()->user->loginUrl);?>" class="btn btn-border btn-radius"><?= Yii::t('controllers', 'landing_btn_signin');?> <i class="ion-ios-arrow-thin-right"></i></a> 
                        <a href="<?= $this->assetsBase.'/docs/presentation.pdf'; ?>" target="_blank" class="btn btn-border btn-radius"> <i class="fa fa-file-text" aria-hidden="true"></i> <?= Yii::t('controllers', 'landing_btn_present'); ?> </a> 
                    </div>
                    <br />
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

<!-- START SECTION SERVICES -->
<section id="service" class="small_pb">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-8 offset-lg-2 col-md-12 col-sm-12">
				<div class="title_default_light title_border text-center">
                  <h4 class="animation" data-animation="fadeInUp" data-animation-delay="0.2s"><?= Yii::t('controllers', 'landing_text_new_1'); ?></h4>
                  <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_2'); ?></p>
        		</div>
			</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12">
            	<div class="box_wrap text-center animation" data-animation="fadeInUp" data-animation-delay="0.6s">
                	<img src="<?= $this->assetsBase.'/landing/img';?>/service_icon1.png" alt="service_icon1"/>
                    <h4><?= Yii::t('controllers', 'landing_text_new_3'); ?></h4>
                    <p style="text-align: justify"><?= Yii::t('controllers', 'landing_text_new_4'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
            	<div class="box_wrap text-center animation" data-animation="fadeInUp" data-animation-delay="0.8s">
                	<img src="<?= $this->assetsBase.'/landing/img';?>/service_icon2.png" alt="service_icon2"/>
                    <h4><?= Yii::t('controllers', 'landing_text_new_5'); ?></h4>
                    <p style="text-align: justify"><?= Yii::t('controllers', 'landing_text_new_6'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
            	<div class="box_wrap text-center animation" data-animation="fadeInUp" data-animation-delay="1s">
                	<img src="<?= $this->assetsBase.'/landing/img';?>/service_icon3.png" alt="service_icon3"/>
                    <h4><?= Yii::t('controllers', 'landing_text_new_7'); ?></h4>
                    <p style="text-align: justify"><?= Yii::t('controllers', 'landing_text_new_8'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
            	<div class="box_wrap text-center animation" data-animation="fadeInUp" data-animation-delay="1s">
                	<img src="<?= $this->assetsBase.'/landing/img';?>/service_icon4.png" alt="service_icon4"/>
                    <h4><?= Yii::t('controllers', 'landing_text_new_9'); ?></h4>
                    <p style="text-align: justify"><?= Yii::t('controllers', 'landing_text_new_10'); ?></p>
                </div>
            </div>
            <div class="col-lg-4  col-md-6 col-sm-12">
            	<div class="box_wrap text-center animation" data-animation="fadeInUp" data-animation-delay="1s">
                	<img src="<?= $this->assetsBase.'/landing/img';?>/service_icon5.png" alt="service_icon5"/>
                    <h4><?= Yii::t('controllers', 'landing_text_new_11'); ?></h4>
                    <p style="text-align: justify"><?= Yii::t('controllers', 'landing_text_new_12'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
            	<div class="box_wrap text-center animation" data-animation="fadeInUp" data-animation-delay="1s">
                	<img src="<?= $this->assetsBase.'/landing/img';?>/service_icon6.png" alt="service_icon6"/>
                    <h4><?= Yii::t('controllers', 'landing_text_new_13'); ?></h4>
                    <p style="text-align: justify"><?= Yii::t('controllers', 'landing_text_new_14'); ?></p>
                </div>
            </div>
    	</div>
  	</div>
</section>
<!-- END SECTION SERVICES --> 


<!-- START SECTION ABOUT US -->
<section id="about" class="small_pt">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 col-sm-12">
            	<div class="text_md_center">
                	<img class="animation" data-animation="zoomIn" data-animation-delay="0.2s" src="<?= $this->assetsBase.'/landing/img';?>/about_img2.png" alt="aboutimg2"/> 
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 res_md_mt_30 res_sm_mt_20">
                <div class="title_default_light title_border">
                  <h4 class="animation" data-animation="fadeInUp" data-animation-delay="0.2s"><?= Yii::t('controllers', 'landing_text_new_15'); ?></h4>
                  <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_16'); ?></p>
                  <p class="animation" data-animation="fadeInUp" data-animation-delay="0.8s"><?= Yii::t('controllers', 'landing_text_new_17'); ?><br><br>
                  <?= Yii::t('controllers', 'landing_text_new_18'); ?>
                  </p>
                </div>
                <a href="<?= $this->assetsBase.'/docs/presentation.pdf'; ?>" target="_blank" class="btn btn-default btn-radius"><i class="fa fa-file-text" aria-hidden="true"></i>
                    <?= Yii::t('controllers', 'landing_text_new_19'); ?></a> 
                
            </div>
        </div>
    </div>
</section>
<!-- END SECTION ABOUT US --> 

<!-- SECTION MOBILE APP -->
<section id="token" class="bg_light_dark" data-z-index="1" data-parallax="scroll" data-image-src="<?= $this->assetsBase.'/landing/img';?>/app_bg.png">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 col-md-12 col-sm-12">
              <div class="title_default_light title_border text_md_center">
                <h4 class="animation" data-animation="fadeInUp" data-animation-delay="0.2s"><?= Yii::t('controllers', 'landing_text_new_20'); ?></h4>
                <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                    <span class="chart_bx color1"></span> <?= Yii::t('controllers', 'landing_text_new_21'); ?><br>
                    <span class="chart_bx color2"></span> <?= Yii::t('controllers', 'landing_text_new_22'); ?><br>
                    <span class="chart_bx color3"></span> <?= Yii::t('controllers', 'landing_text_new_23'); ?><br>

                </p>
                <p class="animation" data-animation="fadeInUp" data-animation-delay="0.6s"><?= Yii::t('controllers', 'landing_text_new_24'); ?></p>
                <h4 class="animation" data-animation="fadeInUp" data-animation-delay="0.2s"><?= Yii::t('controllers', 'landing_text_new_25'); ?></h4>
                <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                    <span class="chart_bx color4"></span> <strong><?= Yii::t('controllers', 'landing_text_new_26'); ?></strong> 1 = 7% |  2 = 3% | 3 = 2% | 4 = 1% | 5 = 1% | 6 = 0,5% | 7 = 0,5%<br>
                    <span class="chart_bx color5"></span> <strong><?= Yii::t('controllers', 'landing_text_new_27'); ?></strong> 1 = 20% |  2 = 10% | 3 = 5% | 4 = 5% | 5 = 4% | 6 = 3% | 7 = 3% <br>
                    <?= Yii::t('controllers', 'landing_text_new_28'); ?><br><br>
                    <span class="chart_bx color6"></span> <strong><?= Yii::t('controllers', 'landing_text_new_29'); ?></i>

                </p>
              </div>
              <div class="btn_group text_md_center animation" data-animation="fadeInUp" data-animation-delay="0.8s"> 
                <a href="<?= $this->assetsBase.'/docs/presentation.pdf'; ?>" target="_blank" class="btn btn-default btn-radius"><i class="fa fa-file-text" aria-hidden="true"></i><?= Yii::t('controllers', 'landing_text_new_30'); ?> </a>
              </div>
            </div>
            <div class="col-lg-5 col-md-12 col-sm-12">
                <div class="res_md_mt_50 res_sm_mt_30 text-center animation" data-animation="fadeInRight" data-animation-delay="0.2s"> 
                    <img src="<?= $this->assetsBase.'/landing/img';?>/mobile_app3.png" alt="mobile_app3"/> 
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END SECTION MOBILE APP --> 

<!-- START SECTION TOKEN SALE -->
<section class="section_token token_sale bg_black_dark" data-z-index="1" data-parallax="scroll" data-image-src="<?= $this->assetsBase.'/landing/img';?>/token_bg.png">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-12 col-sm-12">
                <div class="title_default_light title_border text-center">
                    <h4 class="animation" data-animation="fadeInUp" data-animation-delay="0.2s"><?= Yii::t('controllers', 'landing_text_new_31'); ?></h4>
                    <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_32'); ?> </p>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-3">
                <div class="pr_box">
                      <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.2s"><?= Yii::t('controllers', 'landing_text_new_33'); ?></h6>
                      <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                          <?= Yii::t('controllers', 'landing_text_new_34'); ?>
                      </p>
                    </div>
                <div class="pr_box">
                  <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.6s"><?= Yii::t('controllers', 'landing_text_new_35'); ?></h6>
                  <p class="animation" data-animation="fadeInUp" data-animation-delay="0.8s">
                    <?= Yii::t('controllers', 'landing_text_new_36'); ?>
                  </p>
                </div>
                <div class="pr_box">
                      <h6 class="animation" data-animation="fadeInUp" data-animation-delay="1s"><?= Yii::t('controllers', 'landing_text_new_37'); ?></h6>
                      <p  class="animation" data-animation="fadeInUp" data-animation-delay="1.2s">
                        <?= Yii::t('controllers', 'landing_text_new_38'); ?>
                      </p>
                    </div>
            </div>
            <div class="col-lg-6">
                <div class="token_sale res_md_mb_40 res_md_mt_40 res_sm_mb_30 res_sm_mt_30">
                    <div class="tk_countdown text-center animation token_countdown_bg" data-animation="fadeIn" data-animation-delay="1s">
                        <div class="tk_counter_inner">
                            <div class="transparent_bg tk_counter_inner m-lg-0 banner_token text-center px-0 animation" data-animation="fadeIn" data-animation-delay="1.4s">
                                <a href="https://www.youtube.com/watch?v=II7e7xuaS3A" data-fancybox>
                                    <img src="<?= $this->assetsBase.'/img/video_img.png'; ?>" style="border-radius: 9px; border: 1px solid #dee2e6 !important;"/>
                                </a>
                            </div>
                            <div class="progress animation" data-animation="fadeInUp" data-animation-delay="1.3s">
                            <div class="progress-bar progress-bar-striped gradient" role="progressbar" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100" style="width:97%"> 97% </div>
                                <span class="progress_min_val"><?= Yii::t('controllers', 'landing_text_new_39'); ?></span>
                                <span class="progress_max_val"><?= Yii::t('controllers', 'landing_text_new_40'); ?></span>
                            </div>
                            <a href="<?= $this->createUrl(Yii::app()->user->loginUrl); ?>" class="btn btn-default btn-radius animation" data-animation="fadeInUp" data-animation-delay="1.4s"><?= Yii::t('controllers', 'landing_text_new_41'); ?> <i class="ion-ios-arrow-thin-right"></i></a>
                        </div>
                    </div>
                </div>  
            </div>
            <div class="col-lg-3">
                <div class="pr_box">
                        <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.2s"><?= Yii::t('controllers', 'landing_text_new_42'); ?></h6>
                        <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            <?= Yii::t('controllers', 'landing_text_new_43'); ?>
                        </p>
                    </div>
                <div class="pr_box">
                  <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.6s"><?= Yii::t('controllers', 'landing_text_new_44'); ?></h6>
                  <p class="animation" data-animation="fadeInUp" data-animation-delay="0.8s">
                      <?= Yii::t('controllers', 'landing_text_new_45'); ?>
                  </p>
                </div>
                <div class="pr_box">
                        <h6 class="animation" data-animation="fadeInUp" data-animation-delay="1s"><?= Yii::t('controllers', 'landing_text_new_46'); ?></h6>
                        <p class="animation" data-animation="fadeInUp" data-animation-delay="1.2s"><?= Yii::t('controllers', 'landing_text_new_47'); ?></p>
                    </div>
            </div>
        </div>
    </div>
</section>
<!-- END SECTION TOKEN SALE --> 


<!-- START SECTION TIMELINE -->
<section id="roadmap" class="small_pb bg_light_dark">
    <div class="container">
    <div class="row text-center">
      <div class="col-lg-8 col-md-12 offset-lg-2">
        <div class="title_default_light title_border text-center">
          <h4 class="animation" data-animation="fadeInUp" data-animation-delay="0.2s"><?= Yii::t('controllers', 'landing_text_new_48'); ?></h4>
          <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_49'); ?></p>
        </div>
      </div>
    </div>
    </div>
    <div class="container">
  	<div class="row">
    	<div class="col-md-12">
            <div class="timeline owl-carousel small_space">
              <div class="item">
                <!--
                <div class="timeline_box complete">
                -->
                  <div class="timeline_box complete">
                  <div class="timeline_inner">
                    <div class="timeline_circle"></div>
                    <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.3s"><?= Yii::t('controllers', 'landing_text_new_50'); ?></h6>
                    <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_51'); ?> </p>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="timeline_box complete">
                  <div class="timeline_inner">
                    <div class="timeline_circle"></div>
                    <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.3s"><?= Yii::t('controllers', 'landing_text_new_52'); ?></h6>
                    <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_53'); ?></p>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="timeline_box complete">
                  <div class="timeline_inner">
                    <div class="timeline_circle"></div>
                    <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.3s"><?= Yii::t('controllers', 'landing_text_new_54'); ?></h6>
                    <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_55'); ?> </p>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="timeline_box complete">
                  <div class="timeline_inner">
                    <div class="timeline_circle"></div>
                    <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.3s"><?= Yii::t('controllers', 'landing_text_new_56'); ?></h6>
                    <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_57'); ?></p>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="timeline_box current">
                  <div class="timeline_inner">
                    <div class="timeline_circle"></div>
                    <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.3s"><?= Yii::t('controllers', 'landing_text_new_58'); ?></h6>
                    <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_59'); ?></p>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="timeline_box">
                  <div class="timeline_inner">
                    <div class="timeline_circle"></div>
                    <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.3s"><?= Yii::t('controllers', 'landing_text_new_60'); ?></h6>
                    <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_61'); ?></p>
                  </div>
                </div>
              </div>

              <div class="item">
                <div class="timeline_box">
                  <div class="timeline_inner">
                    <div class="timeline_circle"></div>
                    <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.3s"><?= Yii::t('controllers', 'landing_text_new_62'); ?></h6>
                    <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_63'); ?> </p>
                  </div>
                </div>
              </div>

              <div class="item">
                <div class="timeline_box">
                  <div class="timeline_inner">
                    <div class="timeline_circle"></div>
                    <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.3s"><?= Yii::t('controllers', 'landing_text_new_64'); ?></h6>
                    <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_65'); ?> </p>
                  </div>
                </div>
              </div>

              <div class="item">
                <div class="timeline_box">
                  <div class="timeline_inner">
                    <div class="timeline_circle"></div>
                    <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.3s"><?= Yii::t('controllers', 'landing_text_new_66'); ?></h6>
                    <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_67'); ?></p>
                  </div>
                </div>
              </div>

              <div class="item">
                <div class="timeline_box">
                  <div class="timeline_inner">
                    <div class="timeline_circle"></div>
                    <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.3s"><?= Yii::t('controllers', 'landing_text_new_68'); ?></h6>
                    <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_69'); ?></p>
                  </div>
                </div>
              </div>

              <div class="item">
                <div class="timeline_box">
                  <div class="timeline_inner">
                    <div class="timeline_circle"></div>
                    <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.3s"><?= Yii::t('controllers', 'landing_text_new_70'); ?></h6>
                    <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_71'); ?></p>
                  </div>
                </div>
              </div>

              <div class="item">
                <div class="timeline_box">
                  <div class="timeline_inner">
                    <div class="timeline_circle"></div>
                    <h6 class="animation" data-animation="fadeInUp" data-animation-delay="0.3s"><?= Yii::t('controllers', 'landing_text_new_72'); ?></h6>
                    <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_73'); ?></p>
                  </div>
                </div>
              </div>
             
            </div>
    	</div>
    </div>
  </div>
</section>
<!-- END SECTION TIMELINE --> 


<!-- START SECTION CONTACT -->
<section id="contact" class="contact_section small_pt bg_light_dark">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-8 col-md-12 offset-lg-2">
              <div class="title_default_light title_border text-center">
                <h4 class="animation" data-animation="fadeInUp" data-animation-delay="0.2s"><?= Yii::t('controllers', 'landing_text_new_74'); ?></h4>
              </div>
            </div>
        </div>
        <div class="row align-items-center small_space">
            <div class="col-lg-4 col-md-6 offset-lg-2">
            	<div class="bg_black_dark  contact_box_s2 animation" data-animation="fadeInLeft" data-animation-delay="0.1s">
                    <div class="contact_title">
                        <h5 class="animation" data-animation="fadeInUp" data-animation-delay="0.2s"><?= Yii::t('controllers', 'landing_text_new_74'); ?></h5>
                        <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><?= Yii::t('controllers', 'landing_text_new_75'); ?></p>
                    </div>
                    <ul class="list_none contact_info mt-margin">
                        <li class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"> 
                        	<i class="ion-ios-location"></i>
                            <div class="contact_detail"> <span><?= Yii::t('controllers', 'landing_text_new_76'); ?></span>
                            	<p>Václavské náměstí 21, Praha 1, 110 00 Czech Republic</p>
                            </div>
                        </li>
                        <li class="animation" data-animation="fadeInUp" data-animation-delay="0.5s"> 
                        	<i class="ion-android-call"></i>
                            <div class="contact_detail"> <span><?= Yii::t('controllers', 'landing_text_new_78'); ?></span>
                            	<p>+420733780745</p>
                            </div>
                        </li>
                        <li class="animation" data-animation="fadeInUp" data-animation-delay="0.6s"> 
                        	<i class="ion-ios-email"></i>
                            <div class="contact_detail"> <span>E-mail</span>
                            	<p>info@circle.promo</p>
                            </div>
                        </li>
                  </ul>
                  <div class="contct_follow pt-2 pt-md-4">
                      <span class="text-uppercase animation" data-animation="fadeInUp" data-animation-delay="0.2s"><?= Yii::t('controllers', 'landing_text_new_79'); ?></span>
                      <ul class="list_none social_icon">
                        <li class="animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                        <a href="http://www.youtube.com/c/CirclePromo"><i class=" fab fa-youtube"></i></a></li>
                        <li class="animation" data-animation="fadeInUp" data-animation-delay="0.5s">
                        <a href="https://www.instagram.com/circle_promo/"><i class="fab fa-instagram" aria-hidden="true"></i></a></li>
                        <li class="animation" data-animation="fadeInUp" data-animation-delay="0.6s">
                        <a href="https://vk.com/circlepromo"><i class="fab fa-vk" aria-hidden="true"></i></a></li>
                        <li class="animation" data-animation="fadeInUp" data-animation-delay="0.7s">
                        <a href="https://t.me/circlebit"><i class="fab fa-telegram" aria-hidden="true"></i></a></li>
                        <li class="animation" data-animation="fadeInUp" data-animation-delay="0.7s">
                        <a href="https://t.me/circlechatru"><i class="fab fa-telegram" aria-hidden="true"></i></a></li>
                        <li class="animation" data-animation="fadeInUp" data-animation-delay="0.7s">
                        <a href="https://t.me/circlechateng"><i class="fab fa-telegram" aria-hidden="true"></i></a></li>
                     </ul>
                 </div>
              	</div>
            </div>
        </div>
	</div>
</section>
<!-- END SECTION CONTACT --> 



<!-- START FOOTER SECTION -->
<footer>
    <div class="bottom_footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="copyright">Copyright &copy; 2020 <strong>Circle.promo</strong> <?= Yii::t('controllers', 'landing_rights');?></p>
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
