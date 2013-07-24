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

    $email = Yii::$app->getUser()->getIdentity()->email;
    $first_name = Yii::$app->getUser()->getIdentity()->first_name;
    $last_name = Yii::$app->getUser()->getIdentity()->last_name;
    $notifications = Yii::$app->getUser()->getIdentity()->searchSetting('sendNotifications');

    echo $form->field($model, 'email')->textInput(array('value' => $email));
    echo $form->field($model, 'password')->passwordInput(array('placeholder' => 'Enter new password'));
    echo $form->field($model, 'repeat_password')->passwordInput(array('placeholder' => 'Repeat password'));
    echo $form->field($model, 'first_name')->textInput(array('value' => $first_name));
    echo $form->field($model, 'last_name')->textInput(array('value' => $last_name));

?>

    <div class="controls">

        <?php

            if ($notifications == 'yes') {
                echo Html::checkbox('send_notifications', true);
            }
            else {
                echo Html::checkbox('send_notifications', false);
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