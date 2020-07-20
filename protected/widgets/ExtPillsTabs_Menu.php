<?php

class ExtPillsTabs_Menu extends CWidget
{
    public $encodeLabel;
    public $items = [];
    public $activeCssClass = 'active';
    public $itemCssClass = '';
    public $htmlOptions = [];
    
    public function init() {}
    
    protected function renderMenu($items) {
	$n = count($items);
	if($n > 0) {
            echo CHtml::openTag('div', ['class' => 'col-md-3 col-sm-3']) . "\n";
                echo CHtml::openTag('div', ['id' => 'v-pills-home-tab', 'class' => 'nav flex-column nav-pills relationTabs', 'role' => 'tablist', 'aria-orientation' => 'vertical']) . "\n";
            
                $count = 0;
                foreach($items as $item) {
                    $count++;

                    $options = isset($item['itemOptions']) ? $item['itemOptions'] : [];
                    $classes = isset($item['head']) ? ['nav-main-heading'] : [];

                    if($item['active'] && $this->activeCssClass != '')
                        $classes[] = $this->activeCssClass;

                    if($this->itemCssClass !== null)
                        $classes[] = $this->itemCssClass;

                    if(isset($item['disabled']))
                        $classes[] = 'disabled';

                    if(!empty($classes)) {
                        $classes = implode(' ', $classes);
                        if(!empty($options['class'])) {
                            $options['class'] .= ' ' . $classes;
                        } else {
                            $options['class'] = $classes;
                        }
                    }

                    $menu = $this->renderMenuItem($item);
                    echo $menu;
                }
                echo "</div>\n";
            echo "</div>\n";
	}
    }
    
    protected function renderMenuItem($item) {
	$defLinkClass = [];
        $defLinkClass[] = 'nav-link';
        
        if($item['active'] && $this->activeCssClass != '')
            $defLinkClass[] = $this->activeCssClass;
        
        $item['linkOptions'] = ['class' => implode(' ', $defLinkClass), 'id' => trim($item['url'], '#').'-tab', 'data-toggle' => 'pill', 'role' => 'tab', 'aria-controls' => trim($item['url'], '#'), 'aria-selected' => false];
	
	if(isset($item['url']))
            return CHtml::link($item['label'], $item['url'], $item['linkOptions']);
        else
            return $item['label'];
    }
        
    public function run()
    {
	$this->renderMenu($this->items);
    }
}
