<?php

    /*$chart = CoinsOrder::model()->findAll(['select' => 'MAX(price_perOne) as price_perOne, DATE(update_at) as update_at', 'condition' => 'operation_status = '.CoinsOrder::OSTAT_COMPL.' AND operation_type = '.CoinsOrder::OTYPE_SELL.' AND price_perOne != 0', 'group' => 'DATE(update_at)']);
    foreach ($chart as $crt) {
        $crts['label'][] = date('d.m.Y', strtotime($crt['update_at']));
        $crts['value'][] = $crt['price_perOne'];
    }


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
                    borderColor: ['#007bff'],
                    borderWidth: 1
                }]
            },
            options: {  
                responsive: true,
                maintainAspectRatio: false
            }
        });
    ");

    echo '<div class="row"><div class="col-md-12"><canvas class="chartjs-render-monitor" id="coinsChart" style="max-height: 250px"></canvas></div></div>';
*/

//Доначисление монет по старому курсу
/*$course = 0.03003;
$summ = 286;
$count = floor($summ/$course);
$user_id = 3429;

$modelBuy = new CoinsMarket;
$modelBuy->setScenario('buyCoin');
$modelBuy->operation_type = $modelBuy::TYPE_BUY;
$modelBuy->coinorder_id = 0;
$modelBuy->from_exchange = true;
$modelBuy->user_id = $user_id;
$modelBuy->to_user = 3;
$modelBuy->operation_date = date('Y-m-d H:i:s');
$modelBuy->count = $count;
$modelBuy->operation_summ = $summ;
$modelBuy->price_perOne = $course;
if($modelBuy->save(false)) {
    UsersBalance::model()->formRecord($modelBuy, UsersBalance::TYPE_BUYCOIN);
}*/

    

/*$modelSell = new CoinsMarket;
$modelSell->setScenario('sellCoin');
$modelSell->operation_type = $modelBuy::TYPE_SELL;
$modelSell->coinorder_id = 0;
$modelSell->from_exchange = true;
$modelSell->user_id = 3;
$modelSell->to_user = $user_id;
$modelSell->operation_date = date('Y-m-d H:i:s');
$modelSell->count = $count;
$modelSell->operation_summ = $summ;
$modelSell->price_perOne = $course;
print_r($modelSell->validate());
#UsersBalance::model()->formRecord($modelSell, UsersBalance::TYPE_SALECOIN);





//Рассчет прибыли с покупки статуса
/*
$uid = 41;
$usumm = 500;

$mainRefs = UsersRelation::model()->with(['rData'])->findAllByAttributes(['to_user' => $uid]);
$levels = SprLevels::model()->findAll();
foreach($levels as $level)
    $countToLevel[$level->id] = $level->level_percente_status;
            
$statusNums = UsersStatus::model()->findAll(['select' => 'operation_number']);
foreach($statusNums as $number)
    $sNum[] = $number->operation_number;
            
$summ = $usumm;
$summBack = $usumm/2;
if($mainRefs){
    foreach($mainRefs as $ref){
        if($countToLevel[$ref->level] && $countToLevel[$ref->level] != 0) {
            if($ref->level <= $ref->rData->referral_level) {
                $summProfit = floor(($summ*$countToLevel[$ref->level])/100);
                $summBack -= $summProfit;
            
                if(!in_array($snID = 'SP'.rand(000000000,999999999), $sNum))
                    $sNum[] = $snID;
        
                if($summBack > 0 && $summProfit != 0) {
                    $query[] = [
                        'operation_number' => $snID,
                        'operation_date' => date('Y-m-d H:i:s'),
                        'operation_type' => UsersProfits::TYPE_BUYSTAT,
                        'operation_percent' => $countToLevel[$ref->level],
                        'user_id' => $ref->user_id,
                        'from_user' => $uid,
                        'from_summ' => $usumm,
                        'from_level' => $ref->level,
                        'operation_summ' => ($summProfit > $summBack) ? $summBack : $summProfit,
                    ];
                } else {
                    $summBackQuant = $summProfit - abs($summBack);
                    $query[] = [
                        'operation_number' => $snID,
                        'operation_date' => date('Y-m-d H:i:s'),
                        'operation_type' => UsersProfits::TYPE_BUYSTAT,
                        'operation_percent' => $countToLevel[$ref->level],
                        'user_id' => $ref->user_id,
                        'from_user' => $uid,
                        'from_summ' => $usumm,
                        'from_level' => $ref->level,
                        'operation_summ' => $summBackQuant,
                    ];
                    break;
                }
            }
        }
    }
}
            
if($summBack > 0){
    if(!in_array($snID = 'SP'.rand(000000000,999999999), $sNum))
        $sNum[] = $snID;
                
    $query[] = [
        'operation_number' => $snID,
        'operation_date' => date('Y-m-d H:i:s'),
        'operation_type' => UsersProfits::TYPE_BUYSTAT,
        'user_id' => 3,
        'from_user' => $uid,
        'from_summ' => $usumm,
        'operation_summ' => $summBack,
    ];
}
            
if($query){
    $builder = Yii::app()->db->schema->commandBuilder;
    $command = $builder->createMultipleInsertCommand('{{users_profits}}', $query);
    $command->execute();
}
            
$usersBalances = UsersBalance::model()->findAll(['select' => 'operation_number']);
foreach($usersBalances as $key)
    $balance_operations[] = $key->operation_number;
           
$allUserBalances = UsersBalance::model()->findAll(['select' => '*', 'condition' => 'id IN (SELECT MAX(id) FROM {{users_balance}} GROUP BY user_id)']);
foreach($allUserBalances as $uBalance)
    $uBal[$uBalance->user_id] = $uBalance->operation_summAll;
            
            
$statusProfits = UsersProfits::model()->findAll(['condition' => 'operation_number LIKE :number AND operation_date BETWEEN :date and :date1', 'params' => [':number' => 'SP%', ':date' => date('Y-m-d H:i:s', strtotime('-1 hours')), ':date1' => date('Y-m-d H:i:s')]]);
foreach($statusProfits as $key){
    if(!in_array($key->operation_number, $balance_operations)){
        $queryBalance[]=[
            'user_id' => $key->user_id,
            'operation_number' => $key->operation_number,
            'operation_date' => $key->operation_date,
            'operation_summ' => $key->operation_summ,
            'operation_summAll' => ($uBal[$key->user_id]+$key->operation_summ),
            'operation_type' => UsersBalance::TYPE_PROFITSTAT,
            'operation_system' => MBaseModel::FIN_INNER,
            'profit_id' => $key->id,
        ];
                    
        Users::model()->updateByPk($key->user_id, ['now_balance' => ($uBal[$key->user_id]+$key->operation_summ)]);
    }
}
            
if($queryBalance){
    $builderBalance = Yii::app()->db->schema->commandBuilder;
    $commandBalance = $builderBalance->createMultipleInsertCommand('{{users_balance}}', $queryBalance);
    $commandBalance->execute();
}
*/






