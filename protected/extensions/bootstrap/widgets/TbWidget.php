<?php

abstract class TbWidget extends CWidget 
{
    const CTX_LIGHT = 'light';
    const CTX_PRIMARY = 'primary';
    const CTX_SUCCESS = 'success';
    const CTX_INFO = 'info';
    const CTX_WARNING = 'warning';
    const CTX_DANGER = 'danger';
    const CTX_DARK = 'dark';
	
    const CTX_LIGHT_CLASS = 'light';
    const CTX_PRIMARY_CLASS = 'primary';
    const CTX_SUCCESS_CLASS = 'success';
    const CTX_INFO_CLASS = 'info';
    const CTX_WARNING_CLASS = 'warning';
    const CTX_DANGER_CLASS = 'danger';
    
    public $context = self::CTX_LIGHT;
	
    protected static function addCssClass(&$htmlOptions, $class) 
    {
	if(empty($class))
            return;
	
	if(isset($htmlOptions['class']))
            $htmlOptions['class'] .= ' ' . $class;
	else 
            $htmlOptions['class'] = $class;
    }

    protected function isValidContext($cotext = false) 
    {
        if($cotext)
            return defined(get_called_class().'::CTX_'.strtoupper($context));
	else
            return defined(get_called_class().'::CTX_'.strtoupper($this->context));
    }
	
    protected function getContextClass($context = false) 
    {
        if($context)
            return constant(get_called_class().'::CTX_'.strtoupper($context).'_CLASS');
	else
            return constant(get_called_class().'::CTX_'.strtoupper($this->context).'_CLASS');
    }
}