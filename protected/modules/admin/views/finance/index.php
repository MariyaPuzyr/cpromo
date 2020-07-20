<?php 
    $this->pageTitle = Admin::t('controllers', 'finance_index_title');
    Yii::app()->clientScript->registerScript('filterSellOrder', '
        var ajaxUpdateTimeout, ajaxRequest;
        $("#filterSellOrderForm :input").change(function(){
            ajaxRequest = $(this).serialize();
            clearTimeout(ajaxUpdateTimeout);
            ajaxUpdateTimeout = setTimeout(function(){
                $.fn.yiiGridView.update("coinsorderList", {data: ajaxRequest})
            }, 300);
        });
    ');
?>

<div class="row mt-2">
    <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
         <?= $rFin['virtual'].'$'; ?><br />
        <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_virtual'); ?></span>
    </div>
    <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
         <?= $rFin['real'].'$'; ?><br />
        <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_real'); ?></span>
    </div>
    <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
         <?= $rFin['coinbuy'].'CP'; ?><br />
        <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_buycoins'); ?></span>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
         <?= $rFin['real_payeer'].'$'; ?><br />
        <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_real_payeer'); ?></span>
    </div>
    <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
         <?= $rFin['real_bitcoin'].'$'; ?><br />
        <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_real_bitcoin'); ?></span>
    </div>
    <div class="col-md-3 p-3 text-center border-right text-primary font-large border-bottom-mobile">
         <?= $rFin['real_prfmoney'].'$'; ?><br />
        <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_real_prfmoney'); ?></span>
    </div>
    <div class="col-md-3 p-3 text-center text-primary font-large">
         <?= $rFin['real_coinspay'].'$'; ?><br />
        <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_real_coinspay'); ?></span>
    </div>
</div>

<hr />
<div class="row bg-white p-3">
    <div class="col-md-12">
        <h6 class="mobile-text-center pt-3 pr-3 pl-3" style="vertical-align: inherit;"><?= Admin::t('controllers', 'finance_index_lbl_coinsOrder'); ?></h6>
        <div class="row mt-2">
            <div class="col-md-2 p-3 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rOrder['count']; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_countOpenOrder'); ?></span>
            </div>
            <div class="col-md-2 p-3 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rOrder['countCP'].'CP'; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_countOpenOrderCP'); ?></span>
            </div>
            <div class="col-md-2 p-3 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rOrder['countSumm'].'$'; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_countOpenOrderSumm'); ?></span>
            </div>
            <div class="col-md-2 p-3 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rOrder['countOut']; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_countCloseOrder'); ?></span>
            </div>
            <div class="col-md-2 p-3 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rOrder['countOutCP'].'CP'; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_countCloseOrderCP'); ?></span>
            </div>
            <div class="col-md-2 p-3 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rOrder['countOutSumm'].'$'; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_countCloseOrderSumm'); ?></span>
            </div>
        </div>
        <div class="row p-3">
            <?= CHtml::beginForm('', 'get', ['id' => 'filterSellOrderForm', 'class' => 'form-row', 'style' => 'width: 100%']); ?>
                <div class="col-md-12">
                    <?= CHtml::dropDownList('sellOrderType', $sellOrderType, [0 => 'Открытые', '1' => 'Закрытые'], ['class' => 'form-control form-control-sm', 'empty' => Yii::t('models', 'attr_status')]); ?>
                </div>
            <?= CHtml::endForm(); ?>
        </div>
        <div class="col-md-12 mt-3">
        <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'coinsorderList',
            'dataProvider' => $coinsOrder->search(),
            'enableSorting' => true,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
            'type' => 'stripped', 
            'pagerCssClass' => 'mt-2 pagerNew',
            'extraParams' => ['priceCoin' => Coins::model()->findByPk(1)->price],
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'id', 'header' => Yii::t('models', 'attr_id')],
                ['name' => 'user_id', 'header' => Yii::t('models', 'user_attr_referral_id'), 'type' => 'raw', 'value' => 'CHtml::link($data->user->referral_id, "#", ["onclick" => "getReferralShortInfo(\"{$data->user->referral_id}\", true); return false;"])'],
                ['name' => 'operation_date', 'header' => Admin::t('core', 'attr_date'), 'type' => 'raw', 'value' => 'date("d.m.Y H:i:s", strtotime($data->operation_date))'],
                ['name' => 'count', 'header' => Yii::t('models', 'attr_count'), 'type' => 'raw', 'value' => '$data->count."CP"'],
                ['header' => Admin::t('models', 'attr_summ_by_course'), 'type' => 'raw', 'value' => '($data->count*$this->grid->extraParams["priceCoin"])."$"'],
                ['header' => Yii::t('models', 'attr_status'), 'type' => 'raw', 'value' => '$data->getOperationStatusToGrid($data->operation_status)'],
                ['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{confirmCoinOrder}', 'buttons' => [
                    'confirmCoinOrder' => [
                        'icon' => 'fas fa-check',
                        'label' => Yii::t('core', 'btn_close'),
                        'url' => 'Yii::app()->controller->createUrl("confirmCoinOrder", ["id" => $data->id])', 
                        'options' => [
                            'data-toggle' => false,
                            'confirm' => Admin::t('core', 'btn_confirmCoinOrderConfirm'),
                            'ajax' => [
                                'url' => 'js:$(this).attr("href")',
                                'success'=>'function(data){
                                    var obj = JSON.parse(data);
                                    if(obj.status == "success") {
                                        $(".notifyjs-corner").empty();
                                        showNoty("'.Admin::t('controllers', 'finance_index_ntfSellOrderClose_success').'", "success");
                                        $.fn.yiiGridView.update("coinsorderList");
                                    }
                                }'    
                            ],  
                        ],
                        'visible' => '$data->operation_status == $data::OSTAT_WAIT'
                    ],
                ]]
            ]
        ]); ?>
        </div>
    </div>
