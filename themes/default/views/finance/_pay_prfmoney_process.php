<?php 
    Yii::app()->clientScript->registerScript('payProcess', '$("#prfmoney-checkout-form").submit();', CClientScript::POS_READY);
    echo CHtml::beginForm($prfmoney->url, 'post', ['id' => 'prfmoney-checkout-form']);
    echo CHtml::hiddenField('PAYEE_ACCOUNT', $prfmoney->account);
    echo CHtml::hiddenField('PAYEE_NAME', Yii::app()->name);
    echo CHtml::hiddenField('PAYMENT_UNITS', $prfmoney->units);
    echo CHtml::hiddenField('STATUS_URL', $this->createAbsoluteUrl($prfmoney->resultUrl));
    echo CHtml::hiddenField('PAYMENT_URL', $this->createAbsoluteUrl($prfmoney->successUrl));
    echo CHtml::hiddenField('NOPAYMENT_URL',$this->createAbsoluteUrl( $prfmoney->failureUrl));
    echo CHtml::hiddenField('NOPAYMENT_URL_METHOD', 'POST');
    echo CHtml::hiddenField('PAYMENT_URL_METHOD', 'POST');
    echo CHtml::hiddenField('PAYMENT_ID', $model->operation_number);
    echo CHtml::hiddenField('PAYMENT_AMOUNT', $model->operation_summ);
    echo CHtml::hiddenField('SUGGESTED_MEMO', $prfmoney->suggested_memo);
    echo CHtml::endForm();