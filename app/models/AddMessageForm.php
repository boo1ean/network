<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sophie
 * Date: 18.07.13
 * Time: 0:45
 * To change this template use File | Settings | File Templates.
 */

namespace app\models;
use Yii;

class AddMessageForm extends Message
{
    public function addMessage() {
        if ($this->validate()) {
            $this->save();
            return true;
        }
        return false;
    }
}