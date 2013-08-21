<?php
    use yii\helpers\Html;
    use yii\widgets\LinkPager;
?>
<div class="col-lg-offset-1">
    <h1>Users list</h1>
    <div class="modal fade" id="user-modal"></div>
    <table class="table table-hover">
        <tr >
            <td > <?php echo Html::tag('p', 'Avatar'); ?>    </td>
            <td > <?php echo Html::tag('p', 'E-mail');?>     </td>
            <td > <?php echo Html::tag('p', 'First name');?> </td>
            <td > <?php echo Html::tag('p', 'Last name');?>  </td>
            <td > <?php echo Html::tag('p', 'Actions');?>    </td>
        </tr>
        <?php foreach($users as $user):?>
            <tr <?php echo $user->is_active ? '' : 'class="danger"' ?> >
                <td id="<?php echo $user->id?>_avatar" class="text-center">
                    <?php echo Html::img($user->avatar, array('class' => 'avatar small')); ?>
                </td>
                <td id="<?php echo $user->id?>_email">      <?php echo $user->email;?>              </td>
                <td id="<?php echo $user->id?>_first_name"> <?php echo $user->first_name;?>         </td>
                <td id="<?php echo $user->id?>_last_name">  <?php echo $user->last_name;?>          </td>
                <td >
                <?php

                    echo Html::button('Edit', array(
                        'class'   => 'btn btn-sm btn-success',
                        'data-id' => $user->id,
                        'name'    => 'user-edit'
                    ));
                    echo Html::submitButton($user->is_active ? 'Block account' : 'Unblock account', array(
                        'class'   => $user->is_active ? 'btn btn-sm btn-warning' : 'btn btn-sm btn-info',
                        'data-id' => $user->id,
                        'name'    => 'user-block'
                    ));
                    echo Html::submitButton('Delete', array(
                        'class'   => 'btn btn-sm btn-danger',
                        'data-id' => $user->id,
                        'name'    => 'user-delete'
                    ));
                ?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
</div>
<?php if(!is_null($pagination)):?>
    <div class="text-center">
        <?php echo LinkPager::widget(array('pagination' => $pagination)); ?>
    </div>
<?php endif;?>
