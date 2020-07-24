<div class="card">
  <div class="card-body">
      <?php
      $this->pageTitle = Yii::t('controllers', 'exchange_index_title');
      #Yii::app()->clientScript->registerScript('updateGrids', 'updateExchangeGrids()');

      Yii::app()->clientScript->registerScriptFile($this->assetsBase.'/vendor/chartjs/Chart.bundle.min.js', CClientScript::POS_END);
      Yii::app()->clientScript->registerScript('charts', "
        var ctx = document.getElementById('coinsChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ".json_encode($crts['label']).",
                datasets: [{
                    label: '".Yii::t('controllers', 'exchange_index_lbl_coinCourseChart')."',
                    data: ". json_encode($crts['value']).",
                    fill: false,
                    borderColor: ['#ea4c89'],
                    borderWidth: 1
                }]
            },
            options: {  
                responsive: true,
                maintainAspectRatio: false
            }
        });
    ");

      $this->widget('bootstrap.widgets.TbButton', [
          'label' => 'Схлопнуть',
          'buttonType' => 'link',
          'url' => $this->createUrl('/exchange/closeOrder'),
          'htmlOptions' => [
              'class' => 'btn-gradient-danger'
          ]
      ]);
      ?>
    <div class="row mt-2">
      <div class="col-md-12">
        <canvas class="chartjs-render-monitor" id="coinsChart" style="max-height: 300px"></canvas>
      </div>
    </div>
  </div>
</div>


