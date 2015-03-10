/**
 * Created with IntelliJ IDEA.
 * User: HandsHiles
 * Date: 14-10-24
 * Time: 3:50 PM
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function() {
    $('body').on('click', 'a.dynamic', swapContent);
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
