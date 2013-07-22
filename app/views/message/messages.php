<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sophie
 * Date: 18.07.13
 * Time: 0:07
 * To change this template use File | Settings | File Templates.
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
echo '<h1>' . $conversationTitle . '</h1><br>';
echo '<ul class="inline">';
foreach ($conversationMembers as $member) {
    echo '<li>';
    echo html::a($member->first_name . ' ' . $member->last_name, '#', array('class' => 'btn btn-small disabled')) ;
    echo '</li>';
}
echo '<li>'. Html::a('Add user +', 'message/members/' . $conversationId, array('class' => 'btn btn-small btn-primary')).'</li></ul>';
echo '<br><table id="TableOfMessages">';
foreach ($messages as $message) {
    if($message->user->first_name || $message->user->last_name) {
        echo '<tr><td id="NamesOfUsersInTableOfMessages">' . html::a($message->user->first_name . ' ' . $message->user->last_name, '#', array('class' => 'btn btn-small disabled')).'</td>';
        echo '<td class="MessageCell"><p class="message left">' . $message->body . '</p></td></tr>';
    }
}
echo '<tr><td></td><td>';
$form = ActiveForm::begin(array('options' => array('class' => 'form-inline')));
echo Html::activeTextInput($model, 'body', array('class' => 'input-xxlarge',
                                                 'placeholder' => 'Write your message here',
                                                 'autofocus' => 'true'));
echo Html::submitButton('Send', array('class' => 'btn btn-info',
                                       'id' => 'MessageSend'));
ActiveForm::end();
echo '</td></tr></table><br>';
/*
<form class="form-inline">
    <input class="input-xlarge" type="text" placeholder="Write your message here">
    <button type="submit" class="btn btn-success">Send</button>
</form>
*/


