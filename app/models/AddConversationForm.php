<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sophie
 * Date: 18.07.13
 * Time: 14:02
 * To change this template use File | Settings | File Templates.
 */

namespace app\models;
use app\models\Conversation;
use Yii;

class AddConversationForm extends Conversation
{
    public function scenarios() {
        return array(
            'default' => array('title'),
        );
    }

    public function addConversation() {
        if ($this->validate()) {
            $user = Yii::$app->getUser()->getIdentity();
            $this->save();
            $user->link('conversations', $this);
            return true;
        }
        return false;
    }
}