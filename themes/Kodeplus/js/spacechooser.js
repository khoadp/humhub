/**
 * Handling space chooser user input
 */
function printObject(o) {
    var out = '';
    for (var p in o) {
        out += p + ': ' + o[p] + '\n';
    }
    alert(out);
}
var is_search = false;
var scroll_curpage = 1;
var scroll_iscontinue = true;
var html = '<li class="loadingmore">Loading...</li>';
var otherSpaceTab = $("a[data-target='#other'] span")[0];
const otherSpaceText = otherSpaceTab.textContent;

function getMorePage(keyword) {
    $.ajax({
        url: '/kodeplus_space/kodeplus-space/get-more-space',
        type: 'get',
        async: false,
        data: {
            page: scroll_curpage,
            keyword: keyword
        },
        beforeSend: function () {
            // setting a timeout
            $("#other").append(html);
        },
        success: function (resp) {
            var json;
            try {
                json = JSON.parse(resp);
            } catch (err) {
                console.error(err);
                json = {};
            }

            $(".loadingmore").remove();
            otherSpaceTab.textContent = otherSpaceText + "(" + json.total + ")";
            if (json.data == 'none') {
                scroll_iscontinue = false;
                return;
            }

            $("#other").append(json.data);
            scroll_curpage++;
            return json;
        },
        error: function () {
            $(".loadingmore").remove();
            otherSpaceTab.textContent = otherSpaceText;
            return 'none';
        }
    });
}
function resetPage(keyword) {
    $('#other').html('');
    is_search = false;
    scroll_curpage = 1;
    scroll_iscontinue = true;
    getMorePage(keyword);
}
$(document).ajaxComplete(function (event, request, settings) {
    if (settings.type == "POST" && settings.url.indexOf('request-membership-form') != -1) {
        $('#space-menu-search').val('');
        resetPage('');
    }
});

getMorePage('');

/*$("#space-menu-spaces").niceScroll().scrollend(function (info) {
 if (scroll_iscontinue == true && $("#other").hasClass('active')) {
 var scroll = $(this);
 if (scroll[0].scroll.y >= scroll[0].scrollvaluemax - 10) {
 getMorePage('');
 }

 }
 });*/

function updateSpaceMenuSpacesHeight() {
    var spaceMenuSpace = $('#space-menu-spaces');
    var createSpaceBtn = $("#create-space-btn");
    var windowHeight = $(window).height();

    spaceMenuSpace.height(
        windowHeight // window height
        - spaceMenuSpace[0].getBoundingClientRect().top // position of space menu space with window
        - createSpaceBtn.height() // create button height
        - (createSpaceBtn.closest('li').width() - createSpaceBtn.width()) / 2 // create space button padding
        - 2 // padding bottom
    );
}

