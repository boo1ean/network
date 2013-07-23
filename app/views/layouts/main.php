<?php
use yii\helpers\Html;
use yii\widgets\Menu;
use yii\widgets\Breadcrumbs;

/**
 * @var $this \yii\base\View
 * @var $content string
 */
app\config\AppAsset::register($this);
$this->beginPage();

$guest = Yii::$app->getUser()->getIsGuest();

$items = array(
    array('label' => 'Home', 'url' => array('/site/index')),
);

if ($guest) {
    $items[] = array('label' => 'Login', 'url' => array('/auth/login'));
} else {
    $items[] = array('label' => 'Messages', 'url' => array('/message/conversation'), 'active' => Yii::$app->controller->id == 'message');
    $items[] = array('label' => 'Edit profile', 'url' => array('/auth/edit'));
    $items[] = array('label' => 'Logout', 'url' => array('/auth/logout'));
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
                            'options' => array('class' => 'nav'),
                            'items' => $items,
                        )); ?>
                    </div>
                </div>
            </div>
            <!-- /.navbar -->
        </div>

        <div class="main-container">
            <?php echo Breadcrumbs::widget(array(
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : array(),
            )); ?>

            <?php echo $content; ?>

            <hr>

        <div class="footer, text-center">
            <p>&copy; <a href="http://binary-studio.com">Binary Studio</a> <?php echo date('Y'); ?></p>
            <?php $this->endBody(); ?>
        </div>

    </div>
</body>
</html>
<?php $this->endPage(); ?>
