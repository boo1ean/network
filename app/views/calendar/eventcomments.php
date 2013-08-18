<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Eventcomment;

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

    <br/>

    <?php
    $comments = Eventcomment::byEvent($event->id);

    foreach($comments as $comment) {
    ?>

        <?php echo $comment->body.'<br/>'; ?>

    <?php
    }

    ?>