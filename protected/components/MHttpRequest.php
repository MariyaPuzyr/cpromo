<?php

class MHttpRequest extends CHttpRequest
{
    public $noCsrfValidationRoutes = [];
    private $_csrfToken;
    
    public function getCsrfToken()
    {
        if($this->_csrfToken === null) {
            $session = Yii::app()->session;
            $csrfToken = $session->itemAt($this->csrfTokenName);
            
            if($csrfToken === null) {
                $csrfToken = sha1(uniqid(mt_rand(), true));
                $session->add($this->csrfTokenName, $csrfToken);
            }
            
            $this->_csrfToken = $csrfToken;
        }

        return $this->_csrfToken;
    }
    
    public function validateCsrfToken($event)
    {
        if($this->getIsPostRequest()) {
            $session = Yii::app()->session;
            if($session->contains($this->csrfTokenName) && isset($_POST[$this->csrfTokenName])) {
                $tokenFromSession=$session->itemAt($this->csrfTokenName);
                $tokenFromPost = $_POST[$this->csrfTokenName];
                $valid=$tokenFromSession === $tokenFromPost;
            } else
                $valid = false;
            
            if(!$valid)
                throw new CHttpException(400, Yii::t('core','ntf_csrf_token_not_verified.'));
        }
    }
    
    protected function normalizeRequest(){
        parent::normalizeRequest();

        $route = Yii::app()->getUrlManager()->parseUrl($this);
        if($this->enableCsrfValidation){
            foreach($this->noCsrfValidationRoutes as $cr){
                if(preg_match('#'.$cr.'#', $route)){
                    Yii::app()->detachEventHandler('onBeginRequest', [$this,'validateCsrfToken']);
                    Yii::trace('Route "'.$route.' passed without CSRF validation');
                    break;
                }
            }
        }
    }
}

