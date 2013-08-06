<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Event;

class CalendarSettingsForm extends Model
{
    public function scenarios() {
        return array(
            'default' => array('feed')
        );
    }

    public function saveSettings() {
        if ($this->validate()) {

            $id = Yii::$app->getUser()->getIdentity()->getId();
            $user = User::find($id);

            if (isset($_POST['feed'])) {
                $user->addSetting('gcal_feed', $_POST['feed']);
            }

            $user->save();

            return true;
        }

        return false;
    }
}