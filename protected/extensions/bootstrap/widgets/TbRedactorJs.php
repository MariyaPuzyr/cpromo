<?php

class TbRedactorJS extends CInputWidget
{
    public $editorOptions = [];
    public $selector;
    public $width = '100%';
    public $height = '400px';

    public function init()
    {
    	parent::init();

	if(!isset($this->editorOptions['lang'])) {
            $this->editorOptions['lang'] = substr(Yii::app()->getLanguage(), 0, 2);
	}

	if($this->selector === null) {
            list($this->name, $this->id) = $this->resolveNameID();
            $this->htmlOptions['id'] = $this->id;
            $this->selector = '#' . $this->id;
            if (!array_key_exists('style', $this->htmlOptions)) {
		$this->htmlOptions['style'] = "width:{$this->width};height:{$this->height};";
            }
	
            if ($this->hasModel()) {
                echo CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
            } else {
		echo CHtml::textArea($this->name, $this->value, $this->htmlOptions);
            }
	}
	
        $this->registerClientScript($this->id);
    }

    public function registerClientScript()
    {
	$assets = Bootstrap::getBootstrap()->cs;
	$assets->registerPackage('redactor');
	$baseUrl = $assets->packages['redactor']['baseUrl'];

	if($this->editorOptions['lang'] != 'en') {
            $assets->registerScriptFile($baseUrl . '/lang/' . $this->editorOptions['lang'] . '.js');
	}

	if(isset($this->editorOptions['plugins'])) {
            foreach ($this->editorOptions['plugins'] as $name) {
		$filepath = Yii::getPathOfAlias('bootstrap.assets.redactor.plugins') . '/' . $name . '/' . $name;
		$url = $baseUrl . '/plugins/' . $name . '/' . $name;

		if(file_exists($filepath . '.css'))
                    $assets->registerCssFile($url.'.css');

		if(file_exists($filepath . '.js'))
                    $assets->registerScriptFile($url.'.js');
            }
	}

	$options = $this->editorOptions ? CJavaScript::encode($this->editorOptions) : '';
	$assets->registerScript(uniqid(__CLASS__ . '#', true),"jQuery('{$this->selector}').redactor({$options});");
    }
}
