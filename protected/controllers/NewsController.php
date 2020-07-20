<?php

class NewsController extends MController
{
    public function actionIndex()
    {
        $model = News::model()->order_id_desc()->findAll(['select' => 'id, title_'.Yii::app()->language.', news_text_'.Yii::app()->language.', news_date']);
        $dataProvider = MHelper::getArrayProvider($model, 10, ['news_date']);
        $this->render('index', ['model' => $model, 'dataProvider' => $dataProvider]);
    }
}