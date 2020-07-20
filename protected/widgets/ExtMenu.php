<?php

Yii::import('zii.widgets.CMenu');
class ExtMenu extends CMenu
{
    public $activateParents = true;
    public $activeCssClass = 'active selected';
    
    protected function renderMenuItem($item)
    {
	if(isset($item['url'])){
            $label = $this->linkLabelWrapper === null ? $item['label'] : CHtml::tag($this->linkLabelWrapper, $this->linkLabelWrapperHtmlOptions, $item['label']);
            return 
                $item['icon'] ? 
                    CHtml::link('<span class="has-icon"><i class="'.$item['icon'].'"></i></span><span class="nav-title">'.$label.'</span>', $item['url'], isset($item['linkOptions']) ? $item['linkOptions'] : [])
                : 
                    CHtml::link('<span class="nav-title">'.$label.'</span>', $item['url'], isset($item['linkOptions']) ? $item['linkOptions'] : []);
	} else
            return CHtml::tag('span', isset($item['linkOptions']) ? $item['linkOptions'] : [], $item['label']);
    }
    
    protected function isItemActive($item,$route)
    {
        if(isset($item['url']) && is_array($item['url'])  && !strcasecmp(trim($item['url'][0], '/'), str_replace('/index', '', $route))){
            unset($item['url']['#']);
            if(count($item['url'])>1){
		foreach(array_splice($item['url'],1) as $name=>$value){
                    if(!isset($_GET[$name]) || $_GET[$name]!=$value)
			return false;
		}
            }
            return true;
	}
	
        return false;
    }
}

