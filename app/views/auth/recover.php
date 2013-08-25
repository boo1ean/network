<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
    <h1>Recover</h1>
<?php
$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));
if(isset($message)) {
    echo Html::tag('div', $message);
} else {
    echo $form->field($model, 'password')->passwordInput();
    echo $form->field($model, 'repeat_password')->passwordInput();
    echo Html::submitButton('Recover', array('class' => 'btn btn-success'));
}
?>
<?php ActiveForm::end(); ?>