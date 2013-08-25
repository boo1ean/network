<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Edit data user </h3>
        </div>
        <?php $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal', 'id' => 'edit_user')));?>
        <div class="modal-body">
            <?php
                echo HTML::hiddenInput('id_edit', $model->id_edit, array('id' => 'id_edit'));

                echo $form->field($model, 'email')->textInput(array('value'      => $model->email,      'id' => 'email'));
                echo $form->field($model, 'first_name')->textInput(array('value' => $model->first_name, 'id' => 'first_name'));
                echo $form->field($model, 'last_name')->textInput(array('value'  => $model->last_name,  'id' => 'last_name'));

                echo HTML::tag('b', 'If you wanna change password for this user, write in both of field below');
                echo $form->field($model, 'password')->passwordInput(array('placeholder' => 'Enter new password', 'id' => 'password'));
                echo $form->field($model, 'repeat_password')->passwordInput(array('placeholder' => 'Repeat password', 'id' => 'repeat_password'));
            ?>
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