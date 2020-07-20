<?php

Yii::import('bootstrap.widgets.TbWidget');
class TbAlert extends TbWidget
{
    const CTX_ERROR = 'error';
    const CTX_ERROR_CLASS = 'danger';
	
    public $alerts;
    public $closeText = '&times;';
    public $fade = true;
    public $events = [];
    public $htmlOptions = [];
    public $userComponentId = 'user';
    
    protected static $_containerId = 0;

    public function init()
    {
	if(!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = $this->getId();
	}

	if(is_string($this->alerts)) {
            $this->alerts = array($this->alerts);
	}

	if(!isset($this->alerts)) {
            $this->alerts = array(
		self::CTX_SUCCESS,
		self::CTX_INFO,
		self::CTX_WARNING,
		self::CTX_DANGER,
		self::CTX_ERROR
            );
	}
    }

    public function run()
    {
	$id = $this->htmlOptions['id'];

	echo CHtml::openTag('div', $this->htmlOptions);
            foreach($this->alerts as $type => $alert) {
                if(is_string($alert)) {
                    $type = $alert;
                    $alert = array();
                }

                if(isset($alert['visible']) && $alert['visible'] === false) {
                    continue;
                }

                $userComponent = Yii::app()->getComponent($this->userComponentId);
                if(!$userComponent->hasFlash($type))
                    continue;

                $alertText = $userComponent->getFlash($type);
                if(empty($alertText)) {
                    continue;
                }

                $this->renderSingleAlert($alert, $type, $alertText);
            }
	echo CHtml::closeTag('div');

	$id .= '_' . self::$_containerId++;
	$selector = "#{$id} .alert";

	$cs = Yii::app()->getClientScript();
	$cs->registerScript(__CLASS__ . '#' . $id, "jQuery('{$selector}').alert();");

	foreach($this->events as $name => $handler) {
            $handler = CJavaScript::encode($handler);
            $cs->registerScript(__CLASS__ . '#' . $id . '_' . $name, "jQuery('{$selector}').on('{$name}', {$handler});");
	}
    }

    protected function renderSingleAlert($alert, $context, $alertText)
    {
	$classes = array('alert ');

	if(!isset($alert['fade'])) {
            $alert['fade'] = $this->fade;
	}

	if($alert['fade'] === true) {
            $classes[] = 'fadeIn';
	}

	if($this->isValidContext($context)) {
            $classes[] = 'alert-' . $this->getContextClass($context);
	}

	if(!isset($alert['htmlOptions'])) {
            $alert['htmlOptions'] = array();
	}

	$classes = implode(' ', $classes);
	if (isset($alert['htmlOptions']['class'])) {
            $alert['htmlOptions']['class'] .= ' ' . $classes;
	} else {
            $alert['htmlOptions']['class'] = $classes;
	}

	echo CHtml::openTag('div', $alert['htmlOptions']);
            if(!isset($alert['closeText'])) {
		$alert['closeText'] = (isset($this->closeText) && $this->closeText !== false) ? $this->closeText : false;
            }

            if($alert['closeText'] !== false) {
		echo '<a href="#" class="close" data-dismiss="alert">' . $alert['closeText'] . '</a>';
            }

            echo $alertText;
        echo CHtml::closeTag('div');
    }
	
    protected function isValidContext($context = false)
    {
	return in_array($context, [
            self::CTX_SUCCESS,
            self::CTX_INFO,
            self::CTX_WARNING,
            self::CTX_DANGER,
            self::CTX_ERROR,
	]);
    }
}
