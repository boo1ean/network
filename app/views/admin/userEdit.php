<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
    <h1>Edit data user </h1>
<?php
    $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal', 'id' => 'edit_user')));
    echo HTML::hiddenInput('id_edit', $model->id_edit, array('id' => 'id_edit'));

    echo $form->field($model, 'email')->textInput(array('value' => $model->email, 'id' => 'email'));
    echo $form->field($model, 'password')->passwordInput(array('placeholder' => 'Enter new password', 'id' => 'password'));
    echo $form->field($model, 'repeat_password')->passwordInput(array('placeholder' => 'Repeat password', 'id' => 'repeat_password'));
    echo $form->field($model, 'first_name')->textInput(array('value' => $model->first_name, 'id' => 'first_name'));
    echo $form->field($model, 'last_name')->textInput(array('value' => $model->last_name, 'id' => 'last_name'));

    echo Html::button('Confirm', array('class' => 'btn btn-success', 'onclick' => 'return editUser('.$model->id_edit.', 0);'));
?>
<?php ActiveForm::end(); ?>