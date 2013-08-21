<?php
use yii\helpers\Html;
?>
<div class="col-lg-offset-2">
    <h1>Notifications</h1>
    <ul class="nav nav-stacked" id = "notification-list">
        <?php foreach ($notifications as $notification): ?>
        <li class = "notification">
            <?php
            echo Html::beginTag('a', array('href'  => $notification['link']));
            echo Html::tag('span', '', array('class' => $notification['icon']));
            echo Html::tag('span', $notification['title']);
            echo Html::tag('p', $notification['description']);
            echo Html::endTag('a');
            ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>