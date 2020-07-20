<?php 
    Yii::app()->clientScript->registerScript('payProcess', '$("#payeer-checkout-form").submit();', CClientScript::POS_READY);
    echo CHtml::beginForm('https://payeer.com/merchant/', 'post', ['id' => 'payeer-checkout-form']);
    echo CHtml::hiddenField('m_shop', $model->merchant);
    echo CHtml::hiddenField('m_orderid', $model->action_number);
    echo CHtml::hiddenField('m_amount', $model->price);
    echo CHtml::hiddenField('m_curr', $model->priceCurr);
    echo CHtml::hiddenField('m_desc', '');
    echo CHtml::hiddenField('m_sign', $model->sign);
    echo CHtml::endForm();