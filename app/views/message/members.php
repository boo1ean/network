<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));
echo Html::textInput('title', $conversationTitle); ?>
<br>
<?php if(isset($conversationMembers)): ?>
    <b> Members:
    <?php foreach ($conversationMembers as $member) {
        echo $member->userName . ', ';
    } ?>
    </b><hr>
<?php endif;

$members = array();
foreach($unsubscribedUsers as $user) {
    $members[$user->id] = $user->userName;
}

echo Html::checkboxList('members', null, $members, null);
echo Html::submitButton('Invite', array('class' => 'btn btn-success'));
ActiveForm::end();