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
    $custom_menu_btn = '<div class="btn-group"><a href="#" id="custom_menu_btn"><i class="fa fa-align-justify"></i></a></div>';
    $custom_search_btn = '<div class="btn-group btn-group-custom-search-btn"><a href="#" id="custom_search_btn" data-toggle="popover"><i class="fa fa-search"></a></div>';

    $this->registerJsVar("custom_space_chooser_btn_html", $custom_space_chooser_btn);
    $this->registerJsVar("custom_menu_btn_html", $custom_menu_btn);
    $this->registerJsVar("custom_search_btn_html", $custom_search_btn);
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