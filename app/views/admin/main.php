<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

    <h1>Main administration</h1>

<?php
$form = ActiveForm::begin();
echo Html::tag('p', 'Page should be contained general settings of the network');
?>

<?php ActiveForm::end(); ?>