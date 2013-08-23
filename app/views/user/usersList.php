<?php
use yii\helpers\Html;
use app\helpers\DateTimeHelper;
?>

<div class="col-lg-offset-2">
    <h1>Users</h1>
    <div class="navbar navbar-default navbar-left">
        <div class="collapse navbar-collapse navbar-ex2-collapse">
            <ul class="nav nav-pills navbar-btn">
                <li <?php echo 'all' == $filter ? 'class="active"' : ''?>>
                    <?php echo Html::a('All', '/user/list/all', array(
                        'id'    => 'filter-all',
                        'class' => 'cursorOnNoLink'
                    )); ?>
                </li>
                <li <?php echo 'online' == $filter ? 'class="active"' : ''?>>
                    <?php echo Html::a('Online', '/user/list/online', array(
                        'id'    => 'filter-available',
                        'class' => 'cursorOnNoLink'
                    )); ?>
                </li>
            </ul>
        </div>
    </div>
    <div class = "clear"></div>
    <ul class = "nav nav-stacked" id = "userlist">
        <?php foreach($users as $user):
            if($user->isOnline) {
                $lastActivity = 'online';
                $class = 'online';
            } else {
                $lastActivity = DateTimeHelper::relativeTime($user->last_activity);
                $class = 'offline';
            } ?>
            <li class = "userlist-item">
                <?php echo Html::beginTag('a', array(
                    'href'  => '/user/profile/'.$user->id,
                    'class' => $class
                ));
                echo Html::img($user->avatar, array(
                        'width' => '30',
                        'height' => '30',
                        'class' => 'img-rounded'
                    ));
                echo Html::tag('span', $user->userName, array('class' => 'userlist-name'));
                echo Html::tag('span', $lastActivity, array('class' => 'userlist-time'));
                echo Html::endTag('a'); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>