<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="col-lg-offset-2">
    <h1><?php echo $conversationTitle ?> </h1>
    <div class="row">
        <div class="col-lg-3">
            Add new member: <input type="text" id="not-member-list" data-id="<?php echo $conversationId?>" class="form-control">
        </div>
    </div>
    <br/>
    <div id="member-list">
        <?php foreach ($conversationMembers as $member):?>
            <?php echo html::tag(
                'label',
                $member->userName,
                array('class' => $member->id == $conversationCreator->id ? 'label label-info' : 'label label-success')); ?>
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
        <?php $form = ActiveForm::begin(array('options' => array('class' => 'form-inline')));?>
        <div class="row">
            <div class="col-1">
                <?php echo Html::img(Yii::$app->getUser()->getIdentity()->avatar, array('class' => 'avatar'));?>
            </div>
            <div class="col-11">
                <?php echo Html::textarea('body', '', array(
                    'class'       => 'form-control',
                    'autofocus'   => 'true',
                    'placeholder' => 'Write your message here',
                    'rows'        => '4',
                    'style'       => 'resize: none'
                )); ?>
            </div>

            <div>
                <?php echo Html::submitButton('Send', array(
                    'class' => 'btn btn-info',
                    'style' => 'position: absolute'
                )); ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>



