<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<p>Add new member: <input type="text" id="not-member-list" data-id="<?php echo $conversationId?>" class="typeahead"></p>
<h1><?php echo $conversationTitle ?> </h1>
<ul class="inline" id="member-list">
    <?php foreach ($conversationMembers as $member):?>
        <li>
            <?php echo html::a($member->userName, '#', array('class' => 'btn btn-small disabled')); ?>
        </li>
    <?php endforeach; ?>
</ul>
<!-- Print all messages in conversation -->
<?php foreach ($messages as $message): ?>
    <div class = "messageContainer">
        <div class = "messageUser">
            <span class = "avatar">  <?php echo Html::img($message->user->avatar); ?> </span>
        </div>
        <div class = "messageBody">
            <div class = "popover right in">
                <div class = "arrow"></div>
                <h5 class="popover-title"><?php echo $message->user->userName; ?></h5>
                <div class = "popover-content">
                    <?php echo $message->body; ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<!-- Input for new message -->
<div class = "messageContainer">
    <?php
    $form = ActiveForm::begin(array('options' => array('class' => 'form-inline')));
    echo Html::textarea('body', '', array(
        'placeholder' => 'Write your message here',
        'autofocus'   => 'true',
        'rows' => '2',
        //'maxlength' => '100',
        'id' => 'MessageForm'));
    echo Html::submitButton('Send', array(
        'class' => 'btn btn-info',
        'id' => 'MessageSend'));
    ActiveForm::end();
    ?>
</div>




