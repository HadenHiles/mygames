/**
 * Created with IntelliJ IDEA.
 * User: HandsHiles
 * Date: 14-10-24
 * Time: 3:50 PM
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function() {
//    $('body').on('click', 'a.dynamic', swapContent);

    //Search Trigger
    $('#submit_search').on('click', function(e) {
        e.preventDefault();
        search();
    });
    $('#search').keypress(function (e) {
        if (e.which == 13) {
            search();
        }
    });

    $('.search input').focus(function() {
        $(this).animate({width: "185px"}, 100);
    });
    $('.search input').focusout(function() {
        $(this).animate({width: "100px"}, 100);
    });

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
});

$(document).ready(function() {
    $('#game_manager').on('click', '#edit_game', function(e) {
        e.preventDefault();
        window.location = location.protocol + '//' + location.host + '/pages/edit-game.php' + '?id=' + $(this).attr('game_id');
    });
    $('#game_manager').on('click', '#delete_game', function(e) {
        e.preventDefault();
        if (confirm("Are you sure you want to delete this game?")){
            $(this).closest('li').toggle('puff', {direction: 'up'}, 200);
            var game_id = $(this).attr('game_id');
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
    });
});

//Stick the search field to the top of the screen
$(window).scroll(function() {
    var search = $('.header #search_sticky');
    if($(window).scrollTop() >= 173) {
        search.css({position: 'fixed', top: '3px', left: '0px', right: '0px'});
        $('.logo').css({'margin-bottom': '63px'});
    }
    if($(window).scrollTop() < 173) {
        search.css({position: 'static', top: 'auto'});
        $('.logo').css({'margin-bottom': '0px'});
    }
});

function search() {
    var search_value = $('#search').val();
    var query = encodeURIComponent(search_value);
    if(location.pathname != '/pages/games.php' && location.pathname != '/pages/favorites.php') {
        window.location = location.protocol + '//' + location.host + '/pages/games.php' + '?name=' + query;
    } else {
        window.location = location.protocol + '//' + location.host + location.pathname + '?name=' + query;
    }
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
