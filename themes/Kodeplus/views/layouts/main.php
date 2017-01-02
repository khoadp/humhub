<?php

use yii\helpers\Html;
use humhub\assets\AppAsset;
use yii\helpers\Url;
use humhub\models\Setting;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

// FIXME
if (Yii::$app->hasModule('classified')) {
    \kodeplus\modules\classified\GalleryAsset::register($this);
    \kodeplus\modules\classified\DosamigosAsset::register($this);
}

// FIXME
//if (Yii::$app->hasModule('map')) {
//    \kodeplus\modules\map\library\MapAssets::register($this);
//}

if (!isset($this->context->contentContainer)) {
    if (isset($_SESSION['views']) && isset($_SESSION['space'])) {
        unset($_SESSION['views']);
        unset($_SESSION['space']);
    }
} else {
    if (isset($_SESSION['space'])) {
        if ($_SESSION['space'] !== $this->context->contentContainer->id)
            if (isset($_SESSION['views'])) {
                unset($_SESSION['views']);
                unset($_SESSION['space']);
            }
    }
}
//$keyword = Yii::$app->request->get('keyword', "");
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <!-- start: Meta -->
        <meta charset="utf-8">
        <title><?php echo $this->pageTitle; ?></title>
        <!-- end: Meta -->

        <!-- start: Mobile Specific -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <!-- end: Mobile Specific -->
        <?= Html::csrfMetaTags() ?>
        <?php $this->head() ?>

        <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="<?php echo Yii::getAlias(" @web"); ?>/js/html5shiv.js"></script>
        <
        linkid = "ie-style"
        href = "<?php echo Yii::getAlias("
        @
        web
        "); ?>/css/ie.css"
        rel = "stylesheet" / >
        <![endif]-->

        <!--[if IE 9]>
        <link id="ie9style" href="<?php echo Yii::getAlias(" @web"); ?>/css/ie9.css" rel="stylesheet">
        <![endif]-->

        <!-- start: render additional head (css and js files) -->
        <?php echo $this->render('head'); ?>
        <!-- end: render additional head -->


        <!-- start: Favicon and Touch Icons -->
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo Yii::getAlias("@web"); ?>/ico/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo Yii::getAlias("@web"); ?>/ico/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72"
                href="<?php echo Yii::getAlias("@web"); ?>//ico/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo Yii::getAlias("@web"); ?>/ico/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114"
                href="<?php echo Yii::getAlias("@web"); ?>/ico/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120"
                href="<?php echo Yii::getAlias("@web"); ?>/ico/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144"
                href="<?php echo Yii::getAlias("@web"); ?>/ico/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152"
                href="<?php echo Yii::getAlias("@web"); ?>/ico/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180"
                href="<?php echo Yii::getAlias("@web"); ?>/ico/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"
                href="<?php echo Yii::getAlias("@web"); ?>/ico/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32"
                href="<?php echo Yii::getAlias("@web"); ?>/ico/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96"
                href="<?php echo Yii::getAlias("@web"); ?>/ico/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16"
                href="<?php echo Yii::getAlias("@web"); ?>/ico/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <meta charset="<?= Yii::$app->charset ?>">
        <!-- end: Favicon and Touch Icons -->

    </head>

    <style>

        #search-menu-nav {
            width: 31.7%;
            margin-top: 8px;
        }

        @media only screen and (max-width: 370px) {
            #search-menu-nav {
                width: 100%;
                margin-top: -0.3%;
            }
        }

        @media only screen and (min-width: 371px) and (max-width: 700px) {
            #search-menu-nav {
                width: 45%;
                margin-top: 8px%;
            }
        }
    </style>

    <body>
    <?php
    $primaryColor = '#708fa0';
    $infoColor = '#6fdbe8';
    if (Yii::$app->settings->get('primaryColor')) {
        $primaryColor = Yii::$app->settings->get('primaryColor');
        $infoColor = Yii::$app->settings->get('primaryColor');
    }
    echo '<script>window.primaryColor = "' . $primaryColor . '";</script>';
    if (isset($this->context->contentContainer) && ($this->context->contentContainer instanceof \humhub\modules\space\models\Space)) {
        $infoColor = $this->context->contentContainer->color;
    }
    echo '<script>window.infoColor = "' . $infoColor . '";</script>';

    ?>
    <script>
        less.modifyVars({
            '@primary': window.primaryColor,
            '@info': window.infoColor
        });
    </script>
    <?php $this->beginBody() ?>

    <!-- start: first top navigation bar -->
    <div id="topbar-first" class="topbar">
        <div class="container">
            <div class="topbar-brand hidden-xs">
                <?php echo \humhub\widgets\SiteLogo::widget(); ?>
            </div>

            <div class="topbar-actions pull-right">
                <?php echo \kodeplus\modules\kodeplus_user\widgets\AccountTopMenu::widget(); ?>
            </div>
            <div class="topbar-actions pull-right" style="line-height:3.6em">
                <?php
                echo \kodeplus\modules\kodeplus_user\widgets\Language::widget();
                ?>

            </div>
            <div class="notifications pull-right">

                <?php
                echo \humhub\widgets\NotificationArea::widget([
                    'widgets' => [
                        [\humhub\modules\notification\widgets\Overview::className(), [], ['sortOrder' => 10]],
                    ]
                ]);
                ?>
                <?php
                if (getenv('CHAT_SYSTEM_ENABLE') == 'true') {
                    echo \humhub\widgets\NotificationArea::widget([
                        'widgets' => [
                            [\kodeplus\modules\kodeplus_chat\widgets\Overview::className(), [], ['sortOrder' => 10]],
                        ]
                    ]);
                }
                ?>
            </div>

        </div>

    </div>
    <!-- end: first top navigation bar -->


    <!-- start: second top navigation bar -->
    <div id="topbar-second" class="topbar">
        <div class="container">
            <ul class="nav ">
                <!-- load space chooser widget -->
                <?php echo \humhub\modules\space\widgets\Chooser::widget(); ?>

                <!-- load navigation from widget -->
                <?php echo \humhub\widgets\TopMenu::widget(); ?>
            </ul>

            <ul class="nav pull-right" id="search-menu-nav">
                <?php echo Html::beginForm(Url::to(['/search/search/index']), 'GET'); ?>
                <div class="form-group form-group-search">

                    <?php echo Html::textInput('keyword', '',
                        array('class' => 'form-control form-search', 'id' => 'search-input-field')); ?>

                    <?php echo Html::submitButton(\humhub\widgets\TopMenuRightStack::widget(),
                        array('class' => 'btn btn-default btn-sm form-button-search', 'id' => 'topbar-btn-search')); ?>

                    <?php echo Html::endForm(); ?>
                </div>

                <!--                --><?php //echo \humhub\widgets\TopMenuRightStack::widget(); ?>
            </ul>
        </div>
    </div>

    <!-- end: second top navigation bar -->

    <?php echo \humhub\modules\tour\widgets\Tour::widget(); ?>

    <!-- start: show content (and check, if exists a sublayout -->
    <?php if (isset($this->context->subLayout) && $this->context->subLayout != "") : ?>
        <?php echo $this->render($this->context->subLayout, array('content' => $content)); ?>
    <?php else: ?>
        <?php echo $content; ?>
    <?php endif; ?>
    <!-- end: show content -->

    <!-- start: Modal (every lightbox will/should use this construct to show content)-->
    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <?php echo \humhub\widgets\LoaderWidget::widget(); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- end: Modal -->

    <?php echo \humhub\models\Setting::GetText('trackingHtmlCode'); ?>
    <?php $this->endBody() ?>
    <!-- start : facebook messenger -->
    <?php
    $language = 'en_us';
    if (!empty(Yii::$app->user->language)) {
        $language = Yii::$app->user->language;
        if ($language == 'vi') $language .= '_VN';
    }
    ?>
    <script>
        window.fbAsyncInit = function () {
            FB.init({
                appId: "<?= getenv('FACEBOOK_CLIENT_ID') ?>",
                xfbml: true,
                version: "v2.6",
                cookie: true,
            });

        };

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/<?= $language ?>/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

    </script>

    <div class="fb-messengermessageus"
            messenger_app_id="<?= getenv('FACEBOOK_CLIENT_ID') ?>"
            page_id="<?= getenv('FACEBOOK_MESSENGER_PAGE_ID') ?>"
            color="blue"
            size="large">
    </div>
    <style>
        .fb-messengermessageus {
            position: fixed;
            bottom: 10px;
            right: 10px;
        }
    </style>
    <!-- end : facebook messenger -->


    <?php
    if (isset($this->context->contentContainer)) {
        $custom_space_chooser_btn = '<div class="btn-group btn-group-custom-space-chooser"><a href="#" id="custom_space_chooser_btn"><i class="fa fa-dot-circle-o"></i></a></div>';
        if ($this->context->contentContainer instanceof \humhub\modules\space\models\Space) {
            $custom_space_chooser_btn = '<div class="btn-group btn-group-custom-space-chooser"><a href="#" id="custom_space_chooser_btn">';
            $space = $this->context->contentContainer;
            $custom_space_chooser_btn .= \humhub\modules\space\widgets\Image::widget([
                'space' => $space,
                'width' => 25,
                'htmlOptions' => [
                    'id' => 'custom_space_chooser_img',
                ]
            ]);
            $custom_space_chooser_btn .= ' <b class="caret"></b>';
            $custom_space_chooser_btn .= '</a></div>';
        }
        echo "<script>window.custom_space_chooser_btn_html = '" . $custom_space_chooser_btn . "';</script>";

        $custom_menu_btn = '<div class="btn-group"><a href="#" id="custom_menu_btn"><i class="fa fa-align-justify"></i></a></div>';
        echo "<script>window.custom_menu_btn_html = '" . $custom_menu_btn . "';</script>";
        $custom_search_btn = '<div class="btn-group btn-group-custom-search-btn"><a href="#" id="custom_search_btn" data-toggle="popover"><i class="fa fa-search"></a></div>';
        echo "<script>window.custom_search_btn_html = '" . $custom_search_btn . "';</script>";
    }

    ?>

    <div class="form-group-custom-search-popover hide">
        <div class="form-group form-group-search" style="width: 290px">
            <form action="/search" method="GET">
                <div class="form-group form-group-search">
                    <input type="text" class="form-control form-search popover-search-input" name="keyword"
                            placeholder="<?= Yii::t('SearchModule.views_search_index', 'Search for user, spaces and content') ?>">
                    <button type="submit" class="btn btn-default btn-sm form-button-search"><i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="chat-popup-window" id="chat-popup-window">

    </div>
    <?php
    echo '<script>window.isChatEnabled = "' . getenv('CHAT_SYSTEM_ENABLE') . '";</script>';
    ?>
    <script>
        var stateManager = (function () {
            var state = null;
            var resizePage = function () {
                if ($('body').width() < 760) {
                    if (state !== "mobile" && state !== "mobile_small") {
                        displayMobile();
                    }
                    resizeMobile();
                    if (window.isChatEnabled == 'true') {
                        if ($('body').width() < 370) {
                            if (state !== "mobile_small") {
                                displayMobileSmall();
                            }
                            $('.popover-search-input').width($('body').width() - 110);
                            $('.popover-search-input').parent().width($('body').width() - 60);
                        }
                    }
                }
                else {
                    if (state !== "desktop") {
                        displayDesktop();
                    }
                    resizeDesktop();
                }
            };
            var displayMobileSmall = function () {
                $('#custom_search_btn').hide();
                $('#topbar-btn-search').show();
                $('#topbar-btn-search').click(function (e) {
                    e.preventDefault();
                    $('#custom_search_btn').click();
                });
                state = 'mobile_small';
            };
            var displayMobile = function () {
                if (typeof  window.custom_space_chooser_btn_html != 'undefined') {
                    $('#topbar-second').addClass('topbar-second-hide');
                    $('#topbar-first').find('li.account').addClass('zero-margin-left');
                    $('body').children('.container').addClass('space-layout-container-fix');
                    $('#topbar-first').find('.notifications').append(window.custom_space_chooser_btn_html);
                    $('#topbar-first').find('.notifications').append(window.custom_menu_btn_html);
                    $('#topbar-first').find('.notifications').append(window.custom_search_btn_html);
                    $('#search-input-field').hide();
                    $('#topbar-btn-search').hide();
                    $('#custom_space_chooser_btn').click(function (e) {
                        e.stopPropagation();
                        $('#space-menu').click();
                    });

                    $('#custom_menu_btn').click(function (e) {
                        e.stopPropagation();
                        $('#top-dropdown-menu').click();
                    });

                    $('#custom_search_btn').popover({
                        html: true,
                        placement: 'bottom',
                        content: function () {
                            return $('.form-group-custom-search-popover').html();
                        }
                    }).on('show.bs.popover', function () {
                        setTimeout(function () {
                            $('.popover-content').find('input.popover-search-input').focus();
                        }, 500)

                    });

                    $('.btn-language-select').find('strong').each(function () {
                        $(this).addClass('hide');
                    });

                    $(document).ready(function () {
                        /*  $('body').on('click', function (e) {
                         $('[data-toggle="popover"]').each(function () {
                         //the 'is' for buttons that trigger popups
                         //the 'has' for icons within a button that triggers a popup
                         if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                         $(this).popover('hide');
                         }
                         });
                         });*/
                    });
                }

                state = "mobile";

            };

            var displayDesktop = function () {
                if (typeof  window.custom_space_chooser_btn_html != 'undefined') {
                    $('#topbar-second').removeClass('topbar-second-hide');
                    $('#topbar-first').find('li.account').removeClass('zero-margin-left');
                    $('.space-layout-container').removeClass('space-layout-container-fix');
                    $('#search-input-field').show();
                    $('#topbar-btn-search').show();
                    $('.btn-group-custom-space-chooser').remove();
                    $('#custom_menu_btn').remove();
                    $('.btn-group-custom-search-btn').remove();
                    $('.btn-language-select').find('strong').each(function () {
                        $(this).removeClass('hide');
                    });
                }

                state = "desktop";

            };
            var resizeMobile = function () {

            };
            var resizeDesktop = function () {

            };
            return {
                init: function () {
                    resizePage();
                    $(window).on('resize', resizePage);
                }
            };
        }());
        stateManager.init();
    </script>

    <?php
    if (isset($this->context->contentContainer) && ($this->context->contentContainer instanceof \humhub\modules\space\models\Space)) {
        echo "<script>window.getSingleConversationUrl = '" . $this->context->contentContainer->createUrl('/kodeplus_chat/space-conversation/get-single-conversation') . "';</script>";
    }
    ?>
    <?php
    if (getenv('CHAT_SYSTEM_ENABLE') == 'true' && !Yii::$app->user->isGuest) {
        $user = Yii::$app->user->getIdentity();
        if (empty($user->chat_unique_key)) {
            $user->chat_unique_key = uniqid();
            $user->save();
        }
        ?>
        <script>
            window.user_id = "<?= Yii::$app->user->id ?>";
            window.user_id_unique_key = "<?= $user->chat_unique_key ?>";
            window.goChatServerIP = '<?= getenv('CHAT_SERVER_IP') ?>';
            window.goChatServerPort = '<?= getenv('CHAT_SERVER_PORT') ?>';
        </script>
        <?php
        $this->registerJsFile("@web/themes/Kodeplus/js/socket.io-1.2.0.js");
        $this->registerJsFile("@web/themes/Kodeplus/js/chat.js");
    }
    ?>
    <?= $this->registerJsFile("@web/themes/Kodeplus/js/jquery-track-everything.js"); ?>
    <script>
        $("body").track();
    </script>
    </body>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', '<?= getenv('GOOGLE_ANALYTICS_TRACKING_ID') ?>', 'auto');
        ga('send', 'pageview');

    </script>
    <?php if (Yii::$app->getSession()->hasFlash('enable-module-error')) {
        $error = Yii::$app->getSession()->getFlash('enable-module-error');
        ?>
        <script>
            $(function () {
                var modal = $("#globalModal");
                var contentModal = modal.find(".modal-content");
                contentModal.html('<div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> <h4 class="modal-title" id="myModalLabel"><strong><?= $error['module'] ?></strong></h4> </div>');
                contentModal.append('<div class="modal-body"><h5>Some errors occurred when enabling this module:</h5><ul><?php foreach ($error['errors'] as $error) { echo "<li class=\"text-danger\">$error</li>" ;} ?></li></ul></div>');
                modal.modal('show');
            })
        </script>
    <?php } ?>
    </html>
<?php $this->endPage() ?>