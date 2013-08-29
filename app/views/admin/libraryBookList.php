<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>

    <h1> Book list
        <?php echo Html::tag(
            'button',
            '+ New book',
            array(
                'class'   => 'btn btn-success',
                'data-id' => 0,
                'id'      => 'book-create'
            )
        ); ?>
    </h1>

    <div class="navbar navbar-default navbar-left">
        <div class="collapse navbar-collapse navbar-ex2-collapse">
            <ul class="nav nav-pills navbar-btn">
                <li <?php echo 'all' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('All', '/admin/library/all/'.$order.'/'.$page); ?>
                </li>
                <li <?php echo 'available' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('Available', '/admin/library/available/'.$order.'/'.$page); ?>
                </li>
                <li <?php echo 'taken' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('Taken', '/admin/library/taken/'.$order.'/'.$page); ?>
                </li>
                <li <?php echo 'ask' == $status ? 'class="active"' : ''?>>
                    <?php echo Html::a('In queue', '/admin/library/ask/'.$order.'/'.$page); ?>
                </li>
            </ul>
        </div>
    </div>

    <div class="modal fade" id="book-modal"></div>
    <table class="table table-hover" id="book-table">
        <tr >
            <td >
                <?php echo Html::tag('b', 'Author'); ?>
                <?php echo Html::a('&#8593;', '/admin/library/'.$status.'/author-asc/'.$page, array(
                    'class' => 'label '.('author-asc' == $order ? 'label-info' : 'label-default')
                )); ?>
                <?php echo Html::a('&#8595;', '/admin/library/'.$status.'/author-desc/'.$page, array(
                    'class' => 'label '.('author-desc' == $order ? 'label-info' : 'label-default')
                )); ?>
            </td>
            <td >
                <?php echo Html::tag('b', 'Title');?>
                <?php echo Html::a('&#8593;', '/admin/library/'.$status.'/title-asc/'.$page, array(
                    'class' => 'label '.('title-asc' == $order ? 'label-info' : 'label-default')
                )); ?>
                <?php echo Html::a('&#8595;', '/admin/library/'.$status.'/title-desc/'.$page, array(
                    'class' => 'label '.('title-desc' == $order ? 'label-info' : 'label-default')
                )); ?>
            </td>
            <td > <?php echo Html::tag('b', 'Type');?>    </td>
            <td > <?php echo Html::tag('b', 'Actions');?> </td>
        </tr>
        <tr style="display:none" id="recently-added" class="text-center">
            <td colspan="4"><b class="text-info">Recently added</b></td>
        </tr>
        <tr style="display:none" class="fade" id="recently-added-bottom">
            <td colspan="4" style="border-top: 2px solid #000000"></td>
        </tr>
        <?php foreach($books as $book):?>
            <tr <?php
                echo 'id="'.$book['id'].'" ';
                if('E-book' == $book['type']) {
                    echo 'class="default"';
                } else {
                    switch ($book['status']) {
                        case 'ask':
                            echo 'class="warning"';
                            break;
                        case 'available':
                            echo 'class="success"';
                            break;
                        case 'taken':
                            echo 'class="danger"';
                            break;
                    }
                } ?> >
                <td id="<?php echo $book['id']?>_author"> <?php echo $book['author'];?> </td>
                <td id="<?php echo $book['id']?>_title">  <?php echo $book['title'];?>  </td>
                <td id="<?php echo $book['id']?>_type">   <?php echo $book['type'];?>   </td>
                <td >
                    <?php
                    echo Html::button('Edit', array(
                        'class'   => 'btn btn-sm btn-success',
                        'data-id' => $book['id'],
                        'name'    => 'book-edit'
                    ));
                    echo Html::button('Delete', array(
                        'class'   => 'btn btn-sm btn-danger',
                        'data-id' => $book['id'],
                        'name'    => 'book-delete'
                    ));
                    if('E-book' == $book['type']) {
                        echo Html::a('Download', $book['link'], array(
                            'class'   => 'col-sm-offset-1 btn btn-sm btn-primary '.($book['link'] ? '' : 'disabled'),
                            'data-id' => $book['id'],
                            'name'    => 'book-download',
                            'target'  => '_blank'
                        ));
                    } elseif ('ask' == $book['status']) {
                        echo Html::button('Queue', array(
                            'class'   => 'col-sm-offset-1 btn btn-sm btn-primary',
                            'data-id' => $book['id'],
                            'name'    => 'book-queue'
                        ));
                    } elseif ('taken' == $book['status'] && !empty($book['taken_info'])) {
                        echo Html::button('Return', array(
                            'class'        => 'col-sm-offset-1 btn btn-sm btn-primary',
                            'data-id-book' => $book['id'],
                            'data-id-user' => $book['taken_info']['user_id'],
                            'name'         => 'book-return'
                        ));?>
                        <br/>
                        <div class=" progress progress-striped navbar-btn">
                            <div class="<?php echo 'progress-bar progress-bar-'.$book['taken_info']['class']?>"
                                 style="<?php echo 'position: relative; width:'.$book['taken_info']['percent'].'%'?>">
                                <div <?php echo $book['taken_info']['percent'] < 10 ? 'class="progress-bar-left"' : ''?>>
                                    <?php echo $book['taken_info']['percent']?>%
                                </div>
                            </div>
                        </div>
                    <?php }?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>

<?php if(!is_null($pagination)):?>
    <div class="text-center">
        <?php echo LinkPager::widget(array('pagination' => $pagination)); ?>
    </div>
<?php endif;?>