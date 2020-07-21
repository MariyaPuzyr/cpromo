<?php $this->pageTitle = Yii::t('controllers', 'finance_index_title'); ?>

<?php if($main_test_cut): ?>
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="alert alert-danger" role="alert">
                <h4><?= Yii::t('controllers', 'dashboard_index_youHaveSellOrder', ['#number' => $main['number'], '#count' => $main['count'], '#count_now' => $main['count_now']]); ?></h4>
            </div>
        </div>
    </div>
<?php endif; ?>


<?php 
    $incData = $incomletePays->search(10);
    if($incData->getData()): ?>
<div class="row gutters mt-3">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card bg-white border-none">
            <div class="card-body">
                <h4 class="card-header px-0"><?= Yii::t('controllers', 'finance_index_lbl_incompletePays'); ?></h4>
                <?php 
                    $this->widget('bootstrap.widgets.TbGridView', [
                        'id' => 'incompletePays',
                        'type' => 'stripped',
                        'dataProvider' => $incData,
                        'enableSorting' => true,
                        'template' => '{items}{pager}',
                        'pagerCssClass' => 'mt-2 pagerNew',
                        'htmlOptions' => ['class' => 'table-responsive'],
                        'ajaxUpdate' => true,
                        'columns' => [
                            ['name' => 'operation_number'],
                            ['name' => 'operation_date', 'type' => 'raw', 'value' => 'MHelper::formBeautyDate($data->operation_date)'],
                            ['name' => 'operation_summ', 'type' => 'raw', 'value' => '$data->operation_summ."$"'],
                            ['name' => 'operation_system', 'type' => 'raw', 'value' => '$data->getFinType($data->operation_system)'],
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
                                    'visible' => 'in_array($data->operation_status, [$data::PSTATUS_WAIT])'
                                ]
                            ]]
                        ]
                    ]); ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row gutters mt-3">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card bg-white border-none">
            <div class="card-body">
              <h4 class="card-header px-0"><?= Yii::t('controllers', 'finance_index_lbl_pays'); ?><span class="badge badge-danger text-danger ml-3 rounded"><?= ''; /*CHtml::link(Yii::t('core', 'btn_invest'), '#', ['id' => 'payLink_finance', 'class' => 'font-white']);*/ ?></span></h4>
                <?php 
                    $this->widget('bootstrap.widgets.TbGridView', [
                        'id' => 'paysTable',
                        'type' => 'stripped',
                        'dataProvider' => $pays->search(10),
                        'enableSorting' => true,
                        'template' => '{items}{pager}',
                        'pagerCssClass' => 'mt-2 pagerNew',
                        'htmlOptions' => ['class' => 'table-responsive'],
                        'ajaxUpdate' => true,
                        'columns' => [
                            ['name' => 'operation_number'],
                            ['name' => 'operation_date', 'type' => 'raw', 'value' => 'MHelper::formBeautyDate($data->operation_date)'],
                            ['name' => 'operation_summ', 'type' => 'raw', 'value' => '$data->operation_summ."$"'],
                            ['name' => 'operation_system', 'type' => 'raw', 'value' => '$data->getFinType($data->operation_system)'],
                            ['name' => 'operation_status', 'type' => 'raw', 'value' => '$data->getPayStatusesToGrid($data->operation_status)'],
                            ['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{del_pay}', 'buttons' => [
                                'del_pay' => [
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
                                                    $.fn.yiiGridView.update("paysTable");
                                            }'    
                                        ], 
                                    ],
                                    'visible' => '$data->operation_status != $data::PSTATUS_COMPL'
                                ]
                            ]]
                        ]
                    ]); ?>
            </div>
        </div>
    </div>
</div>

<div class="row gutters mt-3">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card bg-white border-none">
            <div class="card-body">
              <h4 class="card-header px-0"><?= Yii::t('controllers', 'finance_index_lbl_coins'); ?>
                  <?php if(Yii::app()->user->finance->balance != 0): ?>
                    <span class="badge badge-danger text-danger ml-3 rounded"><?= CHtml::link(Yii::t('controllers', 'finance_index_btn_buyCoin'), '/exchange', ['class' => 'font-white']); ?></span>
                  <?php endif; ?>
              </h4>
                <?php 
                    $this->widget('bootstrap.widgets.TbGridView', [
                        'id' => 'coinsTable',
                        'type' => 'stripped',
                        'dataProvider' => $coins->search(10),
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
                            ['name' => 'operation_summ', 'type' => 'raw', 'value' => '$data->operation_summ."$"'],
                            ['name' => 'price_perOne', 'type' => 'raw', 'value' => '$data->price_perOne."$"'],
                            ['name' => 'countAll', 'type' => 'html', 'value' => '$data->countAll."CP"'],
                        ]
                    ]); ?>
            </div>
        </div>
    </div>
