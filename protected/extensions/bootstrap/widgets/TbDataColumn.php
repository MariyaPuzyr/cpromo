<?php

Yii::import('zii.widgets.grid.CDataColumn');
class TbDataColumn extends CDataColumn
{
    public $filterInputOptions;

    protected function renderHeaderCellContent()
    {
	if($this->grid->enableSorting && $this->sortable && $this->name !== null) {
            $sort = $this->grid->dataProvider->getSort();
            $label = isset($this->header) ? $this->header : $sort->resolveLabel($this->name);

            $bootstrap = Bootstrap::getBootstrap();

            if($sort->resolveAttribute($this->name) !== false)
		$label .= ' <span class="caret"></span>';
		
            echo $sort->link($this->name, $label, ['class' => 'sort-link']);
	} else {
            if($this->name !== null && $this->header === null) {
		if($this->grid->dataProvider instanceof CActiveDataProvider) {
                    echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
		} else {
                    echo CHtml::encode($this->name);
		}
            } else {
		parent::renderHeaderCellContent();
            }
	}
    }

    public function renderFilterCell()
    {
	echo CHtml::openTag('td', $this->filterHtmlOptions);
	echo '<div class="filter-container">';
	$this->renderFilterCellContent();
	echo '</div>';
	echo CHtml::closeTag('td');
    }

    protected function renderFilterCellContent()
    {
	if(is_string($this->filter)) {
            echo $this->filter;
	} elseif ($this->filter !== false && $this->grid->filter !== null && $this->name !== null && strpos($this->name, '.') === false) {
            if($this->filterInputOptions) {
		$filterInputOptions = $this->filterInputOptions;
		if(empty($filterInputOptions['id'])) {
                    $filterInputOptions['id'] = false;
		}
            } else {
            $filterInputOptions = array();
            }
			
            if(!isset($filterInputOptions['class']) || empty($filterInputOptions['class']))
		$filterInputOptions['class'] = 'form-control';
            else
		$filterInputOptions['class'] .= ' form-control';
			
            if(is_array($this->filter)) {
		if(!isset($filterInputOptions['prompt'])) {
                    $filterInputOptions['prompt'] = '';
                }
            
                echo CHtml::activeDropDownList($this->grid->filter, $this->name, $this->filter, $filterInputOptions);
            } elseif($this->filter === null) {
		echo CHtml::activeTextField($this->grid->filter, $this->name, $filterInputOptions);
            }
	} else {
            parent::renderFilterCellContent();
        }
    }
}