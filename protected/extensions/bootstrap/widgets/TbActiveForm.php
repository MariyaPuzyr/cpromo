<?php

class TbActiveForm extends CActiveForm
{
	
    const TYPE_VERTICAL = 'vertical';
    const TYPE_INLINE = 'inline';
    	
    protected static $typeClasses = [
	self::TYPE_VERTICAL => '',
	self::TYPE_INLINE => '-inline',
    ];

    public $type = self::TYPE_VERTICAL;
    public $prependCssClass = 'input-group-prepend';
    public $appendCssClass = 'input-group-append';
    public $addOnCssClass = 'input-group-text';
    public $addOnTag = 'div';
    public $addOnWrapperTag = 'div';
    public $hintCssClass = 'form-text text-muted small';
    public $hintTag = 'span';
    public $showErrors = true;
    public $floating = false;
    public $withOutPlaceholder = false;

    public function init() 
    {
	self::addCssClass($this->htmlOptions, 'form' . self::$typeClasses[$this->type]);
		
	$this->errorMessageCssClass = 'form-text text-danger font-small';

	$this->clientOptions['errorCssClass'] = 'has-warning';
	$this->clientOptions['successCssClass'] = 'has-success';
	$this->clientOptions['inputContainer'] = 'div.form-group';
        
	parent::init();
    }

    public function errorSummary($models, $header = null, $footer = null, $htmlOptions = [])
    {
	if (!isset($htmlOptions['class'])) {
            $htmlOptions['class'] = 'alert alert-block alert-danger';
	}

	return parent::errorSummary($models, $header, $footer, $htmlOptions);
    }

