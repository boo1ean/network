<?php

namespace app\controllers;

use yii\web\Controller;
use yii;

class PjaxController extends Controller
{
    public function render($view, $data = array()) {
        if (isset($_SERVER['HTTP_X_PJAX'])) {
            $this->layout = 'pjax';
        }
        return parent::render($view, $data);
    }

    public function beforeAction($action) {
        $user = Yii::$app->getUser()->getIdentity();
        $user->last_activity = date('Y-m-d H:i:s');
        $user->update();
        
        return parent::beforeAction($action);
    }

} 
