<?php

Yii::import('zii.widgets.CListView');
class TbListView extends CListView
{
    public $pagerCssClass = 'pagination';
    public $pager = ['class' => 'bootstrap.widgets.TbPager'];
    public $cssFile = false;

    public function init()
    {
	parent::init();

        $bootstrap = Bootstrap::getBootstrap();
	$popover = $bootstrap->popoverSelector;
	$tooltip = $bootstrap->tooltipSelector;

	$afterAjaxUpdate = "js:function() {
            jQuery('.popover').remove();
            jQuery('{$popover}').popover();
            jQuery('.tooltip').remove();
            jQuery('{$tooltip}').tooltip();
	}";

	if (!isset($this->afterAjaxUpdate)) {
            $this->afterAjaxUpdate = $afterAjaxUpdate;
	}
    }
}
