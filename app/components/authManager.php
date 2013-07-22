<?php
namespace app\components;

use yii\base\InvalidParamException;
use app\models\User;
use yii\base\Object;

class authManager extends Object
{
    public function checkAccess($userId, $itemName, $params = array()) {
        $user = User::findIdentity($userId);

        if (!$user) {
            throw new InvalidParamException("Unknown user with id: " . $itemName);
        }

        if ($itemName == 'admin') {
            $type = (int)$user->type;
            return ($type === User::TYPE_ADMIN);
        } else {
            throw new InvalidParamException("Unknown itemName: " . $itemName);
        }
    }

}