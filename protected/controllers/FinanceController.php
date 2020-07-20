<?php

class FinanceController extends MController
{
    public function allowedActions()
    {
        return 'confirmout, payByPayeerResult, PayByPayeerResult, payByBitcoinResult, PayByBitcoinResult, PayByPrfmoneyResult, payByPrfmoneyResult, payByCoinsResult, PayByCoinsResult';
    }
    
    public function actionIndex()
    {
        $user = Yii::app()->user;
        $incomletePays = new UsersPays;
        $incomletePays->user_id = $user->id;
        $incomletePays->operation_status = UsersPays::PSTATUS_WAIT;
        $incomletePays->order_id_desc();
        $incomletePays->show_vis();
        
        $pays = new UsersPays;
        $pays->unsetAttributes();
        $pays->user_id = $user->id;
        $pays->order_id_desc();
        $pays->show_vis();
        
        $profits = new UsersProfits;
        $profits->unsetAttributes();
        $profits->user_id = $user->id;
        $profits->with(['fromUser']);
        $profits->order_id_desc_find();
        
        $outs = new UsersOuts;
        $outs->unsetAttributes();
        $outs->user_id = $user->id;
        $outs->order_id_desc();
        
        $coins = new CoinsMarket;
        $coins->unsetAttributes();
        $coins->user_id = $user->id;
        $coins->order_id_desc_find();
        $coins->with(['fromUser']);
        
        $firstWeekUsersOrders = CoinsOrder::model()->findAll();
        if($firstWeekUsersOrders)
            foreach($firstWeekUsersOrders as $first){
                $rFUO[] = $first->user_id;
                if(!$first->operation_status){
                    $cnt++;
                    if($first->user_id == Yii::app()->user->id){
                        $main = [
                            'number' => $first->id,
                            'count' => $first->count,
                            'count_now' => $first->count_now
                        ];
                    }
                }
                    
            }
            
            
        $this->render('index', [
            'pays' => $pays,
            'incomletePays' => $incomletePays,
            'profits' => $profits,
            'outs' => $outs,
            'coins' => $coins,
            'main' => $main
        ]);
    }
    
