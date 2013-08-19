<?php

use yii\helpers\Html;
use app\models\Userevent;
use app\models\User;
use app\models\Eventcomment;
use yii\widgets\ActiveForm;
?>

<h1><?php echo $event->title; ?></h1>

<br/>

<blockquote>
    <p><?php echo $event->description; ?></p>
</blockquote>

<p class="text-info">
    Begins: <?php echo $event->start_date.' '.$event->start_time; ?><br/>
    Ends:   <?php echo $event->end_date.' '.$event->end_time; ?><br/>
</p>

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

<p class="text-success">
    Type: <?php echo $type; ?><br/>
</p>

<small>
    Organized by: <?php echo User::getUserNameById($event->user_id); ?><br/>
</small>

<br/>

<div class="row">
    <div class="col-lg-6">
        <?php echo Html::label('Invited friends'); ?>

        <br/>

        <?php
        $invites = Userevent::findByEventId($event->id);
        $array_invited = array();

        foreach($invites as $invite) {
            $array_invited[] = User::getUserNameById($invite->user_id);
        }

        echo Html::listBox('invites', null, $array_invited, array(
            'class' => 'form-control',
            'disabled' => 'disabled'
        ));
        ?>

    </div>
</div>

<br/>

<ul class="nav nav-pills">
    <li><?php echo Html::a('Edit', null, array(
            'name' => 'event-edit',
            'event-id' => $event->id,
            'class' => 'cursorOnNoLink',
            'data-target' => '#myModal',
            'data-toggle' => 'modal'
        )); ?>
    </li>
    <li><?php echo Html::a('Delete', null, array(
            'name' => 'event-delete',
            'event-id' => $event->id,
            'class' => 'cursorOnNoLink'
        )); ?>
    </li>
</ul>

<div class="event_comments">

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

</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>