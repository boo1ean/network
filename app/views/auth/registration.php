<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<h1>Registration</h1>
<?php
$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));
echo $form->field($model, 'email')->textInput();
echo $form->field($model, 'password')->passwordInput();
echo $form->field($model, 'repeat_password')->passwordInput();
echo $form->field($model, 'first_name')->textInput();
echo $form->field($model, 'last_name')->textInput();
?>
    <div class="control-group">
        <div class="controls">
            <?php echo Html::submitButton('Registration', array('class' => 'btn btn-success')); ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>