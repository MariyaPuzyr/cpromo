<?php  
    $uData = Yii::app()->user->model();
    $photo = (file_exists(Yii::getPathOfAlias('webroot.uploads').'/user_photo/'.$uData->photo)) ? $uData->photo : '';
?>

<aside class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="nav">
        <ul class="list-unstyled">
          <li class="nav-item nav-profile">
            <a href="<?= $this->createAbsoluteUrl('/profile');?>" class="nav-link">
              <div class="nav-profile-image">
                  <?= CHtml::image($photo ? 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::getPathOfAlias('webroot.uploads').'/user_photo/'.$photo)) : $this->assetsBase.'/img/no_photo.jpg'); ?>
                <!--              <img src="assets/images/faces/face1.jpg" alt="profile">-->
              </div>
              <div class="nav-profile-text d-flex flex-column">
                <span class="font-weight-bold mb-2"><?= Yii::t('core', 'menu_left_youID', ['#id' => $uData->referral_id]); ?></span>
              </div>
              <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
          </li>

        </ul>
        <?php $this->widget('ExtMenu', [
            'htmlOptions' => [
                'class' => 'unifyMenu font-white',
                'id' => 'unifyMenu',
            ],
            'items' => [
                ['icon' => 'icon-laptop_windows', 'label' => Yii::t('core', 'menu_left_dashboard'), 'url' => ['/dashboard']],
                ['icon' => 'icon-flow-tree', 'label' => Yii::t('core', 'menu_left_rnetwork'), 'url' => ['/rnetwork']],
                ['icon' => 'icon-wallet', 'label' => Yii::t('core', 'menu_left_finance'), 'url' => ['/finance']],
                ['icon' => 'icon-loop2', 'label' => Yii::t('core', 'menu_left_exchange'), 'url' => ['/exchange']],
                #['icon' => 'icon-stats-bars', 'label' => Yii::t('core', 'menu_left_activities'), 'url' => ['/activities']],
                ['icon' => 'icon-newspaper', 'label' => Yii::t('core', 'menu_left_newsAndMedia'), 'url' => ['/news']],
                ['icon' => 'icon-messages', 'label' => Yii::t('core', 'menu_left_support'), 'url' => ['/support']],
            ]
        ]); ?>
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#general-pages" aria-expanded="false" aria-controls="general-pages">
          <span class="menu-title">Articles</span>
          <i class="menu-arrow"></i>
          <i class="mdi mdi-book-open-page-variant menu-icon"></i>
        </a>
        <div class="collapse" id="general-pages">
            <?php $this->widget('ExtMenu', [
                'htmlOptions' => [
                    'class' => 'unifyMenu nav flex-column sub-menu',
                    'id' => 'unifyMenu',
                ],
                'items' => [
                    ['icon' => 'fas fa-angle-double-right', 'label' => Yii::t('core', 'page_about'), 'url' => ['/info/about']],
                    ['icon' => 'fas fa-angle-double-right', 'label' => Yii::t('core', 'page_terms'), 'url' => ['/info/terms']],
                    ['icon' => 'fas fa-angle-double-right', 'label' => Yii::t('core', 'page_security'), 'url' => ['/info/security']],
                    ['icon' => 'fas fa-angle-double-right', 'label' => Yii::t('core', 'page_privacy'), 'url' => ['/info/privacy']],
                    ['icon' => 'fas fa-angle-double-right', 'label' => Yii::t('core', 'page_aml'), 'url' => ['/info/aml']],
                    #['icon' => 'fas fa-angle-double-right', 'label' => Yii::t('core', 'page_offer'), 'url' => ['/info/offer']],
                ]
            ]); ?>
        </div>
      </li>

    </div>
</aside>