<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$userName = array();
?>
<h1><?php echo $conversationTitle ?> </h1><br>
<ul class="inline">
    <?php
        foreach ($conversationMembers as $member) {
            if(isset($member->first_name) || isset($member->last_name)) {
                $userName[$member->id] = $member->first_name . ' ' . $member->last_name;
            } else {
                $userName[$member->id] = $member->email;
            }

    ?>
    <li>
        <?php echo html::a($userName[$member->id], '#', array('class' => 'btn btn-small disabled')); ?>
    </li>
    <?php } ?>
    <li>
        <?php Html::a('Add user +', 'message/members/' . $conversationId, array('class' => 'btn btn-small btn-primary')); ?>
    </li>
</ul>
<br><table id="TableOfMessages">
    <?php foreach ($messages as $message) {
    if($message->user->first_name || $message->user->last_name) { ?>
        <tr>
            <td id="NamesOfUsersInTableOfMessages">
                <?php echo html::a($userName[$message->user->id], '#', array('class' => 'btn btn-small disabled'));?>
            </td>
            <td class="MessageCell">
                <p class="message left">
                    <?php echo $message->body; ?>
                </p>
            </td>
        </tr>
    <?php
    }
}
    ?>
        <tr>
            <td></td>
            <td>
                <?php
                $form = ActiveForm::begin(array('options' => array('class' => 'form-inline')));
                echo Html::textarea('body', '', array(
                    'placeholder' => 'Write your message here',
                    'autofocus'   => 'true',
                    'rows' => '2',
                    'maxlength' => '100',
                    'id' => 'MessageForm'));
                echo Html::submitButton('Send', array(
                                               'class' => 'btn btn-info',
                                               'id' => 'MessageSend'));
                ActiveForm::end();
                ?>
            </td>
        </tr>
</table>