    public function actionPay()
    {
        $model = new UsersPays;
        
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] == get_class($model)) {
            $validate = CActiveForm::validate($model);
            if($validate == '[]' || !$validate){
                $model->save(false);
                echo CJSON::encode(['pay_id' => $model->id]);
            } else
                echo $validate;
            
            Yii::app()->end();
        }
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;  
        $this->renderPartial('//finance/_pay', ['model' => $model], false, true);
    }
    
    public function actionOut()
    {
        if(in_array(Yii::app()->user->id, [6211,4117,1836,6053,4012,6053,4117,4010,2624,4012,2599,4117,1412,1836,612,1019,]))
            return false;
        
        $model = new UsersOuts;
        
        $user = Yii::app()->user->model();
        $resFields = [];
        foreach($model->getOutModeName() as $key => $name) {
            if($user->{$name})
                $resFields[$key] = $model->getFinType($key);
        }
        
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] == get_class($model)) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        
        if(Yii::app()->request->isPostRequest) {
            if(Yii::app()->request->getPost(get_class($model)) !== null) {
                $model->attributes = Yii::app()->request->getPost(get_class($model));
                if($model->save()){
                    MHelper::sendEmail(false, false, Yii::app()->user->model()->email, Yii::t('core', 'mail_confirmOutEmail_subject'), 'confirmOut', ['id' => $model->id, 'summ' => $model->operation_allSumm, 'activkey' => Yii::app()->user->model()->activkey]);
                    Yii::app()->user->setFlash('success', Yii::t('controllers', 'finance_out_ntf_outSuccess', ['#operation_number' => $model->operation_number]));
                    $this->redirect(Yii::app()->getModule('user')->returnUrl);
                } else
                    $model->getErrors ();
            } else
                $this->redirect(Yii::app()->getModule('user')->returnUrl);
        }
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;  
        $this->renderPartial('//finance/_out', ['model' => $model, 'resFields' => $resFields], false, true);
    }
    
    public function actionConfirmOut()
    {
        $model = UsersOuts::model()->findByPk($_GET['order_id']);
        if($model) {
            $user = Users::model()->findByPk($model->user_id);
            if($user) {
                if($user->activkey == $_GET['activkey']) {
                    $model->operation_status = UsersOuts::OSTATUS_WAIT;
                    $model->save(false);
                    
                    $user->activkey = Yii::app()->getModule('user')->encrypting(microtime().$model->id);
                    $user->save(false);
                    
                    Yii::app()->user->setFlash('success', Yii::t('controllers', 'finance_index_confirmOut'));
                    $this->redirect('/finance');
                }
            }
        }
        $this->redirect(Yii::app()->getModule('user')->returnUrl);
    }
    
    public function actionDeposit($type)
    {
        return false;
        Yii::app()->end();
        
        $model = new UsersDeposit;
        
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] == get_class($model)) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        
        if(Yii::app()->request->isPostRequest) {
            if(Yii::app()->request->getPost(get_class($model)) !== null) {
                $model->attributes = Yii::app()->request->getPost(get_class($model));
                if($model->save()){
                    Yii::app()->user->setFlash('success', Yii::t('controllers', $type == $model::TYPE_ONDEP ? 'finance_deposit_ntf_successOn' : 'finance_deposit_ntf_successOff'));
                    $this->redirect(Yii::app()->getModule('user')->returnUrl);
                }
            } else
                $this->redirect(str_replace(Yii::app()->request->getHostInfo(), '', Yii::app()->request->getUrlReferrer()));
        }
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;  
        $this->renderPartial('//finance/_deposit', ['model' => $model, 'type' => $type], false, true);
    }
    
    public function actionBuyStatus()
    {
        if(in_array(Yii::app()->user->id, [6211,4117,1836,6053,4012,6053,4117,4010,2624,4012,2599,4117,1412,1836,612,1019,]))
            return false;
        
        $model = new UsersStatus;
        $statusList = SprStatuses::model()->getListForUpgrade();
        $statusData = SprStatuses::model()->findByPk(array_keys($statusList)[0]);
        
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] == get_class($model)) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        
        if(Yii::app()->request->isPostRequest) {
            if(Yii::app()->request->getPost(get_class($model)) !== null) {
                $model->attributes = Yii::app()->request->getPost(get_class($model));
                if($model->save()){
                    Yii::app()->user->setFlash('success', Yii::t('controllers', 'finance_buyStatus_ntf_success'));
                    $this->redirect(Yii::app()->getModule('user')->returnUrl);
                }
            } else{}
                $this->redirect(str_replace(Yii::app()->request->getHostInfo(), '', Yii::app()->request->getUrlReferrer()));
        }
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;  
        $this->renderPartial('//finance/_buyStatus', ['model' => $model, 'statusList' => $statusList, 'statusData' => $statusData], false, true);
    }
    
    public function actionGetStatusInfo($id)
    {
        $model = SprStatuses::model()->findByPk($id);
        echo '
            <p class="mb-1 mt-2">'.Yii::t('models', 'sprStatuses_attr_max_levels_full', ['#count' => $model->max_levels]).'</p>
            <p class="mb-1">'.Yii::t('models', 'sprStatuses_attr_max_coin_buy_summ_full', ['#summ' => $model->max_coin_buy_summ]).'</p>
            <p>'.Yii::t('models', 'sprStatuses_attr_out_full', ['#count' => $model->out_count, '#period' => SprStatuses::getOutPeriodType($model->out_count_period, true), '#summ' => $model->out_max_summ]).'</p>

        ';
        Yii::app()->end();
    }
    
    public function actionDepositFreezeView()
    {
        $model = UsersDeposit::model()->findAllByAttributes(['user_id' => Yii::app()->user->id, 'operation_type' => UsersDeposit::TYPE_ONDEP]);
        if($model) {
            foreach($model as $onDep){
                if(MHelper::diffDate(date('Y-m-d'), $onDep->operation_date) < Yii::app()->settings->get('system', 'deposit_pay_freeze_period'))
                    $resData[] = $onDep;
            }
        }
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;  
        $this->renderPartial('//finance/_depositFreezeView', ['model' => $resData], false, true);
    }
    
    public function actionDeletePay($id)
    {
        $model = UsersPays::model()->findByPk($id);
        $model->record_status = $model::RSTAT_INVIS;
        $model->save(false);
        echo CJSON::encode(['status' => 'success']);
        Yii::app()->end();
    }
    
    public function actionCancOut($id)
    {
        $model = UsersOuts::model()->findByPk($id);
        $model->operation_status = $model::OSTATUS_CANC;
        $model->update_at = date('Y-m-d H:i:s');
        $model->update_uid = Yii::app()->user->id;
        
        echo CJSON::encode(['status' => $model->save(false) ? 'success' : 'error']);
        Yii::app()->end();
    }
    
    public function actionGetOpertaionInfo($id)
    {
        if($id) {
            $model = UsersBalance::model()->with(['market'])->findByAttributes(['id' => $id, 'user_id' => Yii::app()->user->id]);
            $this->renderPartial('//finance/_operationInfo', ['model' => $model], false, false);
        } else
            return false;
        
        Yii::app()->end();
    }
    
    public function actionPayProcess($pay_id = null, $operation_number = null)
    {
        $model = ($pay_id) ? UsersPays::model()->findByAttributes(['id' => $pay_id, 'user_id' => Yii::app()->user->id]) : UsersPays::model()->findByAttributes(['user_id' => Yii::app()->user->id, 'operation_number' => $operation_number]);
        switch($model->operation_system) {
            case MBaseModel::FIN_PAYEER:
                $payeer = UsersPaysPayeer::model()->findByAttributes(['pay_id' => $model->id]);
                if($payeer) {
                    $payeer->formPayRecord($model);
                    $this->renderPartial('//finance/_pay_payeer_process', ['model' => $payeer], false, true);
                } else {
                    $payeer = new UsersPaysPayeer;
                    $payeer->formPayRecord($model);
                    if($payeer->save())
                        $this->renderPartial('//finance/_pay_payeer_process', ['model' => $payeer], false, true);
                }
                break;
            case MBaseModel::FIN_BITCOIN:
                $this->payByBitcoin($model);
                Yii::app()->end();
                break;
            case MBaseModel::FIN_PRIZM:
                $this->payByPrizm($model);
                Yii::app()->end();
                break;
            case MBaseModel::FIN_PRFMONEY:
                $prfmoney = UsersPaysPrfmoney::model()->findByAttributes(['pay_id' => $model->id]);
                if($prfmoney) {
                    $this->renderPartial('//finance/_pay_prfmoney_process', ['model' => $model, 'prfmoney' => $prfmoney], false, true);
                } else {
                    $prfmoney = new UsersPaysPrfmoney;
                    $prfmoney->pay_id = $model->id;
                    $prfmoney->amount = $model->operation_summ;
                    $prfmoney->status = UsersPays::PSTATUS_WAIT;
                    if($prfmoney->save())
                        $this->renderPartial('//finance/_pay_prfmoney_process', ['model' => $model, 'prfmoney' => $prfmoney], false, true);
                }
                break;
            case MBaseModel::FIN_COINSPAY:
                $coinspay = UsersPaysCoinpayments::model()->findByPk(['pay_id' => $model->id]);
                if($coinspay) {
                    $this->renderPartial('//finance/_pay_coinspay_process', ['model' => $coinspay], false, true);
                } else {
                    $coinspay = new UsersPaysCoinpayments;
                    $coinspay->pay_id = $model->id;
                    $coinspay->pay_summ = $model->operation_summ;
                    $coinspay->item_name = $model->operation_number;
                    if($coinspay->save())
                        $this->renderPartial('//finance/_pay_coinspay_process', ['model' => $coinspay], false, true);
                }
                break;
            default:
                $payeer = new UsersPaysPayeer;
                $payeer->formPayRecord($model);
                if($payeer->save())
                    $this->renderPartial('//finance/_pay_payeer_process', ['model' => $payeer], false, true);
                break;
        }
    }
    
    public function actionPayByPayeerResult($return = false)
    {
        if(!$_GET['return']) {
            if(!in_array($_SERVER['REMOTE_ADDR'], ['185.71.65.92', '185.71.65.189', '149.202.17.210'])) 
                return;
 
            if(isset($_POST['m_operation_id']) && isset($_POST['m_sign'])) {
                $model = UsersPaysPayeer::model()->findByAttributes(['action_number' => $_POST['m_orderid']]);
                $arHash = [
                    $_POST['m_shop'],
                    $_POST['m_orderid'],
                    $_POST['m_amount'],
                    $_POST['m_curr'],
                    base64_encode('')
                ];
                $arHash[] = $model->key;
                
                if($model->sign == UsersPaysPayeer::model()->createSign($arHash)) {
                    if(Yii::app()->request->isPostRequest) {
                        $model->payeer_id = $_POST['m_operation_id'];
                        $model->payeer_ps = $_POST['m_operation_ps'];
                        $model->payeer_status = $_POST['m_status'];
                        $model->payeer_transferID = $_POST['transfer_id'];
                        $model->payeer_payDate = date('Y-m-d H:i:s', strtotime($_POST['m_operation_pay_date']));
                        $model->payeer_client_account = $_POST['client_account'];
                        $model->payeer_client_email = $_POST['client_email'];
                        $model->payeer_summa_out = $_POST['summa_out'];
                        $model->save(false);
                    }
                    ob_end_clean(); exit($_POST['m_orderid'].'|success');
                } else
                    ob_end_clean(); exit($_POST['m_orderid'].'|error');
            }
        } else {
            if($_GET['m_status']) {
                Yii::app()->user->setFlash($_GET['m_status'] == 'success' ? 'success' : $_GET['m_status'] == 'cancelled' ? 'warning' : 'error' , Yii::t('models', 'PaysPayeer_attr_payeer_status_'.$_GET['m_status']));
                $this->redirect(Yii::app()->getModule('user')->returnUrl);
            } else {
                Yii::app()->user->setFlash('warning', Yii::t('models', 'PaysPayeer_attr_payeer_status_cancelled'));
                $this->redirect(Yii::app()->getModule('user')->returnUrl);
            }
        }
    }
    
    public function actionPayByBitcoinResult()
    {
        $invoice_id = $_GET['invoice_id'];
        $transaction_hash = $_GET['transaction_hash'];
        $value_in_btc = $_GET['value'] / 100000000;
        
        $invoice = UsersPaysBit::model()->findByPk($invoice_id);
        if($_GET['address'] == $invoice->address){
            if($_GET['secret'] == $invoice->secret) {
                if($_GET['confirmations'] >= 4) {
                    $payment = UsersPaysBitPayments::model()->findByAttributes(['pay_id' => $invoice_id]);
                    if(!$payment) {
                        $model = new UsersPaysBitPayments;
                        $model->pay_id = $invoice_id;
                        $model->transaction_hash = $transaction_hash;
                        $model->value = $value_in_btc;
                        $model->save();
                        UsersPaysBitPending::model()->deleteAllByAttributes(['pay_id' => $invoice_id]);
                        $pay = UsersPays::model()->findByPk($invoice_id);
                        if($model->value >= $pay->operation_summConvert) {
                            $pay->operation_status = $pay::PSTATUS_COMPL;
                            $pay->save();
                        }
                    }
                } else {
                    $pending = UsersPaysBitPending::model()->findByAttributes(['pay_id' => $invoice_id]);
                    if($pending) {
                        $pending->transaction_hash = $transaction_hash;
                        $pending->value = $value_in_btc;
                        $pending->count = $_GET['confirmations'];
                        $pending->save();
                    } else {
                        $model = new UsersPaysBitPending;
                        $model->pay_id = $invoice_id;
                        $model->transaction_hash = $transaction_hash;
                        $model->value = $value_in_btc;
                        $model->count = $_GET['confirmations'];
                        $model->save();
                    }
                }
            }
        }
    }
    
    public function actionPayByPrfmoneyResult($status = '')
    {
        if(isset($_GET['status'])) {
            $status = $_GET['status'];
            $model = new UsersPaysPrfmoney;
            $data = [
                'PAYMENT_ID' => $_POST['PAYMENT_ID'],
                'PAYEE_ACCOUNT' => $_POST['PAYEE_ACCOUNT'],
                'PAYMENT_AMOUNT' => $_POST['PAYMENT_AMOUNT'],
                'PAYMENT_UNITS' => $_POST['PAYMENT_UNITS'],
                'PAYMENT_BATCH_NUM' => $_POST['PAYMENT_BATCH_NUM'],
                'PAYER_ACCOUNT' => $_POST['PAYER_ACCOUNT'],
                'TIMESTAMPGMT' => $_POST['TIMESTAMPGMT'],
                'V2_HASH' => $_POST['V2_HASH']
            ];
                
            $invoice = UsersPays::model()->findByAttributes(['operation_number' => $_POST['PAYMENT_ID']]);
            if($invoice) {
                $prfPay = UsersPaysPrfmoney::model()->findByPk($invoice->id);
                    
                if($status == 'success'){
                    Yii::app()->user->setFlash('success', Yii::t('controllers', 'finance_prfMoney_ntf_success'));
                    $this->redirect(Yii::app()->getModule('user')->returnUrl);
                }elseif($status == 'result'){
                    if(!$model->checkHash($data))
                        return false;
                    
                    if($invoice->operation_status != UsersPays::PSTATUS_COMPL) {
                        $invoice->operation_status = UsersPays::PSTATUS_COMPL;
                        $invoice->save();
                        $prfPay->status = UsersPays::PSTATUS_COMPL;
                        $prfPay->bath_num = $_POST['BATH_NUM'];
                        $prfPay->payer_account = $_POST['PAYER_ACCOUNT'];
                        $prfPay->timestamp = $_POST['TIMESTAMPGMT'];
                        $prfPay->hashIn = $_POST['V2_HASH'];
                        $prfPay->save();
                    }
                }else{
                    Yii::app()->user->setFlash('error', Yii::t('controllers', 'finance_prfMoney_ntf_canc'));
                    $this->redirect(Yii::app()->getModule('user')->returnUrl);
                }
            }
        }
    }
    
    public function actionPayByBitcoinStatus($operation_number)
    {
        $invoice = UsersPays::model()->findByAttributes(['operation_number' => $operation_number]);
        $product_url = '';
        $amount_paid_btc = 0;
        $amount_pending_btc = 0;
        
        $model = UsersPaysBit::model()->findByPk($invoice->id);
        
        $pendings = UsersPaysBitPending::model()->findAllByAttributes(['pay_id' => $invoice->id]);
        foreach($pendings as $pend)
            $amount_pending_btc += $pend->value;
        $payments = UsersPaysBitPayments::model()->findAllByAttributes(['pay_id' => $invoice->id]);
        
        foreach($payments as $payment)
            $amount_paid_btc += $payment->value;
        
        include_once Yii::getPathOfAlias('application.vendor.phpqrcode').'/qrlib.php';
        QRcode::png($model->address, Yii::getPathOfAlias('application.runtime').'/qr_temp.png', 'L', '4', 2);
        $file = base64_encode(file_get_contents(Yii::getPathOfAlias('application.runtime').'/qr_temp.png'));
            
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;  
        $this->renderPartial('_pay_bitcoin_process', [
            'model' => $model,
            'amount_pending_btc' => $amount_pending_btc,
            'amount_paid_btc' => $amount_paid_btc,
            'operation_number' => $operation_number,
            'file' => $file,
            'invoice' => $invoice
        ], false, true);
    }
    
    public function actionPayByPrizmStatus($operation_number)
    {
        $pay = UsersPays::model()->findByAttributes(['operation_number' => $operation_number]);
        $prizm_pay = UsersPaysPrizm::model()->findByAttributes(['pay_message' => $operation_number]);
        $prizm_file = CJSON::decode(file_get_contents(Yii::getPathOfAlias('currency_files').'/prizm.json'));
        $link = 'https://wallet.prizm.space/index.html?to='.$prizm_pay->wallet_id.'%3A'.$prizm_pay->pub_key.'%3A'.$pay->operation_summConvert.'%3A'.$pay->operation_number;
        
        include_once Yii::getPathOfAlias('application.vendor.phpqrcode').'/qrlib.php';
        QRcode::png($link, Yii::getPathOfAlias('application.runtime').'/qr_temp.png', 'L', '4', 2);
        $file = base64_encode(file_get_contents(Yii::getPathOfAlias('application.runtime').'/qr_temp.png'));
        
        Yii::app()->clientscript->scriptMap['jquery.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;  
        Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;  
        $this->renderPartial('_pay_prizm_process', [
            'pay' => $pay,
            'prizm_pay' => $prizm_pay,
            'link' => $link,
            'file' => $file,
            'address' => UsersPaysPrizm::model()->prizm,
            'pub_key' => UsersPaysPrizm::model()->pub_key,
        ], false, true);
    }
    
    private function payByBitcoin($data)
    {
        $model = new UsersPaysBit;
        $callback_url = $this->createAbsoluteUrl('/finance/payByBitcoinResult', ['invoice_id' => $data->id, 'secret' => $model->secret]);
        
        $bitDataAddress = CJSON::decode(file_get_contents($model->blockchain_receive_root."v2/receive?key=".$model->api_key."&callback=".urlencode($callback_url)."&xpub=".$model->xpub."&gap_limit=".$model->gap_limit));
        
        $model->formPay($data);
        $model->address = $bitDataAddress['address'];
        $pay = UsersPays::model()->findByPk($data->id);
        $pay->operation_summConvert = $model->price_in_btc;
        $pay->save(false);
        if($model->save()) {
            include_once Yii::getPathOfAlias('application.vendor.phpqrcode').'/qrlib.php';
            QRcode::png($model->address, Yii::getPathOfAlias('application.runtime').'/qr_temp.png', 'L', '4', 2);
            $file = base64_encode(file_get_contents(Yii::getPathOfAlias('application.runtime').'/qr_temp.png'));
            echo '
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h5>'.Yii::t('controllers', 'finance_pay_plsSendPrice', ['#price' => $model->price_in_btc]).'</h5>
                        <h4>'.$model->address.'</h4>
                        <img src="data:image/png;base64,'.$file.'" />
                    </div>
                </div>
            ';
        }
    }
    
    private function payByPrizm($data) {
        $model = new UsersPaysPrizm;
        $prizm_file = CJSON::decode(file_get_contents(Yii::getPathOfAlias('currency_files').'/prizm.json'));
        $pay_summ = number_format($data->operation_summ/$prizm_file['price'], 2, '.','');
        $link = 'https://wallet.prizm.space/index.html?to='.$model->wallet_id.'%3A'.$model->pub_key.'%3A'.$pay_summ.'%3A'.$data->operation_number;
        
        $pay = UsersPays::model()->findByPk($data->id);
        $pay->operation_summConvert = $pay_summ;
        $pay->save();
        
        include_once Yii::getPathOfAlias('application.vendor.phpqrcode').'/qrlib.php';
        QRcode::png($link, Yii::getPathOfAlias('application.runtime').'/qr_temp.png', 'L', '4', 2);
        $file = base64_encode(file_get_contents(Yii::getPathOfAlias('application.runtime').'/qr_temp.png'));
        echo '
            <div class="row">
                <div class="col-md-12 text-center">
                    <h5>'.Yii::t('controllers', 'finance_pay_plsSendPricePrizm', ['#price' => $pay_summ]).'</h5>
                    <h4>'.$model->prizm.'</h4>
                    <h6>'.Yii::t('models', 'referralPays_attr_pay_ident', ['#pub_key' => $model->pub_key, '#operation_number' => $data->operation_number]).'</h6>
                    <img src="data:image/png;base64,'.$file.'" />
                    <span class="mt-2 block font-small">'.CHtml::link(Yii::t('core', 'btn_forward'), $link, ['target' => '_blank']).'</span>
                </div>
            </div>
        ';
    }
    
    public function actionPayByCoinsResult()
    {
        if(isset($_GET['pay_status'])){
            Yii::app()->user->setFlash('error', Yii::t('controllers', ($_GET['pay_status'] == 'success') ? 'finance_coinsPay_ntf_success' : 'finance_coinsPay_ntf_cancel'));
            $this->redirect(Yii::app()->getModule('user')->returnUrl);
        }
        
        if (!isset($_POST['ipn_mode']) || $_POST['ipn_mode'] != 'hmac')
            $this->errorAndDie('IPN Mode is not HMAC', $_POST); 
        
        if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC']))
            $this->errorAndDie('No HMAC signature sent.', $_POST); 
        
        $request = file_get_contents('php://input'); 
        if ($request === FALSE || empty($request))
            $this->errorAndDie('Error reading POST data', $_POST); 
         
        if (!isset($_POST['merchant']) || $_POST['merchant'] != trim(UsersPaysCoinpayments::model()->merchant_id))
            $this->errorAndDie($_POST['merchant'].' - '.trim(UsersPaysCoinpayments::model()->merchant_id).' - No or incorrect Merchant ID passed', $_POST); 
        
        
        $hmac = hash_hmac("sha512", $request, trim(UsersPaysCoinpayments::model()->ipn_secret));
        if($hmac != $_SERVER['HTTP_HMAC'])
            $this->errorAndDie($_SERVER['HTTP_HMAC'].' - '.$hmac.' - HMAC signature does not match NEW 29', $_POST); 
        
        if($currency1 != $order_currency)
            $this->errorAndDie('Original currency mismatch!', $_POST); 
     
        if ($amount1 < $order_total)
            $this->errorAndDie('Amount is less than order total!', $_POST); 
        
        $model = UsersPaysCoinpayments::model()->findByPk($_POST['item_number']);
        if($model) {
            $model->txn_id = $_POST['txn_id']; 
            $model->amount1 = floatval($_POST['amount1']); 
            $model->amount2 = floatval($_POST['amount2']); 
            $model->currency1 = $_POST['currency1']; 
            $model->currency2 = $_POST['currency2']; 
            $model->status = intval($_POST['status']); 
            $model->status_text = $_POST['status_text'];
            $model->received_confirms = intval($_POST['received_confirms']);
            
            $pay = UsersPays::model()->findByPk($_POST['item_number']);
            if($pay) {
                if($model->status >= 100 || $model->status == 2) 
                    $pay->operation_status = UsersPays::PSTATUS_COMPL;
                elseif ($status < 0)
                    $pay->operation_status = UsersPays::PSTATUS_ERROR;
                
                if(!$pay->save())
                    $this->errorAndDie('Ne smenil status', [$pay->getErrors()]); 
            }
            
            if(!$model->save())
                $this->errorAndDie('Ne sohranil model', [$model->getErrors()]); 
        } else
            $this->errorAndDie('Net zapisi v base', $_POST); 
    }
    
    private function errorAndDie($error_msg, $data)
    {
        if(!empty(Yii::app()->params->debugCoinsEmail)) { 
            $report = 'Error: '.$error_msg."\n\n"; 
            $report .= "POST Data\n\n"; 
            if($data){
                foreach ($data as $k => $v) { 
                    $report .= "|$k| = |$v|\n"; 
                } 
            }
            
            mail(Yii::app()->params->debugCoinsEmail, 'CoinPayments IPN Error', $report); 
        } 
        
        die('IPN Error: '.$error_msg); 
    }
}

