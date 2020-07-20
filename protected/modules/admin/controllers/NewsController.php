<?php

class NewsController extends MAdminController
{
    public function filters()
    {
    	return [
            'rights',
            [
                'application.filters.YXssFilter',
                'clean'   => '*',
                'tags'    => 'none',
                'actions' => '*'
            ]
        ];
    }
    
    public function actionIndex()
    {
        $model = new News;
        $model->unsetAttributes();
        $model->order_id_desc();
        
        $this->render('index', ['model' => $model]);
    }
    
    public function actionWorkNews($type, $id = false)
    {
        $model = ($type == 'add') ? new News : News::model()->findByPk($id);
        
        if(Yii::app()->request->isAjaxRequest && $_POST['ajax'] === get_class($model)) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        
        if(Yii::app()->request->getPost(get_class($model)) !== null) {
            $model->attributes = Yii::app()->request->getPost(get_class($model));
            $model->save();
        }
        
        $this->render('workNews', ['model' => $model]);
    }
    
    public function actionUploadImage()
    {
        $dir = Yii::getPathOfAlias('webroot.uploads.newsFiles');
        $files = [];
        $types = ['image/png', 'image/jpg', 'image/gif', 'image/jpeg', 'image/pjpeg'];

        if (isset($_FILES['file'])) {
            foreach ($_FILES['file']['name'] as $key => $name) {
                $type = strtolower($_FILES['file']['type'][$key]);
                if (in_array($type, $types)) {
                    $filename = md5($name.date('YmdHis')).'.jpg';
                    $path = $dir.'/'.$filename;

                    move_uploaded_file($_FILES['file']['tmp_name'][$key], $path);

                    $files['file-'.$key] = [
                        'url' => $this->createAbsoluteUrl('/').'/uploads/news/'.$filename,
                        'id' => rand(0000, 9999)
                    ];
                }
            }
        }

        echo stripslashes(json_encode($files));
    }
    
    public function deleteNews($id)
    {
        News::model()->deleteByPk($id);
        $this->redirect($this->createUrl('/admin/news'));
    }
}