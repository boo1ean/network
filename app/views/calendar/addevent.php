<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="modal-dialog">

    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Add event</h4>
        </div>

        <?php
            $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));

            echo Html::hiddenInput('param', 'add');
        ?>

        <div class="modal-body">

            <div class="row">
                <div class="col-lg-6 col-lg-offset-1">
                    <?php echo $form->field($model, 'title')->textInput(array(
                        'placeholder' => 'Enter event title',
                        'id' => 'title'
                    )); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-lg-offset-1">
                    <?php echo $form->field($model, 'description')->textInput(array(
                        'placeholder' => 'Enter event description',
                        'id' => 'description'
                    )); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-lg-offset-1">
                    <?php echo $form->field($model, 'start_date')->input('date', array(
                        'id' => 'start_date',
                        'value' => $start_date
                    )); ?>
                </div>

                <div class="col-lg-3 col-lg-push-1">
                    <?php echo $form->field($model, 'start_time')->input('time', array(
                        'id' => 'start_time',
                        'value' => '12:00:00'
                    )); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-lg-offset-1">
                    <?php echo $form->field($model, 'end_date')->input('date', array(
                        'id' => 'end_date',
                        'value' => $end_date
                    )); ?>
                </div>

                <div class="col-lg-3 col-lg-push-1">
                    <?php echo $form->field($model, 'end_time')->input('time', array(
                        'id' => 'end_time',
                        'value' => '15:00:00'
                    )); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-lg-offset-1">
                    <?php echo Html::label('Type'); ?>
                    <?php echo Html::dropDownList('type', null, array('birthday', 'corp. event', 'holiday', 'day-off'),
                        array('class' => 'form-control')) ?>
                </div>
            </div>

            <?php
                $array_of_users = array();
                $other_users = $users->getOtherUsers();

                foreach($other_users as $user) {
                    $array_of_users[$user->email] = $user->first_name.' '.$user->last_name;
                }
            ?>

            <br/>

            <div class="row">
                <div class="col-lg-6 col-lg-offset-1">
                    <?php echo Html::label('Invite friends'); ?>
                    <?php echo Html::dropDownList('invitations[]', null, $array_of_users, array(
                        'multiple' => 'multiple',
                        'class' => 'form-control'
                    )); ?>
                </div>
            </div>

            <br/>

            <div class="row">
                <div class="col-lg-6 col-lg-offset-1">
                    <?php echo Html::label('Choose event color'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-lg-offset-1">
                    <?php echo Html::input('text', 'colorpicker', '#f00', array(
                        'id' => 'colorpicker'
                    )); ?>
                </div>
            </div>

        </div><!--body-->

        <div class="modal-footer">

            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            <?php echo Html::button('Add event', array(
                'class' => 'btn btn-primary',
                'name' => 'event-add'
            )); ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->