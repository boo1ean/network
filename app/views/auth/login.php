<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Captcha;
?>
<h1>Login</h1>
<?php
$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));
echo $form->field($model, 'email')->textInput();
if(isset($model['password_hash']))
    echo $form->field($model, 'password_hash')->passwordInput();
else
    echo $form->field($model, 'password')->passwordInput();
//echo $form->field($model, 'captcha')->widget(Captcha::className(), array('options' => array('class' => 'input-small'), 'template' => "{input}<br>{image}", 'captchaAction' => 'auth/captcha'));
?>
<div class="control-group">
    <div class="controls">
        <?php echo Html::submitButton('Login', array('class' => 'btn btn-success')); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>