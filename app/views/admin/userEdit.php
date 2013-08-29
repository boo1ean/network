<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Edit user profile</h3>
        </div>
        <?php $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal', 'id' => 'edit_user')));?>
        <div class="modal-body">
            <?php echo HTML::hiddenInput('id_edit', $model->id_edit, array('id' => 'id_edit')); ?>
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <?php echo $form->field($model, 'email')->textInput(array(
                        'id'    => 'email',
                        'value' => $model->email
                    ));?>
                </div>
                <div class="col-lg-10 col-lg-offset-1">
                    <?php echo $form->field($model, 'first_name')->textInput(array(
                        'id'    => 'first_name',
                        'value' => $model->first_name
                    ));?>
                </div>
                <div class="col-lg-10 col-lg-offset-1">
                    <?php echo $form->field($model, 'last_name')->textInput(array(
                        'id'    => 'last_name',
                        'value' => $model->last_name
                    ));?>
                </div>
                <div class="col-lg-10 col-lg-offset-1">
                    <?php echo HTML::tag('b', 'For change password of this user, write in both of field below');?>
                    <?php echo $form->field($model, 'password')->passwordInput(array(
                        'id'          => 'password',
                        'placeholder' => 'Enter new password'
                    ));?>
                </div>
                <div class="col-lg-10 col-lg-offset-1">
                    <?php echo $form->field($model, 'repeat_password')->passwordInput(array(
                        'id'          => 'repeat_password',
                        'placeholder' => 'Repeat password',
                    ));?>
            </div>
        </div>
        <div class="modal-footer">
            <?php
                echo Html::button('Confirm', array(
                    'class'   => 'btn btn-success',
                    'data-id' => $model->id_edit,
                    'name'    => 'user-save'
                ));
            ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>