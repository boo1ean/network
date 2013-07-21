<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sophie
 * Date: 17.07.13
 * Time: 23:15
 * To change this template use File | Settings | File Templates.
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<h1>Conversations</h1>

<?php

$form = ActiveForm::begin(array('options' => array('class' => 'form-inline')));
echo $form->field($model, 'title')->textInput();
echo Html::submitButton('New conversation', array('class' => 'btn btn-primary'));
$form->end();

// Print list of conversations
foreach ($conversations as $conversation) {
    $title = $conversation->title == NULL ? 'conversation #' . $conversation->id : $conversation->title;

?>

<ul class="nav nav-tabs nav-stacked">
    <li><?php echo Html::a($title, 'message/conversation/' . $conversation->id); ?>
</ul>

<?php } ?>
