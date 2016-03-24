<?php

use yii\helpers\Url;
?>


<div class="panel panel-default members" id="share-panel">
    <?php echo \humhub\widgets\PanelMenu::widget(array('id' => 'share-panel')); ?>

    <div class="panel-heading">
        <?php echo Yii::t('DashboardModule.widgets_views_share', '<strong>Share</strong> your opinion with others'); ?>
    </div>
    <div class="panel-body">

        <p>We are glad that you are using <a href="http://yocommunity.example.com" target="_blank" class="colorInfo">Yo Community</a>. If you like it, please support the project and share Yocommunity with your friends and colleagues.</p>

        <a class="popup"
           href="http://twitter.com/intent/tweet?status=I'm really impressed by @Kodeplus. An Open Source Social Network for team communication and collaboration. I love it! http://yocommunity.example.com">
            <div class="share-icon share-icon-twitter"><i class="fa fa-twitter"></i></div>
            <div class="share-text"><?php echo Yii::t('DashboardModule.widgets_views_share', 'Tweet about Yo Community'); ?></div>
        </a>
        <!--<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'http';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>-->
        <div class="clearfix"></div>
        <a class="popup" href="http://www.facebook.com/share.php?u=http://yocommunity.example.com">
            <div class="share-icon share-icon-facebook"><i class="fa fa-facebook"></i></div>
            <div class="share-text"><?php echo Yii::t('DashboardModule.widgets_views_share', 'Post a message on Facebook'); ?></div>
        </a>

        <div class="clearfix"></div>
        <a class="popup" href="http://plus.google.com/share?url=http://yocommunity.example.com">
            <div class="share-icon share-icon-google-plus"><i class="fa fa-google-plus"></i></div>
            <div class="share-text"><?php echo Yii::t('DashboardModule.widgets_views_share', 'Share on Google+'); ?></div>
        </a>

        <div class="clearfix"></div>
        <a class="popup"
           href="http://www.linkedin.com/shareArticle?mini=true&url=http://yocommunity.example.com&title=Yo Community - The flexible Open Source Social Network Kit for Collaboration">
            <div class="share-icon share-icon-linkedin"><i class="fa fa-linkedin-square"></i></div>
            <div class="share-text"><?php echo Yii::t('DashboardModule.widgets_views_share', 'Share with people on LinkedIn '); ?></div>
        </a>

        <div class="clearfix"></div>

        <?php if (!Yii::$app->user->isGuest): ?>
            <hr>
            <a href="javascript:hideSharePanel();" class="colorInfo"><i class="fa fa-times"></i> Hide this panel</a>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.popup').click(function (event) {
            var width = 575,
                    height = 400,
                    left = ($(window).width() - width) / 2,
                    top = ($(window).height() - height) / 2,
                    url = this.href,
                    opts = 'status=1' +
                    ',width=' + width +
                    ',height=' + height +
                    ',top=' + top +
                    ',left=' + left;

            window.open(url, 'twitter', opts);

            return false;
        });
    });

    function hideSharePanel() {

        $.ajax({
            url: '<?= Url::to(["/dashboard/dashboard/hide-panel", "ajax" => 1]); ?>',
            //data: {id: '<id>', 'other': '<other>'},
            success: function (data) {
                $('#share-panel').remove();
            }
        });

    }


</script>

