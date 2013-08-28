<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\DateTimeHelper;
?>

    <h1><?php echo $conversationTitle ?> </h1>
    <div class="row">
        <div class="col-lg-3">
            Add new member: <input type="text" id="not-member-list" data-id="<?php echo $conversationId?>" class="form-control">
        </div>
    </div>
    <br/>
    <div id="member-conversation-list" data-creator="<?echo $is_creator ? 1 : 0;?>">
        <?php foreach ($conversationMembers as $member):?>
            <div class="btn-group" <?php echo 'data-id="'.$member->id.'"';?> >
                <?php
                $is_member_creator = $member->id == $conversationCreator->id;
                $class  = 'btn';
                $class .= $is_member_creator ? ' btn-info' : ' btn-success';
                echo html::tag('a', $member->userName, array(
                    'class' => $class . ' btn-xs',
                    'href'  => '/user/profile/' . $member->id,
                )); ?>

                <?php if ($is_creator && !$is_member_creator || $member->id == $user->id && !$is_member_creator) {
                    $class .= ' glyphicon glyphicon-remove';
                    echo html::tag('button', ' ', array(
                        'class'   => $class,
                        'data-id' => $conversationId,
                        'style'   => 'top:0px;height:22px;'
                    ));
                } ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Print all messages in conversation -->
    <?php foreach ($messages as $message): ?>
        <div class = "messageContainer">
            <div class = "messageUser">
                <span>  <?php echo Html::img($message->user->avatar, array('class' => 'avatar')); ?> </span>
            </div>
            <div class = "messageBody">
                <div class = "popover right in" style="z-index: 0;">
                    <div class = "arrow"></div>
                    <h5 class="popover-title">
                        <?php echo $message->user->userName . ' (' . DateTimeHelper::formatTime($message->datetime, true) . ')'; ?>
                    </h5>
                    <div class = "popover-content">
                        <?php echo $message->body; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- Input for new message -->
    <div class = "messageContainer" id="message-container">
        <?php $form = ActiveForm::begin(array('options' => array('class' => 'form-inline'))); ?>
        <div class="row">
            <div class="col-lg-1 navbar-left" id="avatar-container">
                <?php echo Html::img($user->avatar, array('class' => 'avatar'));?>
            </div>
            <div class="col-lg-11">
                <?php echo Html::textarea('body', '', array(
                    'autofocus'   => 'true',
                    'class'       => 'form-control',
                    'id'          => 'body',
                    'placeholder' => 'Write your message here',
                    'rows'        => '4',
                    'style'       => 'resize: none'
                )); ?>
                <div class="help-block"></div>
            </div>

            <div>
                <?php echo Html::submitButton('Send', array(
                    'class'      => 'btn btn-info',
                    'data-id'    => $conversationId,
                    'data-title' => $user->first_name . ' ' . $user->last_name,
                    'id'         => 'message-send',
                    'style'      => 'position: absolute'
                )); ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>



