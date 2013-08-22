<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Eventcomment;
use app\models\User;

?>

    <h3 style="color: <?php echo $event->color; ?>;">Comments</h3><br/>

    <?php
        $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));

        echo Html::hiddenInput('event_id', $event->id);
    ?>

    <div class="row">
        <div class="col-lg-9">
            <?php echo Html::textarea('comment', '', array(
                'placeholder' => 'Write comment',
                'class'       => 'form-control',
                'rows'        => '3',
                'style'       => 'resize: none',
                'autofocus'   => 'true',
                'style'       => 'color: '.$event->color.'; border-color: '.$event->color.'',
            )); ?>
        </div>

        <?php echo Html::button('Post comment', array(
            'class' => 'btn btn-primary',
            'style' => 'background-color: '.$event->color.'; border-color: '.$event->color.'',
            'name' => 'post-comment',
            'event-id' => $event->id
        )); ?>
    </div>

    <?php ActiveForm::end(); ?>

    <br/>

    <?php
        $comments = Eventcomment::byEvent($event->id);

        foreach($comments as $comment) {

            $comment_author = User::getUserNameById($comment->user_id);
    ?>

    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default" style="border-color: <?php echo $event->color; ?>">

                <div class="panel-heading" style="background-color: <?php echo $event->color; ?>; color: white;">
                    <?php echo $comment_author; ?>
                    <small class="pull-right"><?php echo $comment->post_datetime; ?></small>
                </div>

                <div class="panel-body" style="color: <?php echo $event->color; ?>;">
                    <?php echo $comment->body; ?>
                </div>

            </div>

        </div>
    </div>

    <?php
    }
    ?>