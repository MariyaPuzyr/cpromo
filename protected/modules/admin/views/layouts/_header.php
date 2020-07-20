<?php if(Yii::app()->user->getIsSuperuser()): ?>
    <div class="d-flex justify-content-between pt-2 pr-3 pl-3 pb-2 px-md-4 bg-white sticky-top">
        <h5 class="my-0 mr-md-auto font-weight-normal head-image-mobile" style="max-width: 70%"><?= CHtml::link(CHtml::image($this->assetsBase.'/img/logo_new.png', '', ['class' => 'head-logo']), '/'); ?></h5>
        <div>
            <?php 
                $this->widget('LangSelect', ['type' => 'drop']);
                $this->widget('bootstrap.widgets.TbButton', [
                    'icon' => 'icon-log-out',
                    'buttonType' => 'link',
                    'url' => Yii::app()->getModule('user')->logoutUrl,
                    'htmlOptions' => [
                        'data-toggle' => 'tooltip',
                        'title' => Yii::t('core', 'btn_logout'),
                        'class' => 'border shadow-sm mr-1 btn-withoutBG bg-white'
                    ]
                ]);
            ?>
            <button class="btn border btn-withoutBG shadow-sm mobile-visible" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="icon-menu"></span>
            </button>
        </div>    
    </div>
    <nav class="navbar navbar-expand-lg bg-white mb-3 pb-0 pt-0">
        <div></div>
        <div class="container">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <?php $this->widget('ExtMenu', [
                    'htmlOptions' => [
                        'class' => 'navbar-nav mr-auto topmenu'
                    ],
                    'itemCssClass' => 'nav-item',
                    'linkLabelWrapperHtmlOptions' => [
                        'class' => 'nav-link'
                    ],
                    'items' => [
                        ['label' => Admin::t('core', 'menu_dash'), 'url' => ['/admin/dashboard']],
                        ['label' => Admin::t('core', 'menu_users'), 'url' => ['/admin/users']],
                        ['label' => Admin::t('core', 'menu_finance'), 'url' => ['/admin/finance']],
                        #['label' => Admin::t('core', 'menu_activities'), 'url' => ['/admin/profit']],
                        ['label' => Admin::t('core', 'menu_support'), 'url' => ['/admin/support']],
                        ['label' => Admin::t('core', 'menu_news'), 'url' => ['/admin/news']],
                        ['label' => Admin::t('core', 'menu_settings'), 'url' => ['/admin/settings']],
                    ]
                ]); ?>
            </div>
        </div>
    </nav>
<?php endif; ?>