</div>
<hr />


<div class="row bg-white p-3">
    <div class="col-md-12">
        <h6 class="mobile-text-center pt-3 pr-3 pl-3" style="vertical-align: inherit;"><?= Admin::t('controllers', 'finance_index_lbl_operationsOuts'); ?></h6>
        <div class="row mt-2">
            <div class="col-md-1 p-1 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rOut['count']; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_countOutOpen'); ?></span>
            </div>
            <div class="col-md-1 p-1 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rOut['countCompl']; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_countOutCompl'); ?></span>
            </div>
            <div class="col-md-1 p-1 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rOut['countCanc']; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_countOutCanc'); ?></span>
            </div>
            <div class="col-md-2 p-1 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rOut['countSummNow'].'$'; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_summOutNow'); ?></span>
            </div>
            <div class="col-md-2 p-1 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rOut['countSummAll'].'$'; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_summOutAll'); ?></span>
            </div>
            <div class="col-md-2 p-1 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rOut['countComplReal'].'$'; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_summOutReal'); ?></span>
            </div>
            <div class="col-md-2 p-1 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rOut['countProcent'].'$'; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_summOutProcent'); ?></span>
            </div>
        </div>
        <div class="row p-3">
            <?= CHtml::beginForm('', 'get', ['id' => 'filterOutForm', 'class' => 'form-row', 'style' => 'width: 100%']); ?>
            <div class="col-md-3">
                <?= CHtml::textField('out_number', '', ['class' => 'form-control form-control-sm', 'placeholder' => Yii::t('models', 'attr_operation_number')]); ?>
            </div>
            <div class="col-md-4">
                <?= CHtml::textField('out_summ_min', '', ['class' => 'form-control form-control-sm inline', 'placeholder' => Yii::t('controllers', 'dashboard_index_lbl_summForm')]).' - '.CHtml::textField('out_summ_max', '', ['class' => 'form-control form-control-sm inline', 'placeholder' => Yii::t('controllers', 'dashboard_index_lbl_summTo')]); ?>
            </div>
            <div class="col-md-2">
                <?= CHtml::dropDownList('order_status', $out_status, $outs->getOutStatuses(), ['class' => 'form-control form-control-sm', 'empty' => Yii::t('models', 'attr_status')]); ?>
            </div>
            <div class="col-md-3">
                    <?= CHtml::dropDownList('order_system', $order_system, MBaseModel::getOutMode(), ['class' => 'form-control form-control-sm', 'empty' => 'Платежная система']); ?>
            </div>
            <?= CHtml::endForm(); ?>
        </div>
        <div class="col-md-12">
            <?php
                $this->widget('bootstrap.widgets.TbButton', [
                    'label' => $outsDis->finance_payeer ? 'Выключить PAYEER' : 'Включить PAYEER',
                    'context' => $outsDis->finance_payeer ? 'light' : 'primary',
                    'buttonType' => 'link',
                    'url' => $this->createUrl('/admin/finance/ChangeOuts', ['name' => 'finance_payeer', 'type' => $outsDis->finance_payeer ? 0 : 1]),
                    'htmlOptions' => [
                        'class' => 'mr-2'
                    ]
                ]);
                
                $this->widget('bootstrap.widgets.TbButton', [
                    'label' => $outsDis->finance_prfmoney ? 'Выключить Perfect' : 'Включить Perfect',
                    'context' => $outsDis->finance_prfmoney ? 'light' : 'primary',
                    'buttonType' => 'link',
                    'url' => $this->createUrl('/admin/finance/ChangeOuts', ['name' => 'finance_prfmoney', 'type' => $outsDis->finance_prfmoney ? 0 : 1]),
                    'htmlOptions' => [
                        'class' => 'mr-2'
                    ]
                ]);
                
                 $this->widget('bootstrap.widgets.TbButton', [
                    'label' => $outsDis->finance_usdtrc ? 'Выключить USDT.ERC' : 'Включить USDT.ERC',
                    'context' => $outsDis->finance_usdtrc ? 'light' : 'primary',
                    'buttonType' => 'link',
                    'url' => $this->createUrl('/admin/finance/ChangeOuts', ['name' => 'finance_usdtrc', 'type' => $outsDis->finance_usdtrc ? 0 : 1]),
                     'htmlOptions' => [
                        'class' => 'mr-2'
                    ]
                ]);
            ?>
        </div>
        <div class="col-md-12">
        <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'outList',
            'dataProvider' => $outs->search(),
            'enableSorting' => true,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
            'type' => 'stripped', 
            'pagerCssClass' => 'mt-2 pagerNew',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'user_id', 'header' => Yii::t('models', 'user_attr_referral_id'), 'type' => 'raw', 'value' => 'CHtml::link($data->user->referral_id, "#", ["onclick" => "getReferralShortInfo(\"{$data->user->referral_id}\", true); return false;"])', 'htmlOptions' => ['style' => 'font-size: small!important']],
                ['name' => 'operation_number', 'htmlOptions' => ['style' => 'font-size: small!important']],
                ['name' => 'operation_date', 'header' => Admin::t('core', 'attr_date'), 'type' => 'raw', 'value' => 'date("d.m.Y", strtotime($data->operation_date))', 'htmlOptions' => ['style' => 'font-size: small!important']],
                ['name' => 'operation_system', 'header' => Yii::t('models', 'attr_system'), 'type' => 'raw', 'value' => '$data->getOutMode($data->operation_system)', 'htmlOptions' => ['style' => 'font-size: small!important']],
                ['header' => Yii::t('models', 'attr_system_wallet'), 'type' => 'raw', 'value' => '$data->user->getWallet($data->operation_system)', 'htmlOptions' => ['style' => 'font-size: small!important']],
                ['name' => 'operation_summ', 'header' => Admin::t('core', 'attr_summ'), 'type' => 'raw', 'value' => '$data->operation_summ."$"', 'htmlOptions' => ['style' => 'font-size: small!important']],
                #['name' => 'operation_allSumm', 'type' => 'raw', 'value' => '$data->operation_allSumm."$"', 'htmlOptions' => ['style' => 'font-size: small!important']],
                ['name' => 'operation_status', 'header' => Yii::t('models', 'attr_status'), 'type' => 'raw', 'value' => '$data->getOutStatusesToGrid($data->operation_status)', 'htmlOptions' => ['style' => 'font-size: small!important']],
                #['name' => 'update_at', 'header' => Admin::t('core', 'attr_update'), 'type' => 'raw', 'value' => '$data->update_at ? date("d.m.Y", strtotime($data->update_at)) : ""'],
                ['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{confirmOrder}&nbsp;{disOrder}', 'buttons' => [
                    'confirmOrder' => [
                        'icon' => 'fas fa-check',
                        'label' => Admin::t('core', 'btn_confirmOrder'),
                        'url' => 'Yii::app()->controller->createUrl("confirmOrder", ["id" => $data->id])', 
                        'options' => [
                            'data-toggle' => false,
                            'confirm' => Admin::t('core', 'btn_confirmOrderConfirm'),
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
                        'visible' => 'in_array($data->operation_status, [$data::OSTATUS_WAIT, $data::OSTATUS_WCONFIRM])'
                    ],
                    /*'returnOrder' => [
                        'icon' => 'fas fa-arrow-up',
                        'label' => Admin::t('core', 'btn_returnOrder'),
                        'url' => 'Yii::app()->controller->createUrl("returnOrder", ["id" => $data->id])', 
                        'options' => [
                            'data-toggle' => false,
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
                        'visible' => '$data->operation_status == $data::OSTATUS_COMPL'
                    ],*/
                    'disOrder' => [
                        'icon' => 'fas fa-times',
                        'label' => Admin::t('core', 'btn_disOrder'),
                        'url' => 'Yii::app()->controller->createUrl("disOrder", ["id" => $data->id])', 
                        'options' => [
                            'data-toggle' => false,
                            'confirm' => Admin::t('core', 'btn_disOrderConfirm'),
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
                        'visible' => '$data->operation_status == $data::OSTATUS_WAIT'
                    ],
                ], 'htmlOptions' => ['style' => 'font-size: small!important']]
            ]
        ]); ?>
        </div>
    </div>
