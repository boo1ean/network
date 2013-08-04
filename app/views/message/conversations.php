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

<h1>Conversations
    <?php
    // Link for new conversation creation
    echo Html::tag(
        'button',
        '+ New conversation',
        array(
            'class'       => 'btn btn-success',
            'data-target' => '#conversation-create-modal',
            'data-toggle' => 'modal',
            'id'          => 'conversation-create'
        )
    );
    ?>
</h1>
<div id="conversation-create-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true"></div>

<?php // Print list of conversations
    foreach ($conversations as $conversation):
        $title = $conversation->title == NULL ? 'conversation #' . $conversation->id : $conversation->title;
?>
    <ul class="nav nav-tabs nav-stacked" id="conversation-list">
        <li><?php echo Html::a($title, array('message/conversation/' . $conversation->id)); ?></li>
    </ul>
<?php endforeach; ?>
