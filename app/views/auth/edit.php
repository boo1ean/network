<?php

namespace app\views;

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii;

?>

<h1>Edit profile</h1>

<br/>

<?php

    if(isset($message)) {
        echo Html::tag('div class="alert alert-success"', $message);
        echo Html::tag('/div');
    }

    $form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));
?>
    <div class="controls control-group"><?php echo Html::img($model->getAvatar()); ?></div>
<?php
    echo $form->field($model, 'email')->textInput();
    echo $form->field($model, 'password')->passwordInput(array('placeholder' => 'Enter new password'));
    echo $form->field($model, 'repeat_password')->passwordInput(array('placeholder' => 'Repeat password'));
    echo $form->field($model, 'first_name')->textInput();
    echo $form->field($model, 'last_name')->textInput();

?>

    <div class="controls">

        <?php

            if ('yes' == $model->notification) {
                echo Html::checkbox('notification', true);
            }
            else {
                echo Html::checkbox('notification', false);
            }

            echo ' Send notifications on email when someone send me a private message';
        ?>

    </div>

    <br/><br/>

    <div class="controls">
        <?php echo Html::submitButton('Save', array('class' => 'btn btn-primary')); ?>
    </div>

    <br/>

<?php

    $form->end();

?>