<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sophie
 * Date: 17.07.13
 * Time: 23:15
 * To change this template use File | Settings | File Templates.
 */
use yii\helpers\Html;
?>

<h1>Conversations</h1>
<?php
// Print list of conversations
foreach ($conversations as $conversation) {
    $title = $conversation->title == NULL ? 'conversation #' . $conversation->id : $conversation->title;
    echo Html::a($title, 'message/conversation/' . $conversation->id);
    echo '<br>';
}