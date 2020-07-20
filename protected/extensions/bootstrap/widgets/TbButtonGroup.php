<?php

Yii::import('bootstrap.widgets.TbButton');
class TbButtonGroup extends CWidget
{
    const TOGGLE_CHECKBOX = 'checkbox';
    const TOGGLE_RADIO = 'radio';

    public $buttonType = TbButton::BUTTON_BUTTON;
    public $context = TbButton::CTX_LIGHT;
    public $justified = false;
    public $size;
    public $encodeLabel = true;
    public $htmlOptions = [];
    public $buttons = [];
    public $toggle;
    public $stacked = false;
    public $dropup = false;
    public $disabled = false;

    public function init()
    {
	$classes = [];
		
	if($this->stacked === true) {
            $classes[] = 'btn-group-vertical';
	} else {
            $classes[] = 'btn-group';
	}

	if($this->dropup === true) {
            $classes[] = 'dropup';
	}
		
	if($this->justified === true) {
            $classes[] = 'btn-group-justified';
	}

	if(!empty($classes)) {
            $classes = implode(' ', $classes);
            if(isset($this->htmlOptions['class'])) {
		$this->htmlOptions['class'] .= ' ' . $classes;
            } else {
		$this->htmlOptions['class'] = $classes;
            }
	}

	$validToggles = [self::TOGGLE_CHECKBOX, self::TOGGLE_RADIO];

	if (isset($this->toggle) && in_array($this->toggle, $validToggles)) {
            $this->htmlOptions['data-toggle'] = 'buttons';
	}
    }

    public function run()
    {
	echo CHtml::openTag('div', $this->htmlOptions);

	foreach($this->buttons as $button) {
            if(isset($button['visible']) && $button['visible'] === false) {
		continue;
            }
			
            $validToggles = [self::TOGGLE_CHECKBOX, self::TOGGLE_RADIO];
            if(isset($this->toggle) && in_array($this->toggle, $validToggles)) {
		$this->buttonType = $this->toggle;
            }
			
            $justifiedNotLink = $this->justified && $this->buttonType !== TbButton::BUTTON_LINK;
            if($justifiedNotLink)
		echo '<div class="btn-group" role="group">';
			
            $this->controller->widget('booster.widgets.TbButton',[
		'buttonType' => isset($button['buttonType']) ? $button['buttonType'] : $this->buttonType,
		'context' => isset($button['context']) ? $button['context'] : $this->context,
		'size' => $this->size, // all buttons in a group cannot vary in size
		'icon' => isset($button['icon']) ? $button['icon'] : null,
		'label' => isset($button['label']) ? $button['label'] : null,
		'url' => isset($button['url']) ? $button['url'] : null,
		'active' => isset($button['active']) ? $button['active'] : false,
		'disabled' => isset($button['disabled']) ? $button['disabled'] : false,
		'items' => isset($button['items']) ? $button['items'] : array(),
		'ajaxOptions' => isset($button['ajaxOptions']) ? $button['ajaxOptions'] : array(),
		'htmlOptions' => isset($button['htmlOptions']) ? $button['htmlOptions'] : array(),
		'dropdownOptions' => isset($button['dropdownOptions']) ? $button['dropdownOptions'] : array(),
		'encodeLabel' => isset($button['encodeLabel']) ? $button['encodeLabel'] : $this->encodeLabel,
                'tooltip' => isset($button['tooltip']) ? $button['tooltip'] : false,
                'tooltipOptions' => isset($button['tooltipOptions']) ? $button['tooltipOptions'] : array(),
            ]);
			
            if($justifiedNotLink)
		echo '</div>';
	}
	
        echo '</div>';
    }
}
