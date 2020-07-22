<?php 
    $this->pageTitle = Yii::t('controllers', 'rnetwork_index_title');
    $userData = Yii::app()->user->model();
?>
<div class="card bg-white border-none mb-4">
  <div class="row">
    <div class="card-col col-xl-4 col-lg-4 col-md-4 col-12 border-right">
      <div class="card-body">
        <h4 class="card-header px-0 mb-0"><?= Yii::t('controllers', 'rnetwork_index_lbl_status', ['#status' => SprStatuses::model()->findByPk(Yii::app()->user->model()->status_account)->{'name_' . Yii::app()->language}, '#link' => Yii::app()->user->model()->status_account != Users::STATUSMAX ? CHtml::link('<span class="fas fa-arrow-circle-up small text-primary"></span>', '#', ['id' => 'buyStatusLink_Rnetwork', 'data-toggle' => 'tooltip', 'title' => Yii::t('controllers', 'rnetwork_index_btn_statusUp')]) : '']); ?></h4>
      </div>
    </div>
    <div class="card-col col-xl-8 col-lg-8 col-md-8 col-12 pl-0">
      <div class="card-body card-body pb-0 h-100">
        <div class="row align-items-center h-100">
          <div class="col-md-6">
            <button class="btn btn-gradient-primary btn-block mb-1-mobile" type="button" id="statusInfo"
                    csrf="<?= Yii::app()->request->getCsrfToken(); ?>">
              <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"
                    id="spinner_status"></span>
                <?= Yii::t('controllers', 'rnetwork_index_btn_statusInfo') . ' <i class="icon-arrow-right" style="vertical-align: text-bottom;"></i>'; ?>
            </button>
          </div>
          <div class="col-md-6">
            <button class="btn btn-gradient-info btn-block" type="button" id="levelInfo"
                    csrf="<?= Yii::app()->request->getCsrfToken(); ?>">
              <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"
                    id="spinner_level"></span>
                <?= Yii::t('controllers', 'rnetwork_index_btn_levelInfo') . ' <i class="icon-arrow-right" style="vertical-align: text-bottom;"></i>'; ?>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="card mb-4">
  <div class="card-body">
    <div class="row justify-content-md-center">
      <div class="col-md-6 d-flex flex-wrap flex-md-nowrap justify-content-center">
        <i class="mdi mdi-content-copy text-primary mr-2" id="rCopyLink"></i>
        <div class="col-xl-11 col-lg-11 col-md-11 col-sm-12 text-center border rounded border-red">
          <h6 id="personal_referral_link_network_head" class="mb-1"><?= Yii::t('controllers', 'rnetwork_index_lbl_personalLink');?></h6>
          <h5><span id="rLink" message="<?=Yii::t('controllers', 'rnetwork_index_ntf_linkCopied');?>"><?= $this->createAbsoluteUrl('/register?referral_id='.Yii::app()->user->model()->referral_id); ?></span></h5>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card card-statistics mb-4">
  <div class="row">
    <div class="card-col col-xl-4 col-lg-4 col-md-4 col-12 border-right">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
          <i class="mdi mdi-account-multiple-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
          <div class="wrapper text-center text-sm-left">
            <p class="card-text mb-0"><?= Yii::t('controllers', 'rnetwork_index_lbl_countRefs'); ?></p>
            <div class="fluid-container">
              <h3 class="mb-0 font-weight-medium"><?= $countRelations; ?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-col col-xl-4 col-lg-4 col-md-4 col-12 border-right">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
          <i class="icon-coin-dollar text-primary mr-0 mr-sm-4 icon-lg"></i>
          <div class="wrapper text-center text-sm-left">
            <p class="card-text mb-0"><?= Yii::t('controllers', 'rnetwork_index_lbl_countRefsBalance'); ?></p>
            <div class="fluid-container">
              <h3 class="mb-0 font-weight-medium"><?= number_format($fullbalance,2,".","").'$';?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-col col-xl-4 col-lg-4 col-md-4 col-12">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
          <i class="icon-radio_button_unchecked text-primary mr-0 mr-sm-4 icon-lg"></i>
          <div class="wrapper text-center text-sm-left">
            <p class="card-text mb-0"><?= Yii::t('controllers', 'rnetwork_index_lbl_countRefsCoins'); ?></p>
            <div class="fluid-container">
              <h3 class="mb-0 font-weight-medium"><?= $fullcoins.'CP';?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card mb-4">
  <div class="card-body">
    <div class="row">
      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
          <?php $this->widget('bootstrap.widgets.TbButton', [
              'block' => true,
              'context' => 'primary',
              'label' => Yii::t('controllers', 'rnetwork_index_btn_invite'),
              'htmlOptions' => [
                  'class' => 'btn-gradient-danger',
                  'id' => 'btn_invite'
              ]
          ]); ?>
      </div>
      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
          <?php $this->widget('bootstrap.widgets.TbButton', [
              'block' => true,
              'context' => 'info',
              'label' => Yii::t('controllers', 'rnetwork_index_btn_mainReg'),
              'htmlOptions' => [
                  'class' => 'btn-gradient-success',
                  'id' => 'btn_mainRegister'
              ]
          ]);?>
      </div>
    </div>
  </div>
</div>


