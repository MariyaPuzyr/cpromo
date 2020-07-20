<?php

Yii::import('bootstrap.widgets.TbListView');
class TbThumbnails extends TbListView
{
    public $flex = false;
    public $fluid = false;
    
    public function renderItems()
    {
	echo CHtml::openTag($this->itemsTagName, ['class' => $this->itemsCssClass]) . "\n";
	$data = $this->dataProvider->getData();

	if(!empty($data)) {
            $classes[] = $this->fluid ? 'card-columns' : 'card-columns';
            $classes[] = $this->flex ? 'd-flex justify-content-between' : '';
            
            echo CHtml::openTag('div', ['class' => implode(' ', $classes)]);
                $owner = $this->getOwner();
                $render = $owner instanceof CController ? 'renderPartial' : 'render';
            
                foreach ($data as $i => $item) {
                    $data = $this->viewData;
                    $data['index'] = $i;
                    $data['data'] = $item;
                    $data['widget'] = $this;
                    $owner->$render($this->itemView, $data);
                }

            echo '</div>';
	} else
            $this->renderEmptyText();

	echo CHtml::closeTag($this->itemsTagName);
    }
}
