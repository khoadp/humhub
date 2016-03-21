<?php
/* @var $this \humhub\components\WebView */
/* @var $currentSpace \humhub\modules\space\models\Space */

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\libs\Helpers;
use humhub\modules\space\models\Membership;
use humhub\models\Setting;
use humhub\modules\space\models\Space;

$this->registerJsFile("@web/themes/Kodeplus/js/spacechooser.js");
$this->registerJsVar('scSpaceListUrl', Url::to(['/space/list', 'ajax' => 1]));
?>

<li class="dropdown">
    <a href="#" id="space-menu" class="dropdown-toggle" data-toggle="dropdown">
        <!-- start: Show space image and name if chosen -->
        <?php if ($currentSpace) { ?>
            <?php echo \humhub\modules\space\widgets\Image::widget([
                'space' => $currentSpace,
                'width' => 32,
                'htmlOptions' => [
                    'class' => 'current-space-image',
                ]
            ]); ?>
        <?php } ?>

        <?php
        if (!$currentSpace) {
            echo '<i class="fa fa-dot-circle-o"></i><br>' . Yii::t('SpaceModule.widgets_views_spaceChooser', 'My spaces');
        }
        ?>
        <!-- end: Show space image and name if chosen -->
        <b class="caret"></b>
    </a>
    <ul class="dropdown-menu" id="space-menu-dropdown">
        <li>
            <form action="" class="dropdown-controls"><input type="text" id="space-menu-search"
                                                             class="form-control"
                                                             autocomplete="off"
                                                             placeholder="<?php echo Yii::t('SpaceModule.widgets_views_spaceChooser', 'Search'); ?>">


            </form>

        </li>
        <li>
            <div class="mytabs tabs-style-underline">
                <form>
                    <nav style="display: block;">
                        <ul id="mytab">
                            <li class="active">
                                <a href="#" data-target="#owner" data-toggle="tab"
                                   style="border-left: none"><span>OWNER</span></a>
                            </li>
                            <li>
                                <a href="#" data-target="#other" data-toggle="tab"><span>OTHER</span></a>
                            </li>
                        </ul>
                    </nav>
                </form>
            </div>
            <ul class="media-list notLoaded pull-left" id="space-menu-spaces">
                <div class="tab-content">
                    <div class="tab-pane active" id="owner">
                        <?php foreach ($memberships as $membership): ?>
                            <?php $newItems = $membership->countNewItems(); ?>
                            <li>
                                <a href="<?php echo $membership->space->getUrl(); ?>">
                                    <div class="media">
                                        <!-- Show space image -->
                                        <?php echo \humhub\modules\space\widgets\Image::widget([
                                            'space' => $membership->space,
                                            'width' => 24,
                                            'htmlOptions' => [
                                                'class' => 'pull-left',
                                            ]
                                        ]); ?>
                                        <div class="media-body">
                                            <strong><?php echo Html::encode($membership->space->name); ?></strong>
                                            <?php if ($newItems != 0): ?>
                                                <div class="badge badge-space pull-right"
                                                     style="display:none"><?php echo $newItems; ?></div>
                                            <?php endif; ?>
                                            <br>

                                            <p><?php echo Html::encode(Helpers::truncateText($membership->space->description, 60)); ?></p>
                                        </div>
                                    </div>
                                </a>
                            </li>

                        <?php endforeach; ?>
                    </div>
                    <div class="tab-pane" id="other">

                    </div>
                </div>


            </ul>
        </li>
        <?php if ($canCreateSpace): ?>
            <li>
                <div class="dropdown-footer">
                    <?php
                    echo Html::a(Yii::t('SpaceModule.widgets_views_spaceChooser', 'Create new space'), Url::to(['/space/create/create']), array('class' => 'btn btn-info col-md-12', 'data-target' => '#globalModal'));
                    ?>
                </div>
            </li>
        <?php endif; ?>
    </ul>
</li>

<script type="text/javascript">

    // set niceScroll to SpaceChooser menu
    $("#space-menu-spaces").niceScroll({
        cursorwidth: "7",
        cursorborder: "",
        cursorcolor: "#555",
        cursoropacitymax: "0.2",
        railpadding: {top: 0, right: 3, left: 0, bottom: 0}
    });
    jQuery('.badge-space').fadeIn('slow');
    jQuery('#mytab a:first').tab('show');

    function printObject(o) {
        var out = '';
        for (var p in o) {
            out += p + ': ' + o[p] + '\n';
        }
        alert(out);
    }

    var scroll_curpage = 1;
    var scroll_iscontinue = true;
    var html = '<li class="loadingmore">Loading...</li>';

    function getMorePage() {
        $("#other").append(html);
        $.ajax({
            url: '/spacex/spacex/get-more-space',
            type: 'get',
            data: {
                page: scroll_curpage
            },
            success: function (data) {
                $(".loadingmore").remove();
                if (data == 'none') {
                    scroll_iscontinue = false;
                    return;
                }

                $("#other").append(data);
                scroll_curpage++;
                return data;
            },
            error: function () {
                $(".loadingmore").remove();
                return 'none';
            }
        });
    }
    $(document).ajaxSend(function (event, request, settings) {
        if (settings.type == "POST" && settings.url.indexOf('request-membership-form') != -1) {
            $('#other').html('');
            scroll_curpage=1;
            scroll_iscontinue = true;
            getMorePage();
        }
    });
    getMorePage();

    $("#space-menu-spaces").niceScroll().scrollend(function (info) {
        if (scroll_iscontinue == true && $("#other").hasClass('active')) {

            var scroll = $(this);
            if (scroll[0].scroll.y >= scroll[0].scrollvaluemax - 10) {
                getMorePage();
            }

        }
    });

    /*$(document).on('click', '.membership-btn', function() {
        window.last_membership_btn=$(this);
    });*/
</script>
