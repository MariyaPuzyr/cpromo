<?php

Yii::import('zii.widgets.grid.CButtonColumn');
class TbButtonColumn extends CButtonColumn
{
    public $viewButtonIcon = 'eye-open';
    public $updateButtonIcon = 'pencil';
    public $deleteButtonIcon = 'trash';

    protected function initDefaultButtons() 
    {
	parent::initDefaultButtons();

	if($this->viewButtonIcon !== false && !isset($this->buttons['view']['icon'])) {
            $this->buttons['view']['icon'] = $this->viewButtonIcon;
	}
	
        if($this->updateButtonIcon !== false && !isset($this->buttons['update']['icon'])) {
            $this->buttons['update']['icon'] = $this->updateButtonIcon;
	}
	
        if($this->deleteButtonIcon !== false && !isset($this->buttons['delete']['icon'])) {
            $this->buttons['delete']['icon'] = $this->deleteButtonIcon;
	}
    }

    protected function renderButton($id, $button, $row, $data)
    {
	if(isset($button['visible']) && !$this->evaluateExpression($button['visible'], array('row' => $row, 'data' => $data))) {
            return;
	}

	$label = isset($button['label']) ? $button['label'] : $id;
	$url = isset($button['url']) ? $this->evaluateExpression($button['url'], array('data' => $data, 'row' => $row)) : '#';
	$options = isset($button['options']) ? $button['options'] : array();

	if(!isset($options['title'])) {
            $options['title'] = $label;
	}

	if(!isset($options['data-toggle'])) {
            $options['data-toggle'] = 'tooltip';
	}

	if(isset($button['icon']) && $button['icon']) {
            echo CHtml::link('<i class="' . $button['icon'] . '"></i>', $url, $options);
	} elseif(isset($button['imageUrl']) && is_string($button['imageUrl'])) {
            echo CHtml::link(CHtml::image($button['imageUrl'], $label), $url, $options);
	} else {
            echo CHtml::link($label, $url, $options);
	}
    }
}
