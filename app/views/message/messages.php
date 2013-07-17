<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sophie
 * Date: 18.07.13
 * Time: 0:07
 * To change this template use File | Settings | File Templates.
 */
use yii\helpers\Html;

echo '<h1>' . $conversationTitle . '</h1>';


foreach ($messages as $message)
{
    echo '<div>';
    if($message->user->first_name || $message->user->last_name)
        echo '<b> Author: ' . $message->user->first_name . ' ' . $message->user->last_name . '</b><br>';
    echo '<p> ' . $message->body . '</p>';
}


