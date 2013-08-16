<?php

namespace app\controllers;

use yii\web\Controller;

class PjaxController extends Controller
{
    public function render($view, $data = array()) {
        if (isset($_SERVER['HTTP_X_PJAX'])) {
            return $this->renderPartial($view, $data);
        }
        else {
            return parent::render($view, $data);
        }
    }

} 
