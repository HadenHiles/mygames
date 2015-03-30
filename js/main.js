/**
 * Created with IntelliJ IDEA.
 * User: HandsHiles
 * Date: 14-10-24
 * Time: 3:50 PM
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function() {
//    $('body').on('click', 'a.dynamic', swapContent);

    //Search Triggers
    $('#submit_search').on('click', function(e) {
        e.preventDefault();
        getSearchResults();
    });
    $('#search').keypress(function (e) {
        if (e.which == 13) {
            getSearchResults();
        }
    });
    $('.search input').focus(function() {
        $(this).animate({width: "185px"}, 100);
    });
    $('.search input').focusout(function() {
        $(this).animate({width: "100px"}, 100);
    });

    //Favorite click handler
    $('.fav').on('click', 'i.fa', function(e) {
        e.preventDefault();
        if($(this).hasClass('favorited')) {
            $(this).closest('li').toggle('puff', {direction: 'up'}, 200);
        }
        var user_id = $(this).attr('user_id');
        var game_id = $(this).attr('game_id');
        var request = $.ajax({
            url: "../pages/favorite-game.php",
            type: "POST",
            data: {user_id: user_id, game_id: game_id},
            dataType: "html"
        });

        request.success(function() {
            var icon = $('ul.game_list_container .fav i.icon' + game_id);
            if(icon.hasClass('fa-heart')) {
                icon.removeClass('fa-heart');
                icon.addClass('fa-heart-o');
            } else if(icon.hasClass('fa-heart-o')) {
                icon.removeClass('fa-heart-o');
                icon.addClass('fa-heart');
            }
        });
//        request.fail(function(jqXHR, textStatus) {
//            alert( "Request failed: " + textStatus );
//        });
    });

    //Determine whether the search field should be fixed or not
    var search = $('.header #search_sticky');
    if($(window).scrollTop() >= 171) {
        search.css({position: 'fixed', top: '2px', left: '0px', right: '0px', width: '270px', 'z-index': '2', 'margin-left': 'auto', 'margin-right': 'auto'});
        $('.logo').css({'margin-bottom': '50px'});
    }
    if($(window).scrollTop() < 171) {
        search.css({position: 'static', top: 'auto'});
        $('.logo').css({'margin-bottom': '0px'});
    }
});

$(document).ready(function() {
    $('#game_manager').on('click', '#modify_game', function(e) {
        e.preventDefault();
        var game_to_modify = $(this);
        if(game_to_modify.attr('type') == 'edit-game') {
            editGame(game_to_modify);
        } else if(game_to_modify.attr('type') == 'delete-game') {
            deleteGame(game_to_modify);
        } else if(game_to_modify.attr('type') == 'approve-game') {
            approveGame(game_to_modify);
        } else if(game_to_modify.attr('type') == 'deny-game') {
            denyGame(game_to_modify);
        }
    });
});

$(document).ready(function() {
    $('.game_play_actions a.full_screen').click(function(e) {
        e.preventDefault();
        toggleFullScreen();
    });
    if($('.game_container').length > 0) {
        setTimeout(function() {
            $('html, body').animate({
                scrollTop: $('.game_container').offset().top - 90
            }, 1000);
        }, 1000);
        if($('#join-modal').length > 0) {
            setTimeout(function() {
                $('#join-modal').modal('show');
            }, 2200);
        }
    }
});
$(document).keyup(function(e) {
    if (e.keyCode == 27) { // escape key maps to keycode `27`
        toggleFullScreen();
    }
});

function toggleFullScreen() {
    if($('body').hasClass('full')) {
        $('.game_play_actions a.full_screen i').removeClass('fa-compress');
        $('.game_play_actions a.full_screen i').addClass('fa-expand');
    } else {
        $('.game_play_actions a.full_screen i').removeClass('fa-expand');
        $('.game_play_actions a.full_screen i').addClass('fa-compress');
    }
    $('body').toggleClass('full');
}

//Stick the search field to the top of the screen
$(window).scroll(function() {
    var search = $('.header #search_sticky');
    if($(window).scrollTop() >= 171) {
        search.css({position: 'fixed', top: '2px', left: '0px', right: '0px', width: '270px', 'z-index': '2', 'margin-left': 'auto', 'margin-right': 'auto'});
        $('.logo').css({'margin-bottom': '50px'});
    }
    if($(window).scrollTop() < 171) {
        search.css({position: 'static', top: 'auto'});
        $('.logo').css({'margin-bottom': '0px'});
    }
});

//Trigger a game search on the correct pages
function getSearchResults() {
    var search_value = $('#search').val();
    var query = encodeURIComponent(search_value);
    if(location.pathname != '/pages/games.php' && location.pathname != '/pages/favorites.php') {
        window.location = location.protocol + '//' + location.host + '/pages/games.php' + '?name=' + query;
    } else {
        window.location = location.protocol + '//' + location.host + location.pathname + '?name=' + query;
    }
}

//Functions to modify game properties
function editGame(element) {
    window.location = location.protocol + '//' + location.host + '/pages/edit-game.php' + '?id=' + element.attr('game_id');
}
function deleteGame(element) {
    if (confirm("Are you sure you want to delete this game?")){
        element.closest('li').toggle('puff', {direction: 'up'}, 400);
        var game_id = element.attr('game_id');
        var request = $.ajax({
            url: "../pages/delete-game.php",
            type: "POST",
            data: {id: game_id},
            dataType: "html"
        });

        request.success(function() {
            console.log(game_id + ' deleted');
        });
    }
}
function approveGame(element) {
    element.closest('li').toggle('puff', {direction: 'up'}, 400);
    var game_id = element.attr('game_id');
    var request = $.ajax({
        url: "../pages/approve-game.php",
        type: "POST",
        data: {id: game_id},
        dataType: "html"
    });

    request.success(function() {
        console.log(game_id + ' approved');
    });
}
function denyGame(element) {
    element.closest('li').toggle('puff', {direction: 'up'}, 400);
    var game_id = element.attr('game_id');
    var request = $.ajax({
        url: "../pages/deny-game.php",
        type: "POST",
        data: {id: game_id},
        dataType: "html"
    });

    request.success(function() {
        console.log(game_id + ' denied');
    });
}


//function swapContent(e) {
//    e && e.preventDefault();
//    var content = $('#swap-able-content');
//    content.html('<div class="loading"><img src="../images/loading.gif" alt="Loading..." /></div>');
//
//    var href = $(this).attr('href');
//    $.get(href).success( function(data) {
//        setTimeout(function() {
//            content.html(data);
//        }, 300);
//    }).fail( function() {
//        alert('oops! Fetching ' + href + ' failed... Sorry :) try again if you like!');
//    });
//}
