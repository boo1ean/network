<?php namespace app\controllers;

use yii\web\Controller;

class SiteController extends PjaxController
{
    public function actionIndex() {
        return $this->render('index');
    }
}