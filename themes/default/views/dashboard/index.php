<?php 
    $this->pageTitle = Yii::t('controllers', 'dashboard_index_title');
    $userBalance = Yii::app()->user->finance;
    $userData = Yii::app()->user->model();
?>

<div class="row gutters">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <?php $this->widget('bootstrap.widgets.TbAlert', [
            'fade' => true,
            'closeText' => '&times;',
            'userComponentId' => 'user',
            'alerts' => [
                'success' => ['closeText' => '&times;'],
                'error' => ['closeText' => '&times;']
            ],
        ]); ?>
    </div>
</div>

<div class="row gutters">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card card-statistics">
          <div class="row">
            <div class="card-col col-xl-4 col-lg-3 col-md-3 col-12 border-right">
              <div class="card-body">
                <h4 class="card-title px-0"><?= Yii::t('controllers', 'dashboard_index_lbl_opportunities'); ?></h4>
                <h3 class="bg-primary font-white text-center rounded p-2"><?= Yii::t('controllers', 'dashboard_index_lbl_yourStatus', ['#status' => $userData->statusAccount->{'name_' . Yii::app()->language}]); ?></h3>
              </div>
            </div>
            <div class="card-col col-xl-8 col-lg-8 col-md-8 col-12">
              <div class="d-flex align-items-center justify-content-center flex-column flex-row h-100">
                <p class="mb-1 mt-2"><?= Yii::t('models', 'sprStatuses_attr_max_levels_full', ['#count' => $userData->statusAccount->max_levels]); ?> <?= $userData->status_account != Users::STATUSMAX ? CHtml::link(Yii::t('controllers', 'dashboard_index_btn_buyStatus'), '#', ['id' => 'buyStatusLink_board']) : ''; ?></p>
                <p class="mb-1"><?= Yii::t('models', 'sprStatuses_attr_max_coin_buy_summ_full', ['#summ' => $userData->statusAccount->max_coin_buy_summ]); ?></p>
                <p><?= Yii::t('models', 'sprStatuses_attr_out_full', ['#count' => $userData->statusAccount->out_count, '#period' => SprStatuses::getOutPeriodType($userData->statusAccount->out_count_period, true), '#summ' => $userData->statusAccount->out_max_summ]); ?></p>
              </div>
            </div>
          </div>


          <!--<div class="card-body mb-0 pb-0">
            <div class="row">
              <div class="col-md-12">
                <h4 class="card-title px-0"><?/*= Yii::t('controllers', 'dashboard_index_lbl_opportunities'); */?></h4>
              </div>
            </div>
              <div class="row">
                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                      <h3 class="bg-primary font-white text-center rounded p-2"><?/*= Yii::t('controllers', 'dashboard_index_lbl_yourStatus', ['#status' => $userData->statusAccount->{'name_'.Yii::app()->language}]); */?></h3>
                  </div>
                  <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
                      <p class="mb-1 mt-2"><?/*= Yii::t('models', 'sprStatuses_attr_max_levels_full', ['#count' => $userData->statusAccount->max_levels]); */?> <?/*= $userData->status_account != Users::STATUSMAX ? CHtml::link(Yii::t('controllers', 'dashboard_index_btn_buyStatus'), '#', ['id' => 'buyStatusLink_board']) : ''; */?></p>
                      <p class="mb-1"><?/*= Yii::t('models', 'sprStatuses_attr_max_coin_buy_summ_full', ['#summ' => $userData->statusAccount->max_coin_buy_summ]); */?></p>
                      <p><?/*= Yii::t('models', 'sprStatuses_attr_out_full', ['#count' => $userData->statusAccount->out_count, '#period' => SprStatuses::getOutPeriodType($userData->statusAccount->out_count_period, true), '#summ' => $userData->statusAccount->out_max_summ]); */?></p>
                  </div>
              </div>
          </div>-->
        </div>
    </div>
</div>

