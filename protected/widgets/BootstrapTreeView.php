<?php

class BootstrapTreeView extends CTreeView
{
    public $checkedIcon = 'fas fa-check';
    public $collapseIcon = 'fas fa-minus';
    public $emptyIcon = '';
    public $expandIcon = 'fas fa-plus';
    public $nodeIcon = 'fas fa-stop';
    public $backColor = '#FFFFFF';
    public $color = '#000000';
    public $enableLinks = true;
    public $showTags = true;
    public $showCheckboxes = false;

    public $selectable = "leaves";
    public $multiSelect = false;
    public $levels = 2;
    
    public function init()
    {
        $options = array_merge($this->options, [
            'data' => $this->prepareData($this->data),
            'checkedIcon' => $this->checkedIcon,
            'collapseIcon' => $this->collapseIcon,
            'emptyIcon' => $this->emptyIcon,
            'expandIcon' => $this->expandIcon,
            'nodeIcon' => false,
            'backColor' => $this->backColor,
            'color' => $this->color,
            'enableLinks' => $this->enableLinks,
            'showTags' => $this->showTags,
            'showCheckbox' => $this->showCheckboxes,
            'levels' => $this->levels,
            'multiSelect' => $this->multiSelect

        ]);

        if(isset($this->htmlOptions['id'])) {
            $id = $this->htmlOptions['id'];
        } else {
            $id = $this->htmlOptions['id'] = $this->getId();
        }

        $json = json_encode($options, JSON_PRETTY_PRINT);
        Yii::app()->clientScript->registerScript("bstreeview#$id", "$('#$id').treeview($json);", CClientScript::POS_END);
        echo CHtml::openTag('div', $this->htmlOptions);

    }

    protected function prepareData($data, array $parent = []) {
        $result = [];
        foreach($data as $node) {
            $resultNode = [
                'text' => $node['text'],
                'nodes' => isset($node['children']) ? $this->prepareData($node['children']) : null,
                'icon' => isset($node['icon']) ? "fas fa-{$node['icon']}" : null,
//                'color' => null,
//                'backColor' => isset($node['active']) && $node['active'] ? '#FF0000' : null,
                'href' => isset($node['url']) ? CHtml::normalizeUrl($node['url']) : null,
                'selectable' => $this->selectable == "all" || ($this->selectable == "leaves" && !isset($node['children'])),
                'state' => [
                    'selected' => isset($node['active']) && $node['active'] ? $node['active'] : false,
                ],
                'tags' => isset($node['tags']) ? $node['tags'] : null
            ];

            if (isset($node['expanded'])) {
                $resultNode['state']['expanded'] = $node['expanded'];
            }
            // Add extra data.
            if (isset($node['data'])) {
                $resultNode['data'] = $node['data'];
            }
            $result[] = $resultNode;
        }
        return $result;
    }

    public function run()
    {
        echo CHtml::closeTag('div');

    }
}
