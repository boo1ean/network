<?php

namespace app\controllers;

use yii\web\Controller;

class PjaxController extends Controller
{
    public function render($view, $data = array()) {
        if (isset($_SERVER['HTTP_X_PJAX'])) {
            $this->layout = 'pjax';
        }
        return parent::render($view, $data);
    }

} 
