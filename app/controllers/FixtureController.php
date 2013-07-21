<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sophie
 * Date: 22.07.13
 * Time: 0:05
 * To change this template use File | Settings | File Templates.
 */

namespace app\controllers;

use yii\console\Controller;

class FixtureController extends Controller
{

    /**
     * @var string the default command action.
     */
    public $defaultAction = 'test';

    public function actionTest() {
        echo 'hello, i`m fixture controller =)';
    }
}