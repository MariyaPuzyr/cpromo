<?php

class MApp extends CWebApplication
{
    private $_theme = null;
    
    public function __construct($config = null)
    {
        parent::__construct($config);
    }
    
    public function getTheme()
    {
	if($this->_theme === null)
            $this->_theme = $this->getThemeManager()->getTheme('default');
	return $this->_theme;
    }
}

