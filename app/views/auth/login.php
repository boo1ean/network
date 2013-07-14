<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Captcha;
?>
<h1>Login</h1>
<?php
$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));
echo $form->field($model, 'email')->textInput();
echo $form->field($model, 'password')->passwordInput();
//echo $form->field($model, 'captcha')->widget(Captcha::className(), array('options' => array('class' => 'input-medium')));
?>
<div class="control-group">
    <div class="controls">
        <?php echo Html::submitButton('Login', array('class' => 'btn btn-success')); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>