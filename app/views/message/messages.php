<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sophie
 * Date: 18.07.13
 * Time: 0:07
 * To change this template use File | Settings | File Templates.
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

echo '<h1>' . $conversationTitle . '</h1>';
echo '<b> Members: ';
foreach ($conversationMembers as $member) {
    echo $member->first_name . ' ' . $member->last_name . ', ';
}
echo '</b>'. Html::a('New member', 'message/members/' . $conversationId).'<hr>';


foreach ($messages as $message)
{
    echo '<div>';
    if($message->user->first_name || $message->user->last_name) {
        echo '<u> Author: ' . $message->user->first_name . ' ' . $message->user->last_name . '</u><br>';
    }
    echo '<p> ' . $message->body . '</p>';
}
$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));
echo $form->field($model, 'body')->textArea();
?>
<div class="control-group">
    <div class="controls">
        <?php echo Html::submitButton('Send', array('class' => 'btn btn-success')); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>


