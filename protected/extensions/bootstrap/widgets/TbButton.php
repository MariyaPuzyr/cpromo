<?php

Yii::import('bootstrap.widgets.TbWidget');
class TbButton extends TbWidget
{
    const CLTYPE_OUT = 'outline';
    const CLTYPE_BCK = 'alt';
    
    const SIZE_LARGE = 'large';
    const SIZE_DEFAULT = 'default';
    const SIZE_SMALL = 'small';
    const SIZE_EXTRA_SMALL = 'extra_small';
    
    protected static $typeClasses = [
	self::CLTYPE_OUT => 'outline-',
	self::CLTYPE_BCK => '',
    ];
    
    protected static $sizeClasses = [
	self::SIZE_LARGE => 'btn-lg',
	self::SIZE_DEFAULT => '',
	self::SIZE_SMALL => 'btn-sm',
	self::SIZE_EXTRA_SMALL => 'btn-xs',
    ];
    
    const BUTTON_LINK = 'link';
    const BUTTON_BUTTON = 'button';
    const BUTTON_SUBMIT = 'submit';
    const BUTTON_SUBMITLINK = 'submitLink';
    const BUTTON_RESET = 'reset';
    const BUTTON_AJAXLINK = 'ajaxLink';
    const BUTTON_AJAXBUTTON = 'ajaxButton';
    const BUTTON_AJAXSUBMIT = 'ajaxSubmit';
    const BUTTON_INPUTBUTTON = 'inputButton';
    const BUTTON_INPUTSUBMIT = 'inputSubmit';
    const BUTTON_TOGGLE_RADIO = 'radio';
    const BUTTON_TOGGLE_CHECKBOX = 'checkbox';
    
    public $buttonType = self::BUTTON_BUTTON;
    public $typeClass = self::CLTYPE_BCK;
    public $size = self::SIZE_DEFAULT;
    
    public $icon;
    public $label;
    public $url;
    public $block = false;
    public $active = false;
    public $disabled = false;
    public $encodeLabel = true;
    public $toggle;
    public $loadingText;
    public $completeText;
    public $items;
    public $htmlOptions = [];
    public $ajaxOptions = [];
    public $dropdownOptions = [];
    public $visible = true;
    public $tooltip = false;
    public $tooltipOptions = [];
    public $labelBR = false;
    public $photo = false;
    public $photoClass = '';
    public $iconAfterLabel = false;
    
    public function init()
    {
        if(false === $this->visible)
            return;
        
	$classes = ['btn'];
        
        if ($this->isValidContext()) {
            $classes[] = 'btn-' . self::$typeClasses[$this->typeClass] . $this->getContextClass();
	}
        
        $validSizes = [
            self::SIZE_LARGE, 
            self::SIZE_DEFAULT,
            self::SIZE_SMALL, 
            self::SIZE_EXTRA_SMALL
	];
        
	if(isset($this->size) && in_array($this->size, $validSizes))
            $classes[] = self::$sizeClasses[$this->size];
	
	if($this->block)
            $classes[] = 'btn-block';
	
	if($this->active) {
            $classes[] = 'active';
            $htmlOptions['aria-pressed'] = true;
        }

	if($this->disabled) {
            $disableTypes = [
		self::BUTTON_BUTTON,
		self::BUTTON_SUBMIT,
		self::BUTTON_RESET,
		self::BUTTON_AJAXBUTTON,
		self::BUTTON_AJAXSUBMIT,
		self::BUTTON_INPUTBUTTON,
		self::BUTTON_INPUTSUBMIT
            ];

            if(in_array($this->buttonType, $disableTypes)) {
		$this->htmlOptions['disabled'] = 'disabled';
            }

            $classes[] = 'disabled';
	}

	if(!isset($this->url) && isset($this->htmlOptions['href'])) {
            $this->url = $this->htmlOptions['href'];
            unset($this->htmlOptions['href']);
	}

	if($this->encodeLabel)
            $this->label = CHtml::encode($this->label);
	
	if($this->hasDropdown()) {
            if (!isset($this->url)) {
		$this->url = '#';
            }

            $classes[] = 'dropdown-toggle';
            $this->label .= ' <span class="caret"></span>';
            $this->htmlOptions['data-toggle'] = 'dropdown';
	}

	if(!empty($classes)) {
            $classes = implode(' ', $classes);
            if (isset($this->htmlOptions['class'])) {
		$this->htmlOptions['class'] .= ' ' . $classes;
            } else {
		$this->htmlOptions['class'] = $classes;
            }
	}
        if(!$this->photo) {
            if(isset($this->icon))
                if(!$this->iconAfterLabel)
                    $this->label = !$this->labelBR ? '<i class="' . $this->icon . '"></i> ' . $this->label : '<i class="' . $this->icon . '"></i><br class="mb-3"/> ' . $this->label;
                else
                    $this->label = $this->label.' <i class="'.$this->icon.'" style="vertical-align: text-bottom;"></i>';
        } else 
            $this->label = CHtml::image($this->photo, '', ['class' => $this->photoClass]);
	
	if(!isset($this->htmlOptions['id']))
            $this->htmlOptions['id'] = $this->getId();
	
	if(isset($this->toggle))
            $this->htmlOptions['data-toggle'] = 'button';
	
	if(isset($this->loadingText))
            $this->htmlOptions['data-loading-text'] = $this->loadingText;

	if(isset($this->completeText))
            $this->htmlOptions['data-complete-text'] = $this->completeText;
	

        if (!empty($this->tooltip) && !$this->toggle) {
            if (!is_array($this->tooltipOptions)) {
                $this->tooltipOptions = [];
            }

            $this->htmlOptions['data-toggle'] = 'tooltip';
            foreach ($this->tooltipOptions as $key => $value) {
                $this->htmlOptions['data-' . $key] = $value;
            }

            if (isset($this->htmlOptions['data-delay']) && is_array($this->htmlOptions['data-delay'])) {
                $this->htmlOptions['data-delay'] = CJSON::encode($this->htmlOptions['data-delay']);
            }
        }
    }
    
