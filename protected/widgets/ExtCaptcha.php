<?php

class ExtCaptcha extends CCaptchaAction
{
    protected function generateVerifyCode()
    {
        $length = 6;
        $digits = '0123456789';
        $code = '';
        
        for($i = 0; $i < $length; $i++) {
            $code .= $digits[mt_rand(0, 9)];
        }
        return $code;
    }
    
    public function run()
    {
	if(isset($_GET[self::REFRESH_GET_VAR])) {
            $code = $this->getVerifyCode(true);
            echo CJSON::encode([
		'hash1' => $this->generateValidationHash($code),
		'hash2' => $this->generateValidationHash(strtolower($code)),
		'url' => $this->getController()->createUrl($this->getId(), ['v' => uniqid()]),
            ]);
	} else
            $this->renderImage($this->getVerifyCode());
	
        Yii::app()->end();
    }
}

