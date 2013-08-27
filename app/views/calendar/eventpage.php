<?php

use yii\helpers\Html;
use app\models\Event;
use app\models\User;
use app\models\Eventcomment;
use yii\widgets\ActiveForm;
use app\helpers\DateTimeHelper;
?>

<div id='event_page_style'>

        <?php
        switch($event->type) {
            case '0':
                $type = 'birthday';
                break;
            case '1':
                $type = 'corp. event';
                break;
            case '2':
                $type = 'holiday';
                break;
            case '3':
                $type = 'day-off';
                break;
            default:
                break;
        }
        ?>

        <div class="panel panel-info event" style="border-color: <?php echo $event->color; ?>; color: <?php echo $event->color; ?>">
            <div class="panel-heading" style="background-color: <?php echo $event->color; ?>;">
                <b class='events-heading'>
                    <?php echo DateTimeHelper::formatTime($event->start_date.' '.$event->start_time).' - '.
                               DateTimeHelper::formatTime($event->end_date.' '.$event->end_time); ?>
                </b>

                <b class="pull-right">
                    <?php echo Html::a('<span class="glyphicon glyphicon-edit white"></span>', null, array(
                        'name' => 'event-edit',
                        'event-id' => $event->id,
                        'class' => 'cursorOnNoLink',
                        'data-target' => '#myModal',
                        'data-toggle' => 'modal'
                    )); ?>

                    <?php echo Html::a('<span class="glyphicon glyphicon-remove white"></span>', null, array(
                        'name' => 'event-delete',
                        'event-id' => $event->id,
                        'class' => 'cursorOnNoLink'
                    )); ?>
                </b>
            </div>

            <div class="panel-body">

                <p class='lead'>
                    <strong>
                        <?php echo $event->title; ?>
                    </strong>

                    <b class="pull-right">
                        <span class="label label-default"><?php echo $type; ?></span>
                    </b>
                </p>

                <p class = "event-description">
                    <?php echo $event->description; ?>
                </p>

                <b>
                    <?php echo 'Invited friends'; ?>
                </b>

                <?php
                $event = Event::find($event->id);
                $users = $event->users;

                $number_of_users = count($users);

                foreach($users as $user) {
                    echo Html::beginTag('small', array('class' => 'event-members'));
                    echo Html::a($user->userName, '/user/profile/' . $user->id);
                    echo Html::endTag('small');
                }
                ?>

                <em class="pull-left">
                    <span class="glyphicon glyphicon-user"></span>
                    <?php echo $number_of_users; ?>
                </em>

                <small class="pull-right">
                    Organized by:
                    <?php $creator = User::find($event->user_id);
                    echo Html::a($creator->userName, '/user/profile/' . $creator->id); ?>
                </small>
            </div>
        </div>

        <div class="event_comments" style="color: <?php echo $event->color; ?>;">

            <h3>Comments</h3>

            <?php
                $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));

                echo Html::hiddenInput('event_id', $event->id);
            ?>

            <div class="row">
                <div class="col-md-7">
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

                    <div class="panel panel-default" style="border-color: <?php echo $event->color; ?>">

                        <div class="panel-heading" style="background-color: <?php echo $event->color; ?>; color: white;">
                            <?php echo $comment_author; ?>
                            <small class="pull-right"><?php echo $comment->post_datetime; ?></small>
                        </div>

                        <div class="panel-body" style="color: <?php echo $event->color; ?>;">
                            <?php echo $comment->body; ?>
                        </div>

                    </div>
            <?php
                }
            ?>

        </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>