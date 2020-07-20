<?php

Yii::import('bootstrap.widgets.TbBaseInputWidget');
class TbDatePicker extends TbBaseInputWidget
{
    public $form;
    public $options = [];
    public $events = [];

    public function init()
    {
	$this->htmlOptions['type'] = 'text';
	$this->htmlOptions['autocomplete'] = 'off';
		
	if(!isset($this->options['language'])) {
            $this->options['language'] = Yii::app()->language;
	}
		
	parent::init();
    }
	
    public function run()
    {
	list($name, $id) = $this->resolveNameID();

	if($this->hasModel()) {
            if($this->form) {
		echo $this->form->textField($this->model, $this->attribute, $this->htmlOptions);
            } else {
                echo CHtml::activeTextField($this->model, $this->attribute, $this->htmlOptions);
            }
        } else
            echo CHtml::textField($name, $this->value, $this->htmlOptions);
	

	$this->registerClientScript();
	$this->registerLanguageScript();
	$options = !empty($this->options) ? CJavaScript::encode($this->options) : '';

	ob_start();
	echo "jQuery('#{$id}').datepicker({$options})";
	foreach($this->events as $event => $handler) {
            echo ".on('{$event}', " . CJavaScript::encode($handler) . ")";
	}

	Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $this->getId(), ob_get_clean() . ';');
    }

    public function registerClientScript()
    {
        Bootstrap::getBootstrap()->registerPackage('datepicker');
    }

    public function registerLanguageScript()
    {
	$bootstrap = Bootstrap::getBootstrap();

	if (isset($this->options['language']) && $this->options['language'] != 'en'){
            $filename = '/bootstrap-datepicker/locales/bootstrap-datepicker.'.$this->options['language'].'.min.js';

            if(file_exists(Yii::getPathOfAlias('bootstrap').'/assets'.$filename))
                $bootstrap->cs->registerScriptFile($bootstrap->getAssetsUrl().$filename, CClientScript::POS_HEAD);
        }
    }
}
