<?php

class MBaseController extends RController
{
    public $_assetsBase;
    private $_adminAssetsBase;
    private $_pageTitle;
    
    public function filters()
    {
	return [
            'rights',
            [
                'application.filters.YXssFilter',
                'clean'   => '*',
                'tags'    => 'strict',
                'actions' => 'all'
            ]
        ];
    }
    
    public function __construct($id, $module = null){
        parent::__construct($id, $module);
        
        if(isset($_POST['language'])) {
            $lang = $_POST['language'];
            $MultilangReturnUrl = $_POST[$lang];
            $this->redirect($MultilangReturnUrl);
        }
    
        if(isset($_GET['language'])) {
            Yii::app()->language = $_GET['language'];
            Yii::app()->user->setState('language', $_GET['language']); 
            $cookie = new CHttpCookie('language', $_GET['language']);
            $cookie->expire = time() + (60*60*24*365); // (1 year)
            Yii::app()->request->cookies['language'] = $cookie; 
        } elseif(Yii::app()->user->hasState('language')) {
            Yii::app()->language = Yii::app()->user->getState('language');
        } elseif(isset(Yii::app()->request->cookies['language'])) {
            Yii::app()->language = Yii::app()->request->cookies['language']->value;
        }
    }
    
    public function createMultilanguageReturnUrl($lang = false){
        if (count($_GET)>0){
            $arr = $_GET;
            $arr['language']= $lang;
        }
        else 
            $arr = ['language' => $lang ? $lang : Yii::app()->language];
        
        return $this->createUrl('', $arr);
    }
    
    public function getAssetsBase()
    {
        if ($this->_assetsBase === null) {
            $this->_assetsBase = Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('webroot.themes.default.assets'),
                    false,
                    -1,
                    YII_DEBUG
                );
            }
        return $this->_assetsBase;
    }
    
    public function getAdminAssetsBase()
    {
        if ($this->_adminAssetsBase === null) {
            $this->_adminAssetsBase = Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('application.modules.admin.assets'),
                    false,
                    -1,
                    YII_DEBUG
                );
            }
        return $this->_adminAssetsBase;
    }
    
    public function setPageTitle($title)
    {
	$this->_pageTitle = $title;
    }

    public function getPageTitle()
    {
	$title = Yii::app()->name;
	if(!empty($this->_pageTitle)) {
            $title .= ' # '.$this->_pageTitle;
        }
        return $title;
    }   
}

