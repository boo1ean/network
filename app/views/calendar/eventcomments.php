<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Eventcomment;
use app\models\User;

?>

    <h3 class="text-center">Comments</h3><br/>

    <?php
        $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));

        echo Html::hiddenInput('event_id', $event->id);
    ?>

    <div class="row">
        <div class="col-lg-5">
            <?php echo Html::textarea('comment', '', array(
                'placeholder' => 'Write comment',
                'class' => 'form-control',
                'rows'        => '3',
                'style'       => 'resize: none',
                'autofocus'   => 'true'
            )); ?>
        </div>
    </div>

    <br/>

    <?php echo Html::button('Post comment', array(
        'class' => 'btn btn-primary',
        'name' => 'post-comment',
        'event-id' => $event->id
    )); ?>

    <?php ActiveForm::end(); ?>

    <br/><br/>

    <?php
        $comments = Eventcomment::byEvent($event->id);

        foreach($comments as $comment) {

            $comment_author = User::getUserNameById($comment->user_id);
    ?>

    <div class="row">
        <div class="col-lg-6">

            <div class="panel">

                <div class="panel-heading">
                    <?php echo $comment_author; ?>
                    <small class="pull-right"><?php echo $comment->post_datetime; ?></small>
                </div>

                <div class="panel-body">
                    <?php echo $comment->body; ?>
                </div>

            </div>

        </div>
    </div>

    <?php
    }
    ?>