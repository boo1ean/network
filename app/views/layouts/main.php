<?php
use yii\helpers\Html;
use yii\widgets\Menu;
use yii\widgets\Breadcrumbs;
use app\widgets\UserBox;

/**
 * @var $this \yii\base\View
 * @var $content string
 */
app\config\AppAsset::register($this);
$this->beginPage();

//$guest = Yii::$app->getUser()->getIsGuest();
$authManager = Yii::$app->getComponent('authManager');
$controller_id = Yii::$app->controller->id;
$user = Yii::$app->getUser()->getIdentity();


$items = array(
    array('label' => 'Home', 'url' => array('/')),
);

if ($user === null) {
    $items[] = array('label' => 'Login', 'url' => array('/auth/login'));
} else {

    if ($authManager->checkAccess($user->id, 'admin')) {

        $items_sub = array(
            //array('label' => 'Users',            'url' => array('/admin/user')),
            //array('label' => 'Library',          'url' => array('/admin/library')),
            //array('label' => 'Send invite',      'url' => array('/admin/send-invite')),
            //array('label' => 'Send test invite', 'url' => array('/admin/send-invite-test'))
        );

        $items[] = array('label' => 'Administrate', 'url' => array('/admin'), 'items' => $items_sub, 'active' => 'admin' == $controller_id);
    }

    $items[] = array('label' => 'Conversations', 'url' => array('/conversation/conversation-list'), 'active' => 'conversation' == $controller_id);
    $items[] = array('label' => 'Library',       'url' => array('/library/books'));
    $items[] = array('label' => 'Calendar',      'url' => array('/calendar/calendar'));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title><?php echo Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
</head>

<body>

    <div class="container">

        <?php $this->beginBody(); ?>

        <div class="row">

            <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="side-bar">
                    <?php echo Menu::widget(array(
                        'options' => array('class' => 'side-bar-menu'),
                        'items' => $items,
                    )); ?>
                </div>
            </div>

            <div class="col-xs-10 col-sm-10 col-md-10">
                <div class="main-container" id="pjax-container">

                    <?php echo Breadcrumbs::widget(array(
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : array(),
                    )); ?>

                    <?php if(!Yii::$app->getUser()->getIsGuest()) {
                        $user = Yii::$app->getUser()->getIdentity();
                        echo UserBox::widget(array(
                            'avatar'              => $user->avatar,
                            'username'            => $user->userName,
                            'notificationsCount'  => $user->notificationsCount,
                            'link'                => '/user/profile/' . $user->id
                        ));
                    }
                    ?>

                    <?php echo $content; ?>

                </div>
            </div>

        </div>

        <hr>

        <div class="footer, text-center">
            <p>&copy; <a href="http://binary-studio.com">Binary Studio</a> <?php echo date('Y'); ?></p>
            <?php $this->endBody(); ?>
        </div>

    </div>

</body>
</html>
<?php $this->endPage(); ?>
