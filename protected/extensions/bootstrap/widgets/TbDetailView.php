<?php

Yii::import('zii.widgets.CDetailView');
class TbDetailView extends CDetailView
{
    const TYPE_STRIPED = 'striped';
    const TYPE_BORDERED = 'bordered';
    const TYPE_CONDENSED = 'condensed';

    public $type = [self::TYPE_STRIPED, self::TYPE_CONDENSED];
    public $cssFile = false;

    public function init()
    {
	parent::init();
	$classes = ['table'];
	if(isset($this->type)) {
            if(is_string($this->type)) {
		$this->type = explode(' ', $this->type);
            }

            $validTypes = [self::TYPE_STRIPED, self::TYPE_BORDERED, self::TYPE_CONDENSED];

            if(!empty($this->type)) {
		foreach($this->type as $type) {
                    if(in_array($type, $validTypes)) {
			$classes[] = 'table-' . $type;
                    }
		}
            }
	}

	if(!empty($classes)) {
            $classes = implode(' ', $classes);
            if(isset($this->htmlOptions['class'])) {
		$this->htmlOptions['class'] .= ' ' . $classes;
            } else {
		$this->htmlOptions['class'] = $classes;
            }
	}
    }
}
