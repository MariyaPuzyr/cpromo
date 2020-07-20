<?php

class AdminModule extends MBaseModule
{
    public $defaultController = 'dashboard';
    
    public function init()
    {
    	$this->setImport([
            'admin.components.*',
            'admin.controllers.*',
            'admin.models.*',
	]);
    }
}