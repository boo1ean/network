<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>

    <h1> Library </h1>

    <div class="navbar navbar-default navbar-left">
        <div class="collapse navbar-collapse navbar-ex2-collapse">
            <ul class="nav nav-pills navbar-btn">
                <li <?php echo 'all' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('All', '/library/books/all/'.$order.'/'.$page); ?>
                </li>
                <li <?php echo 'available' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('Available', '/library/books/available/'.$order.'/'.$page); ?>
                </li>
                <li <?php echo 'taken' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('Taken', '/library/books/taken/'.$order.'/'.$page); ?>
                </li>
            </ul>
        </div>
    </div>

    <div class="navbar navbar-default navbar-left col-lg-offset-1">
        <div class="collapse navbar-collapse navbar-ex2-collapse">
            <ul class="nav nav-pills navbar-btn">
                <li <?php echo 'author-asc' == $order ? 'class="active"' : ''?>>
                    <?php echo Html::a('Author &#8593;', '/library/books/'.$status.'/author-asc/'.$page); ?>
                </li>
                <li <?php echo 'author-desc' == $order ? 'class="active"' : ''?>>
                    <?php echo Html::a('Author &#8595;', '/library/books/'.$status.'/author-desc/'.$page); ?>
                </li>
                <li <?php echo 'title-asc' == $order ? 'class="active"' : ''?>>
                    <?php echo Html::a('Title &#8593;', '/library/books/'.$status.'/title-asc/'.$page); ?>
                </li>
                <li <?php echo 'title-desc' == $order ? 'class="active"' : ''?>>
                    <?php echo Html::a('Title &#8595;', '/library/books/'.$status.'/title-desc/'.$page); ?>
                </li>
            </ul>
        </div>
    </div>

    <br/><br/><br/><br/>
    <ul class="nav nav-list panel-group" id="books-list">
        <?php foreach ($books as $book):?>
            <li <?php
                if('E-book' == $book['type']) {
                    echo 'class="panel panel-default"';
                } else {
                    switch ($book['status']) {
                        case 'ask':
                            echo 'class="panel panel-warning"';
                            break;
                        case 'available':
                            echo 'class="panel panel-success"';
                            break;
                        case 'taken':
                            echo 'class="panel panel-danger"';
                            break;
                    }
                } ?>
                >
                <div class="panel-heading ">
                    <h4 class="panel-title modal-header" style="border-bottom: 0px;">
                        <div class="navbar-left" id="<?php echo $book['id'].'-title-author'?>">
                            <b class="h2" title="Book title">
                                <?php echo $book['title']; ?>
                            </b>
                            <div title="Book author">
                                <?php echo $book['author']; ?>
                            </div>
                        </div>
                        <div class="navbar-right">
                            <?php
                            foreach ($book['tags'] as $tag) {
                                echo Html::a($tag->title, null, array(
                                    'id'      => $tag->title,
                                    'class'   => 'label label-info'
                                )).' ';
                            } ?>
                        </div>
                        <br/><br/><br/><br/>
                        <div class="navbar-left">
                            <a class="accordion-toggle" data-parent="#books-list" data-toggle="collapse" href="<?php echo '#'.$book['id'].'-collapse'?>">
                                Description
                            </a>
                        </div>
                        <div class="navbar-right">
                            <?php
                                if ('E-book' == $book['type']) {
                                    echo Html::a('Download', $book['link'] ? $book['link'] : null, array(
                                        'class'  => 'label label-success',
                                        'style'  => $book['link'] ? '' : 'text-decoration: line-through',
                                        'target' => '_blank'));
                                } elseif ($book['show_ask'] && 'taken' != $book['status']) {
                                    echo Html::button('Ask for book', array(
                                        'class'   => 'btn btn-xs',
                                        'data-id' => $book['id'],
                                        'name'    => 'book-ask'
                                    ));
                            } ?>
                        </div>
                    </h4>
                </div>
                <div id="<?php echo $book['id'].'-collapse'?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <blockquote>
                            <p><?php echo $book['description']; ?></p>
                        </blockquote>
                    </div>
                </div>
            </li>
        <?php endforeach;?>
    </ul>

<?php if(!is_null($pagination)):?>
    <div class="text-center">
        <?php echo LinkPager::widget(array('pagination' => $pagination)); ?>
    </div>
<?php endif;?>