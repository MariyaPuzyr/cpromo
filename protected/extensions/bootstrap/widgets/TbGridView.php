<?php

Yii::import('zii.widgets.grid.CGridView');
Yii::import('bootstrap.widgets.TbDataColumn');
class TbGridView extends CGridView
{
    const TYPE_STRIPED = 'striped';
    const TYPE_BORDERED = 'bordered';
    const TYPE_CONDENSED = 'condensed';
    const TYPE_HOVER = 'hover';

    public $type;
    public $pagerCssClass = 'no-class';
    public $pager = ['class' => 'bootstrap.widgets.TbPager'];
    public $cssFile = false;
    public $responsiveTable = false;
    public $extraParams = [];
    public $emptyCssClass = '';

    public function init()
    {
	parent::init();

	$classes = ['table'];
	if(isset($this->type)) {
            if(is_string($this->type)) {
		$this->type = explode(' ', $this->type);
            }

            if(!empty($this->type)) {
		$validTypes = [self::TYPE_STRIPED, self::TYPE_BORDERED, self::TYPE_CONDENSED, self::TYPE_HOVER];

		foreach($this->type as $type) {
                    if(in_array($type, $validTypes)) {
			$classes[] = 'table-' . $type;
                    }
		}
            }
	}

        if(!empty($classes)) {
            $classes = implode(' ', $classes);
            if(isset($this->itemsCssClass)) {
		$this->itemsCssClass .= ' ' . $classes;
            } else {
		$this->itemsCssClass = $classes;
            }
	}

        $bootstrap = Bootstrap::getBootstrap();
	$popover = $bootstrap->popoverSelector;
	$tooltip = $bootstrap->tooltipSelector;

	$afterAjaxUpdate = "js:function() {
            jQuery('.popover').remove();
            jQuery('{$popover}').popover();
            jQuery('.tooltip').remove();
            jQuery('{$tooltip}').tooltip();
	}";

	if(!isset($this->afterAjaxUpdate)) {
            $this->afterAjaxUpdate = $afterAjaxUpdate;
	}
    }

    protected function initColumns()
    {
	foreach($this->columns as $i => $column) {
            if(is_array($column) && !isset($column['class'])) {
		$this->columns[$i]['class'] = 'bootstrap.widgets.TbDataColumn';
            }
	}

	parent::initColumns();

	if($this->responsiveTable) {
            $this->writeResponsiveCss();
	}
    }

    protected function createDataColumn($text)
    {
	if(!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $text, $matches)) {
            throw new CException(Yii::t('zii', 'The column must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));
        }

	$column = new TbDataColumn($this);
	$column->name = $matches[1];

	if(isset($matches[3]) && $matches[3] !== '') {
            $column->type = $matches[3];
	}

	if(isset($matches[5])) {
            $column->header = $matches[5];
	}

	return $column;
    }

    protected function writeResponsiveCss()
    {
	$cnt = 1;
	$labels = '';
	foreach($this->columns as $column) {
            ob_start();
            $column->renderHeaderCell();
            $name = strip_tags(ob_get_clean());

            $labels .= "#$this->id td:nth-of-type($cnt):before { content: '{$name}'; }\n";
            $cnt++;
	}

	$css = <<<EOD
            @media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px)  {
		#{$this->id} table,#{$this->id} thead,#{$this->id} tbody,#{$this->id} th,#{$this->id} td,#{$this->id} tr {
                    display: block;
		}

		#{$this->id} thead tr {
                    position: absolute;
                    top: -9999px;
                    left: -9999px;
		}

		#{$this->id} tr { border: 1px solid #ccc; }

		#{$this->id} td {
                    border: none;
                    border-bottom: 1px solid #eee;
                    position: relative;
                    padding-left: 50%;
		}

		#{$this->id} td:before {
                    position: absolute;
                    top: 6px;
                    left: 6px;
                    width: 45%;
                    padding-right: 10px;
                    white-space: nowrap;
		}
                
		.grid-view .button-column {
                    text-align: left;
                    width:auto;
		}
		{$labels}
            }
EOD;
        Yii::app()->clientScript->registerCss(__CLASS__ . '#' . $this->id, $css);
    }
    public $emptyTagName = 'div';
    public function renderEmptyText()
    {
	$emptyText = $this->emptyText === null ? Yii::t('zii','No results found.') : $this->emptyText;
    	echo CHtml::tag($this->emptyTagName, ['class'=>'empty '.$this->emptyCssClass], $emptyText);
    }
}
