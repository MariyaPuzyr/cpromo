<?php

class TbSwitch extends CInputWidget
{
    public $form;
    public $events = [];
    public $options = [];
    
    public function run()
    {
	list($name, $id) = $this->resolveNameID();

	if($this->hasModel()) {
            if($this->form) {
		echo $this->form->checkBox($this->model, $this->attribute, $this->htmlOptions);
            } else {
		echo CHtml::activeCheckBox($this->model, $this->attribute, $this->htmlOptions);
            }
	} else {
            echo CHtml::checkBox($name, $this->value, $this->htmlOptions);
	}

	$this->registerClientScript($id);
    }

    protected function registerClientScript($id)
    {
        $bootstrap = Bootstrap::getBootstrap();
        $bootstrap->registerPackage('switch');
	$config = CJavaScript::encode($this->options);
		
	ob_start();
	echo "$('#$id').bootstrapSwitch({$config})";
	foreach ($this->events as $event => $handler) {
            $event = $event.'.bootstrapSwitch';
            if (!$handler instanceof CJavaScriptExpression && strpos($handler, 'js:') === 0)
		$handler = new CJavaScriptExpression($handler);
            
            echo ".on('{$event}', " . $handler . ")";
	}

	Yii::app()->clientScript->registerScript(__CLASS__ . '#' . $this->getId(), ob_get_clean() . ';');
    }
}
