$(document).ready(function() {
    /*----If we don't want to use pjax for all links-----*/
    $('a').attr('data-pjax', '#pjax-container');    
    $('#userBoxLogout').removeAttr('data-pjax');
    $(document).pjax('a[data-pjax]', { container: '#pjax-container', timeout: 0})
        .on('pjax:success', function() {
              $('.masthead li.active').removeClass('active');
              $('.masthead li:has(a[href=\"' + window.location.pathname + '\"])').addClass('active');
              calendarReady();
        });
    
    /*------------------For all links-------------*/
    //$(document).pjax('a', { container: '#pjax-container', timeout: 0});
}); 