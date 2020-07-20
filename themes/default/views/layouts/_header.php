<header class="app-header navbar fixed-top p-0">
    <div class="container-fluid p-0">
        <div class="default-layout-navbar col-lg-12 col-12 p-0 d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
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
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
              <button class="navbar-toggler navbar-toggler align-self-center mr-auto" type="button" data-toggle="minimize">
                <span class="mdi mdi-menu"></span>
              </button>
                <?php $this->widget('LangSelect', ['type' => 'drop2']); ?>
                <ul class="navbar-nav">
                  <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" id="userSettings" href="#" data-toggle="dropdown" aria-expanded="false">
                      <div class="nav-profile-text">
                        <p class="mb-1 text-blackuser-name"><?= CHtml::encode(Yii::app()->user->model()->username); ?></p>
                      </div>
                    </a>
                    <div class="dropdown-menu navbar-dropdown" aria-labelledby="userSettings">
                      <a class="dropdown-item" href="<?= $this->createUrl('/profile'); ?>">
                        <i class="mdi mdi-account mr-2 text-success"></i>
                        <?= Yii::t('core', 'menu_head_btn_profile'); ?></a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#<?= $this->createUrl('/finance'); ?>">
                        <i class="mdi mdi-cash-multiple mr-2 text-success"></i>
                        <?= Yii::t('core', 'menu_head_btn_finance'); ?></a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="<?= $this->createUrl('/rnetwork'); ?>">
                        <i class="mdi mdi-account-network mr-2 text-success"></i>
                        <?= Yii::t('core', 'menu_head_btn_rnetwork'); ?></a>
                      <div class="dropdown-divider"></div>
                      <div class="dropdown-item" href="#"><?= CHtml::link(Yii::t('core', 'menu_head_btn_logout'), ['/logout']) ?></div>
                    </div>
                  </li>
                </ul>
            </div>
        </div>
    </div>
</header>