<?php

class TbBaseInputWidget extends CInputWidget
{
    public function init()
    {
	$this->setDefaultPlaceholder();
	if(!isset($this->htmlOptions['class']) || empty($this->htmlOptions['class']))
            $this->htmlOptions['class'] = 'ct-form-control';
	else
            $this->htmlOptions['class'] .= ' ct-form-control';
    }
	
    protected function setDefaultPlaceholder()
    {
	if(!$this->model)
            return;
	
	if(!isset($this->htmlOptions['placeholder'])) {
            $this->htmlOptions['placeholder'] = $this->model->getAttributeLabel($this->attribute);
	}
    }
}