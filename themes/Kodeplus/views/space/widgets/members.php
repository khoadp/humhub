<?php

use yii\helpers\Html;
?>

<div class="panel panel-default members" id="space-members-panel">
    <?php echo \humhub\widgets\PanelMenu::widget(['id' => 'space-members-panel']); ?>
    <div class="panel-heading"><?php echo Yii::t('SpaceModule.widgets_views_spaceMembers', '<strong>Space</strong> members'); ?></div>
    <div class="panel-body">
        <?php
        $conversationId = '';
        $conversationUsers = [];
        if(!Yii::$app->user->isGuest)
        {
            $connection = Yii::$app->getDb();
            $sql = 'SELECT * from chat_conversation_user where conversation_id IN (SELECT chat_conversation_user.conversation_id FROM chat_conversation_user JOIN chat_conversation on chat_conversation.id = chat_conversation_user.conversation_id and chat_conversation.type = 0 where user_id = '.Yii::$app->user->id.' and chat_conversation.space_id = '.$space->id.') and user_id <> '.Yii::$app->user->id;
            $conversationUsers = $connection->createCommand($sql)->queryAll();
        }

        ?>
        <?php foreach ($users as $user) { ?>
            <?php
            $conversationId = 0;
            foreach ($conversationUsers as $conversationUser) {
                if ($user->id == $conversationUser['user_id']) {
                    $conversationId = $conversationUser['conversation_id'];
                    break;
                }
            }
            ?>
            <a id="conversation_id_<?= $conversationId ?>" href="<?php echo $user->getUrl(); ?> " class="popup-chat-open">
                <input type="hidden" class="chat_user_id hide" value="<?= $user->id ?>"/>
                <img src="<?php echo $user->getProfileImage()->getUrl(); ?>" class="img-rounded tt img_margin"
                     height="24" width="24" alt="24x24" data-src="holder.js/24x24"
                     style="width: 24px; height: 24px;" data-toggle="tooltip" data-placement="top" title=""
                     data-original-title="<?php echo Html::encode($user->displayName); ?>">
            </a>
        <?php }?>
        <?php if (count($users) == $maxMembers) : ?>
            <br>
            <a href="<?php echo $space->createUrl('/space/membership/members-list'); ?>" data-target="#globalModal" class="btn btn-default btn-sm"><?php echo Yii::t('SpaceModule.widgets_views_spaceMembers', 'Show all'); ?></a>
        <?php endif; ?>
    </div>
</div>