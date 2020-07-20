<?php

class TbPager extends CLinkPager 
{
    const ALIGNMENT_CENTER = 'centered';
    const ALIGNMENT_RIGHT = 'right';

    public $containerTag = 'div';
    public $containerHtmlOptions = [];
    public $alignment = self::ALIGNMENT_RIGHT;
    public $header = '';
    public $cssFile = false;
    public $displayFirstAndLast = false;

    public function init()
    {
	if($this->nextPageLabel === null) {
            $this->nextPageLabel = '&raquo;';
	}

	if($this->prevPageLabel === null) {
            $this->prevPageLabel = '&laquo;';
	}

	$classes = ['pagination justify-content-center'];

	$style = '';
	$containerStyle = '';
		
	$validAlignments = [self::ALIGNMENT_CENTER, self::ALIGNMENT_RIGHT];

	if(in_array($this->alignment, $validAlignments)) {
            if($this->alignment == self::ALIGNMENT_RIGHT)
		$classes[] = '';
			
            if($this->alignment == self::ALIGNMENT_CENTER) {
		$containerStyle = 'text-align: center;';
            }
	}

	if(!empty($classes)) {
            $classes = implode(' ', $classes);
            if(isset($this->htmlOptions['class'])) {
		$this->htmlOptions['class'] = ' ' . $classes;
            } else {
		$this->htmlOptions['class'] = $classes;
            }
	}
		
	if(!empty($style)) {
            if(isset($this->htmlOptions['style']) && !empty($this->htmlOptions['style']))
		$this->htmlOptions['style'] .= ' '.$style;
            else 
		$this->htmlOptions['style'] = $style;
	}
		
	if(!empty($containerStyle)) {
            if(isset($this->containerHtmlOptions['style']) && !empty($this->containerHtmlOptions['style']))
		$this->containerHtmlOptions['style'] .= ' '.$containerStyle;
            else
		$this->containerHtmlOptions['style'] = $containerStyle;
        }

	parent::init();
    }
	
    public function run()
    {
	$this->registerClientScript();
	$buttons = $this->createPageButtons();
	if(empty($buttons))
            return;
	
        echo CHtml::openTag($this->containerTag, $this->containerHtmlOptions);
	echo $this->header;
	echo CHtml::tag('ul',$this->htmlOptions,implode("\n",$buttons));
	echo '<div style="clear: both;"></div>';
	echo $this->footer;
	echo CHtml::closeTag($this->containerTag);
    }

    protected function createPageButtons()
    {
	if(($pageCount = $this->getPageCount()) <= 1) {
            return [];
	}

	list ($beginPage, $endPage) = $this->getPageRange();
        $currentPage = $this->getCurrentPage(false);
	$buttons = [];

	if($this->displayFirstAndLast) {
            $buttons[] = $this->createPageButton($this->firstPageLabel, 0, 'first', $currentPage <= 0, false);
	}

	if(($page = $currentPage - 1) < 0) {
            $page = 0;
	}

	$buttons[] = $this->createPageButton($this->prevPageLabel, $page, 'previous', $currentPage <= 0, false);

	for($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->createPageButton($i + 1, $i, '', false, $i == $currentPage);
	}

	if(($page = $currentPage + 1) >= $pageCount - 1) {
            $page = $pageCount - 1;
	}

	$buttons[] = $this->createPageButton(
            $this->nextPageLabel,
            $page,
            'next',
            $currentPage >= ($pageCount - 1),
            false
	);

	if($this->displayFirstAndLast) {
            $buttons[] = $this->createPageButton(
		$this->lastPageLabel,
		$pageCount - 1,
		'last',
		$currentPage >= ($pageCount - 1),
		false
            );
	}

	return $buttons;
    }

    protected function createPageButton($label, $page, $class, $hidden, $selected)
    {
	if($hidden || $selected) {
            $class .= ' ' . ($hidden ? 'disabled' : 'active');
	}

	return CHtml::tag('li', ['class' => 'page-item '.$class], CHtml::link($label, $this->createPageUrl($page), ['class' => 'page-link']));
    }
}