<?php if($inviteGrid): ?>
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-center">
          <div class="card bg-white">
            <div class="card-header"><strong><?= Yii::t('controllers', 'rnetwork_index_lbl_inviteOutList'); ?></strong></div>
            <div class="card-body">
                <?php $this->widget('bootstrap.widgets.TbGridView', [
                    'id' => 'inviteOutList',
                    'dataProvider' => $inviteGrid,
                    'enableSorting' => true,
                    'template' => '{items}{pager}',
                    'htmlOptions' => ['class' => 'table-responsive tableNotify'],
                    'pagerCssClass' => 'mt-2 pageNotify',
                    'ajaxUpdate' => true,
                    'columns' => [
                        ['name' => 'invite_email', 'header' => Yii::t('models', 'Invite_attr_invite_email'), 'type' => 'html', 'value' => '$data->invite_email'],
                        ['name' => 'invite_date', 'header' => Yii::t('models', 'Invite_attr_invite_date'), 'type' => 'raw', 'value' => '($data->invite_date != null) ? date("d.m.Y", strtotime($data->invite_date)) : ""'],
                        ['header' => false, 'type' => 'raw', 'value' => '$data->inviteStatusGrid($data->user_ok)'],
                        ['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{repeat}', 'buttons' => [
                            'repeat' => [
                                'icon' => 'fas fa-redo-alt',
                                'url' => 'Yii::app()->controller->createUrl("repeatInvite", ["invite_email" => $data->invite_email])',
                                'options' => [
                                    'data-toggle' => 'tooltip',
                                    'data-placement' => 'bottom',
                                    'title' => Yii::t('core', 'btn_repeat'),
                                    'ajax' => [
                                        'url' => 'js:$(this).attr("href")',
                                        'success'=>'function(data){
                                            var obj = JSON.parse(data);
                                            if(obj.status == "success") {
                                               showNoty("'.Yii::t('controllers', 'rnetwork_index_ntf_repeatInviteSuccess').'", "success");
                                            }
                                        }'
                                    ],
                                ],
                                'visible' => '!$data->user_ok'
                            ],
                        ]]
                    ]
                ]); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>


<div class="card">
  <div class="card-body">
    <div class="row gutters mt-3">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-center">
          <?php if($tabs): ?>
            <div class="card bg-white">
              <div class="card-header"><strong><?= Yii::t('controllers', 'rnetwork_index_lbl_relationList'); ?></strong></div>
              <div class="card-body">
                  <?php
                  if(!$mobileDetect->isMobile() && !$mobileDetect->isIphone()):
                      $this->widget('ExtPillsTabs', [
                          'type' => 'pills',
                          'tabs' => $tabs,
                      ]);
                  else:
                      ?>
                    <div class="accordion" id="accordionRelation">
                        <?php foreach ($tabs as $key => $val): ?>
                          <div class="card">
                            <div class="card-header" id="heading<?= $key; ?>">
                              <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse<?= $key; ?>" aria-expanded="<?= $val['active'] ? true : false; ?>" aria-controls="collapse<?= $key; ?>">
                                    <?= $val['label']; ?>
                                </button>
                              </h5>
                            </div>
                            <div id="collapse<?= $key; ?>" class="collapse <?= $val['active'] ? 'show' : ''; ?>" aria-labelledby="heading<?= $key; ?>" data-parent="#accordionRelation">
                              <div class="card-body">
                                  <?= $val['content']; ?>
                              </div>
                            </div>
                          </div>
                        <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
              </div>
            </div>

            <div class="card bg-white mt-3 text-left">
              <div class="card-header"><strong><?= Yii::t('controllers', 'rnetwork_index_lbl_relationList2'); ?></strong></div>
              <div class="card-body">
                  <?php
                  Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/bootstraptree/bootstrap-treeview.js');
                  Yii::app()->clientScript->registerCssFile($this->assetsBase.'/vendor/bootstraptree/bootstrap-treeview.css');

                  $this->widget('BootstrapTreeView', [
                      'data' => $this->listTree(),
                      'htmlOptions' => ['id' => 'treeRefList']
                  ]);
                  ?>
              </div>
            </div>
          <?php else: ?>
            <h5><?= Yii::t('controllers', 'rnetwork_index_lbl_relationList_empty'); ?></h5>
          <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php if($tabsOut): ?>
  <div class="card">
    <div class="card-body">
      <div class="row gutters mt-3">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-center">
          <div class="card bg-white">
            <div class="card-header text-danger"><strong><?= Yii::t('controllers', 'rnetwork_index_lbl_relationOutList', ['#help' => '<span style="vertical-align: text-top" class="text-danger far fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('controllers', 'rnetwork_index_lbl_refOut_tooltip').'"></span>']); ?></strong><?= Yii::app()->user->finance->balance != 0 &&  $userData->status_account != Users::STATUSMAX ? CHtml::link(Yii::t('controllers', 'dashboard_index_btn_buyStatus'), '#', ['id' => 'buyStatusLink_rnet']) : ''; ?></div>
            <div class="card-body">
                <?php
                if(!$mobileDetect->isMobile() && !$mobileDetect->isIphone()):
                    $this->widget('ExtPillsTabs', [
                        'type' => 'pills',
                        'tabs' => $tabsOut
                    ]);
                else:
                    ?>
                  <div class="accordion" id="accordionRelationOut">
                      <?php foreach ($tabsOut as $key => $val): ?>
                        <div class="card">
                          <div class="card-header" id="headingOut<?= $key; ?>">
                            <h5 class="mb-0">
                              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOut<?= $key; ?>" aria-expanded="<?= $val['active'] ? true : false; ?>" aria-controls="collapseOut<?= $key; ?>">
                                  <?= $val['label']; ?>
                              </button>
                            </h5>
                          </div>
                          <div id="collapseOut<?= $key; ?>" class="collapse <?= $val['active'] ? 'show' : ''; ?>" aria-labelledby="headingOut<?= $key; ?>" data-parent="#accordionRelationOut">
                            <div class="card-body">
                                <?= $val['content']; ?>
                            </div>
                          </div>
                        </div>
                      <?php endforeach; ?>
                  </div>
                <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>