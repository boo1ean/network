<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Userevent;
use app\models\User;
?>

<div class="modal-dialog">

    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Edit event</h4>
        </div>

        <?php

        $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));

        if (isset($event_id)) {
            echo Html::hiddenInput('id_event', $event_id);
        }

        ?>

        <div class="modal-body">

            <div class="row">
                <div class="col-lg-6">
                    <?php echo $form->field($model, 'title')->textInput(array(
                        'placeholder' => 'Enter event title',
                        'value' => $event->title,
                        'id' => 'title'
                    )); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <?php echo $form->field($model, 'description')->textInput(array(
                        'placeholder' => 'Enter event description',
                        'value' => $event->description,
                        'id' => 'description'
                    )); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <?php echo $form->field($model, 'start_date')->input('date', array(
                        'value' => $event->start_date,
                        'id' => 'start_date'
                    )); ?>
                </div>

                <div class="col-lg-3 col-lg-push-1">
                    <?php echo $form->field($model, 'start_time')->input('time', array(
                        'value' => $event->start_time,
                        'id' => 'start_time'
                    )); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <?php echo $form->field($model, 'end_date')->input('date', array(
                        'value' => $event->end_date,
                        'id' => 'end_date'
                    )); ?>
                </div>

                <div class="col-lg-3 col-lg-push-1">
                    <?php echo $form->field($model, 'end_time')->input('time', array(
                        'value' => $event->end_time,
                        'id' => 'end_time'
                    )); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <?php echo Html::activeLabel($model, 'type', array('class' => 'control-label')); ?>
                    <?php echo Html::dropDownList('type', $event->type, array('birthday', 'corp. event', 'holiday', 'day-off'),
                        array('class' => 'form-control')); ?>
                </div>
            </div>

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

        </div><!--body-->

        <div class="modal-footer">

            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            <?php echo Html::button('Delete', array(
                'class' => 'btn btn-danger',
                'name' => 'event-delete',
                'event-id' => $event->id,
                'data-dismiss' => 'modal'
            )); ?>

            <?php echo Html::button('Confirm', array(
                'class' => 'btn btn-primary',
                'name' => 'event-save'
            )); ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
