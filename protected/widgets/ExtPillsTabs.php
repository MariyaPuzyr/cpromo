<?php
class ExtPillsTabs extends CWidget
{
    public $type = 'pills';
    public $tabs = [];
    public $events = [];
    public $encodeLabel = true;
    public $htmlOptions = [];
    public $tabContentHtmlOptions = [];
    public $tabMenuHtmlOptions = [];

    public function init() {
	$classes = ['row mt-10'];
        if(!empty($classes)) {
            $classes = implode(' ', $classes);
            if(isset($this->htmlOptions['class']))
		$this->htmlOptions['class'] .= ' ' . $classes;
            else
		$this->htmlOptions['class'] = $classes;
	}

	$this->tabContentHtmlOptions['class'] = 'tab-content';
        $this->tabContentHtmlOptions['id'] = 'v-pills-tabContent';
    }

    public function run()
    {
	$id = $this->id;
    	$content = [];
	$items = $this->normalizeTabs($this->tabs, $content);
        
        ob_start();
        $this->controller->widget('ExtPillsTabs_Menu', [
            'encodeLabel' => $this->encodeLabel,
            'htmlOptions' => $this->tabMenuHtmlOptions,
            'items' => $items,
        ]);
        $tabs = ob_get_clean();

	ob_start();
        echo CHtml::openTag('div', ['class' => 'col-md-9 col-sm-9']);
            echo CHtml::openTag('div', $this->tabContentHtmlOptions);
            echo implode('', $content);
            echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');
        $content = ob_get_clean();

	echo CHtml::openTag('div', $this->htmlOptions);
	echo $tabs . $content;
	echo CHtml::closeTag('div');

	$cs = Yii::app()->getClientScript();
	$cs->registerScript(__CLASS__ . '#' . $id, "jQuery('#{$id}').tab('show');");

	foreach ($this->events as $name => $handler) {
            $handler = CJavaScript::encode($handler);
            $cs->registerScript(__CLASS__ . '#' . $id . '_' . $name, "jQuery('#{$id}').on('{$name}', {$handler});");
	}
    }

    protected function normalizeTabs($tabs, &$panes, &$i = 0)
    {
	$id = $this->getId();
	$items = [];

	foreach($tabs as $tab) {
            $item = $tab;

            if(isset($item['visible']) && $item['visible'] === false)
                continue;
            
            if(!isset($item['itemOptions']))
		$item['itemOptions'] = [];
	
            if(!isset($item['url']))
		$item['linkOptions']['data-toggle'] = 'tab';
	

            if(isset($tab['items']))
		$item['items'] = $this->normalizeTabs($item['items'], $panes, $i);
            else {
		if(!isset($item['id']))
                    $item['id'] = $id . '_tab_' . ($i + 1);
            }

            if(!isset($item['url']))
		$item['url'] = '#' . $item['id'];

            if(!isset($item['content']))
                $item['content'] = '';

            $content = $item['content'];
            unset($item['content']);

            if(!isset($item['paneOptions']))
		$item['paneOptions'] = [];
		

            $paneOptions = $item['paneOptions'];
            unset($item['paneOptions']);

            $paneOptions['id'] = $item['id'];

            $classes = ['tab-pane fade'];

            if(isset($item['active']) && $item['active'])
		$classes[] = 'active show';
		

            $classes = implode(' ', $classes);
            if(isset($paneOptions['class']))
		$paneOptions['class'] .= ' ' . $classes;
            else
		$paneOptions['class'] = $classes;
		

            $panes[] = CHtml::tag('div', $paneOptions, $content);
            $i++;
	

            $items[] = $item;
        }
	return $items;
    }
}