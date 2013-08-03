<?php
    use yii\helpers\Html;
    use yii\widgets\LinkPager;
?>

    <h1>Users list</h1>
<div id="user-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="container-group">
    <div class="row-item">
        <span class="avatar head"> <?php echo Html::tag('p', 'Avatar'); ?>    </span>
        <span class="email head">  <?php echo Html::tag('p', 'E-mail');?>     </span>
        <span class="text head">   <?php echo Html::tag('p', 'First name');?> </span>
        <span class="text head">   <?php echo Html::tag('p', 'Last name');?>  </span>
        <span class="text head">   <?php echo Html::tag('p', 'Actions');?>  </span>
    </div>
    <?php foreach($users as $user):?>
        <div class="row-item" >
            <span class="avatar" id="<?php echo $user->id?>_avatar">     <?php echo Html::img($user->avatar); ?> </span>
            <span class="email"  id="<?php echo $user->id?>_email">      <?php echo $user->email;?>              </span>
            <span class="text"   id="<?php echo $user->id?>_first_name"> <?php echo $user->first_name;?>         </span>
            <span class="text"   id="<?php echo $user->id?>_last_name">  <?php echo $user->last_name;?>          </span>
            <span class="text" style="width: auto;">
            <?php

                echo Html::button('Edit', array(
                    'class'       => 'btn btn-success',
                    'data-id'     => $user->id,
                    'data-target' => '#user-modal',
                    'data-toggle' => 'modal',
                    'name'        => 'user-edit'
                ));
                echo Html::submitButton($user->is_active ? 'Block account' : 'Unblock account', array(
                    'class'   => $user->is_active ? 'btn btn-warning' : 'btn btn-info',
                    'data-id' => $user->id,
                    'name'    => 'user-block'
                ));
                echo Html::submitButton('Delete', array(
                    'class'   => 'btn btn-danger',
                    'data-id' => $user->id,
                    'name'    => 'user-delete'
                ));
            ?>
            </span>
        </div>
    <?php endforeach;?>
</div>
<?php if(!is_null($pagination)):?>
    <div class="pagination-centered">
        <?php echo LinkPager::widget(array('pagination' => $pagination)); ?>
    </div>
<?php endif;?>