</div>

<div class="row mt-3 bg-white p-3">
    <div class="col-md-12">
        <h6 class="mobile-text-center pt-3 pr-3 pl-3" style="vertical-align: inherit;"><?= Admin::t('controllers', 'finance_index_lbl_operationsPays'); ?></h6>
        <div class="row p-3">
        <?php echo CHtml::beginForm('', 'get', ['id' => 'filterPayForm', 'class' => 'form-row', 'style' => 'width: 100%']); ?>
            <div class="col-md-2 filterMobile">
                <?= CHtml::textField('pay_number', '', ['class' => 'form-control form-control-sm', 'placeholder' => Yii::t('models', 'attr_operation_number')]); ?>
            </div>
            <div class="col-md-3 filterMobile">
                <?php $this->widget('bootstrap.widgets.TbDateRangePicker', [
                    'name' => 'pay_date',
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
            <div class="col-md-3 text-center filterMobile">
                <?= CHtml::textField('pay_summ_min', '', ['class' => 'form-control form-control-sm inline', 'placeholder' => Yii::t('controllers', 'dashboard_index_lbl_summForm')]).' - '.CHtml::textField('pay_summ_max', '', ['class' => 'form-control form-control-sm inline', 'placeholder' => Yii::t('controllers', 'dashboard_index_lbl_summTo')]); ?>
            </div>
            <div class="col-md-2 filterMobile">
                <?= CHtml::dropDownList('pay_status', $pay_status, $pays->getPayStatuses(), ['class' => 'form-control form-control-sm', 'empty' => Yii::t('models', 'attr_status')]); ?>
            </div>
            <div class="col-md-2 filterMobile">
                <?= CHtml::dropDownList('pay_system', $pay_system, $pays->getPayMode(), ['class' => 'form-control form-control-sm', 'empty' => Yii::t('models', 'attr_system')]); ?>
            </div>
        <?= CHtml::endForm(); ?>
        </div>
    </div>
    <div class="col-md-12">
        <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'payList',
            'dataProvider' => $pays->search(),
            'enableSorting' => true,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
            'type' => 'stripped', 
            'pagerCssClass' => 'mt-2 pagerNew',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'operation_number'],
                ['name' => 'operation_date', 'header' => Yii::t('models', 'attr_date'), 'type' => 'raw', 'value' => 'date("d.m.Y", strtotime($data->operation_date))'],
                ['name' => 'operation_summ', 'header' => Yii::t('models', 'attr_summ'), 'type' => 'raw', 'value' => '$data->operation_summ."$"'],
                ['name' => 'operation_system', 'type' => 'raw', 'value' => '$data->getFinType($data->operation_system)'],
                ['name' => 'operation_status', 'header' => Yii::t('models', 'attr_status'), 'type' => 'raw', 'value' => '$data->getPayStatusesToGrid($data->operation_status)'],
                ['name' => 'user_id', 'header' => Yii::t('models', 'user_attr_referral_id'), 'type' => 'raw', 'value' => 'CHtml::link($data->user->referral_id, "#", ["onclick" => "getReferralShortInfo(\"{$data->user->referral_id}\", true); return false;"])'],
                ['class' => 'bootstrap.widgets.TbButtonColumn', 'template' => '{editPay}&nbsp;{deletePay}', 'buttons' => [
                    'editPay' => [
                        'icon' => 'fas fa-search',
                        'url' => 'Yii::app()->controller->createUrl("viewPay", ["id" => $data->id])', 
                        'options' => [
                            'data-toggle' => 'tooltip',
                            'title' => Yii::t('core', 'btn_view'),
                            'class' => 'text-dark'
                        ],
                    ],        
                    'deletePay' => [
                        'icon' => 'fas fa-trash',
                        'url' => 'Yii::app()->controller->createUrl("deletePay", ["id" => $data->id])', 
                        'options' => [
                            'data-toggle' => 'tooltip',
                            'title' => Admin::t('core', 'btn_delete'),
                            'confirm' => Admin::t('core', 'btn_deletePayConfirm'),
                            'class' => 'text-dark',
                            'ajax' => [
                                'url' => 'js:$(this).attr("href")',
                                'success'=>'function(data){
                                    var obj = JSON.parse(data);
                                    if(obj.status == "success") {
                                        $(".notifyjs-corner").empty();
                                        $.fn.yiiGridView.update("payList");
                                    }
                                }'    
                            ],  
                        ],
                        'visible' => '$data->operation_status == $data::PSTATUS_WAIT'
                    ],
                ]]
            ]
        ]); ?>
    </div>
