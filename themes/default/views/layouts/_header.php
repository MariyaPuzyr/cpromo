<header class="app-header">
    <div class="container-fluid">
        <div class="row gutters">
            <div class="col-xl-7 col-lg-7 col-md-6 col-sm-7 col-6">
                <?php if(Yii::app()->mobileDetect->isMobile()): ?>
                    <a class="mini-nav-btn" href="#" id="app-side-mini-toggler">
                        <i class="icon-chevron-thin-left"></i>
                    </a>
                    <a href="#app-side" data-toggle="onoffcanvas" class="onoffcanvas-toggler" aria-expanded="true">
                        <i class="icon-chevron-thin-left"></i>
                    </a>
                <?php endif; ?>
                <a href="<?= $this->createAbsoluteUrl('/dashboard');?>" class="logo_head">
                    <?=CHtml::image($this->assetsBase.DIRECTORY_SEPARATOR.'img/logo_new.png'); ?>
                </a>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-6 col-sm-5 col-6 d-flex justify-content-end">
                <?php $this->widget('LangSelect', ['type' => 'drop2']); ?>
                <ul class="header-actions">
                    <li class="dropdown">
                        <a href="#" id="userSettings" class="user-settings"  style="padding: 19px 18px;" data-toggle="dropdown" aria-haspopup="true">
                            <i class="fas fa-user avatar"></i>
                            <span class="user-name"><?= CHtml::encode(Yii::app()->user->model()->username); ?></span>
                            <i class="fas fa-chevron-down" style="font-size: small!important"></i>
                        </a>
                        <div class="dropdown-menu lg dropdown-menu-right" aria-labelledby="userSettings">
                            <ul class="user-settings-list">
                                <li>
                                    <a href="<?= $this->createUrl('/profile'); ?>">
                                        <div class="icon"><i class="icon-user"></i></div>
                                        <p><?= Yii::t('core', 'menu_head_btn_profile'); ?></p>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $this->createUrl('/finance'); ?>">
                                        <div class="icon red"><i class="icon-wallet"></i></div>
                                        <p><?= Yii::t('core', 'menu_head_btn_finance'); ?></p>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $this->createUrl('/rnetwork'); ?>">
                                        <div class="icon yellow"><i class="icon-flow-tree"></i></div>
                                        <p><?= Yii::t('core', 'menu_head_btn_rnetwork'); ?></p>
                                    </a>
                                </li>
                            </ul>
                            <div class="logout-btn text-center">
                                <?= CHtml::link(Yii::t('core', 'menu_head_btn_logout'), ['/logout']) ?>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>