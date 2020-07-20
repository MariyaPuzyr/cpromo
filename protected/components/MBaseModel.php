<?php

class MBaseModel extends CActiveRecord
{
    #Обозначение месяцев
    const MYAN = 1;
    const MFEV = 2;
    const MMAR = 3;
    const MAPR = 4;
    const MMAY = 5;
    const MIUN = 6;
    const MIUL = 7;
    const MAVG = 8;
    const MSEN = 9;
    const MOKT = 10;
    const MNOV = 11;
    const MDEC = 12;
    
    #Список платежных систем
    const FIN_PAYEER = 0;
    const FIN_CARD = 1;
    const FIN_BITCOIN = 2;
    const FIN_PAYPAL = 3;
    const FIN_YANDEX = 4;
    const FIN_PRIZM = 5;
    const FIN_ADVCASH = 6;
    const FIN_PRFMONEY = 7;
    const FIN_COINSPAY = 8;
    const FIN_INNER = 9;
    
    const RSTAT_VIS = 1;
    const RSTAT_INVIS = 0;
    
    public static function monthsForChart()
    {
        $months = [
            self::MYAN => Yii::t('models', 'month_yan'),
            self::MFEV => Yii::t('models', 'month_fev'),
            self::MMAR => Yii::t('models', 'month_mar'),
            self::MAPR => Yii::t('models', 'month_apr'),
            self::MMAY => Yii::t('models', 'month_may'),
            self::MIUN => Yii::t('models', 'month_iun'),
            self::MIUL => Yii::t('models', 'month_iul'),
            self::MAVG => Yii::t('models', 'month_avg'),
            self::MSEN => Yii::t('models', 'month_sen'),
            self::MOKT => Yii::t('models', 'month_okt'),
            self::MNOV => Yii::t('models', 'month_nov'),
            self::MDEC => Yii::t('models', 'month_dec'),
        ];
        
        return array_values($months);
    }
    
    
    public static function getFinType($code = null)
    {
        $_items = [
            self::FIN_PAYEER => Yii::t('models', 'base_attr_finType_payeer'),
            self::FIN_CARD => Yii::t('models', 'base_attr_finType_card'),
            self::FIN_BITCOIN => Yii::t('models', 'base_attr_finType_bitcoin'),
            self::FIN_PAYPAL => Yii::t('models', 'base_attr_finType_paypal'),
            self::FIN_YANDEX => Yii::t('models', 'base_attr_finType_yandex'),
            self::FIN_PRIZM => Yii::t('models', 'base_attr_finType_prizm'),
            self::FIN_ADVCASH => Yii::t('models', 'base_attr_finType_advcash'),
            self::FIN_PRFMONEY => Yii::t('models', 'base_attr_finType_prfmoney'),
            self::FIN_COINSPAY => Yii::t('models', 'base_attr_finType_coinspay'),
            self::FIN_INNER => Yii::t('models', 'base_attr_finType_inner'),
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
    
    public static function getFinTypeName($code = null)
    {
        $_items = [
            self::FIN_PAYEER => Yii::t('models', 'base_attr_finType_payeer_name'),
            self::FIN_CARD => Yii::t('models', 'base_attr_finType_card_name'),
            self::FIN_BITCOIN => Yii::t('models', 'base_attr_finType_bitcoin_name'),
            self::FIN_PAYPAL => Yii::t('models', 'base_attr_finType_paypal_name'),
            self::FIN_YANDEX => Yii::t('models', 'base_attr_finType_yandex_name'),
            self::FIN_PRIZM => Yii::t('models', 'base_attr_finType_prizm_name'),
            self::FIN_ADVCASH => Yii::t('models', 'base_attr_finType_advcash_name'),
            self::FIN_PRFMONEY => Yii::t('models', 'base_attr_finType_prfmoney_name'),
            self::FIN_COINSPAY => Yii::t('models', 'base_attr_finType_coinspay_name'),
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
    
    public static function getPayMode($code = null)
    {
        $_items = [
            self::FIN_PAYEER => Yii::t('models', 'base_attr_finType_payeer'),
            #self::FIN_BITCOIN => Yii::t('models', 'base_attr_finType_bitcoin'),
            self::FIN_PRFMONEY => Yii::t('models', 'base_attr_finType_prfmoney'),
            #self::FIN_COINSPAY => Yii::t('models', 'base_attr_finType_coinspay')
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
    
    public static function getOutMode($code = null)
    {
        $_items = [
            self::FIN_PAYEER => Yii::t('models', 'base_attr_finType_payeer'),
            #self::FIN_CARD => Yii::t('models', 'base_attr_finType_card'),
            self::FIN_PRFMONEY => Yii::t('models', 'base_attr_finType_prfmoney'),
            self::FIN_COINSPAY => Yii::t('models', 'base_attr_finType_coinspay_out_usdtrc20'),
        ];
        
        
        $outsDis = UsersOutsDisabled::model()->findByPk(1);
        if(!$outsDis->finance_payeer)
            unset($_items[self::FIN_PAYEER]);
        if(!$outsDis->finance_prfmoney)
            unset($_items[self::FIN_PRFMONEY]);
        if(!$outsDis->finance_usdtrc)
            unset($_items[self::FIN_COINSPAY]);
        
        
        
        
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
    
    public static function getOutModeName($code = null)
    {
        $_items = [
            self::FIN_PAYEER => Yii::t('models', 'base_attr_finType_payeer_name'),
            self::FIN_PRFMONEY => Yii::t('models', 'base_attr_finType_prfmoney_name'),
            self::FIN_COINSPAY => Yii::t('models', 'base_attr_finType_coinspay_name'),
        ];
        
        $outsDis = UsersOutsDisabled::model()->findByPk(1);
        if(!$outsDis->finance_payeer)
            unset($_items[self::FIN_PAYEER]);
        if(!$outsDis->finance_prfmoney)
            unset($_items[self::FIN_PRFMONEY]);
        if(!$outsDis->finance_usdtrc)
            unset($_items[self::FIN_COINSPAY]);
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
    
    public static function getOutTypePaySystem($code = null)
    {
        $_items = [
            self::FIN_PAYEER => Yii::t('models', 'base_attr_finTypeSmall_payeer'),
            self::FIN_CARD => Yii::t('models', 'base_attr_finTypeSmall_card'),
            self::FIN_BITCOIN => Yii::t('models', 'base_attr_finTypeSmall_bitcoin'),
            self::FIN_PAYPAL => Yii::t('models', 'base_attr_finTypeSmall_paypal'),
            self::FIN_YANDEX => Yii::t('models', 'base_attr_finTypeSmall_yandex'),
            self::FIN_PRIZM => Yii::t('models', 'base_attr_finType_prizm'),
            self::FIN_ADVCASH => Yii::t('models', 'base_attr_finType_advcash'),
            self::FIN_PRFMONEY => Yii::t('models', 'base_attr_finType_prfmoney'),
            self::FIN_COINSPAY => Yii::t('models', 'base_attr_finType_coinspay'),
            self::FIN_INNER => Yii::t('models', 'base_attr_finType_inner'),
        ];
        
        if(isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
	else
            return isset($_items) ? $_items : false;
    }
}