//Перенос структуры
/*
$uid = 76;
$to_uid = 1952;

echo 'Строим начальную линию для реферала:<br />';
echo 'Удаляем всех для пользователя '.$uid.'<br />';
$mRefsDeleteFirst  = UsersRelation::model()->deleteAllByAttributes(['to_user' => $uid]);
echo 'Затем, строим его старовую линию:<br />';
$mRefsFirst  = UsersRelation::model()->findAllByAttributes(['to_user' => $to_uid]);
echo 'Добавляем: '.$uid.' - '.$to_uid.' - 1<br />';
$query[] = ['user_id' => $to_uid, 'to_user' => $uid, 'level' => 1];
$rFC[$to_uid] = 1;
foreach($mRefsFirst as $ref) {
    $level = $ref->level + 1;
    echo 'Добавляем: '.$uid.' - '.$ref->user_id.' - '.$level.'<br />';
    $rFC[$ref->user_id] = $level;
    $query[]=['user_id' => $ref->user_id, 'to_user' => $uid, 'level' => $level];
}

echo 'Затем, меняем всю вложенность:<br />';
$mRefsSecond  = UsersRelation::model()->findAllByAttributes(['user_id' => $uid]);
foreach($mRefsSecond as $ref) {
    echo 'Удаляем всех для пользователя '.$ref->to_user.'<br />';
    UsersRelation::model()->deleteAllByAttributes(['to_user' => $ref->to_user], 'level > '.$ref->level);
    $i = 1;
    foreach($rFC as $k => $v) {
        $level = $ref->level + 1;
        echo 'Добавляем: '.$k.' - '.$ref->to_user.' - '.$level.'<br />';    
        $query[]=['user_id' => $k, 'to_user' => $ref->to_user, 'level' => $level];
        $i++;
    }
}

if($query){
    $builderBalance = Yii::app()->db->schema->commandBuilder;
    $commandBalance = $builderBalance->createMultipleInsertCommand('{{users_relation}}', $query);
    $commandBalance->execute();
}
*/

            
            
//Подтверждение платежа
$model = UsersPays::model()->findByPk(12931);
if ($model->operation_status != UsersPays::PSTATUS_COMPL) {
    $model->operation_status = UsersPays::PSTATUS_COMPL;
    $model->save();
}







#print_r(Yii::app()->getModule('user')->encrypting('Aa258456!'));
            
            