<div class="row gutters mt-5">
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
              <img src="https://www.bootstrapdash.com/demo/purple-admin-free/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image">
                <div class="stats-widget">
                    <div class="stats-widget-header">
                        <ul class="row no-gutters pl-0">
                            <li class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col">
                                <i class="icon-coin-dollar text-white"></i>
                            </li>
                            <li class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col text-right text-white">
                                <h6 class="mb-0">&nbsp;</h6>
                                <h6 class="mb-0"><?= Yii::t('controllers', 'dashboard_index_lbl_balance_coinsBuy_freeze', ['#summ' => number_format($userBalance->buy_freeze,2,".","")]);?></h6>
                                <h6 class="mb-0"><?= Yii::t('controllers', 'dashboard_index_lbl_balance_outs_freeze', ['#summ' => number_format($userBalance->outs_freeze,2,".","")]);?></h6>
                                <h6 class="mb-0">&nbsp;</h6>
                            </li>
                        </ul>
                    </div>
                    <div class="stats-widget-body">
                        <ul class="row no-gutters pl-0">
                            <li class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col">
                                <h6 class="title text-white"><?= Yii::t('controllers', 'dashboard_index_lbl_balance'); ?></h6>
                            </li>
                            <li class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col">
                                <h4 class="total text-white"><?= number_format($userBalance->balance - $userBalance->outs_freeze - $userBalance->buy_freeze,2,".","") ?>$</h4>
                            </li>
                        </ul>
                        <div class="row">
                            <div class="col-md-6">
                                <?php 
                                    $this->widget('bootstrap.widgets.TbButton', [
                                        'block' => true,
                                        'context' => 'primary',
                                        'label' => Yii::t('controllers', 'dashboard_index_btn_pay'),
                                        'htmlOptions' => [
                                            'id' => 'payLink_dash',
                                            'class' => 'btn-inverse-dark btn-light px-0'
                                        ]
                                    ]);
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                    $this->widget('bootstrap.widgets.TbButton', [
                                        'block' => true,
                                        'context' => 'light',
                                        'label' => Yii::t('controllers', 'dashboard_index_btn_out'),
                                        'disabled' => $userBalance->balance <= 0,
                                        'htmlOptions' => [
                                            'id' => 'outLink_dash',
                                            'class' => 'btn-inverse-dark btn-light'
                                        ]
                                    ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
              <img src="https://www.bootstrapdash.com/demo/purple-admin-free/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image">
                <div class="stats-widget">
                    <div class="stats-widget-header">
                        <ul class="row no-gutters pl-0">
                            <li class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col">
                                <i class="icon-radio_button_unchecked text-white"></i>
                            </li>
                            <li class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col text-right text-white">
                                <h6 class="mb-0"><?= Yii::t('controllers', 'dashboard_index_lbl_activ_profit', ['#count' => $userBalance->coinsProfit]);?></h6>
                                <h6 class="mb-0"><?= Yii::t('controllers', 'dashboard_index_lbl_activ_buy', ['#count' => $userBalance->coins_buy]);?></h6>
                                <h6 class="mb-0"><?= Yii::t('controllers', 'dashboard_index_lbl_activ_sell', ['#count' => $userBalance->coins_sell]);?></h6>
                                <h6 class="mb-0"><?= Yii::t('controllers', 'dashboard_index_lbl_activ_freeze', ['#count' => $userBalance->coins_freeze]);?></h6>
                            </li>
                        </ul>
                        
                    </div>
                    <div class="stats-widget-body">
                        <!-- Row start -->
                        <ul class="row no-gutters pl-0">
                            <li class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col">
                                <h6 class="title text-white"><?= Yii::t('controllers', 'dashboard_index_lbl_activ'); ?></h6>
                            </li>
                            <li class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col text-right">
                                <h4 class="total text-white"><?= $userBalance->coins - $userBalance->coins_freeze; ?>CP</h4>
                            </li>
                        </ul>
                        <div class="row">
                            <div class="col-md-6">
                                <?php $this->widget('bootstrap.widgets.TbButton', [
                                    'block' => 'true',
                                    'context' => $userBalance->balance == 0 ? 'light' : 'primary',
                                    'label' => Yii::t('core', 'btn_buy'),
                                    'disabled' => $userBalance->balance == 0,
                                    'buttonType' => 'link',
                                    'url' => $this->createUrl('/exchange'),
                                    'htmlOptions' => [
                                        'class' => 'btn-inverse-dark btn-light'
                                    ]
                                ]);?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                    $this->widget('bootstrap.widgets.TbButton', [
                                        'block' => true,
                                        'context' => 'light',
                                        'url' => $this->createUrl('/exchange'),
                                        'buttonType' => 'link',
                                        'label' => Yii::t('controllers', 'dashboard_index_btn_sell'),
                                        'disabled' => $userBalance->now_coins = 0,
                                        'htmlOptions' => [
                                            'class' => 'btn-inverse-dark btn-light'
                                        ]
                                    ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
              <img src="https://www.bootstrapdash.com/demo/purple-admin-free/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image">
                <div class="stats-widget">
                    <div class="stats-widget-header">
                        <ul class="row no-gutters pl-0">
                            <li class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col">
                                <i class="icon-stats-bars text-white"></i>
                            </li>
                            <li class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col text-right text-white">
                                <h6 class="mb-0">&nbsp;</h6>
                                <h6 class="mb-0">&nbsp;</h6>
                                <h6 class="mb-0">&nbsp;</h6>
                                <h6 class="mb-0">&nbsp;</h6>
                            </li>
                        </ul>
                    </div>
                    <div class="stats-widget-body">
                        <!-- Row start -->
                        <ul class="row no-gutters pl-0">
                            <li class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col">
                                <h6 class="title text-white"><?= Yii::t('controllers', 'dashboard_index_lbl_coinCourse'); ?></h6>
                            </li>
                            <li class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col">
                                <h4 class="total text-white"><?= $coins->price; ?>$</h4>
                            </li>
                        </ul>
                        <?php $this->widget('bootstrap.widgets.TbButton', [
                            'block' => 'true',
                            'context' => 'primary',
                            'label' => Yii::t('controllers', 'dashboard_index_lbl_forDay', ['#price' => $chartPrice]),
                            'disabled' => true,
                            'htmlOptions' => ['class' => 'btn-inverse-dark btn-light']
                        ]);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 co0l-sm-12 stretch-card grid-margin">
        <div class="card bg-gradient-primary card-img-holder text-white">
            <div class="card-body">
              <img src="https://www.bootstrapdash.com/demo/purple-admin-free/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image">
                <div class="stats-widget">
                    <div class="stats-widget-header">
                        <ul class="row no-gutters pl-0">
                            <li class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col">
                                <i class="icon-flow-tree text-white"></i>
                            </li>
                            <li class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col text-right text-white">
                                <h6 class="mb-0">&nbsp;</h6>
                                <h6 class="mb-0">&nbsp;</h6>
                                <h6 class="mb-0">&nbsp;</h6>
                                <h6 class="mb-0">&nbsp;</h6>
                            </li>
                        </ul>
                    </div>
                    <div class="stats-widget-body">
                        <!-- Row start -->
                        <ul class="row no-gutters pl-0">
                            <li class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col">
                                <h6 class="title text-white"><?= Yii::t('controllers', 'dashboard_index_lbl_rActiv'); ?></h6>
                            </li>
                            <li class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col">
                                <h4 class="total text-white"><?= $refs['count'] ? $refs['count'] : 0; ?> <?= ($refs['countOfDay']) ? Yii::t('controllers', 'dashboard_index_lbl_partnerOnDay', ['#count' => $refs['countOfDay']]) : Yii::t('controllers', 'dashboard_index_lbl_partnerOnDay', ['#count' => $refs['countOfDay']])?></h4>
                            </li>
                        </ul>
                        <?php $this->widget('bootstrap.widgets.TbButton', [
                            'block' => 'true',
                            'context' => 'primary',
                            'label' => Yii::t('core', 'btn_view'),
                            'disabled' => !$refs['count'],
                            'buttonType' => 'link',
                            'url' => $this->createUrl('/rnetwork'),
                            'htmlOptions' => ['class' => 'btn-inverse-dark btn-light']
                        ]);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row gutters">
  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 stretch-card">
      <div class="card bg-gradient-secondary card-img-holder text-white">
            <div class="card-body">
              <img src="https://www.bootstrapdash.com/demo/purple-admin-free/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image">
                <div class="stats-widget">
                    <div class="stats-widget-header">
                        <i class="fas fa-arrow-up text-white"></i>
                    </div>
                    <div class="stats-widget-body">
                        <ul class="row no-gutters pl-0">
                            <li class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col">
                                <h6 class="title text-white"><?= Yii::t('controllers', 'dashboard_index_lbl_invest'); ?></h6>
                            </li>
                            <li class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col">
                                <h4 class="total text-white"><?= number_format(($userBalance->invest_status + $userBalance->invest_coin),2,".","").'$'; ?></h4>
                            </li>
                        </ul>
                        <div class="text-right text-white">
                            <h6 class="mb-0 mt-2"><?= Yii::t('controllers', 'dashboard_index_lbl_invest_status', ['#summ' => number_format($userBalance->invest_status,2,".","")]);?></h6>
                            <h6 class="mb-0"><?= Yii::t('controllers', 'dashboard_index_lbl_invest_coins', ['#summ' => number_format($userBalance->invest_coin,2,".","")]);?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 stretch-card">
        <div class="card bg-gradient-warning card-img-holder text-white">
            <div class="card-body">
              <img src="https://www.bootstrapdash.com/demo/purple-admin-free/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image">
                <div class="stats-widget">
                    <div class="stats-widget-header">
                        <i class="fas fa-plus text-white"></i>
                    </div>
                    <div class="stats-widget-body">
                        <ul class="row no-gutters pl-0">
                            <li class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col">
                                <h6 class="title text-white"><?= Yii::t('controllers', 'dashboard_index_lbl_profit'); ?></h6>
                            </li>
                            <li class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col">
                                <h4 class="total text-white"><?= number_format($userBalance->profits,2,".","").'$'; ?></h4>
                            </li>
                        </ul>
                        <div class="text-right text-white">
                            <h6 class="mb-0 mt-2"><?= Yii::t('controllers', 'dashboard_index_lbl_profit_refs', ['#summ' => number_format($userBalance->profit_refs,2,".","")]);?></h6>
                            <h6 class="mb-0"><?= Yii::t('controllers', 'dashboard_index_lbl_profit_coins', ['#summ' => number_format($userBalance->profit_coin,2,".","")]);?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 stretch-card">
      <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
              <img src="https://www.bootstrapdash.com/demo/purple-admin-free/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image">
                <div class="stats-widget">
                    <div class="stats-widget-header">
                        <i class="fas fa-arrow-down text-white"></i>
                    </div>
                    <div class="stats-widget-body">
                        <ul class="row no-gutters pl-0">
                            <li class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col">
                                <h6 class="title text-white"><?= Yii::t('controllers', 'dashboard_index_lbl_outs'); ?></h6>
                            </li>
                            <li class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col">
                                <h4 class="total text-white"><?= $userBalance->outs ? number_format($userBalance->outs,2,".","").'$' : "0$"; ?></h4>
                            </li>
                        </ul>
                        <div class="text-right text-muted">
                            <h6 class="mb-0 mt-2">&nbsp;</h6>
                            <h6 class="mb-0 text-white text-center"><?= Yii::t('controllers', 'dashboard_index_lbl_outs_desc'); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 stretch-card">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
              <img src="https://www.bootstrapdash.com/demo/purple-admin-free/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image">
                <div class="stats-widget">
                    <div class="stats-widget-header">
                        <i class="icon-dots-three-horizontal text-white"></i>
                    </div>
                    <div class="stats-widget-body text-center">
                        <ul class="row no-gutters pl-0">
                            <li class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col">
                                <h4 class="total text-center text-white"><?= Yii::t('controllers', 'dashboard_index_lbl_soon'); ?></h4>
                            </li>
                        </ul>
                        <h6 class="mb-0 mt-2">&nbsp;</h6>
                        <h6 class="mb-0 text-white"><?= Yii::t('controllers', 'dashboard_index_lbl_soon2'); ?></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if($main_test_cut): ?>
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="alert alert-danger" role="alert">
                <h4><?= Yii::t('controllers', 'dashboard_index_youHaveSellOrder', ['#number' => $main['number'], '#count' => $main['count'], '#count_now' => $main['count_now']]); ?></h4>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row gutters mt-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card bg-white border-none">
            <div class="card-header"><strong><?= Yii::t('controllers', 'dashboard_index_lbl_incompletePays'); ?></strong></div>
            <div class="card-body mb-0 pb-0">
                <?php 
                    $this->widget('bootstrap.widgets.TbGridView', [
                        'id' => 'incompletePays',
                        'dataProvider' => $inPays->search(10),
                        'type' => 'stripped',
                        'enableSorting' => true,
                        'emptyTagName' => 'span',
                        'emptyCssClass' => 'text-center text-success d-flex justify-content-center h4',
                        'emptyText' => Yii::t('models', 'Pays_lbl_incompletePaysEmpty'),
                        'template' => '{items}{pager}',
                        'pagerCssClass' => 'mt-2 pagerNew',
                        'htmlOptions' => ['class' => 'table-responsive'],
                        'ajaxUpdate' => true,
                        'columns' => [
                            ['name' => 'operation_number'],
                            ['name' => 'operation_date', 'type' => 'raw', 'value' => 'MHelper::formBeautyDate($data->operation_date)'],
                            ['name' => 'operation_summ', 'type' => 'raw', 'value' => 'number_format($data->operation_summ,2,".","")."$"'],
                            ['name' => 'operation_system', 'type' => 'raw', 'value' => '$data->getPayMode($data->operation_system)'],
                            ['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{bitstatus}{prizmastatus}{pay}&nbsp;{del}', 'buttons' => [
                                    'pay' => [
                                        'icon' => 'fas fa-redo-alt',
                                        'label' => Yii::t('core', 'btn_info'),
                                        'click' => 'js: function(){
                                            $("#modalData").load("'.$this->createUrl('/finance/payProcess', ['operation_number' => '']).'"+$(this).parent().parent().children(":first-child").text());
                                        }',
                                        'options' => [
                                            'data-toggle' => 'tooltip',
                                            'title' => Yii::t('core', 'btn_repeat'),
                                        ],
                                        'visible' => '$data->operation_status == $data::PSTATUS_WAIT && !in_array($data->operation_system, [MBaseModel::FIN_BITCOIN, MBaseModel::FIN_PRIZM])'
                                    ],
                                    'bitstatus' => [
                                        'icon' => 'far fa-question-circle',
                                        'label' => Yii::t('core', 'btn_info'),
                                        'click' => 'js: function(){
                                            $("#modalData").load("'.$this->createUrl('/finance/payByBitcoinStatus', ['operation_number' => '']).'"+$(this).parent().parent().children(":first-child").text(), function(){$.fancybox.open($("#modalWindow"), {touch: false,toolbar: false,hash: false,clickSlide: false});});
                                        }',
                                        'options' => [
                                            'data-toggle' => false
                                        ],
                                        'visible' => '$data->operation_system == $data::FIN_BITCOIN'
                                    ],
                                    'prizmastatus' => [
                                        'icon' => 'far fa-question-circle',
                                        'label' => Yii::t('core', 'btn_info'),
                                        'click' => 'js: function(){
                                            $("#modalData").load("'.$this->createUrl('/finance/payByPrizmStatus', ['pay_number' => '']).'"+$(this).parent().parent().children(":first-child").text(), function(){$.fancybox.open($("#modalWindow"), {touch: false,toolbar: false,hash: false,clickSlide: false});});
                                        }',
                                        'options' => [
                                            'data-toggle' => false
                                        ],
                                        'visible' => '$data->operation_system == $data::FIN_PRIZM'
                                    ],
                                    'del' => [
                                        'icon' => 'fas fa-trash',
                                        'label' => Yii::t('core', 'btn_delete'),
                                        'url' => 'Yii::app()->controller->createUrl("/finance/deletePay", ["id" => $data->id])',
                                        'options' => [
                                            'data-toggle' => false,
                                            'confirm' => Yii::t('controllers', 'finance_btn_deleteConfirm'),
                                            'ajax' => [
                                                'url' => 'js:$(this).attr("href")',
                                                'success'=>'function(data){
                                                    var obj = JSON.parse(data);
                                                    if(obj.status == "success")
                                                        $.fn.yiiGridView.update("incompletePays");
                                                }'    
                                            ], 
                                        ],
                                        'visible' => '$data->operation_status == $data::PSTATUS_WAIT'
                                    ]
                                ]
                            ]
                        ]
                    ]); ?>
            </div>
        </div>
    </div>
