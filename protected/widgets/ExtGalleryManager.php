<?php

class ExtGalleryManager extends GalleryManager
{
    public function run()
    {
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile(Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets') . '/galleryManager.css');

        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('jquery.ui');

        $cs->registerScriptFile($this->assets . '/jquery.iframe-transport.js');
        $cs->registerScriptFile($this->assets . '/jquery.galleryManager.js');
        
        if ($this->controllerRoute === null)
            throw new CException('$controllerRoute must be set.', 500);

        $photos = [];
        foreach ($this->gallery->galleryPhotos as $photo) {
            $photos[] = [
                'id' => $photo->id,
                'rank' => $photo->rank,
                'name' => (string)$photo->name,
                'description' => (string)$photo->description,
                'preview' => $photo->getPreview(),
            ];
        }
        
        $opts = [
            'hasName' => $this->gallery->name ? true : false,
            'hasDesc' => $this->gallery->description ? true : false,
            'uploadUrl' => Yii::app()->createUrl($this->controllerRoute . '/ajaxUpload', ['gallery_id' => $this->gallery->id]),
            'deleteUrl' => Yii::app()->createUrl($this->controllerRoute . '/delete'),
            'updateUrl' => Yii::app()->createUrl($this->controllerRoute . '/changeData'),
            'arrangeUrl' => Yii::app()->createUrl($this->controllerRoute . '/order'),
            'nameLabel' => Yii::t('galleryManager.main', 'Name'),
            'descriptionLabel' => Yii::t('galleryManager.main', 'Description'),
            'photos' => $photos,
        ];

        if (Yii::app()->request->enableCsrfValidation) {
            $opts['csrfTokenName'] = Yii::app()->request->csrfTokenName;
            $opts['csrfToken'] = Yii::app()->request->csrfToken;
        }
        
        $opts = CJavaScript::encode($opts);
        $cs->registerScript('galleryManager#' . $this->id, "$('#{$this->id}').galleryManager({$opts});");

        $this->htmlOptions['id'] = $this->id;
        $this->htmlOptions['class'] = 'GalleryEditor';

        $this->render('ExtGalleryManager');
    }
}
