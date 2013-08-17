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
<div class="col-lg-offset-2">
    <h1>Conversations
        <?php
        // Link for new conversation creation
        echo Html::tag(
            'button',
            '+ New conversation',
            array(
                'class' => 'btn btn-success',
                'id'    => 'conversation-create'
            )
        );
        ?>
    </h1>
    <div id="conversation-create-modal" class="modal fade"></div>
    <ul class="nav nav-stacked" id="conversation-list">
        <?php // Print list of conversations
            foreach ($conversations as $conversation):
                $title = $conversation['title'] == NULL ? 'conversation #' . $conversation['id'] : $conversation['title'];
        ?>
        <li>
            <?php
            // Class for link to conversation depends on read state of conversation
            $aClass = 'conversation';
            if($conversation['unread']) {
                $aClass .= ' unread';
            }
            // Begin to make link for conversation
            echo Html::beginTag('a', array(
                'href' => '/conversation/' . $conversation['id'],
                'class' => $aClass
            ));
            echo Html::beginTag('div', array('class' => 'conversation_info'));
            // Title of conversation
            echo Html::tag('span', $title, array('class' => 'conversation_title'));
            // Users of conversation
            echo Html::beginTag('div', array('class' => 'conversation_users'));
            //echo Html::tag('b', $members);
            foreach ($conversation['users'] as $user) {
                // User avatar
                echo Html::img($user->avatar, array(
                    'width' => '20',
                    'height' => '20',
                    'class' => 'img-rounded'
                ));
                // User name
                echo ' ' . $user->userName;
                echo Html::tag('br');
            }
            echo Html::endTag('div');
            echo Html::endTag('div');
            // Last message in conversation
            if(isset($conversation['lastMessage'])) {
                echo Html::beginTag('div', array('class' => 'conversation_message'));

                echo Html::img($conversation['lastMessageAvatar'], array(
                    'width' => '50',
                    'height' => '50',
                    'class' => 'img-rounded left'
                ));
                echo Html::tag('b', $conversation['lastMessageUser'] . ' ( ' . $conversation['lastMessage']->datetime . ' )');
                echo Html::tag('p', $conversation['lastMessage']->body);
                echo Html::endTag('div');
            }
            echo Html::endTag('a');
            ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
