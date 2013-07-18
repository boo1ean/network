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
// Print list of conversations
foreach ($conversations as $conversation) {
    $title = $conversation->title == NULL ? 'conversation #' . $conversation->id : $conversation->title;
    echo Html::a($title, 'message/conversation/' . $conversation->id);
    echo '<br>';
}
$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));
echo $form->field($model, 'title')->textInput();
?>
    <div class="control-group">
        <div class="controls">
            <?php echo Html::submitButton('New conversation', array('class' => 'btn btn-success')); ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>