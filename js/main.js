/**
 * Created with IntelliJ IDEA.
 * User: HandsHiles
 * Date: 14-10-24
 * Time: 3:50 PM
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function() {
    $('body').on('click', 'a.dynamic', swapContent);

    //Search Trigger
    $('#submit_search').on('click', function(e) {
        e.preventDefault();
        var search_value = $('#search').val();
        var query = encodeURIComponent(search_value);
        if(location.pathname != 'games.php' || location.pathname != 'mygames.php') {
            window.location = location.protocol + '//' + location.host + '/pages/games.php' + '?name=' + query;
        } else {
            window.location = location.protocol + '//' + location.host + location.pathname + '?name=' + query;
        }
    });
    $('#search').keypress(function (e) {
        if (e.which == 13) {
            var search_value = $(this).val();
            var query = encodeURIComponent(search_value);
            if(location.pathname != 'games.php' || location.pathname != 'mygames.php') {
                window.location = location.protocol + '//' + location.host + '/pages/games.php' + '?name=' + query;
            } else {
                window.location = location.protocol + '//' + location.host + location.pathname + '?name=' + query;
            }
        }
    });
});
function swapContent(e) {
    e && e.preventDefault();
    var content = $('#swap-able-content');
    content.html('<div class="loading"><img src="../images/loading.gif" alt="Loading..." /></div>');

    var href = $(this).attr('href');
    $.get(href).success( function(data) {
        setTimeout(function() {
            content.html(data);
        }, 300);
    }).fail( function() {
        alert('oops! Fetching ' + href + ' failed... Sorry :) try again if you like!');
    });
}
