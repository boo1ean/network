<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sophie
 * Date: 09.08.13
 * Time: 9:56
 * To change this template use File | Settings | File Templates.
 */

namespace app\controllers;

use yii\web\Controller;
use app\models\User;

class NotificationController extends Controller
{
    public function actionIndex(){
        return $this->render('all');
    }
}