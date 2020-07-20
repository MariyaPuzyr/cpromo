<?php

Yii::import('bootstrap.widgets.TbBaseInputWidget');
class TbDateRangePicker extends TbBaseInputWidget
{
    public $form;
    public $selector;
    public $callback;
    public $options = [];
    public $htmlOptions = [];

    public function init()
    {
	$this->registerClientScript();
	parent::init();
    }
	
    public function run()
    {
	if($this->selector) {
            $this->registerScript($this->selector, $this->options, $this->callback);
	} else {
            list($name, $id) = $this->resolveNameID();
            if($this->hasModel()) {
		if($this->form) {
                    echo $this->form->textField($this->model, $this->attribute, $this->htmlOptions);
		} else {
                    echo CHtml::activeTextField($this->model, $this->attribute, $this->htmlOptions);
		}
            } else {
		echo CHtml::textField($name, $this->value, $this->htmlOptions);
            }

            $this->setLocaleSettings();
            $this->registerScript('#' . $id, $this->options, $this->callback);
	}
    }
	
    public function registerScript($selector, $options = [], $callback = null)
    {
	Yii::app()->clientScript->registerScript(uniqid(__CLASS__ . '#', true), '$("' . $selector . '").daterangepicker(' . CJavaScript::encode($options) . ($callback ? ', ' . CJavaScript::encode($callback) : '') . ');');
    }

    private function setLocaleSettings()
    {
	$this->setDaysOfWeekNames();
	$this->setMonthNames();
    }

    private function setDaysOfWeekNames()
    {
	if(empty($this->options['locale']['daysOfWeek'])) {
            $this->options['locale']['daysOfWeek'] = Yii::app()->locale->getWeekDayNames('narrow', true);
	}
    }

    private function setMonthNames()
    {
	if(empty($this->options['locale']['monthNames'])) {
            $this->options['locale']['monthNames'] = array_values(Yii::app()->locale->getMonthNames('wide', true));
	}
    }

    public function registerClientScript()
    {
        $bootstrap = Bootstrap::getBootstrap();
        $bootstrap->registerPackage('daterangepicker');
    }
}