</div>

<div class="row mt-3 bg-white p-3">
    <div class="col-md-12">
        <h6 class="mobile-text-center pt-3 pr-3 pl-3" style="vertical-align: inherit;"><?= Admin::t('controllers', 'finance_index_lbl_operationsProfits'); ?></h6>
        
        <div class="row mt-2">
            <div class="col-md-2 p-3 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rStat['c1']; ?><br />
                <span class="text-muted small">&nbsp;</span><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'C1'); ?></span>
            </div>
            <div class="col-md-2 p-3 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rStat['c2']; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_v_o', ['#summ' => $rStat['c2_p']]); ?></span><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'C2'); ?></span>
            </div>
            <div class="col-md-2 p-3 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rStat['c3']; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_v_o', ['#summ' => $rStat['c3_p']]); ?></span><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'C3'); ?></span>
            </div>
            <div class="col-md-2 p-3 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rStat['c4']; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_v_o', ['#summ' => $rStat['c4_p']]); ?></span><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'C4'); ?></span>
            </div>
            <div class="col-md-2 p-3 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $rStat['c5']; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_v_o', ['#summ' => $rStat['c5_p']]); ?></span><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'C5'); ?></span>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-2 p-3 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $sSumm.'$'; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_buystatussum'); ?></span>
            </div>
            <div class="col-md-2 p-3 text-center border-right text-primary font-large border-bottom-mobile">
                <?= ($sSumm/2).'$'; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_buystatussumcomp'); ?></span>
            </div>
            <div class="col-md-2 p-3 text-center border-right text-primary font-large border-bottom-mobile">
                <?= $sCountU; ?><br />
                <span class="text-muted small"><?= Admin::t('controllers', 'finance_index_lbl_usersbuystatus'); ?></span>
            </div>
        </div>
        <hr />
        <div class="row p-3 mt-3">
        <?php echo CHtml::beginForm('', 'get', ['id' => 'filterProfitForm', 'class' => 'form-row', 'style' => 'width: 100%']); ?>
            <div class="col-md-3">
                <?php $this->widget('bootstrap.widgets.TbDateRangePicker', [
                    'name' => 'profit_date',
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
            <div class="col-md-3">
                <?= CHtml::textField('profit_summ_min', '', ['class' => 'form-control form-control-sm inline', 'placeholder' => Yii::t('controllers', 'dashboard_index_lbl_summForm')]).' - '.CHtml::textField('profit_summ_max', '', ['class' => 'form-control form-control-sm inline', 'placeholder' => Yii::t('controllers', 'dashboard_index_lbl_summTo')]); ?>
            </div>
            <div class="form-group col-md-3">
                <?= CHtml::dropDownList('profit_type', $profit_type, $profits->profitType(), ['class' => 'form-control form-control-sm', 'empty' => Yii::t('models', 'attr_type')]); ?>
            </div>
        <?= CHtml::endForm(); ?>
        </div>
    </div>
    <div class="col-md-12">
        <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'profitList',
            'dataProvider' => $profits->search(),
            'enableSorting' => true,
            'template' => '{items}{pager}',
            'htmlOptions' => ['class' => 'table-responsive tableNotify tableWithoutSort'],
            'type' => 'stripped', 
            'pagerCssClass' => 'mt-2 pagerNew',
            'ajaxUpdate' => true,
            'columns' => [
                ['name' => 'user_id', 'type' => 'raw', 'value' => 'CHtml::link($data->user->referral_id, "#", ["onclick" => "getReferralShortInfo(\"{$data->user->referral_id}\", true); return false;"])'],
                ['name' => 'operation_date', 'type' => 'raw', 'value' => 'date("d.m.Y", strtotime($data->operation_date))'],
                ['name' => 'operation_summ', 'type' => 'raw', 'value' => '$data->operation_summ."$"'],
                ['name' => 'operation_type', 'type' => 'raw', 'value' => '$data->profitTypeGrid($data->operation_type)'],
                ['name' => 'operation_percent'],
                ['name' => 'from_user', 'type' => 'raw', 'value' => 'CHtml::link($data->fromUser->referral_id, "#", ["onclick" => "getReferralShortInfo(\"{$data->fromUser->referral_id}\", true); return false;"])'],
                ['name' => 'from_level'],
                ['name' => 'from_summ', 'type' => 'html', 'value' => '$data->from_summ."$"'],
                
            ]
        ]); ?>
    </div>
</div>
