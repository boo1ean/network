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
    array('label' => 'Home', 'url' => array('/site/index')),
);

if ($user === null) {
    $items[] = array('label' => 'Login', 'url' => array('/auth/login'));
} else {

    if ($authManager->checkAccess($user->id, 'admin')) {
        $items_sub = array(
            array('label' => 'Users',            'url' => array('/admin/user')),
            array('label' => 'Library',          'url' => array('/admin/library')),
            array('label' => 'Send invite',      'url' => array('/admin/send-invite')),
            //array('label' => 'Send test invite', 'url' => array('/admin/send-invite-test'))
        );

        $items[] = array('label' => 'Administrate', 'url' => array('/admin'), 'items' => $items_sub, 'active' => 'admin' == $controller_id);
    }

    $items[] = array('label' => 'Conversations', 'url' => array('/conversation/conversation-list'), 'active' => 'conversation' == $controller_id);
    $items[] = array('label' => 'Library',       'url' => array('/library/books'));

    $items_cal_sub = array(
        array('label' => 'Settings',            'url' => array('/calendar/settings')),
    );

    $items[] = array('label' => 'Calendar',      'url' => array('/calendar/calendar'), 'items' => $items_cal_sub);
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
        <div class="masthead">
            <div class="navbar navbar-fixed-top">
                <div class="navbar-inner">
                    <div class="container">
                        <?php echo Menu::widget(array(
                            'options' => array('class' => 'nav navbar-nav'),
                            'items' => $items,
                        )); ?>
                    </div>
                </div>
            </div>
            <!-- /.navbar -->
        </div>

        <div class="main-container" id="pjax-container">
            <?php echo Breadcrumbs::widget(array(
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : array(),
            )); ?>

            <?php if(!Yii::$app->getUser()->getIsGuest()) {
                echo UserBox::widget(array(
                    'avatar'              => Yii::$app->getUser()->getIdentity()->avatar,
                    'username'            => Yii::$app->getUser()->getIdentity()->userName,
                    'notificationsCount'  => Yii::$app->getUser()->getIdentity()->notificationsCount,
                ));
            }
            ?>

            <?php echo $content; ?>
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
