<?php 
    Yii::app()->clientScript->registerScript('payProcess', '$("#coinspay-checkout-form").submit();', CClientScript::POS_READY);
    echo CHtml::beginForm('https://www.coinpayments.net/index.php', 'post', ['id' => 'coinspay-checkout-form']);
    echo CHtml::hiddenField('cmd', '_pay_simple');
    echo CHtml::hiddenField('reset', 1);
    echo CHtml::hiddenField('merchant', $model->merchant_id);
    echo CHtml::hiddenField('item_name', $model->item_name);
    echo CHtml::hiddenField('item_desc', $model->item_name);
    echo CHtml::hiddenField('item_number', $model->pay_id);
    echo CHtml::hiddenField('invoice', $model->pay_id);
    echo CHtml::hiddenField('currency', 'USD');
    echo CHtml::hiddenField('amountf', $model->pay_summ);
    echo CHtml::hiddenField('want_shipping', 0);
    echo CHtml::hiddenField('success_url', $this->createAbsoluteUrl($model->success_url));
    echo CHtml::hiddenField('cancel_url', $this->createAbsoluteUrl($model->cancel_url));
    echo CHtml::hiddenField('ipn_url', $this->createAbsoluteUrl($model->ipn_url));
    echo CHtml::endForm();