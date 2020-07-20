<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="stats-widget">
                    <div class="stats-widget-header">
                        <i class="icon-credit-card"></i>
                    </div>
                    <div class="stats-widget-body">
                        <ul class="row no-gutters">
                            <li class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col">
                                <h6 class="title"><?= Yii::t('controllers', 'dashboard_index_lbl_deposit'); ?></h6>
                            </li>
                            <li class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col">
                                <h4 class="total"><?= $userBalance->deposit ? $userBalance->deposit.'$' : "0$"; ?></h4>
                            </li>
                        </ul>
                        <div class="row">
                            <div class="col-md-6">
                                <?php 
                                    $this->widget('bootstrap.widgets.TbButton', [
                                        'block' => true,
                                        'context' => $userBalance->balance == 0 ? 'light' : 'primary',
                                        'label' => Yii::t('controllers', 'dashboard_index_btn_pay'),
                                        'disabled' => $userBalance->balance == 0,
                                        'htmlOptions' => [
                                            'id' => 'depPayLink_dash',
                                            'class' => 'mt-3'
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
                                        'disabled' => $userBalance->deposit - $userBalance->freezeDeposit <= 0,
                                        'htmlOptions' => [
                                            'id' => 'depOutLink_dash',
                                            'class' => 'mt-3'
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
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="stats-widget">
                    <div class="stats-widget-header">
                        <i class="icon-lock"></i>
                    </div>
                    <div class="stats-widget-body">
                        <!-- Row start -->
                        <ul class="row no-gutters">
                            <li class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col">
                                <h6 class="title"><?= Yii::t('controllers', 'dashboard_index_lbl_freeze'); ?></h6>
                            </li>
                            <li class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col">
                                <h4 class="total"><?= $userBalance->freezeDeposit ? $userBalance->freezeDeposit.'$' : "0$"; ?></h4>
                            </li>
                        </ul>
                        <?php $this->widget('bootstrap.widgets.TbButton', [
                            'block' => 'true',
                            'context' => $userBalance->freezeDeposit == 0 ? 'light' : 'primary',
                            'label' => Yii::t('core', 'btn_view'),
                            'disabled' => $userBalance->freezeDeposit == 0,
                            'htmlOptions' => ['class' => 'mt-3', 'id' => 'infoDepFreeze']
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>



<?= $userBalance->balance != 0 &&  $userData->status_account != Users::STATUSMAX ? CHtml::link(Yii::t('controllers', 'dashboard_index_btn_buyStatus'), '#', ['id' => 'buyStatusLink_board']) : ''; ?>