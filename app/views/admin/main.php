<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

    <h1>Main administration</h1>

<?php
$form = ActiveForm::begin();
echo Html::tag('p', 'Page should be contained general settings of the network');
?>

<?php echo Html::a('Users', '/admin/user', array(
    'class' => 'btn btn-primary',
    'data-pjax' => '#pjax-container'
)); ?>

<?php echo Html::a('Library', '/admin/library', array(
    'class' => 'btn btn-primary',
    'data-pjax' => '#pjax-container'
));
?>

<?php echo Html::a('Send invite', '/admin/send-invite', array(
    'class' => 'btn btn-primary',
    'data-pjax' => '#pjax-container'
));
?>

<?php ActiveForm::end(); ?>