<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<h1><?php echo $conversationTitle ?> </h1><br>
<ul class="inline">
    <?php foreach ($conversationMembers as $member):?>
        <li>
            <?php echo html::a($member->userName, '#', array('class' => 'btn btn-small disabled')); ?>
        </li>
    <?php endforeach; ?>
    <li>
        <?php echo Html::a('Add user +', 'message/members/' . $conversationId, array('class' => 'btn btn-small btn-primary')); ?>
    </li>
</ul>
<br><table id="TableOfMessages">
    <?php foreach ($messages as $message): ?>
        <!--<tr>
            <td id="NamesOfUsersInTableOfMessages">
                <?php /*echo html::a($message->user->userName, '#', array('class' => 'btn btn-small disabled'));*/?>
            </td>
            <td class="MessageCell">
                <p class="message left">
                    <?php /*echo $message->body; */?>
                </p>
            </td>
        </tr>-->
        <div class="messageContainer">
            <div class = "messageUser">
                <?php echo html::a($message->user->userName, '#', array('class' => 'btn btn-small disabled'));?>
            </div>
            <div class = "messageBody">
                <div class="popover right in">
                    <div class="arrow"></div>
                    <!--<h3 class="popover-title"></h3>-->
                    <div class="popover-content">
                        <?php echo $message->body; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
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