$(document).ready(function () {

    var chosen = []; // Array for visible space menu entries
    var arrPosition = ""; // Save the current position inside the chosen array

    /**
     * Open space chooser and load user spaces
     */
    $('#space-menu').click(function () {

        // use setIntervall to setting the focus
        var spaceFocus = setInterval(setFocus, 10);

        function setFocus() {
            // set focus
            $('#space-menu-search').focus();

            // update height
            updateSpaceMenuSpacesHeight();

            // stop interval
            clearInterval(spaceFocus);
        }

    });

    $(window).on('resize', updateSpaceMenuSpacesHeight);

    /**
     * Show and navigate through spaces depends on user input
     */
    $('#space-menu-search').keyup(function (event) {

        if (event.keyCode == 40) {

            // set current array position
            if (arrPosition === "") {
                arrPosition = 1;
            } else if ((arrPosition) < chosen.length - 1) {
                arrPosition++;
            }

            // remove selection from last space entry
            $('#space-menu-dropdown li ul li').removeClass('selected');

            // add selection to the current space entry
            $('#space-menu-dropdown li ul li:eq(' + chosen[arrPosition] + ')').addClass('selected');

            return false;

        } else if (event.keyCode == 38) {

            // set current array position
            if (arrPosition === "") {
                arrPosition = 1;
            } else if ((arrPosition) > 0) {
                arrPosition--;
            }

            $('#space-menu-dropdown li ul li').removeClass('selected');

            // add selection to the current space entry
            $('#space-menu-dropdown li ul li:eq(' + chosen[arrPosition] + ')').addClass('selected');

            return false;

        } else if (event.keyCode == 13) {
            if ($('#other').hasClass('active')) {
                var input = $(this).val().toLowerCase();
                resetPage(input);
                is_search = true;
            }
            // check if one space is selected
            if ($('#space-menu-spaces li').hasClass("selected")) {

                // move to selected space, by hitting enter
                window.location.href = $('#space-menu-dropdown li ul li.selected a').attr('href');
            }

        } else {

            // lowercase and save entered string in variable
            var input = $(this).val().toLowerCase();

            if (input > 0) {
                // remove max-height property to hide the nicescroll scrollbar
                $('#space-menu-spaces').css({ 'max-height': 'none' });
            } else {
                // set max-height property to show the nicescroll scrollbar
                $('#space-menu-spaces').css({ 'max-height': '400px' });
            }

            // empty variable and array
            chosen = [];
            arrPosition = "";

            $(".tab-pane.active li").each(function (index) {

                // remove selected classes from all space entries
                $('.tab-pane.active li').removeClass('selected');


                // lowercase and save space strings in variable
                var text = $(this).find('.spacename').text() + $(this).find('.spacedescription').text();
                var str = text.toLowerCase();

                if (str.search(input) == -1) {
                    // hide elements when not matched
                    $(this).css('display', 'none');
                } else {
                    // show elements when matched
                    $(this).css('display', 'block');

                    // update array with the right li element
                    chosen.push(index);
                }

            });

            $(".tab-pane li").each(function (index) {

                // remove selected classes from all space entries
                $('.tab-pane li').removeClass('selected');


                // lowercase and save space strings in variable
                var text = $(this).find('.spacename').text() + $(this).find('.spacedescription').text();
                var str = text.toLowerCase();

                if (str.search(input) == -1) {
                    // hide elements when not matched
                    $(this).css('display', 'none');
                } else {
                    // show elements when matched
                    $(this).css('display', 'block');

                    // update array with the right li element
                    chosen.push(index);
                }

            });

            // add selection to the first space entry
            $('#space-menu-dropdown li ul li:eq(' + chosen[0] + ')').addClass('selected');

            // check if entered string is empty or not
            if (input.length == 0) {
                // reset inputs
                resetSpaceSearch();
                if (is_search == true) {
                    if ($("#other").hasClass('active')) {
                        is_search = false;
                        resetPage('');
                    }
                }
            } else {
                // show search reset icon
                $('#space-search-reset').fadeIn('fast');
            }

            // remove hightlight
            $("#space-menu-dropdown li ul li").removeHighlight();

            // add new highlight matching strings
            $("#space-menu-dropdown li ul li").highlight(input);


        }

        //return event.returnValue;

    })

    /**
     * Disable enter key
     */
    $('#space-menu-search').keypress(function (event) {
        if (event.keyCode == 13) {
            // deactivate the standard press event
            event.preventDefault();
            return false;
        }
    });


    /**
     * Click handler to reset user input
     */
    $('#space-search-reset').click(function () {
        resetSpaceSearch();
    })

    /**
     * Reset user input
     */
    function resetSpaceSearch() {

        // fade out the cross icon
        $('#space-search-reset').fadeOut('fast');

        // empty input field
        $('#space-menu-search').val('');

        // set focus to input field
        $('#space-menu-search').focus();

        $("#space-menu-dropdown li ul li").each(function () {

            // show all space entries
            $(this).css('display', 'block');

            // remove search result highlighting
            $("#space-menu-dropdown li ul li").removeHighlight();

            // remove the curren tspace entry selection
            $('#space-menu-dropdown li ul li').removeClass('selected');

        });

        // set max-height property to show the nicescroll scrollbar
        $('#space-menu-spaces').css({ 'max-height': '400px' });
    }
});