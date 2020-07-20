<?php

class Bootstrap extends CApplicationComponent
{
    public $minify = true;
    public $coreCss = true;
    public $fontawesome = false;
    public $notify = true;
    public $ajaxCssLoad = false;
    public $ajaxJsLoad = false;
    public $forceCopyAssets = false;
    public $packages = [];
    public $cs;
    public $_assetsUrl;
    public $popoverSelector = '[data-toggle=popover]';
    public $tooltipSelector = '[data-toggle=tooltip]';
    
    private static $_instance;

    public function init()
    {
	if ($this->isInConsoleMode())
            return;

        self::setBootstrap($this);

        $this->setRootAliasIfUndefined();
	$this->setAssetsRegistryIfNotDefined();
	$this->includeAssets();

	parent::init();
    }

    protected function isInConsoleMode()
    {
	return Yii::app() instanceof CConsoleApplication || PHP_SAPI == 'cli';
    }

    
    protected function setRootAliasIfUndefined()
    {
	if (Yii::getPathOfAlias('bootstrap') === false)
            Yii::setPathOfAlias('bootstrap', realpath(dirname(__FILE__) . '/..'));
    }

    protected function includeAssets()
    {
	$this->appendUserSuppliedPackagesToOurs();
        $this->addOurPackagesToYii();
	$this->registerPackagesIfEnabled();
	$this->registerJsPackagesIfEnabled();
    }

    protected function appendUserSuppliedPackagesToOurs() {
	$bootstrapPackages = require(Yii::getPathOfAlias('bootstrap.components') . '/packages.php');
	$bootstrapPackages += $this->createBootstrapCssPackage();
        $bootstrapPackages += $this->createSelect2Package();

	$this->packages = CMap::mergeArray(
            $bootstrapPackages,
            $this->packages
	);
    }
    
    protected function addOurPackagesToYii() {
	foreach ($this->packages as $name => $definition) {
            $this->cs->addPackage($name, $definition);
	}
    }
    
    protected function registerPackagesIfEnabled()
    {
	if(!$this->coreCss)
            return;
        
        if (!$this->ajaxCssLoad && Yii::app()->request->isAjaxRequest)
            return;

	$this->registerBootstrapCss();
        
        if($this->fontawesome)
            $this->registerFontAwesome();
    }

    protected function registerJsPackagesIfEnabled()
    {
        if(!$this->ajaxJsLoad && Yii::app()->request->isAjaxRequest)
            return;
        
        $this->registerPackage('bootstrap.js');
        
        if($this->notify)
            $this->registerPackage('notify');
    }

    
    public function registerPackage($name)
    {
	return $this->cs->registerPackage($name);
    }
    
    public function registerAssetCss($name, $media = '')
    {
	$this->cs->registerCssFile($this->getAssetsUrl() . "/css/{$name}", $media);
    }

    public function registerAssetJs($name, $position = CClientScript::POS_END)
    {
	$this->cs->registerScriptFile($this->getAssetsUrl() . "/js/{$name}", $position);
    }
    
    public function getAssetsUrl()
    {
	if(isset($this->_assetsUrl)) {
            return $this->_assetsUrl;
	} else {
            return $this->_assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('bootstrap.assets'), false, -1, $this->forceCopyAssets);
	}
    }
    
    protected function setAssetsRegistryIfNotDefined()
    {
	if (!$this->cs)
            $this->cs = Yii::app()->getClientScript();
    }
    
    public function registerBootstrapCss()
    {
	$this->cs->registerPackage('bootstrap.css');
    }
    
    protected function createBootstrapCssPackage() {
	return [
            'bootstrap.css' => [
                'baseUrl' => $this->getAssetsUrl() . '/bootstrap/',
                'css' => [$this->minify ? 'css/bootstrap.min.css' : 'css/bootstrap.css'],
            ]
        ];
    }

    protected function createSelect2Package()
    {
        $jsFiles = [$this->minify ? 'select2.min.js' : 'select2.js'];
        if(strpos(Yii::app()->language, 'en') !== 0) {
            $locale = 'select2_locale_'. substr(Yii::app()->language, 0, 2). '.js';
            if(@file_exists(Yii::getPathOfAlias('bootstrap.assets.select2') . DIRECTORY_SEPARATOR . $locale )) {
                $jsFiles[] = $locale;
            } else {
                $locale = 'select2_locale_'. Yii::app()->language . '.js';
                if(@file_exists(Yii::getPathOfAlias('bootstrap.assets.select2') . DIRECTORY_SEPARATOR . $locale )) {
                    $jsFiles[] = $locale;
                } else {
                    $locale = 'select2_locale_'. substr(Yii::app()->language, 0, 2) . '-' . strtoupper(substr(Yii::app()->language, 3, 2)) . '.js';
                    if(@file_exists(Yii::getPathOfAlias('bootstrap.assets.select2') . DIRECTORY_SEPARATOR . $locale )) {
                        $jsFiles[] = $locale;
                    }
                }
            }
        }

        return [
            'select2' => [
                'baseUrl' => $this->getAssetsUrl() . '/select2/',
                'js' => $jsFiles,
                'css' => ['select2.css', 'select2-bootstrap.css'],
                'depends' => ['jquery'],
            ]
        ];
    }
    
    public function registerFontAwesome()
    {
        $this->registerPackage('fontawesome');
    }

    public static function setBootstrap($value)
    {
        if ($value instanceof Bootstrap) {
            self::$_instance = $value;
        }
    }

    public static function getBootstrap()
    {
        if (null === self::$_instance) {
            $module = Yii::app()->getController()->getModule();
            if ($module) {
                if ($module->hasComponent('bootstrap')) {
                    self::$_instance = $module->getComponent('bootstrap');
                }
            }

            if (null === self::$_instance) {
                if (Yii::app()->hasComponent('bootstrap')) {
                    self::$_instance = Yii::app()->getComponent('bootstrap');
                }
            }
        }
        
        return self::$_instance;
    }
}
