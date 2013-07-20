<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sophie
 * Date: 19.07.13
 * Time: 22:50
 * To change this template use File | Settings | File Templates.
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

echo '<h1>' . Html::a($conversationTitle, 'message/conversation/' . $conversationId) . '</h1>';
echo '<b> Members: ';
foreach ($conversationMembers as $member) {
    echo $member->first_name . ' ' . $member->last_name . ', ';
}
echo '</b><hr>';
$members = array();
foreach($unsubscribedUsers as $user) {
    $members[$user->id] = $user->first_name . ' ' . $user->last_name . ' (' . $user->email . ')';
}
$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));
echo Html::checkboxList('members', null, $members, null);
echo Html::submitButton('Invite', array('class' => 'btn btn-success'));
ActiveForm::end();