</div>

<?php if($inOuts): ?>
<div class="row gutters mt-3">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card bg-white border-none">
            <div class="card-header"><strong><?= Yii::t('controllers', 'dashboard_index_lbl_incompleteOuts'); ?></strong></div>
            <div class="card-body mb-0 pb-0">
                <?php $this->widget('bootstrap.widgets.TbGridView', [
                    'id' => 'outList',
                    'dataProvider' => MHelper::getArrayProvider($inOuts, 10),
                    'type' => 'stripped',
                    'enableSorting' => true,
                    'template' => '{items}{pager}',
                    'pagerCssClass' => 'mt-2 pagerNew',
                    'htmlOptions' => ['class' => 'table-responsive'],
                    'ajaxUpdate' => true,
                    'columns' => [
                        ['name' => 'operation_date', 'header' => Yii::t('models', 'attr_date'), 'type' => 'raw', 'value' => 'date("d.m.Y", strtotime($data->operation_date))'],
                        ['name' => 'operation_number', 'header' => Yii::t('models', 'attr_operation_number')],
                        ['name' => 'operation_summ', 'header' => Yii::t('models', 'attr_summ'), 'type' => 'raw', 'value' => 'number_format($data->operation_summ,2,".","")."$"'],
                        ['name' => 'operation_system', 'header' => Yii::t('models', 'attr_system'), 'type' => 'raw', 'value' => '$data->getFinType($data->operation_system)'],
                        ['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{cancOrder}', 'buttons' => [
                            'cancOrder' => [
                                'icon' => 'fas fa-times',
                                'url' => 'Yii::app()->controller->createUrl("/finance/cancOut", ["id" => $data->id])', 
                                'options' => [
                                    'data-toggle' => 'tooltip',
                                    'title' => Yii::t('core', 'btn_cancel'),
                                    'confirm' => Yii::t('controllers', 'dashboard_index_lbl_confirmCancOrder'),
                                    'ajax' => [
                                        'url' => 'js:$(this).attr("href")',
                                        'success'=>'function(data){
                                            var obj = JSON.parse(data);
                                            if(obj.status == "success") {
                                                $(".notifyjs-corner").empty();
                                                $.fn.yiiGridView.update("outList");
                                            }
                                        }'    
                                    ],  
                                ],
                            ],
                        ]]
                    ]
                ]); ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row gutters mt-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card bg-white border-none">
            <div class="card-header"><strong><?= Yii::t('controllers', 'dashboard_index_lbl_hystoryBalance'); ?></strong></div>
            <div class="card-body mb-0 pb-0">
                <div class="col-md-12">
                    <?= CHtml::beginForm('', 'get', ['id' => 'filterBalance', 'style' => 'width: 100%']); ?>
                    <div class="row p-3">
                        <div class="col-md-3 filterMobile">
                            <?= CHtml::dropDownList('operation_type', $operation_type, UsersBalance::model()->operationType(), ['class' => 'form-control form-control-sm', 'empty' => Yii::t('controllers', 'dashboard_index_lbl_chooseActionType')]); ?>
                        </div>
                        <div class="col-md-5 text-center filterMobile">
                            <?= CHtml::textField('summ_min', '', ['class' => 'form-control form-control-sm inline', 'placeholder' => Yii::t('controllers', 'dashboard_index_lbl_summForm')]).' - '.CHtml::textField('summ_max', '', ['class' => 'form-control form-control-sm inline', 'placeholder' => Yii::t('controllers', 'dashboard_index_lbl_summTo')]); ?>
                        </div>
                        <div class="col-md-4">
                            <?php $this->widget('bootstrap.widgets.TbDateRangePicker', [
                                'name' => 'balance_period',
                                'options' => [
                                    'showDropdowns' => true,
                                    'drops' => 'down',
                                    'autoApply' => true,
                                    'autoUpdateInput' => true,
                                    'locale' => [
                                        'format'=>'DD.MM.YYYY',
                                        'customRangeLabel' => Yii::t('controllers', 'dashboard_historyOperation_lbl_mainPeriod')
                                    ],
                                    'linkedCalendars' => false,
                                    'language' => Yii::app()->language,
                                    'minDate' => '01.01.2020',
                                    'maxDate' => date('d.m.Y'),
                                    'startDate' => '01.01.2020',
                                    'endDate' => date('d.m.Y'),
                                    'ranges' => [
                                        Yii::t('controllers', 'dashboard_historyOperation_lbl_today') => [date('d.m.Y'), date('d.m.Y')],
                                        Yii::t('controllers', 'dashboard_historyOperation_lbl_yesterday') => [date('d.m.Y' ,strtotime('-1 day')), date('d.m.Y')],
                                        Yii::t('controllers', 'dashboard_historyOperation_lbl_last7day') => [date('d.m.Y' ,strtotime('-7 day')), date('d.m.Y')],
                                        Yii::t('controllers', 'dashboard_historyOperation_lbl_last30day') => [date('d.m.Y' ,strtotime('-30 day')), date('d.m.Y')],
                                        Yii::t('controllers', 'dashboard_historyOperation_lbl_thisMonth') => [date('01.m.Y'), date('d.m.Y')],
                                        Yii::t('controllers', 'dashboard_historyOperation_lbl_thisYear') => [date('01.01.Y'), date('d.m.Y')],
                                    ]
                                ],
                                'htmlOptions' => [
                                'style' => 'min-width: 100%',
                                'class' => 'form-control form-control-sm'
                                ]
                            ]); ?>
                        </div>
                    </div>
                    <?= CHtml::endForm(); ?>
                </div> 
                <?php if($balance){
                    $this->widget('bootstrap.widgets.TbGridView', [
                        'id' => 'operationList',
                        'type' => 'stripped',
                        'dataProvider' => $balance->search(10),
                        'template' => '{items}{pager}',
                        'htmlOptions' => ['class' => 'table-responsive mt-0'],
                        'pagerCssClass' => 'mt-2 pagerNew',
                        'ajaxUpdate' => true,
                        'selectionChanged' => "function(id){getOperationInfo($.fn.yiiGridView.getSelection(id));}",
                        'columns' => [
                            [
                                'name' => 'operation_date',
                                'header' => false,
                                'type' => 'html',
                                'htmlOptions' => ['class' => 'text-center'],
                                'value' => '"<span class=\"block\">".date(\'d.m\', strtotime($data->operation_date))."</span>
                                    <span class=\"text-muted historyTime\">".date(\'H:i\', strtotime($data->operation_date))."</span>"'
                            ],
                            [
                                'name' => 'operation_type',
                                'header' => false,
                                'type' => 'html',
                                'htmlOptions' => ['class' => 'history_type'],
                                'value' => '$data->operationTypeWithColor($data->operation_type)'
                            ],
                            [
                                'name' => 'operation_summ',
                                'header' => false,
                                'type' => 'html',
                                'htmlOptions' => ['class' => 'history_summ'],
                                'value' => '$data->getValueOnHistory($data->operation_type, $data->operation_type == $data::TYPE_PROFITCOIN ? $data->market->count: $data->operation_summ)'
                            ],
                        ]
                    ]);
                } else 
                    echo '<span class="text-center text-muted d-flex justify-content-center h4">'.Yii::t('models', 'Balance_lbl_operationsEmpty').'</span>';
                ?>
            </div>
        </div>
    </div>
</div>