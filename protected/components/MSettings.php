<?php

class MSettings extends CComponent
{
    protected $data = [];
    
    public function init()
    {
	$system = Settings::model()->find();
        
        if($system) {
            foreach($system as $key => $val)
                if(!isset($this->data['system'][$key]))
                    $this->data['system'][$key] = $val;
        }
    }

    public function get($category, $key = false)
    {
	if(!isset($this->data[$category]))
            return false;

	return $key ? $this->data[$category][$key] : $this->data[$category];
    }
}
