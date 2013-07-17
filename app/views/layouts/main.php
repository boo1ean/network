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
    $items[] = array('label' => 'Registration', 'url' => array('/auth/registration'));
} else {
    $items[] = array('label' => 'Messages', 'url' => array('/message/conversation'));
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
            <h3 class="muted">My Company</h3>

            <div class="navbar">
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

        <?php echo Breadcrumbs::widget(array(
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : array(),
        )); ?>

        <?php echo $content; ?>

        <hr>

        <div class="footer">
            <p>&copy; My Company <?php echo date('Y'); ?></p>
            <p>
                <?php echo Yii::powered(); ?>
                Template by <a href="http://twitter.github.io/bootstrap/">Twitter Bootstrap</a>
            </p>
        </div>
        <?php $this->endBody(); ?>
    </div>
</body>
</html>
<?php $this->endPage(); ?>
