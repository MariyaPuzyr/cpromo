<?php  
    $uData = Yii::app()->user->model();
    $photo = (file_exists(Yii::getPathOfAlias('webroot.uploads').'/user_photo/'.$uData->photo)) ? $uData->photo : '';
?>

<aside class="app-side" id="app-side">
    <div class="side-content">
        <div class="text-center p-3">
            <a href="<?= $this->createAbsoluteUrl('/profile');?>" class="sidebar-profile-image">
                <?= CHtml::image($photo ? 'data:image/png;base64,'.base64_encode(file_get_contents(Yii::getPathOfAlias('webroot.uploads').'/user_photo/'.$photo)) : $this->assetsBase.'/img/no_photo.jpg'); ?>
            </a>
            <span style="color: #fff!important;"><?= Yii::t('core', 'menu_left_youID', ['#id' => $uData->referral_id]); ?></span>
        </div>
        <hr style="border-top: 1px solid!important;" />
        <nav class="side-nav">
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
        </nav>
        <nav class="side-nav mt-lg-5">
            <hr style="border-top: 1px solid!important;" />
            <?php $this->widget('ExtMenu', [
                'htmlOptions' => [
                    'class' => 'unifyMenu font-white',
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
        </nav>
    </div>
</aside>