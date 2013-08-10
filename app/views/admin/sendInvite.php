<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
?>

<h1>Send invite</h1>

<?php
    $form = ActiveForm::begin();
    if(isset($message) && is_string($message)) {
        echo Html::tag('div', $message, array('class' => "alert alert-success"));
        echo Html::a('back', '/admin', array('class' => 'btn btn-success'));
    } else {
        echo $form->field($model, 'email')->textInput();
        echo Html::submitButton('Send', array('class' => 'btn btn-success'));
    }
?>

<?php ActiveForm::end(); ?>