    public function run()
    {
	if(false === $this->visible)
            return;
		
	if($this->hasDropdown()) {
            echo $this->createButton();
		
            $this->controller->widget('bs4.widgets.Dropdown', [
                'encodeLabel' => $this->encodeLabel,
		'items' => $this->items,
		'htmlOptions' => $this->dropdownOptions,
		'id' => isset($this->dropdownOptions['id']) ? $this->dropdownOptions['id'] : null,
            ]);
	} else {
            echo $this->createButton();
	}
    }

    protected function createButton()
    {
	switch ($this->buttonType) {
            case self::BUTTON_LINK:
		return CHtml::link($this->label, $this->url, $this->htmlOptions);

            case self::BUTTON_SUBMIT:
		$this->htmlOptions['type'] = 'submit';
		return CHtml::htmlButton($this->label, $this->htmlOptions);

            case self::BUTTON_RESET:
		$this->htmlOptions['type'] = 'reset';
		return CHtml::htmlButton($this->label, $this->htmlOptions);

            case self::BUTTON_SUBMITLINK:
		return CHtml::linkButton($this->label, $this->htmlOptions);

            case self::BUTTON_AJAXLINK:
		return CHtml::ajaxLink($this->label, $this->url, $this->ajaxOptions, $this->htmlOptions);

            case self::BUTTON_AJAXBUTTON:
		$this->ajaxOptions['url'] = $this->url;
            	$this->htmlOptions['ajax'] = $this->ajaxOptions;
		return CHtml::htmlButton($this->label, $this->htmlOptions);

            case self::BUTTON_AJAXSUBMIT:
		$this->ajaxOptions['type'] = isset($this->ajaxOptions['type']) ? $this->ajaxOptions['type'] : 'POST';
		$this->ajaxOptions['url'] = $this->url;
		$this->htmlOptions['type'] = 'submit';
		$this->htmlOptions['ajax'] = $this->ajaxOptions;
		return CHtml::htmlButton($this->label, $this->htmlOptions);

            case self::BUTTON_INPUTBUTTON:
		return CHtml::button($this->label, $this->htmlOptions);

            case self::BUTTON_INPUTSUBMIT:
		$this->htmlOptions['type'] = 'submit';
		return CHtml::button($this->label, $this->htmlOptions);
				
            case self::BUTTON_TOGGLE_RADIO:
		return $this->createToggleButton('radio');
				
            case self::BUTTON_TOGGLE_CHECKBOX:
		return $this->createToggleButton('checkbox');
				
            default:
                case self::BUTTON_BUTTON:
                    return CHtml::htmlButton($this->label, $this->htmlOptions);
	}
    }
	
    protected function createToggleButton($toggleType)
    {
	$html = '';
	$html .= CHtml::openTag('label', $this->htmlOptions);
	$html .= "<input type='{$toggleType}' name='{$this->id}_options' id='option_{$this->id}'> {$this->label}";
	$html .= CHtml::closeTag('label');
		
	return $html;
    }

    protected function hasDropdown() {
	return isset($this->items) && !empty($this->items);
    }
}
