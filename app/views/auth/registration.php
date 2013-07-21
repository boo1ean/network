<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<h1>Registration</h1>
<?php
$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));
if(isset($message)) {
    echo Html::tag('div', $message);
} else {
    echo '<div class="control-group"><p class="control-label">Email:</p> <p class="controls"> '.$model['email'].'</p></div>';
    echo $form->field($model, 'password')->passwordInput();
    echo $form->field($model, 'repeat_password')->passwordInput();
    echo $form->field($model, 'first_name')->textInput();
    echo $form->field($model, 'last_name')->textInput();
    echo Html::submitButton('Registration', array('class' => 'btn btn-success'));
}
?>
<?php ActiveForm::end(); ?>