<div class="card card-statistics my-4">
  <div class="row">
    <div class="card-col col-xl-3 col-lg-6 col-md-6 col-12 border-right">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
          <i class="mdi mdi-format-list-numbered text-primary mr-0 mr-sm-4 icon-lg"></i>
          <div class="wrapper text-center text-sm-left">
            <p class="card-text mb-0"><?= Yii::t('controllers', 'exchange_index_lbl_countOrders'); ?></p>
            <div class="fluid-container">
              <h3 class="mb-0 font-weight-medium" id="nowCountClosed"><?= $count['closed'];?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-col col-xl-3 col-lg-6 col-md-6 col-12 border-right">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
          <i class="mdi mdi-format-list-numbered text-primary mr-0 mr-sm-4 icon-lg"></i>
          <div class="wrapper text-center text-sm-left">
            <p class="card-text mb-0"><?= Yii::t('controllers', 'exchange_index_lbl_countBuyClosed'); ?></p>
            <div class="fluid-container">
              <h3 class="mb-0 font-weight-medium" id="nowCountClosed"><?= $count['count_buy_closed'];?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-col col-xl-3 col-lg-6 col-md-6 col-12 border-right">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
          <i class="mdi mdi-format-list-numbered text-primary mr-0 mr-sm-4 icon-lg"></i>
          <div class="wrapper text-center text-sm-left">
            <p class="card-text mb-0"><?= Yii::t('controllers', 'exchange_index_lbl_countSellClosed'); ?></p>
            <div class="fluid-container">
              <h3 class="mb-0 font-weight-medium" id="nowCountClosed"><?= $count['count_sell_closed'];?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-col col-xl-3 col-lg-6 col-md-6 col-12">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
          <i class="mdi mdi-format-list-numbered text-primary mr-0 mr-sm-4 icon-lg"></i>
          <div class="wrapper text-center text-sm-left">
            <p class="card-text mb-0"><?= Yii::t('controllers', 'exchange_index_lbl_emission'); ?></p>
            <div class="fluid-container">
              <h3 class="mb-0 font-weight-medium" id="nowCountClosed">45000000CP</h3>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card card-statistics my-4">
  <div class="row">
    <div class="card-col col-xl-3 col-lg-6 col-md-6 col-12 border-right">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
          <i class="mdi mdi mdi-chart-bar text-primary mr-0 mr-sm-4 icon-lg"></i>
          <div class="wrapper text-center text-sm-left">
            <p class="card-text mb-0"><?= Yii::t('controllers', 'exchange_index_lbl_coinCourse'); ?></p>
            <div class="fluid-container">
              <h3 class="mb-0 font-weight-medium" id="nowCourse"><?= $count['course'].'$'; ?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-col col-xl-3 col-lg-6 col-md-6 col-12 border-right">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
          <i class="mdi mdi mdi-chart-bar text-primary mr-0 mr-sm-4 icon-lg"></i>
          <div class="wrapper text-center text-sm-left">
            <p class="card-text mb-0"><?= Yii::t('controllers', 'exchange_index_lbl_coinCourseEnd'); ?></p>
            <div class="fluid-container">
              <h3 class="mb-0 font-weight-medium" id="nowCourse"><?= $count['course_end'].'$'; ?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-col col-xl-3 col-lg-6 col-md-6 col-12 border-right">
      <div class="card-body pr-0">
        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
          <i class="mdi mdi mdi-cart-plus text-primary mr-0 mr-sm-4 icon-lg"></i>
          <div class="wrapper text-center text-sm-left">
            <p class="card-text mb-0"><?= Yii::t('controllers', 'exchange_index_lbl_countBuy'); ?></p>
            <div class="fluid-container">
              <h3 class="mb-0 font-weight-medium" id="nowCountBuy"><?= number_format($count['buy'],2,'.','').'$'; ?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-col col-xl-3 col-lg-6 col-md-6 col-12">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
          <i class="mdi mdi-format-list-numbered text-primary mr-0 mr-sm-4 icon-lg"></i>
          <div class="wrapper text-center text-sm-left">
            <p class="card-text mb-0"><?= Yii::t('controllers', 'exchange_index_lbl_countOrdersNeedBuy'); ?></p>
            <div class="fluid-container">
              <h3 class="mb-0 font-weight-medium" id="nowCountBuy"><?= $count['count_buy']; ?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4"><?= Yii::t('controllers', 'exchange_index_lbl_ordersSell'); ?></h4>
            <?php $this->widget('bootstrap.widgets.TbButton', [
                'context' => 'primary',
                'block' => true,
                'label' => Yii::t('controllers', 'exchange_index_btn_order'),
                'disabled' => ($balance->coins - $balance->coins_freeze || !$button_disabled) > 0 ? false : true,
                'htmlOptions' => ['id' => 'btn_order_sell', 'class' => 'btn-gradient-primary']
            ]); ?>
            <?php $this->widget('bootstrap.widgets.TbGridView', [
                'id' => 'orderSell',
                'dataProvider' => $order->search(false, [CoinsOrder::OTYPE_SELL], CoinsOrder::OSTAT_WAIT, 't.id ASC', 10),
                'type' => 'stripped',
                'enableSorting' => true,
                'template' => '{items}',
                'pagerCssClass' => 'mt-2 pagerNew',
                'htmlOptions' => ['class' => 'table-responsive mt-3'],
                'ajaxUpdate' => true,
                'columns' => [
                    ['name' => 'id'],
                    ['header' => Yii::t('models', 'attr_user_id'), 'type' => 'html', 'value' => '$data->user->referral_id'],
                    ['name' => 'operation_date', 'type' => 'raw', 'value' => 'MHelper::formBeautyDate($data->operation_date)'],
                    ['name' => 'price_perOne', 'type' => 'raw', 'value' => '$data->price_perOne."$"'],
                    #['name' => 'count_now', 'type' => 'html', 'value' => '$data->count_now."CP"'],
                    ['header' => Yii::t('models', 'coinsOrder_attr_buy_summ'), 'type' => 'raw', 'value' => '($data->count_now*$data->price_perOne)."$"'],
                ]
            ]); ?>
        </div>
      </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4"><?= Yii::t('controllers', 'exchange_index_lbl_ordersBuy'); ?></h4>
          <?php $this->widget('bootstrap.widgets.TbButton', [
              'context' => 'primary',
              'block' => true,
              'label' => Yii::t('controllers', 'exchange_index_btn_order'),
              'disabled' => (($balance->balance - $balance->outs_freeze - $balance->buy_freeze) || !$button_disabled) > 0 ? false : true,
              'htmlOptions' => ['id' => 'btn_order_buy', 'class' => 'btn-gradient-primary']
          ]); ?>
            <?php $this->widget('bootstrap.widgets.TbGridView', [
                'id' => 'orderBuy',
                'dataProvider' => $order->search(false, [CoinsOrder::OTYPE_BUY], CoinsOrder::OSTAT_WAIT, 't.id ASC', 10),
                'type' => 'stripped',
                'enableSorting' => true,
                'template' => '{items}',
                'pagerCssClass' => 'mt-2 pagerNew',
                'htmlOptions' => ['class' => 'table-responsive mt-3'],
                'ajaxUpdate' => true,
                'columns' => [
                    ['header' => Yii::t('models', 'attr_user_id'), 'type' => 'html', 'value' => '$data->user->referral_id'],
                    ['name' => 'operation_date', 'type' => 'raw', 'value' => 'MHelper::formBeautyDate($data->operation_date)'],
                    #['name' => 'price_perOne', 'type' => 'raw', 'value' => '$data->price_perOne."$"'],
                    ['name' => 'buy_summ', 'type' => 'html', 'value' => 'number_format($data->buy_summ,2,".","")."$"'],
                ]
            ]); ?>
        </div>
      </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card bg-white border-none">
            <div class="card-body">
              <h4 class="card-header px-0"><?= Yii::t('controllers', 'exchange_index_lbl_orders'); ?></h4>
                <?php 
                    $this->widget('bootstrap.widgets.TbGridView', [
                        'id' => 'orders_buy_list',
                        'type' => 'stripped',
                        'dataProvider' => $order->search(Yii::app()->user->id, [CoinsOrder::OTYPE_BUY, CoinsOrder::OTYPE_SELL], false, $order = 't.id DESC'),
                        'enableSorting' => true,
                        'template' => '{items}{pager}',
                        'pagerCssClass' => 'mt-2 pagerNew',
                        'htmlOptions' => ['class' => 'table-responsive'],
                        'ajaxUpdate' => true,
                        'columns' => [
                            ['name' => 'id'],
                            ['name' => 'operation_date', 'type' => 'raw', 'value' => 'MHelper::formBeautyDate($data->operation_date)'],
                            ['name' => 'operation_type', 'type' => 'raw', 'value' => '$data->getOperationType($data->operation_type)'],
                            #['name' => 'price_perOne', 'type' => 'raw', 'value' => '$data->price_perOne."$"'],
                            ['header' => Yii::t('models', 'coinsOrder_attr_count_now'), 'type' => 'raw', 'value' => '$data->operation_type == $data::OTYPE_BUY ? $data->count."CP" : $data->count_now."CP"'],
                            #['name' => 'sell_summ', 'type' => 'raw', 'value' => '$data->operation_type == $data::OTYPE_SELL ? number_format($data->count * $data->price_perOne,2,".","")."$" : ""'],
                            ['name' => 'buy_summ', 'type' => 'raw', 'value' => '$data->operation_type == $data::OTYPE_BUY ? $data->buy_summ."$" : ""'],
                            ['name' => 'update_at', 'type' => 'raw', 'value' => '$data->update_at ? MHelper::formBeautyDate($data->update_at) : ""'],
                            ['name' => 'operation_status', 'type' => 'raw', 'value' => '$data->getOperationStatusToGrid($data->operation_status)'],
                            /*['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{cancOrder}', 'buttons' => [
                                'cancOrder' => [
                                    'icon' => 'fas fa-trash',
                                    'url' => 'Yii::app()->controller->createUrl("cancorder", ["id" => $data->id])', 
                                    'options' => [
                                        'data-toggle' => 'tooltip',
                                        'title' => Yii::t('core', 'btn_cancel'),
                                        'ajax' => [
                                            'url' => 'js:$(this).attr("href")',
                                            'success'=>'function(data){
                                                var obj = JSON.parse(data);
                                                if(obj.status == "success") {
                                                    $(".notifyjs-corner").empty();
                                                    showNoty("'.Yii::t('controllers', 'exchange_index_ntf_cancOrderSuccess').'", "success");
                                                    $.fn.yiiGridView.update("orders");
                                                    $.fn.yiiGridView.update("orderBuy");
                                                }
                                            }'    
                                        ],  
                                    ],
                                    'visible' => '$data->operation_status == $data::OSTAT_WAIT'
                                ],
                            ]]*/
                        ]
                    ]); ?>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card bg-white border-none">
            <div class="card-body">
              <h4 class="card-header px-0"><?= Yii::t('controllers', 'exchange_index_lbl_coinsBuy'); ?></h4>
                <?php 
                    $this->widget('bootstrap.widgets.TbGridView', [
                        'id' => 'coinsTable_buy',
                        'type' => 'stripped',
                        'dataProvider' => $coinsMarket->search(10, [CoinsMarket::TYPE_BUY, CoinsMarket::TYPE_SELL], 't.id DESC'),
                        'enableSorting' => true,
                        'template' => '{items}{pager}',
                        'pagerCssClass' => 'mt-2 pagerNew',
                        'htmlOptions' => ['class' => 'table-responsive'],
                        'ajaxUpdate' => true,
                        'columns' => [
                            ['name' => 'operation_date', 'type' => 'raw', 'value' => 'MHelper::formBeautyDate($data->operation_date)'],
                            ['name' => 'operation_type', 'type' => 'raw', 'value' => '$data->getOperationTypeGrid($data->operation_type)'],
                            ['name' => 'count', 'type' => 'html', 'value' => '$data->count."CP"'],
                            ['name' => 'operation_summ', 'type' => 'raw', 'value' => 'number_format($data->operation_summ,2,".","")."$"'],
                            ['name' => 'price_perOne', 'type' => 'raw', 'value' => '$data->price_perOne."$"'],
                            ['name' => 'countAll', 'type' => 'html', 'value' => '$data->countAll."CP"'],
                        ]
                    ]); ?>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card bg-white border-none">
            <div class="card-body">
              <h4 class="card-header px-0"><?= Yii::t('controllers', 'exchange_index_lbl_coinsProfit'); ?></h4>
                <?php 
                    $this->widget('bootstrap.widgets.TbGridView', [
                        'id' => 'coinsTable',
                        'type' => 'stripped',
                        'dataProvider' => $coinsMarket->search(10, CoinsMarket::TYPE_PROF, 't.id DESC'),
                        'enableSorting' => true,
                        'template' => '{items}{pager}',
                        'pagerCssClass' => 'mt-2 pagerNew',
                        'htmlOptions' => ['class' => 'table-responsive'],
                        'ajaxUpdate' => true,
                        'columns' => [
                            ['name' => 'operation_date', 'type' => 'raw', 'value' => 'date("d.m.Y", strtotime($data->operation_date))'],
                            ['name' => 'operation_type', 'type' => 'raw', 'value' => '$data->getOperationTypeGrid($data->operation_type)'],
                            ['name' => 'count', 'type' => 'html', 'value' => '$data->count."CP"'],
                            ['name' => 'from_count', 'type' => 'html', 'value' => '$data->from_count."CP"'],
                            ['name' => 'from_user', 'type' => 'html', 'value' => '$data->fromUser->username'],
                            ['name' => 'from_level'],
                            ['name' => 'price_perOne', 'type' => 'raw', 'value' => '$data->price_perOne."$"'],
                            ['name' => 'countAll', 'type' => 'html', 'value' => '$data->countAll."CP"'],
                        ]
                    ]); ?>
            </div>
        </div>
    </div>
</div>