    public function emailFieldGroup($model, $attribute, $options = [])
    {
	$this->initOptions($options);
	$widgetOptions = $options['widgetOptions'];

	$this->addCssClass($widgetOptions['htmlOptions'], 'form-control');
        $fieldData = [[$this, 'emailField'], [$model, $attribute, $widgetOptions['htmlOptions']]];

	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function numberFieldGroup($model, $attribute, $options = [])
    {
	$this->initOptions($options);
	$widgetOptions = $options['widgetOptions'];

	$this->addCssClass($widgetOptions['htmlOptions'], 'form-control');
        $fieldData = [[$this, 'numberField'], [$model, $attribute, $widgetOptions['htmlOptions']]];

        return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function rangeFieldGroup($model, $attribute, $options = [])
    {
	$this->initOptions($options);
	$widgetOptions = $options['widgetOptions'];

	$this->addCssClass($widgetOptions['htmlOptions'], 'form-control');
	$fieldData = [[$this, 'rangeField'], [$model, $attribute, $widgetOptions['htmlOptions']]];	

	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function dateFieldGroup($model, $attribute, $options = [])
    {
	$this->initOptions($options);
	$widgetOptions = $options['widgetOptions'];

	$this->addCssClass($widgetOptions['htmlOptions'], 'form-control');
	$fieldData = [[$this, 'dateField'], [$model, $attribute, $widgetOptions['htmlOptions']]];	

	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function timeFieldGroup($model, $attribute, $options = [])
    {
	$this->initOptions($options);
	$widgetOptions = $options['widgetOptions'];

	$this->addCssClass($widgetOptions['htmlOptions'], 'form-control');
        $fieldData = [[$this, 'timeField'], [$model, $attribute, $widgetOptions['htmlOptions']]];

        return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function textFieldGroup($model, $attribute, $options = [])
    {
	$this->initOptions($options);
	$widgetOptions = $options['widgetOptions'];
		
	$this->addCssClass($widgetOptions['htmlOptions'], 'form-control');
	$fieldData = [[$this, 'textField'], [$model, $attribute, $widgetOptions['htmlOptions']]];		
	
	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }
	
    public function passwordFieldGroup($model, $attribute, $options = [])
    {
	$this->initOptions($options);
	$this->addCssClass($options['widgetOptions']['htmlOptions'], 'form-control');
	$fieldData = [[$this, 'passwordField'], [$model, $attribute, $options['widgetOptions']['htmlOptions']]];
	
	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function textAreaGroup($model, $attribute, $options = [])
    {
	$this->initOptions($options);
	$this->addCssClass($options['widgetOptions']['htmlOptions'], 'form-control');
        $fieldData = [[$this, 'textArea'], [$model, $attribute, $options['widgetOptions']['htmlOptions']]];

	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function fileFieldGroup($model, $attribute, $options = [])
    {
	$this->initOptions($options);
	$widgetOptions = $options['widgetOptions'];
		
	$this->addCssClass($widgetOptions['htmlOptions'], 'form-control');
	$fieldData = [[$this, 'fileField'], [$model, $attribute, $widgetOptions['htmlOptions']]];	

	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function radioButtonGroup($model, $attribute, $options = [])
    {
	$this->initOptions($options);
	$widgetOptions = $options['widgetOptions']['htmlOptions'];
		
	self::addCssClass($options['labelOptions'], 'radio');
	if($this->type == self::TYPE_INLINE || (isset($options['inline']) && $options['inline']))
            self::addCssClass($options['labelOptions'], 'radio-inline');
		
	$field = $this->radioButton($model, $attribute, $widgetOptions);
	if((!array_key_exists('uncheckValue', $widgetOptions) || isset($widgetOptions['uncheckValue'])) && preg_match('/\<input.*?type="hidden".*?\>/', $field, $matches)) {
            $hiddenField = $matches[0];
            $field = str_replace($hiddenField, '', $field);
	}

	$realAttribute = $attribute;
	CHtml::resolveName($model, $realAttribute);

	ob_start();
	if (isset($hiddenField)) echo $hiddenField;
	echo CHtml::tag('label', $options['labelOptions'], false, false);
	echo $field;
	if (isset($options['label'])) {
            if ($options['label'])
		echo $options['label'];
        } else
            echo $model->getAttributeLabel($realAttribute);
	
        echo CHtml::closeTag('label');
	$fieldData = ob_get_clean();

	$widgetOptions['label'] = '';

	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function checkboxGroup($model, $attribute, $options = [])
    {
	$this->initOptions($options);
	
	if ($this->type == self::TYPE_INLINE)
            self::addCssClass($options['labelOptions'], 'inline');
	
	$field = $this->checkbox($model, $attribute, $options['widgetOptions']['htmlOptions']);
	if ((!array_key_exists('uncheckValue', $options['widgetOptions']) || isset($options['widgetOptions']['uncheckValue'])) && preg_match('/\<input.*?type="hidden".*?\>/', $field, $matches)) {
            $hiddenField = $matches[0];
            $field = str_replace($hiddenField, '', $field);
	}
	
	$realAttribute = $attribute;
	CHtml::resolveName($model, $realAttribute);
	
	ob_start();
	echo '<div class="checkbox">';
	if (isset($hiddenField)) echo $hiddenField;
	echo CHtml::tag('label', $options['labelOptions'], false, false);
	echo $field;
	if(isset($options['label'])) {
            if($options['label'])
		echo $options['label'];
	} else
            echo ' '.$model->getAttributeLabel($realAttribute);
	
        echo CHtml::closeTag('label');
	echo '</div>';
	$fieldData = ob_get_clean();
	
	$options['label'] = '';
	
	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function dropDownListGroup($model, $attribute, $options = [])
    {
	$this->initOptions($options, true);
	$widgetOptions = $options['widgetOptions'];
		
	$this->addCssClass($widgetOptions['htmlOptions'], 'form-control');
	$fieldData = [[$this, 'dropDownList'], [$model, $attribute, $widgetOptions['data'], $widgetOptions['htmlOptions']]];		

	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function listBoxGroup($model, $attribute, $options = [])
    {
	$this->initOptions($options, true);
	$widgetOptions = $options['widgetOptions'];
		
	$this->addCssClass($widgetOptions['htmlOptions'], 'form-control');
        $fieldData = [[$this, 'listBox'], [$model, $attribute, $widgetOptions['data'], $widgetOptions['htmlOptions']]];	
	
	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function checkboxListGroup($model, $attribute, $options = [])
    {
	$this->initOptions($options, true);

	$widgetOptions = $options['widgetOptions']['htmlOptions'];
		
	if (!isset($widgetOptions['labelOptions']['class']))
            $widgetOptions['labelOptions']['class'] = 'checkbox';
		
	if(isset($options['inline']) && $options['inline'])
            $widgetOptions['labelOptions']['class'] = 'checkbox-inline';

	if (!isset($widgetOptions['template']))
            $widgetOptions['template'] = '{beginLabel}{input}{labelTitle}{endLabel}';

	if (!isset($widgetOptions['separator']))
            $widgetOptions['separator'] = "\n";

	$data = $options['widgetOptions']['data'];
	$fieldData = [[$this, 'checkboxList'], [$model, $attribute, $data, $widgetOptions]];		
	
	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function radioButtonListGroup($model, $attribute, $options = [])
    {
	$this->initOptions($options, true);
		
	$widgetOptions = $options['widgetOptions']['htmlOptions'];
		
	if(!isset($widgetOptions['labelOptions']['class']))
            $widgetOptions['labelOptions']['class'] = 'radio';
		
	if(isset($options['inline']) && $options['inline'])
            $widgetOptions['labelOptions']['class'] = 'checkbox-inline';
        
        if(isset($options['block']) && $options['block'])
            $widgetOptions['labelOptions']['class'] = ' block';
		
	if(!isset($widgetOptions['template']))
            $widgetOptions['template'] = '{beginLabel}{input}{labelTitle}{endLabel}';

	if(!isset($widgetOptions['separator']))
            $widgetOptions['separator'] = "\n";
		
	$data = $options['widgetOptions']['data'];
        $fieldData = [[$this, 'radioButtonList'], [$model, $attribute, $data, $widgetOptions]];	
	
	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function switchGroup($model, $attribute, $options = [])
    {
	return $this->widgetGroupInternal('bootstrap.widgets.TbSwitch', $model, $attribute, $options);
    }

    public function datePickerGroup($model, $attribute, $options = [])
    {
	return $this->widgetGroupInternal('bootstrap.widgets.TbDatePicker', $model, $attribute, $options);
    }

    public function dateRangeGroup($model, $attribute, $options = [])
    {
	return $this->widgetGroupInternal('bootstrap.widgets.TbDateRangePicker', $model, $attribute, $options);
    }

    public function timePickerGroup($model, $attribute, $options = [])
    {
	return $this->widgetGroupInternal('bootstrap.widgets.TbTimePicker', $model, $attribute, $options);
    }

    public function dateTimePickerGroup($model, $attribute, $options = [])
    {
	return $this->widgetGroupInternal('bootstrap.widgets.TbDateTimePicker', $model, $attribute, $options);
    }

    public function select2Group($model, $attribute, $options = [])
    {
	return $this->widgetGroupInternal('bootstrap.widgets.TbSelect2', $model, $attribute, $options);
    }

    public function redactorGroup($model, $attribute, $options = []) {
	return $this->widgetGroupInternal('bootstrap.widgets.TbRedactorJs', $model, $attribute, $options);
    }

    public function typeAheadGroup($model, $attribute, $options = [])
    {
	return $this->widgetGroupInternal('bootstrap.widgets.TbTypeahead', $model, $attribute, $options);
    }

    public function maskedTextFieldGroup($model, $attribute, $options = [])
    {
	return $this->widgetGroupInternal('CMaskedTextField', $model, $attribute, $options);
    }

    public function captchaGroup($model, $attribute, $htmlOptions = array(), $options = [])
    {
	$this->initOptions($options);
	$widgetOptions = $options['widgetOptions'];
		
	$this->addCssClass($widgetOptions['htmlOptions'], 'form-control');

	$fieldData = $this->textField($model, $attribute, $widgetOptions['htmlOptions']);
	unset($widgetOptions['htmlOptions']);
	$fieldData .= '<div class="captcha">' . $this->owner->widget('CCaptcha', $widgetOptions, true) . '</div>';

	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function customFieldGroup($fieldData, $model, $attribute, $options = [])
    {
	$this->initOptions($options);
	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    public function widgetGroup($className, $model, $attribute, $options = [])
    {
	$this->initOptions($options);
	$widgetOptions = isset($options['widgetOptions']) ? $options['widgetOptions'] : null;
		
	$fieldData = [[$this->owner, 'widget'], [$className, $widgetOptions, true]];

	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    protected function widgetGroupInternal($className, &$model, &$attribute, &$options)
    {
	$this->initOptions($options);
	$widgetOptions = $options['widgetOptions'];
	$widgetOptions['model'] = $model;
	$widgetOptions['attribute'] = $attribute;
		
	$this->addCssClass($widgetOptions['htmlOptions'], 'form-control');
	$fieldData = [[$this->owner, 'widget'], [$className, $widgetOptions, true]];

	return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

    protected function customFieldGroupInternal(&$fieldData, &$model, &$attribute, &$options)
    {
	$this->setDefaultPlaceholder($fieldData);

	ob_start();
	switch($this->type) {
            case self::TYPE_VERTICAL:
                $this->verticalGroup($fieldData, $model, $attribute, $options);
		break;
            case self::TYPE_INLINE:
		$this->inlineGroup($fieldData, $model, $attribute, $options);
		break;
            default:
		throw new CException('Invalid form type');
	}

	return ob_get_clean();
    }

    protected function setDefaultPlaceholder(&$fieldData)
    {
	if(!is_array($fieldData) || empty($fieldData[0][1]) | !is_array($fieldData[1]))
            return;
			
	$model = $fieldData[1][0];
	if(!$model instanceof CModel)
            return;
		
	$attribute = $fieldData[1][1];
	if(!empty($fieldData[1][3]) && is_array($fieldData[1][3])) {
            $htmlOptions = &$fieldData[1][3];
	} else {
            $htmlOptions = &$fieldData[1][2];
	}
	
        if (!isset($htmlOptions['placeholder'])) {
            $htmlOptions['placeholder'] = !$this->withOutPlaceholder ? $model->getAttributeLabel($attribute) : false;
	}
    }

    protected function verticalGroup(&$fieldData, &$model, &$attribute, &$options)
    {
	$groupOptions = isset($options['groupOptions']) ? $options['groupOptions']: [];
        
        self::addCssClass($groupOptions, $this->floating ? 'form-label-group' : 'form-group');
        if(!empty($options['prepend']) || !empty($options['append'])) {
            self::addCssClass($groupOptions, 'input-group');
        }
        	
	if ($model->hasErrors($attribute))
            self::addCssClass($groupOptions, 'has-warning');
		
	echo CHtml::openTag('div', $groupOptions);
		
	if(!empty($options['prepend']) || !empty($options['append'])) {
            $this->renderAddOnBegin($options['prepend'], $options['append'], $options['prependOptions']);
	}
		
	if(is_array($fieldData)) {
            echo call_user_func_array($fieldData[0], $fieldData[1]);
	} else
            echo $fieldData;
	
	if(isset($options['label'])) {
            if(!empty($options['label'])) {
		echo CHtml::label($options['label'], CHtml::activeId($model, $attribute), $options['labelOptions']);
            }
	} else
            echo $this->labelEx($model, $attribute, $options['labelOptions']);
        
        
	if(!empty($options['prepend']) || !empty($options['append'])) {
            $this->renderAddOnEnd($options['append'], $options['appendOptions']);
	}
		
	if($this->showErrors && $options['errorOptions'] !== false) {
            echo $this->error($model, $attribute, $options['errorOptions'], $options['enableAjaxValidation'], $options['enableClientValidation']);
	}
	
	if(isset($options['hint'])) {
            self::addCssClass($options['hintOptions'], $this->hintCssClass);
            echo CHtml::tag($this->hintTag, $options['hintOptions'], $options['hint']);
	}
		
	echo '</div>';
    }

    protected function inlineGroup(&$fieldData, &$model, &$attribute, &$options) 
    {
        echo '<div class="form-group">';
        if (!empty($options['prepend']) || !empty($options['append']))
            $this->renderAddOnBegin($options['prepend'], $options['append'], $options['prependOptions']);

	if(is_array($fieldData)) {
            echo call_user_func_array($fieldData[0], $fieldData[1]);
	} else
            echo $fieldData;
	
        if(!empty($options['prepend']) || !empty($options['append']))
            $this->renderAddOnEnd($options['append'], $options['appendOptions']);

        if ($this->showErrors && $options['errorOptions'] !== false) {
            echo $this->error($model, $attribute, $options['errorOptions'], $options['enableAjaxValidation'], $options['enableClientValidation']);
        }

        echo "</div>\r\n"; 
    }

    protected function renderAddOnBegin($prependText, $appendText, $prependOptions)
    {
	$wrapperCssClass = [];
	if(!empty($prependText))
            $wrapperCssClass[] = $this->prependCssClass;
	if(!empty($appendText))
            $wrapperCssClass[] = $this->appendCssClass;

	echo CHtml::tag($this->addOnWrapperTag, ['class' => implode(' ', $wrapperCssClass)], false, false);
	if(!empty($prependText)) {
            if(isset($prependOptions['isRaw']) && $prependOptions['isRaw'])
		echo $prependText;
            else {
		self::addCssClass($prependOptions, $this->addOnCssClass);
		echo CHtml::tag($this->addOnTag, $prependOptions, $prependText);
            }
	}
        echo CHtml::closeTag('div');
    }

    protected function renderAddOnEnd($appendText, $appendOptions)
    {
	if(!empty($appendText)) {
            if(isset($appendOptions['isRaw']) && $appendOptions['isRaw'])
		echo $appendText;
            else {
		self::addCssClass($appendOptions, $this->addOnCssClass);
		echo CHtml::tag($this->addOnTag, $appendOptions, $appendText);
            }
	}

	echo CHtml::closeTag($this->addOnWrapperTag);
    }

    protected function initOptions(&$options, $initData = false)
    {
	if(!isset($options['groupOptions']))
            $options['groupOptions'] = [];
		
	if(!isset($options['labelOptions']))
            $options['labelOptions'] = [];
		
	if(!isset($options['widgetOptions']))
            $options['widgetOptions'] = [];
		
	if(!isset($options['widgetOptions']['htmlOptions']))
            $options['widgetOptions']['htmlOptions'] = [];
		
	if($initData && !isset($options['widgetOptions']['data']))
            $options['widgetOptions']['data'] = [];
		
	if(!isset($options['errorOptions']))
            $options['errorOptions'] = [];
	
	if(!isset($options['prependOptions']))
            $options['prependOptions'] = [];
	
	if(!isset($options['prepend']))
            $options['prepend'] = null;
	
	if(!isset($options['appendOptions']))
            $options['appendOptions'] = [];
	
	if(!isset($options['append']))
            $options['append'] = null;
	
	if(!isset($options['enableAjaxValidation']))
            $options['enableAjaxValidation'] = true;
	
	if(!isset($options['enableClientValidation']))
            $options['enableClientValidation'] = true;
    }

    protected static function addCssClass(&$htmlOptions, $class)
    {
	if(empty($class))
            return;

	if(isset($htmlOptions['class'])){
            $htmlOptions['class'] .= ' ' . $class;
	} else {
            $htmlOptions['class'] = $class;
	}
    }
}
