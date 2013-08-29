<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
$tags_string = empty($tags_filter) ? '' : '/' . implode('/', $tags_filter);
?>

    <h1> Library </h1>

    <div class="navbar navbar-default navbar-left">
        <div class="collapse navbar-collapse navbar-ex2-collapse">
            <ul class="nav nav-pills navbar-btn">
                <li <?php echo 'all' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('All', '/library/books/all/'.$order.'/'.$page.$tags_string); ?>
                </li>
                <li <?php echo 'available' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('Available', '/library/books/available/'.$order.'/'.$page.$tags_string); ?>
                </li>
                <li <?php echo 'taken' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('Taken', '/library/books/taken/'.$order.'/'.$page.$tags_string); ?>
                </li>
                <li <?php echo 'ask' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('In queue', '/library/books/ask/'.$order.'/'.$page.$tags_string); ?>
                </li>
            </ul>
        </div>
    </div>

    <div class="navbar navbar-default navbar-left col-lg-offset-1">
        <div class="collapse navbar-collapse navbar-ex2-collapse">
            <ul class="nav nav-pills navbar-btn">
                <li <?php echo 'author-asc' == $order ? 'class="active"' : ''?>>
                    <?php echo Html::a('Author &#8593;', '/library/books/'.$status.'/author-asc/'.$page.$tags_string); ?>
                </li>
                <li <?php echo 'author-desc' == $order ? 'class="active"' : ''?>>
                    <?php echo Html::a('Author &#8595;', '/library/books/'.$status.'/author-desc/'.$page.$tags_string); ?>
                </li>
                <li <?php echo 'title-asc' == $order ? 'class="active"' : ''?>>
                    <?php echo Html::a('Title &#8593;', '/library/books/'.$status.'/title-asc/'.$page.$tags_string); ?>
                </li>
                <li <?php echo 'title-desc' == $order ? 'class="active"' : ''?>>
                    <?php echo Html::a('Title &#8595;', '/library/books/'.$status.'/title-desc/'.$page.$tags_string); ?>
                </li>
            </ul>
        </div>
    </div>

    <br/><br/><br/><br/>
    <div class="navbar navbar-default">
        <div class="collapse navbar-collapse navbar-ex2-collapse">
            <?php foreach ($tags as $tag):
                $class = '';
                $href  = '';

                if(!empty($tags_filter)) {
                    foreach ($tags_filter as $key => $tag_filter) {
                        if($tag_filter == $tag->title) {
                            $class = 'btn-primary';
                            $tmp = $tags_filter;
                            unset($tmp[$key]);
                            if(count($tmp) > 0) {
                                $href = '/library/books/'.$status.'/'.$order.'/'.$page.'/'.implode('/', $tmp);
                            } else {
                                $href = '/library/books/'.$status.'/'.$order.'/'.$page;
                            }
                            break;
                        } else {
                            $href = '/library/books/'.$status.'/'.$order.'/'.$page.$tags_string.'/'.$tag->title;
                        }
                    }
                } else {
                    $href = '/library/books/'.$status.'/'.$order.'/'.$page.'/'.$tag->title;
                } ?>

                <div class="btn-group navbar-btn">
                    <?php echo html::a($tag->title, $href, array('class' => 'btn btn-sm ' . $class )); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
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
                                $class = 'label-info';
                                $href  = '';

                                if(!empty($tags_filter)) {
                                    foreach ($tags_filter as $key => $tag_filter) {
                                        if($tag_filter == $tag->title) {
                                            $class = 'label-primary';
                                            $tmp = $tags_filter;
                                            unset($tmp[$key]);
                                            if(count($tmp) > 0) {
                                                $href = '/library/books/'.$status.'/'.$order.'/'.$page.'/'.implode('/', $tmp);
                                            } else {
                                                $href = '/library/books/'.$status.'/'.$order.'/'.$page;
                                            }
                                            break;
                                        } else {
                                            $href = '/library/books/'.$status.'/'.$order.'/'.$page.$tags_string.'/'.$tag->title;
                                        }
                                    }
                                } else {
                                    $href = '/library/books/'.$status.'/'.$order.'/'.$page.'/'.$tag->title;
                                }

                                echo Html::a($tag->title, $href, array(
                                    'id'    => $tag->title,
                                    'class' => 'label '.$class
                                )).' ';
                            } ?>
                        </div>
                        <br/><br/><br/><br/>
                        <div class="navbar-left">
                            <a class="accordion-toggle" data-parent="#books-list" data-toggle="collapse" href="<?php echo '#'.$book['id'].'-collapse'?>">
                                Description
                            </a>
                        </div>
                        <div class="navbar-right" style="width: 80%;">
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
                                } elseif ('taken' == $book['status']) {?>
                                    <div class=" progress progress-striped" style="margin-bottom: 0px;">
                                        <div class="<?php echo 'progress-bar progress-bar-'.$book['class']?>"
                                             style="<?php echo 'position: relative; width:'.$book['percent'].'%'?>">
                                            <div <?php echo $book['percent'] < 10 ? 'class="progress-bar-left"' : ''?>>
                                                <?php echo $book['percent']?>%
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
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