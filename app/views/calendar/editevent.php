<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Event;
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
                <div class="col-lg-10 col-lg-offset-1">
                    <?php echo $form->field($model, 'title')->textInput(array(
                        'placeholder' => 'Enter event title',
                        'value' => $event->title,
                        'id' => 'title'
                    )); ?>
                </div>
                <div class="col-lg-10 col-lg-offset-1">
                    <?php echo $form->field($model, 'description')->textInput(array(
                        'placeholder' => 'Enter event description',
                        'value' => $event->description,
                        'id' => 'description'
                    )); ?>
                </div>
                <div class="col-lg-6 col-lg-offset-1">
                    <div class="form-group">
                        <b>Start date</b>
                        <div class="date-time-picker input-group" style="padding-left: 0px;">
                            <?php
                                $start_date = explode('-', $event->start_date);
                                $start_date = $start_date[2] . '/' . $start_date[1] . '/' . $start_date[0] .' ' . $event->start_time;
                            ?>
                            <input name="start_datetime" data-format="dd/MM/yyyy hh:mm" id='start_date' type="text" class="form-control" value="<?php echo $start_date ?>"/>
                            <span class="input-group-addon add-on">
                                <i style="color: #000000" class="glyphicon glyphicon-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-lg-offset-1">
                    <div class="form-group">
                        <b>End date</b>
                        <div class="date-time-picker input-group" style="padding-left: 0px;">
                            <?php
                                $end_date = explode('-', $event->end_date);
                                $end_date = $end_date[2] . '/' . $end_date[1] . '/' . $end_date[0] .' ' . $event->end_time;
                            ?>
                            <input name="end_datetime" data-format="dd/MM/yyyy hh:mm" id='end_date' type="text" class="form-control" value="<?php echo $end_date?>"/>
                            <span class="input-group-addon add-on">
                                <i style="color: #000000" class="glyphicon glyphicon-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-lg-offset-1">
                    <div class="form-group drop">
                        <?php echo Html::activeLabel($model, 'type', array('class' => 'control-label')); ?>
                        <?php echo Html::dropDownList('type', $event->type, array('birthday', 'corp. event', 'holiday', 'day-off'),
                            array('class' => 'form-control')); ?>
                    </div>
                </div>

                <div class="col-lg-10 col-lg-offset-1">
                    <div class="form-group">
                        Add new member:
                        <input type="text" id="new-member-list" class="form-control">
                        <p class="help-block" style="display: none;"></p>
                     </div>
                </div>

                <div id="member-event-list" class="col-lg-10 col-lg-offset-1" data-creator="<?echo $is_creator ? 1 : 0;?>">
                    <?php foreach ($members as $member):?>
                        <div class="btn-group navbar-btn" <?php echo 'data-id="'.$member->id.'"';?> >

                            <?php
                            $is_member_creator = $member->id == $user->id;
                            $class  = 'btn';
                            $class .= $is_member_creator ? ' btn-info' : ' btn-success';

                            echo html::checkbox('invitations['.$member->id.']', true, array(
                                'style' => 'display: none;',
                                'value' => $member->id
                            ));

                            echo html::tag('a', $member->userName, array(
                                'class' => $class . ' btn-xs',
                                'href'  => '/user/profile/' . $member->id,
                            )); ?>

                            <?php if ($is_creator && !$is_member_creator || $member->id == $user->id && !$is_member_creator) {
                                $class .= ' glyphicon glyphicon-remove';
                                echo html::tag('button', ' ', array(
                                    'class'   => $class,
                                    'data-id' => $event->id,
                                    'style'   => 'top:0px;height:22px;'
                                ));
                            } ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-lg-6 col-lg-offset-1">
                    <div class="form-group">
                        <?php echo Html::label('Choose event color'); ?>
                    </div>
                </div>
                <div class="col-lg-6 col-lg-offset-1">
                    <div class="form-group">
                        <?php echo Html::input('text', 'colorpicker', $event->color, array(
                            'id' => 'colorpicker',
                            'value' => $event->color
                        )); ?>
                    </div>
                </div>
            </div>

        </div><!--body-->

        <div class="modal-footer">

            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            <?php echo Html::button('Confirm', array(
                'class' => 'btn btn-primary',
                'name' => 'event-save'
            )); ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
