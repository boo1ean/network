<?php
    use yii\helpers\Html;
    use yii\widgets\LinkPager;
?>

    <h1>Users list</h1>
<div id="fancy_frame_user" style="display:none;"></div>
<div class="container-group">
    <div class="row-item">
        <span class="avatar head"> <?php echo Html::tag('p', 'Avatar'); ?>    </span>
        <span class="email head">  <?php echo Html::tag('p', 'E-mail');?>     </span>
        <span class="text head">   <?php echo Html::tag('p', 'First name');?> </span>
        <span class="text head">   <?php echo Html::tag('p', 'Last name');?>  </span>
        <span class="text head">   <?php echo Html::tag('p', 'Actions');?>  </span>
    </div>
    <?php foreach($users as $user):?>
        <div class="row-item">
            <span class="avatar"> <?php echo Html::img($user->avatar); ?> </span>
            <span class="email">  <?php echo $user->email;?>              </span>
            <span class="text">   <?php echo $user->first_name;?>         </span>
            <span class="text">   <?php echo $user->last_name;?>          </span>
            <span class="text" style="width:auto;">
            <?php
                echo Html::submitButton('Edit',          array('id' => $user->id, 'class' => 'btn btn-success', 'onclick' => 'editUser(id)'));
                echo Html::submitButton('Delete',        array('id' => $user->id, 'class' => 'btn btn-danger'));
                echo Html::submitButton('Send on email', array('id' => $user->id, 'class' => 'btn btn-info'));
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