</div>

<div class="row gutters mt-3">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card bg-white border-none">
            <div class="card-body">
              <h4 class="card-header px-0"><?= Yii::t('controllers', 'finance_index_lbl_profits'); ?></h4>
                <?php 
                    $this->widget('bootstrap.widgets.TbGridView', [
                        'id' => 'profitsTable',
                        'type' => 'stripped',
                        'dataProvider' => $profits->search(10),
                        'enableSorting' => true,
                        'template' => '{items}{pager}',
                        'pagerCssClass' => 'mt-2 pagerNew',
                        'htmlOptions' => ['class' => 'table-responsive'],
                        'ajaxUpdate' => true,
                        'columns' => [
                            ['name' => 'operation_date', 'type' => 'raw', 'value' => 'date("d.m.Y", strtotime($data->operation_date))'],
                            ['name' => 'operation_type', 'type' => 'raw', 'value' => '$data->profitTypeGrid($data->operation_type)',],
                            ['name' => 'operation_summ', 'type' => 'raw', 'value' => '$data->operation_summ."$"'],
                            ['name' => 'from_user', 'type' => 'html', 'value' => '$data->fromUser->username'],
                            ['name' => 'from_level'],
                            ['name' => 'from_summ', 'type' => 'html', 'value' => '$data->from_summ."$"'],
                        ]
                    ]); ?>
            </div>
        </div>
    </div>
</div>

<div class="row gutters mt-3">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card bg-white border-none">
            <div class="card-body">
              <h4 class="card-header px-0"><?= Yii::t('controllers', 'finance_index_lbl_outs'); ?>
                  <?php if($balance->balance_out != 0): ?>
                    <span class="badge badge-danger text-danger ml-3 rounded"><?= CHtml::link(Yii::t('controllers', 'finance_index_btn_out'), '#', ['id' => 'outLink_finance', 'class' => 'font-white']); ?></span>
                  <?php endif; ?>
              </h4>
                <?php 
                    $this->widget('bootstrap.widgets.TbGridView', [
                        'id' => 'outsTable',
                        'type' => 'stripped',
                        'dataProvider' => $outs->search(10),
                        'enableSorting' => true,
                        'template' => '{items}{pager}',
                        'pagerCssClass' => 'mt-2 pagerNew',
                        'htmlOptions' => ['class' => 'table-responsive'],
                        'ajaxUpdate' => true,
                        'columns' => [
                            ['name' => 'operation_number'],
                            ['name' => 'operation_date', 'type' => 'raw', 'value' => 'date("d.m.Y", strtotime($data->operation_date))'],
                            ['name' => 'operation_summ', 'type' => 'raw', 'value' => '$data->operation_summ."$"'],
                            ['name' => 'operation_system', 'type' => 'raw', 'value' => '$data->getFinType($data->operation_system)'],
                            ['name' => 'operation_status', 'type' => 'raw', 'value' => '$data->getOutStatusesToGrid($data->operation_status)'],
                            ['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{cancOrder}', 'buttons' => [
                                'cancOrder' => [
                                    'icon' => 'fas fa-times',
                                    'label' => Yii::t('core', 'btn_cancel'),
                                    'url' => 'Yii::app()->controller->createUrl("/finance/cancOut", ["id" => $data->id])', 
                                    'options' => [
                                        'data-toggle' => false,
                                        'confirm' => Yii::t('controllers', 'finance_index_lbl_confirmCancOrder'),
                                        'ajax' => [
                                            'url' => 'js:$(this).attr("href")',
                                            'success'=>'function(data){
                                                var obj = JSON.parse(data);
                                                if(obj.status == "success") {
                                                    $(".notifyjs-corner").empty();
                                                    $.fn.yiiGridView.update("outsTable");
                                                }
                                            }'    
                                        ],  
                                    ],
                                    'visible' => 'in_array($data->operation_status, [$data::OSTATUS_WAIT, $data::OSTATUS_WCONFIRM])'
                                ],
                            ]]
                        ]
                    ]); ?>
            </div>
        </div>
    </div>
</div>